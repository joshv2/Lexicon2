<?php

namespace App\Controller\Moderators;

use App\Controller\AppController;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class PanelController extends AppController {
    public function index()
        {
            array_map([$this, 'loadModel'], ['Words', 'Suggestions', 'Pronunciations']);
            
            $userLevel = $this->request->getSession()->read('Auth.role');
            $userid = $this->request->getSession()->read('Auth.id');
            //debug($userid);
            if('user' == $userLevel){
                $submittedPronunciations = $this->Pronunciations->get_user_pronunciations($this->request->getSession()->read('Auth.id'));
                $pendingPronunciations = [];
            } elseif ('superuser' == $userLevel){
                $submittedPronunciations = $this->Pronunciations->get_user_pronunciations($this->request->getSession()->read('Auth.id'));
                $pendingPronunciations = $this->Pronunciations->get_pending_pronunciations();
            }
            $submittedWords = $this->Words->get_user_words($this->request->getSession()->read('Auth.id'));
            $newWords = $this->Words->get_pending_words();

            $pendingSuggestions = $this->Suggestions->find('all')
                ->where(['status =' => 'unread'])
                ->contain(['Words']);

            $this->set(compact('userid', 'newWords', 'pendingSuggestions', 'submittedPronunciations', 'submittedWords', 'userLevel', 'pendingPronunciations')); //, 'newEdits', 'pendingSuggestions'
            
            
            $this->viewBuilder()->setLayout('moderators');
            $this->render('edits');

            
        }

        public function logs()
        {
            $userid = $this->request->getSession()->read('Auth.id');
            $file = new File(LOGS.'events.log');
            $eventfile = $file->read();
            $filerows = explode("\n", $eventfile);
            $wordLogs = [];
            $pronunciationLogs = [];
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
                }
            }
            $this->set(compact('wordLogs', 'pronunciationLogs', 'userid'));
            $this->viewBuilder()->setLayout('moderators');
        }
    }