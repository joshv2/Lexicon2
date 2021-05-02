<?php

namespace App\Controller\Moderators;

use App\Controller\AppController;

class PanelController extends AppController {
    public function index()
        {
            if ($this->request->query('dismiss_id') != null){
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
                );
            
            $newWords = $this->Edit->find('all', 
                array(
                    'conditions' => array(
                        'status =' => 'Pending',
                        'word_id =' => NULL
                    ),
                    'order' => array(
                        'created' => 'ASC'
                    )
                )
            );

            $newEdits = $this->Edit->find('all', 
                array('conditions' => array(
                    'status =' => 'Pending',
                    'word_id !=' => NULL
                    ),
                    'order' => array(
                        'created' => 'ASC'
                    )
                )
            );

            $this->set(compact('newWords', 'newEdits', 'pendingSuggestions'));
            
            
            $this->layout = 'moderators';
            $this->render('edits');

            
        }
    }