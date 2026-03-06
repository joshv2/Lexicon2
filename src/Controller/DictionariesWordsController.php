<?php

namespace App\Controller;

class DictionariesWordsController extends AppController
{
    public function indexByWord(int $wordId)
    {
        $query = $this->DictionariesWords->find()
            ->contain(['Dictionaries'])
            ->where(['DictionariesWords.word_id' => $wordId])
            ->orderBy(['DictionariesWords.id' => 'ASC']);

        $dictionaryLinks = $this->paginate($query);

        $this->set(compact('dictionaryLinks', 'wordId'));
    }

    public function addByWord(int $wordId)
    {
        $link = $this->DictionariesWords->newEmptyEntity();

        if ($this->request->is('post')) {
            $link = $this->DictionariesWords->patchEntity($link, $this->request->getData());
            $link->word_id = $wordId;

            if ($this->DictionariesWords->save($link)) {
                $this->Flash->success('Dictionary link added.');

                return $this->redirect(['action' => 'indexByWord', $wordId]);
            }

            $this->Flash->error('Could not add dictionary link.');
        }

        $languageId = (int)$this->request->getAttribute('sitelang')->id;

        $dictionaries = $this->DictionariesWords->Dictionaries
            ->find('list', valueField: 'dictionary')
            ->where(['Dictionaries.language_id' => $languageId])
            ->orderBy(['Dictionaries.dictionary' => 'ASC'])
            ->toArray();

        $this->set(compact('link', 'wordId', 'dictionaries'));
    }

    public function delete(int $id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $link = $this->DictionariesWords->get($id);
        $wordId = (int)$link->word_id;

        if ($this->DictionariesWords->delete($link)) {
            $this->Flash->success('Dictionary link deleted.');
        } else {
            $this->Flash->error('Could not delete dictionary link.');
        }

        return $this->redirect(['action' => 'indexByWord', $wordId]);
    }
}
