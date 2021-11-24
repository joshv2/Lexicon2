<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Alphabets Controller
 *
 * @property \App\Model\Table\AlphabetsTable $Alphabets
 * @method \App\Model\Entity\Alphabet[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AlphabetsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Languages'],
        ];
        $alphabets = $this->paginate($this->Alphabets);

        $this->set(compact('alphabets'));
    }

    /**
     * View method
     *
     * @param string|null $id Alphabet id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $alphabet = $this->Alphabets->get($id, [
            'contain' => ['Languages'],
        ]);

        $this->set(compact('alphabet'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $alphabet = $this->Alphabets->newEmptyEntity();
        if ($this->request->is('post')) {
            $alphabet = $this->Alphabets->patchEntity($alphabet, $this->request->getData());
            if ($this->Alphabets->save($alphabet)) {
                $this->Flash->success(__('The alphabet has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The alphabet could not be saved. Please, try again.'));
        }
        $languages = $this->Alphabets->Languages->find('list', ['limit' => 200]);
        $this->set(compact('alphabet', 'languages'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Alphabet id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $alphabet = $this->Alphabets->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $alphabet = $this->Alphabets->patchEntity($alphabet, $this->request->getData());
            if ($this->Alphabets->save($alphabet)) {
                $this->Flash->success(__('The alphabet has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The alphabet could not be saved. Please, try again.'));
        }
        $languages = $this->Alphabets->Languages->find('list', ['limit' => 200]);
        $this->set(compact('alphabet', 'languages'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Alphabet id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $alphabet = $this->Alphabets->get($id);
        if ($this->Alphabets->delete($alphabet)) {
            $this->Flash->success(__('The alphabet has been deleted.'));
        } else {
            $this->Flash->error(__('The alphabet could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
