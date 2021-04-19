<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Alternates Controller
 *
 * @property \App\Model\Table\AlternatesTable $Alternates
 * @method \App\Model\Entity\Alternate[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AlternatesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Words'],
        ];
        $alternates = $this->paginate($this->Alternates);

        $this->set(compact('alternates'));
    }

    /**
     * View method
     *
     * @param string|null $id Alternate id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $alternate = $this->Alternates->get($id, [
            'contain' => ['Words'],
        ]);

        $this->set(compact('alternate'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $alternate = $this->Alternates->newEmptyEntity();
        if ($this->request->is('post')) {
            $alternate = $this->Alternates->patchEntity($alternate, $this->request->getData());
            if ($this->Alternates->save($alternate)) {
                $this->Flash->success(__('The alternate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The alternate could not be saved. Please, try again.'));
        }
        $words = $this->Alternates->Words->find('list', ['limit' => 200]);
        $this->set(compact('alternate', 'words'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Alternate id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $alternate = $this->Alternates->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $alternate = $this->Alternates->patchEntity($alternate, $this->request->getData());
            if ($this->Alternates->save($alternate)) {
                $this->Flash->success(__('The alternate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The alternate could not be saved. Please, try again.'));
        }
        $words = $this->Alternates->Words->find('list', ['limit' => 200]);
        $this->set(compact('alternate', 'words'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Alternate id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $alternate = $this->Alternates->get($id);
        if ($this->Alternates->delete($alternate)) {
            $this->Flash->success(__('The alternate has been deleted.'));
        } else {
            $this->Flash->error(__('The alternate could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
