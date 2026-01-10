<?php

namespace App\Controller;

class RegionsWordsController extends AppController
{
    public function indexByWord(int $wordId)
    {
        $query = $this->RegionsWords->find()
            ->contain(['Regions'])
            ->where(['RegionsWords.word_id' => $wordId])
            ->orderBy(['RegionsWords.id' => 'ASC']);

        $regionLinks = $this->paginate($query);

        $this->set(compact('regionLinks', 'wordId'));
    }

    public function addByWord(int $wordId)
    {
        $link = $this->RegionsWords->newEmptyEntity();

        if ($this->request->is('post')) {
            $link = $this->RegionsWords->patchEntity($link, $this->request->getData());
            $link->word_id = $wordId;

            if ($this->RegionsWords->save($link)) {
                $this->Flash->success('Region link added.');
                return $this->redirect(['action' => 'indexByWord', $wordId]);
            }

            $this->Flash->error('Could not add region link.');
        }

        $languageId = (int)$this->request->getAttribute('sitelang')->id;

        $regions = $this->RegionsWords->Regions
            ->find('list', valueField: 'region')
            ->matching('Languages', function (\Cake\ORM\Query\SelectQuery $q) use ($languageId) {
                return $q->where(['RegionsLanguages.language_id' => $languageId]);
            })
            ->distinct(['Regions.id'])
            ->orderBy(['Regions.region' => 'ASC'])
            ->toArray();

        $this->set(compact('link', 'wordId', 'regions'));
    }

    public function delete(int $id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $link = $this->RegionsWords->get($id);
        $wordId = $link->word_id;

        if ($this->RegionsWords->delete($link)) {
            $this->Flash->success('Region link deleted.');
        } else {
            $this->Flash->error('Could not delete region link.');
        }

        return $this->redirect(['action' => 'indexByWord', $wordId]);
    }
}