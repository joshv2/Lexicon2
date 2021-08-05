<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Log\Log;
/**
 * Pronunciations Controller
 *
 * @property \App\Model\Table\PronunciationsTable $Pronunciations
 * @method \App\Model\Entity\Pronunciation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PronunciationsController extends AppController
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
        $pronunciations = $this->paginate($this->Pronunciations);

        $this->set(compact('pronunciations'));
    }

    /**
     * View method
     *
     * @param string|null $id Pronunciation id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pronunciation = $this->Pronunciations->get($id, [
            'contain' => ['Words'],
        ]);

        $this->set(compact('pronunciation'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        array_map([$this, 'loadModel'], ['Words']);
        
        $pronunciation = $this->Pronunciations->newEmptyEntity();
        $word =  $this->Words->get($id);
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            //Process sound files
            $postData['user_id'] = $this->request->getSession()->read('Auth.id');
            $soundFiles = $this->request->getUploadedFiles();
            $i = 0;
            foreach ($soundFiles as $soundFile) {
                $name = $soundFile->getClientFilename();
                $finalname = str_replace(' ', '', $postData['spelling']) . time() . $i . '.webm';
                $targetPath = WWW_ROOT. 'recordings'. DS . $finalname;
                $type = $soundFile->getClientMediaType();
                if ($type == 'audio/webm') {
                    if(!empty($name)){
                        if ($soundFile->getSize() > 0 && $soundFile->getError() == 0) {
                            $soundFile->moveTo($targetPath);
                            $postData['sound_file'] = $finalname;
                        }
                    }
                }
                $i++;
            }

            if (null !== $this->request->getSession()->read('Auth.username') && 'superuser' == $this->request->getSession()->read('Auth.role')){
                $datefortimestamp = date('Y-m-d h:i:s', time());
                $postData['approved'] = 1;
                $postData['approved_date'] = $datefortimestamp;
                $postData['approving_user_id'] = $this->request->getSession()->read('Auth.id');
            }

            $pronunciation = $this->Pronunciations->patchEntity($pronunciation, $postData);
            if ($this->Pronunciations->save($pronunciation)) {
                Log::info('Pronunciation \/\/ ' . $this->request->getSession()->read('Auth.username') . ' added a pronunciation for ' . $word->spelling . ' \/\/ ' . $word->id, ['scope' => ['events']]);
                $this->Flash->success(__('The pronunciation has been saved.'));

                return $this->redirect(['controller' => 'Words', 'action' => 'view', $word->id]);
            }
            $this->Flash->error(__('The pronunciation could not be saved. Please, try again.'));
        }
        $words = $this->Pronunciations->Words->find('list', ['limit' => 200]);
        $this->set(compact('pronunciation', 'words', 'word'));
    }

    public function manage($wordid = null) 
    {
        array_map([$this, 'loadModel'], ['Words']);
        $word =  $this->Words->get($wordid);
        $requested_pronunciations = $this->Pronunciations->find()->where(['word_id' => $wordid])->contain(['RecordingUsers', 'ApprovingUsers'])->order(['display_order' => 'ASC']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $success = 0;
            foreach ($postData['pronunciations'] as $pronunciation_edit) {

                $pronunciation = $this->Pronunciations->get($pronunciation_edit['id'], [
                    'contain' => [],
                ]);
                $pronunciation = $this->Pronunciations->patchEntity($pronunciation, $pronunciation_edit);
                if ($this->Pronunciations->save($pronunciation)) {
                    //$this->Flash->success(__('The pronunciation has been saved.'));

                    //return $this->redirect(['action' => 'index']);
                    $success += 1;
                } else {
                    $this->Flash->error(__('The pronunciation could not be saved. Please, try again.'));
                }
            }
            if ($success == count($postData['pronunciations'])) {
                Log::info($this->request->getSession()->read('Auth.username') . ' ranked ' . $pronunciation->spelling , ['scope' => ['events']]);
                $this->Flash->success(__('The rankings have been saved.'));
                return $this->redirect(['controller' => 'Words', 'action' => 'view', $wordid]);
            }

        }   
        $words = $this->Pronunciations->Words->find('list', ['limit' => 200]);
        $this->set(compact('requested_pronunciations', 'words', 'word'));
        $this->render('ranking');
    }

    /**
     * Edit method
     *
     * @param string|null $id Pronunciation id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null, $wordid)
    {
        $pronunciation = $this->Pronunciations->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $postData['approving_user_id'] = $this->request->getSession()->read('Auth.id');
            $postData['approved_date'] = date('Y-m-d h:i:s', time());
            $pronunciation = $this->Pronunciations->patchEntity($pronunciation, $postData);
            if ($this->Pronunciations->save($pronunciation)) {
                Log::info('Pronunciation \/\/ ' . $this->request->getSession()->read('Auth.username') . ' denied ' . $pronunciation->spelling  . ' \/\/', ['scope' => ['events']]);
                $this->Flash->success(__('The pronunciation has been denied.'));

                return $this->redirect(['action' => 'manage', $wordid]);
            }
            $this->Flash->error(__('The pronunciation could not be saved. Please, try again.'));
        }
        $words = $this->Pronunciations->Words->find('list', ['limit' => 200]);
        $this->set(compact('pronunciation', 'words'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Pronunciation id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null, $wordid)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pronunciation = $this->Pronunciations->get($id);
        if ($this->Pronunciations->delete($pronunciation)) {
            Log::info('Pronunciation \/\/ ' . $this->request->getSession()->read('Auth.username') . ' deleted ' . $pronunciation->spelling . ' \/\/', ['scope' => ['events']]);
            $this->Flash->success(__('The pronunciation has been deleted.'));
        } else {
            $this->Flash->error(__('The pronunciation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'manage', $wordid]);
    }

    public function approve($id = null, $wordid){
        $this->request->allowMethod(['post']);
        $datefortimestamp = date('Y-m-d h:i:s', time());
        $pronunciation = $this->Pronunciations->get($id);
        $pronunciation->approved = 1;
        $pronunciation->approved_date = $datefortimestamp;
        $pronunciation->approving_user_id = $this->request->getSession()->read('Auth.id');
        $pronunciation->notes = '';
        if ($this->Pronunciations->save($pronunciation)) {
            Log::info('Pronunciation \/\/ ' . $this->request->getSession()->read('Auth.username') . ' approved ' . $pronunciation->spelling . ' \/\/ ' . $wordid, ['scope' => ['events']]);
            $this->Flash->success(__('The word has been approved.'));
        } else {
            $this->Flash->error(__('The word could not be approved. Please, try again.'));
        }

        return $this->redirect(['action' => 'manage', $wordid]);
    }

    
}
