<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Core\Configure;
/**
 * Suggestions Controller
 *
 * @property \App\Model\Table\SuggestionsTable $Suggestions
 * @method \App\Model\Entity\Suggestion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SuggestionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Words', 'Users'],
        ];
        $suggestions = $this->paginate($this->Suggestions);

        $this->set(compact('suggestions'));
    }

    /**
     * View method
     *
     * @param string|null $id Suggestion id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $suggestion = $this->Suggestions->get($id, [
            'contain' => ['Words', 'Users'],
        ]);

        $this->set(compact('suggestion'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        //array_map([$this, 'loadModel'], ['Words']);
        $suggestion = $this->Suggestions->newEmptyEntity();
        if ($this->request->is('post')) {
            $suggestion = $this->Suggestions->patchEntity($suggestion, $this->request->getData());
            if ($this->Suggestions->save($suggestion)) {
                $this->Flash->success(__('The suggestion has been saved.'));

                return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
            }
            $this->Flash->error(__('The suggestion could not be saved. Please, try again.'));
        }
        $word = $this->fetchTable('Words')->get($id);
        $recaptcha_user = Configure::consume('recaptcha_user');
        //$users = $this->Suggestions->Users->find('list', ['limit' => 200]);
        $this->set(compact('suggestion', 'word', 'recaptcha_user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Suggestion id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $suggestion = $this->Suggestions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            //reCaptcha authentication
            if (null == $this->request->getSession()->read('Auth.username')){
                $recaptcha = $postData['g-recaptcha-response'];
                $google_url = "https://www.google.com/recaptcha/api/siteverify";
                $secret = Configure::consume('recaptcha_secret');
                $ip = $_SERVER['REMOTE_ADDR'];
                $url = $google_url . "?secret=" . $secret . "&response=" . $recaptcha ."&remoteip=" . $ip;
                $http = new Client();

                $res = $http->get($url);
                $json = $res->getJson();
                $validationSet = 'notloggedin';
            } else {
                $json['success'] = 'false';
                $validationSet = 'default';
            }

            $suggestion = $this->Suggestions->patchEntity($suggestion, $this->request->getData());
            if ($json['success'] == "true") {
                if ($this->Suggestions->save($suggestion)) {
                    $this->Flash->success(__('The suggestion has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->Flash->error(__('The suggestion could not be saved. Please, try again.'));
        }
        $words = $this->Suggestions->Words->find('list', ['limit' => 200]);
        $users = $this->Suggestions->Users->find('list', ['limit' => 200]);
        $this->set(compact('suggestion', 'words', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Suggestion id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $suggestion = $this->Suggestions->get($id);
        if ($this->Suggestions->delete($suggestion)) {
        } else {
            $this->Flash->error(__('The suggestion could not be deleted. Please, try again.'));
        }

        return $this->redirect($this->referer());
    }
}
