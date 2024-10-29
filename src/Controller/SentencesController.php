<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Log\Log;

/**
 * Sentences Controller
 *
 * @property \App\Model\Table\SentencesTable $Sentences
 * @method \App\Model\Entity\Sentence[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SentencesController extends AppController
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
        $sentences = $this->paginate($this->Sentences);

        $this->set(compact('sentences'));
    }

    /**
     * View method
     *
     * @param string|null $id Sentence id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sentence = $this->Sentences->get($id, [
            'contain' => ['Words', 'SentenceRecordings'],
        ]);

        $this->set(compact('sentence'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sentence = $this->Sentences->newEmptyEntity();
        if ($this->request->is('post')) {
            $sentence = $this->Sentences->patchEntity($sentence, $this->request->getData());
            if ($this->Sentences->save($sentence)) {
                $this->Flash->success(__('The sentence has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sentence could not be saved. Please, try again.'));
        }
        $words = $this->Sentences->Words->find('list', ['limit' => 200]);
        $this->set(compact('sentence', 'words'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sentence id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sentence = $this->Sentences->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sentence = $this->Sentences->patchEntity($sentence, $this->request->getData());
            if ($this->Sentences->save($sentence)) {
                $this->Flash->success(__('The sentence has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sentence could not be saved. Please, try again.'));
        }
        $words = $this->Sentences->Words->find('list', ['limit' => 200]);
        $this->set(compact('sentence', 'words'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sentence id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sentence = $this->Sentences->get($id);
        if ($this->Sentences->delete($sentence)) {
            $this->Flash->success(__('The sentence has been deleted.'));
        } else {
            $this->Flash->error(__('The sentence could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    public function ajaxdelete($id = null)
    {
        $response = [];

        if( $this->request->is('post') ) {
            $sentence = $this->Sentences->get($id);
            if ($this->Sentences->delete($sentence)) {
                Log::info('Sentence \/\/ ' . $this->request->getSession()->read('Auth.username') . ' deleted ' . $sentence->sentence . ' \/\/', ['scope' => ['events']]);
                $data['success'] = 1;
            } else {
                $data['success'] = 0;
            }
            //debug($response['spelling']);
        } else {
            $data['success'] = 0;
        }

        $this->response = $this->response->withType('application/json')
                                     ->withStringBody(json_encode($data));
    
        return $this->response;    }
}
