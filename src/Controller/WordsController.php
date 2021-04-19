<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Words Controller
 *
 * @property \App\Model\Table\WordsTable $Words
 * @method \App\Model\Entity\Word[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WordsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $words = $this->paginate($this->Words);

        $this->set(compact('words'));
    }

    public function alphabetical()
    {
        
        $letter = $this->request->getParam('pass')[0];
        
        $words = $this->Words->get_words_starting_with_letter($letter);
        $this->set(compact('letter', 'words'));
    }
    
    
    
    /**
     * View method
     *
     * @param string|null $id Word id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $word = $this->Words->get($id, [
            'contain' => ['Dictionaries', 'Origins', 'Regions', 'Types', 'Languages', 'Alternates', 'Definitions', 'Sentences', 'Pronunciations'],
            //'contain' => ['Definitions'],
            'cache' => false
        ]);

        $this->set(compact('word'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $word = $this->Words->newEmptyEntity();
        if ($this->request->is('post')) {
            $word = $this->Words->patchEntity($word, $this->request->getData());
            if ($this->Words->save($word)) {
                $this->Flash->success(__('The word has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The word could not be saved. Please, try again.'));
        }
        $dictionaries = $this->Words->Dictionaries->find('list', ['limit' => 200]);
        $origins = $this->Words->Origins->find('list', ['limit' => 200]);
        $regions = $this->Words->Regions->find('list', ['limit' => 200]);
        $types = $this->Words->Types->find('list', ['limit' => 200]);
        $this->set(compact('word', 'dictionaries', 'origins', 'regions', 'types'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Word id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $word = $this->Words->get($id, [
            'contain' => ['Dictionaries', 'Origins', 'Regions', 'Types'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $word = $this->Words->patchEntity($word, $this->request->getData());
            if ($this->Words->save($word)) {
                $this->Flash->success(__('The word has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The word could not be saved. Please, try again.'));
        }
        $dictionaries = $this->Words->Dictionaries->find('list', ['limit' => 200]);
        $origins = $this->Words->Origins->find('list', ['limit' => 200]);
        $regions = $this->Words->Regions->find('list', ['limit' => 200]);
        $types = $this->Words->Types->find('list', ['limit' => 200]);
        $this->set(compact('word', 'dictionaries', 'origins', 'regions', 'types'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Word id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $word = $this->Words->get($id);
        if ($this->Words->delete($word)) {
            $this->Flash->success(__('The word has been deleted.'));
        } else {
            $this->Flash->error(__('The word could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
