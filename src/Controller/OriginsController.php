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
    public function index(?int $wordId = null)
    {
        $query = $this->Origins
                    ->find()
                    ->contain(['Words']);
        
        if($wordId){
            $query->where(['Origins.word_id' => $wordId]);
        }
        $origins = $this->paginate($this->Origins);
        /*$sitelang = $this->request->getAttribute('sitelang');
        $this->paginate = [
            'contain' => ['Languages']
        ];
        $origins = $this->paginate($this->Origins->find()->where(['language_id' => $sitelang->id]));
        */
        $this->set(compact('origins', 'wordId'));
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
        $sitelang = $this->request->getAttribute('sitelang');
        if ($this->request->is('post')) {
            $origin = $this->Origins->patchEntity($origin, $this->request->getData());
            if ($this->Origins->save($origin)) {
                $this->Flash->success(__('The origin has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The origin could not be saved. Please, try again.'));
        }
        $languages = $this->Origins->Languages->find(type: 'list', options: ['limit' => 200]);
        $words = $this->Origins->Words->find(type: 'list', options: ['limit' => 200]);
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
        $sitelang = $this->request->getAttribute('sitelang');
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
        $languages = $this->Origins->Languages->find(type: 'list', options: ['limit' => 200]);
        $words = $this->Origins->Words->find(type: 'list', options: ['limit' => 200]);
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

    public function word(int $wordId)
    {
        $word = $this->Origins->Words->get($wordId);
        $query = $this->Origins->getOriginsForWordIdQuery($wordId);
        $origins = $this->paginate($query);
        $this->set(compact('origins', 'word'));
        $this->render('index');
    }
}
