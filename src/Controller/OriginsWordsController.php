<?php
namespace App\Controller;

class OriginsWordsController extends AppController
{
    
    
    public function indexByWord(int $wordId)
    {
        $query = $this->OriginsWords->find()
            ->contain(['Origins'])
            ->where(['OriginsWords.word_id' => $wordId])
            ->orderBy(['OriginsWords.id' => 'ASC']);

        $originLinks = $this->paginate($query);

        $this->set(compact('originLinks', 'wordId'));
    }

    public function delete(int $id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $link = $this->OriginsWords->get($id);
        $wordId = $link->word_id;

        if ($this->OriginsWords->delete($link)) {
            $this->Flash->success('Origin link deleted.');
        } else {
            $this->Flash->error('Could not delete origin link.');
        }

        return $this->redirect(['action' => 'indexByWord', $wordId]);
    }

    public function addByWord(int $wordId)
    {
        $link = $this->OriginsWords->newEmptyEntity();

        if ($this->request->is('post')) {
            $link = $this->OriginsWords->patchEntity($link, $this->request->getData());

            // Force word_id from the URL (don’t trust the form)
            $link->word_id = $wordId;

            if ($this->OriginsWords->save($link)) {
                $this->Flash->success('Origin link added.');
                return $this->redirect(['action' => 'indexByWord', $wordId]);
            }

            $this->Flash->error('Could not add origin link.');
        }

        $languageId = (int)$this->request->getAttribute('sitelang')->id;

        $origins = $this->OriginsWords->Origins
            ->find('list', valueField: 'origin')
            ->matching('Languages', function (\Cake\ORM\Query\SelectQuery $q) use ($languageId) {
                return $q->where(['OriginsLanguages.language_id' => $languageId]);
            })
            ->distinct(['Origins.id'])
            ->orderBy(['Origins.origin' => 'ASC'])
            ->toArray();

        $this->set(compact('link', 'wordId', 'origins'));
    }
}