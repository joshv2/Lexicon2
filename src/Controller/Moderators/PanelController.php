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
            $submittedPronunciations = $this->Pronunciations->get_user_pronunciations($this->request->getSession()->read('Auth.id'));
            $submittedWords = $this->Words->get_user_words($this->request->getSession()->read('Auth.id'));
            $newWords = $this->Words->get_pending_words();

            $pendingSuggestions = $this->Suggestions->find('all')
                ->where(['status =' => 'unread'])
                ->contain(['Words']);

            $this->set(compact('newWords', 'pendingSuggestions', 'submittedPronunciations', 'submittedWords', 'userLevel')); //, 'newEdits', 'pendingSuggestions'
            
            
            $this->viewBuilder()->setLayout('moderators');
            $this->render('edits');

            
        }

        public function logs()
        {
            $file = new File(LOGS.'events.log');
            $eventfile = $file->read();
            $filerows = explode("\n", $eventfile);
            $wordLogs = [];
            $pronciationLogs = [];
            foreach ($filerows as $row){
                $findFirstInfo = strpos("Info:", $row)
                $eventTime = substr($row, 0, 19);
                //$splitTimeandMessage = substr($row, 19);
                //$eventTime = $splitTimeandMessage[0];
                $logData = substr($row, 26);
                $logDataParsed = explode("\/\/", $logData);
                if ('Word' == $logDataParsed[0]){
                    array_unshift($wordLogs, $logDataParsed);
                } elseif ('Pronunciation' == $logDataParsed[0]) {
                    array_unshift($pronunciationLogs, $logDataParsed);
                }
            }
            $this->set(compact('wordLogs', 'pronunciationLogs'));
        }
    }