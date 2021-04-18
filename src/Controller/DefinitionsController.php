<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Definitions Controller
 *
 * @property \App\Model\Table\DefinitionsTable $Definitions
 * @method \App\Model\Entity\Definition[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DefinitionsController extends AppController
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
        $definitions = $this->paginate($this->Definitions);

        $this->set(compact('definitions'));
    }

    /**
     * View method
     *
     * @param string|null $id Definition id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $definition = $this->Definitions->get($id, [
            'contain' => ['Words'],
        ]);

        $this->set(compact('definition'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $definition = $this->Definitions->newEmptyEntity();
        if ($this->request->is('post')) {
            $definition = $this->Definitions->patchEntity($definition, $this->request->getData());
            if ($this->Definitions->save($definition)) {
                $this->Flash->success(__('The definition has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The definition could not be saved. Please, try again.'));
        }
        $words = $this->Definitions->Words->find('list', ['limit' => 200]);
        $this->set(compact('definition', 'words'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Definition id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $definition = $this->Definitions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $definition = $this->Definitions->patchEntity($definition, $this->request->getData());
            if ($this->Definitions->save($definition)) {
                $this->Flash->success(__('The definition has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The definition could not be saved. Please, try again.'));
        }
        $words = $this->Definitions->Words->find('list', ['limit' => 200]);
        $this->set(compact('definition', 'words'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Definition id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $definition = $this->Definitions->get($id);
        if ($this->Definitions->delete($definition)) {
            $this->Flash->success(__('The definition has been deleted.'));
        } else {
            $this->Flash->error(__('The definition could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
