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
class PronunciationsController extends AppController {
    
    public function initialize(): void {
        parent::initialize();
        $this->loadComponent('ProcessFile');
        //$this->loadComponent('LoadORTD');

    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index() {
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
    public function view($id = null) {
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
    public function add($id = null) {
        //array_map([$this, 'loadModel'], ['Words']);
        
        $pronunciation = $this->Pronunciations->newEmptyEntity();
        $word =  $this->fetchTable('Words')->get($id);
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            // Process sound files (optional)
            $postData['user_id'] = $this->request->getSession()->read('Auth.id');

            // Ensure association field exists even if the form name changes.
            if (empty($postData['word_id'])) {
                $postData['word_id'] = $id;
            }

            // Avoid nulls for optional text fields.
            if (!array_key_exists('notes', $postData) || $postData['notes'] === null) {
                $postData['notes'] = '';
            }

            $soundFiles = $this->request->getUploadedFiles();
            $hasFiles = $this->Processfile->areThereAnyFiles($soundFiles);

            if ($hasFiles) {
                if (!$this->Processfile->checkFormats($soundFiles)) {
                    $this->Flash->error(__('Please record or upload a file in MP3 format.'));
                    goto render;
                }

                $processedFiles = $this->Processfile->processSoundfiles(
                    $soundFiles,
                    $controller = $this->request->getParam('controller'),
                    $id = $id
                );

                if (!empty($processedFiles)) {
                    if (null !== $this->request->getSession()->read('Auth.username')
                        && 'superuser' == $this->request->getSession()->read('Auth.role')) {
                        $datefortimestamp = date('Y-m-d h:i:s', time());
                        $postData['sentence_id'] = $id;
                        $postData['approved'] = 1;
                        $postData['approved_date'] = $datefortimestamp;
                        $postData['approving_user_id'] = $this->request->getSession()->read('Auth.id');

                        $this->Processfile->convertMP3($processedFiles);
                    } else {
                        $postData['sentence_id'] = $id;
                        $postData['approved'] = 0;
                    }

                    $postData['sound_file'] = implode(' ,', $processedFiles);
                } else {
                    // No usable recording after processing; treat as no recording.
                    $postData['sound_file'] = '';
                }
            } else {
                $postData['sound_file'] = '';
            }

            $spelling = trim((string)($postData['spelling'] ?? ''));
            $phonetic = trim((string)($postData['pronunciation'] ?? ''));
            if ($postData['sound_file'] === '' && $spelling === '' && $phonetic === '') {
                $this->Flash->error(__('Please enter a spelling and/or phonetic spelling, or attach a recording.'));
                goto render;
            }

            if (!array_key_exists('approved', $postData) || $postData['approved'] === null) {
                $postData['approved'] = 0;
            }

            $pronunciation = $this->Pronunciations->patchEntity($pronunciation, $postData);
            if ($this->Pronunciations->save($pronunciation)) {
                Log::info('Pronunciation \/\/ ' . $this->request->getSession()->read('Auth.username') . ' added a pronunciation for ' . $word->spelling . ' \/\/ ' . $word->id, ['scope' => ['events']]);
                $this->Flash->success(__('The pronunciation has been saved.'));

                return $this->redirect(['controller' => 'Words', 'action' => 'view', $word->id]);
            }

            $this->Flash->error(__('The pronunciation could not be saved. Please, try again.'));
        }    

        render:
        $words = $this->Pronunciations->Words->find(type: 'list', options: ['limit' => 200]);

        $this->set(compact('pronunciation', 'words', 'word'));
    }

    public function manage($wordid = null) {
        //array_map([$this, 'loadModel'], ['Words']);
        $word =  $this->fetchTable('Words')->get($wordid);
        $requested_pronunciations = $this->Pronunciations->find()
                                            ->where(['word_id' => $wordid])
                                            ->contain(['RecordingUsers', 'ApprovingUsers'])
                                            ->order(['display_order' => 'ASC']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $success = 0;
            $attempted = 0;

            $pronunciations = $postData['pronunciations'] ?? [];
            foreach ($pronunciations as $pronunciationEdit) {
                if (empty($pronunciationEdit['id']) || !array_key_exists('display_order', $pronunciationEdit)) {
                    continue;
                }

                $attempted += 1;
                $pronunciation = $this->Pronunciations->get($pronunciationEdit['id'], [
                    'contain' => [],
                ]);

                $saveData = [
                    'display_order' => $pronunciationEdit['display_order'],
                ];
                $pronunciation = $this->Pronunciations->patchEntity($pronunciation, $saveData);

                if ($this->Pronunciations->save($pronunciation)) {
                    $success += 1;
                } else {
                    $this->Flash->error(__('The pronunciation order could not be saved. Please, try again.'));
                }
            }

            if ($attempted > 0 && $success === $attempted) {
                Log::info($this->request->getSession()->read('Auth.username') . ' updated pronunciation rankings for word ' . $wordid, ['scope' => ['events']]);
                $this->Flash->success(__('The rankings have been saved.'));
                return $this->redirect(['action' => 'manage', $wordid]);
            }

            if ($attempted === 0) {
                $this->Flash->error(__('No rankings were submitted.'));
                return $this->redirect(['action' => 'manage', $wordid]);
            }

        }   
        $words = $this->Pronunciations->Words->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('requested_pronunciations', 'words', 'word'));
        $this->render('ranking');
    }

    /**
     * Deny method (separate from edit)
     *
     * @param string|int|null $wordid Word id.
     * @param string|int|null $id Pronunciation id.
     * @return \Cake\Http\Response|null|void
     */
    public function deny($wordid = null, $id = null)
    {
        if (empty($id)) {
            $this->Flash->error(__('No pronunciation id provided.'));
            return $this->redirect(['action' => 'manage', $wordid]);
        }

        try {
            $pronunciation = $this->Pronunciations->get($id, [
                'contain' => [],
            ]);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            $this->Flash->error(__('Pronunciation not found.'));
            return $this->redirect(['action' => 'manage', $wordid]);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $postData['approved'] = -1;
            $postData['approving_user_id'] = $this->request->getSession()->read('Auth.id');
            $postData['approved_date'] = date('Y-m-d h:i:s', time());

            $pronunciation = $this->Pronunciations->patchEntity($pronunciation, $postData);
            if ($this->Pronunciations->save($pronunciation)) {
                Log::info('Pronunciation \/\/ ' . $this->request->getSession()->read('Auth.username') . ' denied ' . $pronunciation->spelling . ' \/\/', ['scope' => ['events']]);
                $this->Flash->success(__('The pronunciation has been denied.'));
                return $this->redirect(['action' => 'manage', $wordid]);
            }

            $this->Flash->error(__('The pronunciation could not be saved. Please, try again.'));
        }

        $words = $this->Pronunciations->Words->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('pronunciation', 'words'));

        // Reuse the existing deny form UI.
        $this->render('edit');
    }

