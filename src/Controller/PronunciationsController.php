<?php
declare(strict_types=1);

namespace App\Controller;

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
    public function add()
    {
        $pronunciation = $this->Pronunciations->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            //Process sound files
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
            $pronunciation = $this->Pronunciations->patchEntity($pronunciation, $postData);
            if ($this->Pronunciations->save($pronunciation)) {
                $this->Flash->success(__('The pronunciation has been saved.'));

                return $this->redirect(['prefix' => 'Moderators', 'controller' => 'Panel', 'action' => 'index']);
            }
            $this->Flash->error(__('The pronunciation could not be saved. Please, try again.'));
        }
        $words = $this->Pronunciations->Words->find('list', ['limit' => 200]);
        $this->set(compact('pronunciation', 'words'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Pronunciation id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $pronunciation = $this->Pronunciations->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pronunciation = $this->Pronunciations->patchEntity($pronunciation, $this->request->getData());
            if ($this->Pronunciations->save($pronunciation)) {
                $this->Flash->success(__('The pronunciation has been saved.'));

                return $this->redirect(['action' => 'index']);
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
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pronunciation = $this->Pronunciations->get($id);
        if ($this->Pronunciations->delete($pronunciation)) {
            $this->Flash->success(__('The pronunciation has been deleted.'));
        } else {
            $this->Flash->error(__('The pronunciation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
