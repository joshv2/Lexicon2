<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Dictionaries Controller
 *
 * @property \App\Model\Table\DictionariesTable $Dictionaries
 * @method \App\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DictionariesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $sitelang = $this->languageinfo();
        //$this->paginate = [
        //    'contain' => ['Languages']
        //];
        $dictionariesQuery = $this->Dictionaries->find()
        ->contain(['Languages'])
        ->where(['language_id' => $sitelang->id]);
        //$this->setPaginated($dictionaries); 
        $this->set('dictionaries', $this->paginate($dictionariesQuery));
        $this->set(compact('sitelang'));
    }

    /**
     * View method
     *
     * @param string|null $id Dictionary id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dictionary = $this->Dictionaries->get($id, [
            'contain' => ['Languages', 'Words'],
        ]);

        $this->set(compact('dictionary'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sitelang = $this->languageinfo();
        $dictionary = $this->Dictionaries->newEmptyEntity();
        if ($this->request->is('post')) {
            $dictionary = $this->Dictionaries->patchEntity($dictionary, $this->request->getData());
            if ($this->Dictionaries->save($dictionary)) {
                $this->Flash->success(__('The dictionary has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dictionary could not be saved. Please, try again.'));
        }
        $words = $this->Dictionaries->Words->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('dictionary', 'words', 'sitelang'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dictionary id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sitelang = $this->languageinfo();
        $dictionary = $this->Dictionaries->get($id, [
            'contain' => ['Words'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dictionary = $this->Dictionaries->patchEntity($dictionary, $this->request->getData());
            if ($this->Dictionaries->save($dictionary)) {
                $this->Flash->success(__('The dictionary has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dictionary could not be saved. Please, try again.'));
        }
        $languages = $this->Dictionaries->Languages->find(type: 'list', options: ['limit' => 200]);
        $words = $this->Dictionaries->Words->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('dictionary', 'words', 'languages', 'sitelang'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dictionary id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dictionary = $this->Dictionaries->get($id);
        if ($this->Dictionaries->delete($dictionary)) {
            $this->Flash->success(__('The dictionary has been deleted.'));
        } else {
            $this->Flash->error(__('The dictionary could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