    /**
     * Edit method
     *
     * @param string|null $id Pronunciation id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($wordid, $id = null)
    {
        if (empty($id)) {
            $this->Flash->error(__('No pronunciation id provided.'));
            return $this->redirect(['action' => 'manage', $wordid]);
        }

        // Inline editing happens on the manage page; do not serve a separate edit screen.
        if ($this->request->is('get')) {
            return $this->redirect(['action' => 'manage', $wordid]);
        }

        try {
            $pronunciation = $this->Pronunciations->get($id, [
                'contain' => [],
            ]);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            $this->Flash->error(__('Pronunciation not found.'));
            return $this->redirect(['action' => 'manage', $wordid]);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $rows = $postData['pronunciations'] ?? [];

            $rowData = null;
            foreach ($rows as $row) {
                if (!empty($row['id']) && (string)$row['id'] === (string)$id) {
                    $rowData = $row;
                    break;
                }
            }

            if ($rowData === null) {
                $this->Flash->error(__('No pronunciation data was submitted.'));
                return $this->redirect(['action' => 'manage', $wordid]);
            }

            $saveData = [
                'spelling' => $rowData['spelling'] ?? $pronunciation->spelling,
                'pronunciation' => $rowData['pronunciation'] ?? $pronunciation->pronunciation,
            ];

            $pronunciation = $this->Pronunciations->patchEntity($pronunciation, $saveData);
            if ($this->Pronunciations->save($pronunciation)) {
                Log::info('Pronunciation \/\/ ' . $this->request->getSession()->read('Auth.username') . ' edited pronunciation ' . $pronunciation->id . ' \/\/', ['scope' => ['events']]);
                $this->Flash->success(__('The pronunciation has been saved.'));
                return $this->redirect(['action' => 'manage', $wordid]);
            }

            $this->Flash->error(__('The pronunciation could not be saved. Please, try again.'));
            return $this->redirect(['action' => 'manage', $wordid]);
        }

        return $this->redirect(['action' => 'manage', $wordid]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Pronunciation id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null, $wordid = null)
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

    public function approve($id = null, $wordid = null){
        $this->request->allowMethod(['post']);
        //array_map([$this, 'loadModel'], ['Words']);
        $word =  $this->fetchTable('Words')->get($wordid);

        $datefortimestamp = date('Y-m-d h:i:s', time());
        $pronunciation = $this->Pronunciations->get($id);
        $pronunciation->approved = 1;
        $pronunciation->approved_date = $datefortimestamp;
        $pronunciation->approving_user_id = $this->request->getSession()->read('Auth.id');
        $pronunciation->notes = '';
        
        if (1 == $word->approved) {
            if ($this->Pronunciations->save($pronunciation)) {
                $this->Processfile->converttomp3($pronunciation->sound_file);
                Log::info('Pronunciation \/\/ ' . $this->request->getSession()->read('Auth.username') . ' approved ' . $pronunciation->spelling . ' \/\/ ' . $wordid, ['scope' => ['events']]);
                $this->Flash->success(__('The pronunciation has been approved.'));
            } else {
                $this->Flash->error(__('The pronunciation could not be approved. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('The word for this pronunciation needs to be approved prior to the pronuinciation being approved.'));
        }
        return $this->redirect(['action' => 'manage', $wordid]);
    }

    
}
