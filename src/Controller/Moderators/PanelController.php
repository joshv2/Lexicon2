<?php

namespace App\Controller\Moderators;

use App\Controller\AppController;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Log\Log;

class PanelController extends AppController {

    public function initialize(): void
    {
        parent::initialize();

        Log::write('debug', 'PanelController initialize method called.');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        Log::write('debug', 'PanelController beforeFilter method called.');

        // Check if the user is logged in for all actions in this controller
        if (!$this->request->getSession()->check('Auth.id')) {
            Log::write('debug', 'User not logged in. Redirecting to login.');

            // Redirect to the AuthController's login action with the current URL as the redirect parameter
            return $this->redirect([
                'prefix' => false, 
                'controller' => 'Auth',
                'action' => 'login',
                '?' => ['redirect' => $this->request->getRequestTarget()]
            ]);
        }
    }

    public function index()
    {
        $remainingcredits = $this->getremainingcredits();
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $userLevel = $this->request->getSession()->read('Auth.role');
        $userid = $this->request->getSession()->read('Auth.id');

        if ('user' == $userLevel) {
            $submittedPronunciations = $this->fetchTable('Pronunciations')->get_user_pronunciations($userid);
            $noPronunciations = $this->fetchTable('Words')->get_words_with_no_pronunciations($sitelang->id);
            $pendingPronunciations = [];
            $allPronunciations = [];
            $pendingSentenceRecordings = [];
        } elseif ('superuser' == $userLevel) {
            $submittedPronunciations = $this->fetchTable('Pronunciations')->get_user_pronunciations($userid);
            $noPronunciations = $this->fetchTable('Words')->get_words_with_no_pronunciations($sitelang->id);
            $pendingPronunciations = $this->fetchTable('Pronunciations')->get_pending_pronunciations($sitelang->id);
            $allPronunciations = $this->fetchTable('Pronunciations')->get_all_pronunciations($sitelang->id);
            $pendingSentenceRecordings = $this->fetchTable('Sentences')->get_sentences_with_pending_recordings($sitelang->id);
        }

        $submittedWords = $this->fetchTable('Words')->get_user_words($userid, $sitelang->id);
        $newWords = $this->fetchTable('Words')->get_pending_words($sitelang->id);
        $pendingSuggestions = $this->fetchTable('Suggestions')->get_pending_suggestions($sitelang->id);

        $this->set(compact(
            'userid',
            'newWords',
            'pendingSuggestions',
            'submittedPronunciations',
            'submittedWords',
            'userLevel',
            'pendingPronunciations',
            'allPronunciations',
            'noPronunciations',
            'sitelang',
            'remainingcredits',
            'pendingSentenceRecordings'
        ));

        $this->viewBuilder()->setLayout('moderators');
        $this->render('edits');
    }

    public function logs()
    {
        $remainingcredits = $this->getremainingcredits();
        $userid = $this->request->getSession()->read('Auth.id');
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $userLevel = $this->request->getSession()->read('Auth.role');
        $file = new File(LOGS . 'events.log');
        $eventfile = $file->read();
        $filerows = explode("\n", $eventfile);
        $wordLogs = [];
        $pronunciationLogs = [];
        $sentenceRecordingLogs = [];
        foreach ($filerows as $row) {
            $findFirstInfo = strpos($row, "Info:");
            $eventTime = substr($row, 0, 19);
            $logData = substr($row, 26);
            $logDataParsed = explode("\/\/", $logData);
            array_unshift($logDataParsed, $eventTime);
            if ('Word' == trim($logDataParsed[1])) {
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
        $remainingcredits = $this->getremainingcredits();
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $userLevel = $this->request->getSession()->read('Auth.role');
        $userid = $this->request->getSession()->read('Auth.id');

        if ('user' == $userLevel) {
            $submittedPronunciations = $this->fetchTable('Pronunciations')->get_user_pronunciations($userid);
            $pendingPronunciations = [];
            $allPronunciations = [];
        } elseif ('superuser' == $userLevel) {
            $submittedPronunciations = $this->fetchTable('Pronunciations')->get_user_pronunciations($userid);
            $pendingPronunciations = $this->fetchTable('Pronunciations')->get_pending_pronunciations();
            $allPronunciations = $this->fetchTable('Pronunciations')->get_all_pronunciations();
        }

        $submittedWords = $this->fetchTable('Words')->get_user_words($userid, $sitelang->id);
        $newWords = $this->fetchTable('Words')->get_pending_words($sitelang->id);
        $pendingSuggestions = $this->fetchTable('Suggestions')->find()
            ->where(['status =' => 'unread'])
            ->contain(['Words']);

        $this->set(compact(
            'userid',
            'newWords',
            'pendingSuggestions',
            'submittedPronunciations',
            'submittedWords',
            'userLevel',
            'pendingPronunciations',
            'allPronunciations',
            'sitelang',
            'remainingcredits'
        ));

        $this->viewBuilder()->setLayout('moderators');
    }
}