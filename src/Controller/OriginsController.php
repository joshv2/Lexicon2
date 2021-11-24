<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Origins Controller
 *
 * @property \App\Model\Table\OriginsTable $Origins
 * @method \App\Model\Entity\Origin[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OriginsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $sitelang = $this->languageinfo();
        $this->paginate = [
            'contain' => ['Languages']
        ];
        $origins = $this->paginate($this->Origins->find('all')->where(['language_id' => $sitelang->id]));

        $this->set(compact('origins'));
    }

    /**
     * View method
     *
     * @param string|null $id Origin id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        
        $origin = $this->Origins->get($id, [
            'contain' => ['Languages', 'Words'],
        ]);

        $this->set(compact('origin'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $origin = $this->Origins->newEmptyEntity();
        $sitelang = $this->languageinfo();
        if ($this->request->is('post')) {
            $origin = $this->Origins->patchEntity($origin, $this->request->getData());
            if ($this->Origins->save($origin)) {
                $this->Flash->success(__('The origin has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The origin could not be saved. Please, try again.'));
        }
        $languages = $this->Origins->Languages->find('list', ['limit' => 200]);
        $words = $this->Origins->Words->find('list', ['limit' => 200]);
        $this->set(compact('origin', 'languages', 'words','sitelang'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Origin id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sitelang = $this->languageinfo();
        $origin = $this->Origins->get($id, [
            'contain' => ['Words'],
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $origin = $this->Origins->patchEntity($origin, $this->request->getData());
            if ($this->Origins->save($origin)) {
                $this->Flash->success(__('The origin has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The origin could not be saved. Please, try again.'));
        }
        $languages = $this->Origins->Languages->find('list', ['limit' => 200]);
        $words = $this->Origins->Words->find('list', ['limit' => 200]);
        $this->set(compact('origin', 'languages', 'words', 'sitelang'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Origin id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $origin = $this->Origins->get($id);
        if ($this->Origins->delete($origin)) {
            $this->Flash->success(__('The origin has been deleted.'));
        } else {
            $this->Flash->error(__('The origin could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
