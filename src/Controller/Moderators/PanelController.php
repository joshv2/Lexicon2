<?php

namespace App\Controller\Moderators;

use App\Controller\AppController;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class PanelController extends AppController {

    public function index()
        {
            if (null == $this->request->getSession()->read('Auth.username')){
                return $this->redirect('/login');
            } else {
            
                $remainingcredits = 0; //$this->getremainingcredits();
                //array_map([$this, 'loadModel'], ['Words', 'Suggestions', 'Pronunciations', 'Sentences', 'SentenceRecordings']);
                $sitelang = $this->languageinfo();
                $userLevel = $this->request->getSession()->read('Auth.role');
                $userid = $this->request->getSession()->read('Auth.id');
                //debug($userid);
                if('user' == $userLevel){
                    $submittedPronunciations = $this->fetchTable('Pronunciations')->get_user_pronunciations($this->request->getSession()->read('Auth.id'));
                    $noPronunciations = $this->fetchTable('Words')->get_words_with_no_pronunciations($sitelang->id);
                    $pendingPronunciations = [];
                    $allPronunciations = [];
                    $pendingSentenceRecordings = [];
                } elseif ('superuser' == $userLevel){
                    $submittedPronunciations = $this->fetchTable('Pronunciations')->get_user_pronunciations($this->request->getSession()->read('Auth.id'));
                    $noPronunciations = $this->fetchTable('Words')->get_words_with_no_pronunciations($sitelang->id);
                    $pendingPronunciations = $this->fetchTable('Pronunciations')->get_pending_pronunciations($sitelang->id);
                    $allPronunciations = $this->fetchTable('Pronunciations')->get_all_pronunciations($sitelang->id);
                    $pendingSentenceRecordings = $this->fetchTable('Sentences')->get_sentences_with_pending_recordings($sitelang->id);


                    //debug($allPronunciations);
                }
                $submittedWords = $this->fetchTable('Words')->get_user_words($this->request->getSession()->read('Auth.id'), $sitelang->id);
                $newWords = $this->fetchTable('Words')->get_pending_words($sitelang->id);
                $langid = $sitelang->id;
                $pendingSuggestions = $this->fetchTable('Suggestions')->get_pending_suggestions($sitelang->id);

                $this->set(compact('userid', 'newWords', 'pendingSuggestions', 'submittedPronunciations', 'submittedWords', 'userLevel', 'pendingPronunciations', 'allPronunciations', 'noPronunciations', 'sitelang', 'remainingcredits', 'pendingSentenceRecordings')); //, 'newEdits', 'pendingSuggestions'
                
                
                $this->viewBuilder()->setLayout('moderators');
                $this->render('edits');
            }

            
        }

    public function logs()
        {
            $remainingcredits = 0; //$this->getremainingcredits();
            $userid = $this->request->getSession()->read('Auth.id');
            $sitelang = $this->languageinfo();
            $userLevel = $this->request->getSession()->read('Auth.role');
            $file = new File(LOGS.'events.log');
            $eventfile = $file->read();
            $filerows = explode("\n", $eventfile);
            $wordLogs = [];
            $pronunciationLogs = [];
            $sentenceRecordingLogs = [];
            foreach ($filerows as $row){
                $findFirstInfo = strpos($row, "Info:");
                $eventTime = substr($row, 0, 19);
                //$splitTimeandMessage = substr($row, 19);
                //$eventTime = $splitTimeandMessage[0];
                $logData = substr($row, 26);
                $logDataParsed = explode("\/\/", $logData);
                array_unshift($logDataParsed, $eventTime);
                //debug($logDataParsed);
                if ('Word' == trim($logDataParsed[1])){
                    array_unshift($wordLogs, $logDataParsed);
                } elseif ('Pronunciation' == trim($logDataParsed[1])) {
                    array_unshift($pronunciationLogs, $logDataParsed);
                } elseif ('Sentence Recording' == trim($logDataParsed[1])) {
                    array_unshift($sentenceRecordingLogs, $logDataParsed);
                }
            }
            $this->set(compact('wordLogs', 'pronunciationLogs', 'sentenceRecordingLogs', 'userid', 'sitelang', 'userLevel', 'remainingcredits'));
            $this->viewBuilder()->setLayout('moderators');
        }

    public function me()
        {
            $remainingcredits = 0; //$this->getremainingcredits();
            //array_map([$this, 'loadModel'], ['Words', 'Suggestions', 'Pronunciations']);
            $sitelang = $this->languageinfo();
            $userLevel = $this->request->getSession()->read('Auth.role');
            $userid = $this->request->getSession()->read('Auth.id');
            //debug($userid);
            if('user' == $userLevel){
                $submittedPronunciations = $this->fetchTable('Pronunciations')->get_user_pronunciations($this->request->getSession()->read('Auth.id'));
                $pendingPronunciations = [];
                $allPronunciations = [];
            } elseif ('superuser' == $userLevel){
                $submittedPronunciations = $this->fetchTable('Pronunciations')->get_user_pronunciations($this->request->getSession()->read('Auth.id'));
                $pendingPronunciations = $this->fetchTable('Pronunciations')->get_pending_pronunciations();
                $allPronunciations = $this->fetchTable('Pronunciations')->get_all_pronunciations();
                //debug($allPronunciations);
            }
            $submittedWords = $this->fetchTable('Words')->get_user_words($this->request->getSession()->read('Auth.id'), $sitelang->id);
            $newWords = $this->fetchTable('Words')->get_pending_words($sitelang->id);

            $pendingSuggestions = $this->fetchTable('Suggestions')->find()
                ->where(['status =' => 'unread'])
                ->contain(['Words']);

            $this->set(compact('userid', 'newWords', 'pendingSuggestions', 'submittedPronunciations', 'submittedWords', 'userLevel', 'pendingPronunciations', 'allPronunciations', 'sitelang', 'remainingcredits')); //, 'newEdits', 'pendingSuggestions'


            $this->viewBuilder()->setLayout('moderators');
            //$this->render('edits');

            
        }
}