<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Regions Controller
 *
 * @property \App\Model\Table\RegionsTable $Regions
 * @method \App\Model\Entity\Region[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RegionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $this->paginate = [
            'contain' => ['Languages'],
        ];
        $regions = $this->paginate($this->Regions->find()->where(['language_id' => $sitelang->id]));

        $this->set(compact('regions'));
    }

    /**
     * View method
     *
     * @param string|null $id Region id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $region = $this->Regions->get($id, [
            'contain' => ['Languages', 'Words'],
        ]);

        $this->set(compact('region'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $region = $this->Regions->newEmptyEntity();
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        if ($this->request->is('post')) {
            $region = $this->Regions->patchEntity($region, $this->request->getData());
            if ($this->Regions->save($region)) {
                $this->Flash->success(__('The region has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The region could not be saved. Please, try again.'));
        }
        $languages = $this->Regions->Languages->find(type: 'list', options: ['limit' => 200]);
        $words = $this->Regions->Words->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('region', 'languages', 'words', 'sitelang'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Region id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $region = $this->Regions->get($id, [
            'contain' => ['Words'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $region = $this->Regions->patchEntity($region, $this->request->getData());
            if ($this->Regions->save($region)) {
                $this->Flash->success(__('The region has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The region could not be saved. Please, try again.'));
        }
        $languages = $this->Regions->Languages->find(type: 'list', options: ['limit' => 200]);
        $words = $this->Regions->Words->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('region', 'languages', 'words', 'sitelang'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Region id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $region = $this->Regions->get($id);
        if ($this->Regions->delete($region)) {
            $this->Flash->success(__('The region has been deleted.'));
        } else {
            $this->Flash->error(__('The region could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
