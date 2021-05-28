<?php

namespace App\Controller\Moderators;

use App\Controller\AppController;

class PanelController extends AppController {
    public function index()
        {
            array_map([$this, 'loadModel'], ['Words', 'Suggestions']);
            
            /*if ($this->request->query('dismiss_id') != null){
                $suggestid = $this->request->query['dismiss_id'];
                $this->loadModel('Suggestion');
                $data = array('id' => $suggestid, 'status' => 'read');
                $this->Suggestion->save($data);
            }
            
            $this->helpers[] = 'NiceTime';

            $this->Edit->recursive = -1;
            $this->loadModel('Suggestion');
            $pendingSuggestions = $this->Suggestion->find('all', 
                array(
                    'conditions' => array(
                        'status =' => 'new'
                        ),
                    'order' => array(
                        'submitted_on' => 'DESC'
                        )
                    )
                );*/
            
            $newWords = $this->Words->get_pending_words();

            $pendingSuggestions = $this->Suggestions->find('all')
                ->where(['status =' => 'unread'])
                ->contain(['Words']);

            /*$newEdits = $this->Edit->find('all', 
                array('conditions' => array(
                    'status =' => 'Pending',
                    'word_id !=' => NULL
                    ),
                    'order' => array(
                        'created' => 'ASC'
                    )
                )
            );*/

            $this->set(compact('newWords', 'pendingSuggestions')); //, 'newEdits', 'pendingSuggestions'
            
            
            $this->viewBuilder()->setLayout('moderators');
            $this->render('edits');

            
        }
    }