<?php

namespace App\Controller;

class TypesWordsController extends AppController
{
    public function indexByWord(int $wordId)
    {
        $query = $this->TypesWords->find()
            ->contain(['Types'])
            ->where(['TypesWords.word_id' => $wordId])
            ->orderBy(['TypesWords.id' => 'ASC']);

        $typeLinks = $this->paginate($query);

        $this->set(compact('typeLinks', 'wordId'));
    }

    public function addByWord(int $wordId)
    {
        $link = $this->TypesWords->newEmptyEntity();

        if ($this->request->is('post')) {
            $link = $this->TypesWords->patchEntity($link, $this->request->getData());
            $link->word_id = $wordId;

            if ($this->TypesWords->save($link)) {
                $this->Flash->success('Type link added.');
                return $this->redirect(['action' => 'indexByWord', $wordId]);
            }

            $this->Flash->error('Could not add type link.');
        }

        $languageId = (int)$this->request->getAttribute('sitelang')->id;

        $types = $this->TypesWords->Types
            ->find('list', valueField: 'type')
            ->matching('Languages', function (\Cake\ORM\Query\SelectQuery $q) use ($languageId) {
                return $q->where(['TypesLanguages.language_id' => $languageId]);
            })
            ->distinct(['Types.id'])
            ->orderBy(['Types.type' => 'ASC'])
            ->toArray();

        $this->set(compact('link', 'wordId', 'types'));
    }

    public function delete(int $id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $link = $this->TypesWords->get($id);
        $wordId = $link->word_id;

        if ($this->TypesWords->delete($link)) {
            $this->Flash->success('Type link deleted.');
        } else {
            $this->Flash->error('Could not delete type link.');
        }

        return $this->redirect(['action' => 'indexByWord', $wordId]);
    }
}