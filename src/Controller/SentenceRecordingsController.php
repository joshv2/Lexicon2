<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Log\Log;
/**
 * SentenceRecordings Controller
 *
 * @property \App\Model\Table\SentenceRecordingsTable $SentenceRecordings
 * @method \App\Model\Entity\SentenceRecording[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SentenceRecordingsController extends AppController
{
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    
    
    public function index()
    {
        $this->paginate = [
            'contain' => ['Sentences'],
        ];
        $sentenceRecordings = $this->paginate($this->SentenceRecordings);

        $this->set(compact('sentenceRecordings'));
    }

    /**
     * View method
     *
     * @param string|null $id Sentence Recording id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sentenceRecording = $this->SentenceRecordings->get($id, [
            'contain' => ['Sentences'],
        ]);

        $this->set(compact('sentenceRecording'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        array_map([$this, 'loadModel'], ['Sentences']);
        $sentences = $this->Sentences->get_sentences($id);
        $sentenceRecording = $this->SentenceRecordings->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $postData['user_id'] = $this->request->getSession()->read('Auth.id');
            $soundFiles = $this->request->getUploadedFiles();
            // send soundfiles and the controller making the request
            if ($this->Processfile->areThereAnyFiles($soundFiles)) {
                if ($this->Processfile->checkFormats($soundFiles)) {
                    $postData['sound_file'] = $this->Processfile->processSoundfiles($soundFiles, $controller = $this->request->getParam('controller'), $id = $id);
                    if (sizeof($postData['sound_file']) > 0) {
                        if (null !== $this->request->getSession()->read('Auth.username') 
                                && 'superuser' == $this->request->getSession()->read('Auth.role')){
                                    $datefortimestamp = date('Y-m-d h:i:s', time());
                                    $postData['sentence_id'] = $id;
                                    $postData['approved'] = 1;
                                    $postData['approved_date'] = $datefortimestamp;
                                    $postData['approving_user_id'] = $this->request->getSession()->read('Auth.id');

                                    $this->Processfile->convertMP3($postData['sound_file']);
                                    $postData['sound_file'] = implode(' ,', $postData['sound_file']);
                                    
                        } 
                        
                        else {
                            $postData['sound_file'] = implode(' ,', $postData['sound_file']);
                            $postData['sentence_id'] = $id;
                            $postData['approved'] = 0;
                        }
            
                    
                    
                        $sentenceRecording = $this->SentenceRecordings->patchEntity($sentenceRecording, $postData);
                        if ($this->SentenceRecordings->save($sentenceRecording)) {
                            Log::info('Sentence Recording \/\/ ' . $this->request->getSession()->read('Auth.username') . ' added a sentence recoding for sentence ID ' . $id . ' \/\/ '. $sentences[0]->word_id . ' \/\/ ' . $id, ['scope' => ['events']]);
                            $this->Flash->success(__('The sentence recording has been saved.'));
                            
                            return $this->redirect(['action' => 'success']);
                        }
                    } else {
                        $this->Flash->error(__('Please record or upload a file in MP3 format.'));
                    }
                } else {
                    $this->Flash->error(__('Please record or upload a recording before submitting.'));
                }
            } else {
                $this->Flash->error(__('The sentence recording could not be saved. Please, try again.'));
            }
        }
        //$sentences = $this->SentenceRecordings->Sentences->find('list', ['limit' => 200]);
        $this->set(compact('sentenceRecording', 'sentences'));
        

    }

    public function manage($wordid = null, $sentenceid = null) 
    {
        array_map([$this, 'loadModel'], ['Sentences', 'Words']);
        $assocWord = $this->Words->get($wordid);
        $assocSentence = $this->Sentences->get($sentenceid);
        $sentRecs =  $this->SentenceRecordings->get_recordings($sentenceid);
        //$requested_pronunciations = $this->Pronunciations->find()->where(['word_id' => $wordid])->contain(['RecordingUsers', 'ApprovingUsers'])->order(['display_order' => 'ASC']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $success = 0;
            foreach ($postData['recordings'] as $recording_edit) {

                $recording = $this->SentenceRecordings->get($recording_edit['id'], [
                    'contain' => [],
                ]);
                $recording = $this->SentenceRecordings->patchEntity($recording, $recording_edit);
                if ($this->SentenceRecordings->save($recording)) {
                    //$this->Flash->success(__('The pronunciation has been saved.'));

                    //return $this->redirect(['action' => 'index']);
                    $success += 1;
                } else {
                    $this->Flash->error(__('The recording order could not be saved. Please, try again.'));
                }
            }
            if ($success == count($postData['recordings'])) {
                Log::info($this->request->getSession()->read('Auth.username') . ' ranked ' . $recording->sentence_id , ['scope' => ['events']]);
                $this->Flash->success(__('The rankings have been saved.'));
                return $this->redirect($this->referer());
            }

        }   
        //$words = $this->Pronunciations->Words->find('list', ['limit' => 200]);
        $this->set(compact('assocWord', 'assocSentence', 'sentRecs'));
        $this->render('ranking');
    }

    public function approve($id = null){
        $this->request->allowMethod(['post']);
        array_map([$this, 'loadModel'], ['Words']);
        //$word =  $this->Words->get($wordid);

        $datefortimestamp = date('Y-m-d h:i:s', time());
        $sentenceRec = $this->SentenceRecordings->get($id, [
            'contain' => ['Sentences']
        ]);
        $sentenceRec->approved = 1;
        $sentenceRec->approved_date = $datefortimestamp;
        $sentenceRec->approving_user_id = $this->request->getSession()->read('Auth.id');
        $sentenceRec->notes = '';
        
        
        if ($this->SentenceRecordings->save($sentenceRec)) {
            $this->converttomp3($sentenceRec->sound_file);
            Log::info('Sentence Recording \/\/ ' . $this->request->getSession()->read('Auth.username') . ' approved ' . strip_tags($sentenceRec->sentence->sentence) . ' \/\/ '. $sentenceRec->sentence->word_id . ' \/\/ ' . $sentenceRec->sentence->id, ['scope' => ['events']]);
            $this->Flash->success(__('The recording has been approved.'));
        } else {
            $this->Flash->error(__('The recording could not be approved. Please, try again.'));
        }
       
        return $this->redirect($this->referer());
    }

    /**
     * Edit method
     *
     * @param string|null $id Sentence Recording id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sentenceRecording = $this->SentenceRecordings->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sentenceRecording = $this->SentenceRecordings->patchEntity($sentenceRecording, $this->request->getData());
            if ($this->SentenceRecordings->save($sentenceRecording)) {
                $this->Flash->success(__('The sentence recording has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sentence recording could not be saved. Please, try again.'));
        }
        $sentences = $this->SentenceRecordings->Sentences->find('list', ['limit' => 200]);
        $this->set(compact('sentenceRecording', 'sentences'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sentence Recording id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sentenceRecording = $this->SentenceRecordings->get($id);
        if ($this->SentenceRecordings->delete($sentenceRecording)) {
            $this->Flash->success(__('The sentence recording has been deleted.'));
        } else {
            $this->Flash->error(__('The sentence recording could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function choose($id = null)
    {
        if( $this->request->is('post') ) {
            $data = $this->request->getData();
            $this->redirect('/sentenceRecordings/add/'.$data['sentenceToRecord']);
        } else {
        array_map([$this, 'loadModel'], ['Words', 'Sentences']);
        $wordResult = $this->Words->get_word_for_view($id);
        $word = $wordResult[0];
        $this->set(compact('word'));
        }
    }

    public function success(){

    }

    public function tips() {
    }
}
