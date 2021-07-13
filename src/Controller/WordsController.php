<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Collection\Collection;
/**
 * Words Controller
 *
 * @property \App\Model\Table\WordsTable $Words
 * @method \App\Model\Entity\Word[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WordsController extends AppController
{
    
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        array_map([$this, 'loadModel'], ['Words', 'Origins', 'Regions', 'Types', 'Dictionaries']);

        //private function 
        
        $origins = $this->Origins->top_origins_for_home();
        $origins['other'] = 'Other';
        $regions = $this->Regions->top_regions_for_home();
        $regions['other'] = 'Other';
        $types = $this->Types->top_types_for_home();
        $types['other'] = 'Other';
        $dictionaries = $this->Dictionaries->top_dictionaries();
        $dictionaries['other'] = 'Other';
        $dictionaries['none'] = 'None';

        $originvalue = $this->request->getQuery('origin');
        $regionvalue = $this->request->getQuery('region');
        $typevalue = $this->request->getQuery('use');
        $dictionaryvalue = $this->request->getQuery('dictionary');
        
        /*if ('other' !== $this->request->getQuery('dictionary')){
            $dictionaryvalue = $this->request->getQuery('dictionary');
        } elseif ('other' == $this->request->getQuery('dictionary')) {
            $dictionaryvalue = 'other';
        }*/

        $current_condition = ['origin' => $originvalue,
                              'region' => $regionvalue,
                              'use' => $typevalue,
                              'dictionary' => $dictionaryvalue];

        $this->paginate = [
            'contain' => [
                'Definitions',
                'Origins',
                'Regions',
                'Types',
                'Dictionaries'
            ]];
        
        $words = $this->Paginator->paginate($this->Words->browse_words_filter($originvalue, $regionvalue, $typevalue, $dictionaryvalue));
        $title = 'Home';

        $this->set(compact('words', 'current_condition', 'origins', 'regions', 'types', 'dictionaries', 'title'));
        $this->render('browse');
    }

    public function random() {
        $words = $this->Paginator->paginate($this->Words->get_random_words());
        $title = 'Random Word Listing';
        $this->set(compact('words', 'title'));

    }

    public function alphabetical()
    {
        
        $letter = $this->request->getParam('pass')[0];
        
        $words = $this->Words->get_words_starting_with_letter($letter);
        $title = 'Alphabetical Listing';
        $this->set(compact('letter', 'words', 'title'));
    }
    
    
    
    /**
     * View method
     *
     * @param string|null $id Word id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        
        //$word = $this->Words->get($id, [
            //'contain' => ['Dictionaries', 'Origins', 'Regions', 'Types', 'Languages', 'Alternates', 'Definitions', 'Sentences', 'Pronunciations' => ['sort' => ['Pronunciations.display_order' => 'ASC']]]]);
        
        
        $wordResult = $this->Words->get_word_for_view($id);
        $word = $wordResult[0];
        //debug($word);
       /*$this->Words->find()
                    ->where(['id' => $id])
                    ->contain('Dictionaries', 'Origins', 'Regions', 'Types', 'Languages', 'Alternates', 'Definitions', 'Sentences')
                    ->contain('Pronunciations', function (Query $q) {
                        return $q
                            ->where(['Pronunciations.approved' => 1])
                            ->order(['Pronunciations.display_order' => 'ASC']);
                    });*/
                    
                    //) => ['sort' => ['Pronunciations.display_order' => 'ASC']]]]);
        
        
        $contain = ['Dictionaries', 'Origins', 'Regions', 'Types', 'Alternates', 'Definitions', 'Sentences', 'Pronunciations']; //, 'Sentences', 'Pronunciations'
        $valuenames = ['Dictionaries' => ['dictionary'], 
                        'Origins' => ['origin'], 
                        'Regions' => ['region'], 
                        'Types' => ['type'],
                        'Alternates' => ['spelling'],
                        'Definitions' => ['definition'], 
                        'Sentences' => ['sentence'], 'Pronunciations' => ['spelling', 'pronunciation', 'sound_file', 'notes']];//'Sentences' => ['sentence'], 'Pronunciations' => ['spelling', 'pronunciation', 'sound_file', 'notes']
        
        if (null == $this->request->getSession()->read('Auth.username')){
            if (1 == $word->approved){
            $arraysforcompact = [];
            foreach ($contain as $assoc){ //for each association
                $lowerassoc = strtolower($assoc); //make a lowercase value of that name to use in retieving actual value
                //debug($lowerassoc);
                foreach ($valuenames[$assoc] as $arrayname){ //loop through each set of values we want to retrieve from the word
                    //debug($assoc. '_' . $arrayname);
                    $finalarrayname = $assoc . '_' . $arrayname;
                    $$finalarrayname = array();
                
                    foreach ($word->$lowerassoc as $toplevelassoc){ //gets the word level association, loop through all values in that association
                        //debug($toplevelassoc);
                        if (!empty($toplevelassoc)) {
                            $$finalarrayname[] = $toplevelassoc->$arrayname;
                        }
                        
                    }
                    //debug($finalarrayname);
                    //debug($$finalarrayname);
                    $arraysforcompact[$finalarrayname] = $$finalarrayname;
                }
            }
            $arraysforcompact['word'] = $word;
            $arraysforcompact['title'] = $word->spelling;
            //debug($arraysforcompact);
            $this->set($arraysforcompact);
        } else {
            return $this->redirect(['action' => 'wordnotfound']);
        }
        } elseif (!null == $this->request->getSession()->read('Auth.username')){
            $arraysforcompact = [];
            foreach ($contain as $assoc){ //for each association
                $lowerassoc = strtolower($assoc); //make a lowercase value of that name to use in retieving actual value
                //debug($lowerassoc);
                foreach ($valuenames[$assoc] as $arrayname){ //loop through each set of values we want to retrieve from the word
                    //debug($assoc. '_' . $arrayname);
                    $finalarrayname = $assoc . '_' . $arrayname;
                    $$finalarrayname = array();
                
                    foreach ($word->$lowerassoc as $toplevelassoc){ //gets the word level association, loop through all values in that association
                        //debug($toplevelassoc);
                        if (!empty($toplevelassoc)) {
                            $$finalarrayname[] = $toplevelassoc->$arrayname;
                        }
                        
                    }
                    //debug($finalarrayname);
                    //debug($$finalarrayname);
                    $arraysforcompact[$finalarrayname] = $$finalarrayname;
                }
            }
            $arraysforcompact['word'] = $word;
            $arraysforcompact['title'] = $word->spelling;
            //debug($arraysforcompact);
            $this->set($arraysforcompact);
        } else {
            return $this->redirect(['action' => 'wordnotfound']);
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $word = $this->Words->newEmptyEntity();
        //debug($this->request->getAttribute('params'));
        $getRoute = explode("/", $this->request->getRequestTarget());
        $controllerName = $getRoute[1];

        //debug($this->request->getSession()->read('Auth.username'));
        if ($this->request->is('post')) {
            //assign the post data to a variable
            $postData = $this->request->getData();
            
            

            //process the WYSIWYG Quills
            $quillAssoc2 = ['definitions', 'sentences'];
            $processFields = ['definitions' => 'definition', 'sentences' => 'sentence'];
            try {
                foreach ($quillAssoc2 as $quillAssoc){
                    $i = 0;
                    while ($i < count($postData[$quillAssoc])){
                        $original = $postData[$quillAssoc][$i][$processFields[$quillAssoc]];
                        if ('{"ops":[{"insert":"\n"}]}' == $original){
                            unset($postData[$quillAssoc][$i]);
                        } else {
                            $jsonFromOriginal = json_decode($original);
                            //debug($jsonFromOriginal->ops[0]->insert);
                            $postData[$quillAssoc][$i][$processFields[$quillAssoc] . '_json'] = json_encode($jsonFromOriginal);
                            $quill = new \DBlackborough\Quill\Render($postData[$quillAssoc][$i][$processFields[$quillAssoc]]);
                            $defresult = $quill->render();
                            $postData[$quillAssoc][$i][$processFields[$quillAssoc]] = $defresult;
                        }
                        $i += 1;
                    }
                }
                $original = $postData['etymology'];
                if ('{"ops":[{"insert":"\n"}]}' == $original){
                    unset($postData['etymology']);
                } else {
                    $jsonFromOriginal = json_decode($original);
                    $postData['etymology_json'] = json_encode($jsonFromOriginal);
                    $quill = new \DBlackborough\Quill\Render($postData['etymology']);
                    $defresult = $quill->render();
                    $postData['etymology'] = $defresult;
                }
                $original = $postData['notes'];
                if ('{"ops":[{"insert":"\n"}]}' == $original){
                    unset($postData['notes']);
                } else {
                    $jsonFromOriginal = json_decode($original);
                    $postData['notes_json'] = json_encode($jsonFromOriginal);
                    $quill = new \DBlackborough\Quill\Render($postData['notes']);
                    $defresult = $quill->render();
                    $postData['notes'] = $defresult;
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            //account for non-subitted data
            $associated =  ['Alternates', 'Languages', 'Definitions', 'Pronunciations', 'Sentences', 'Dictionaries', 'Origins', 'Regions', 'Types'];
            $associatedforfilter =  ['Alternates', 'Pronunciations']; //'Definitions', 'Sentences'
            foreach ($associatedforfilter as $assoc){
                //debug($postData[strtolower($assoc)]);
                if (!array_filter($postData[strtolower($assoc)][0])) {
                    unset($postData[strtolower($assoc)]);  
                }
            }

            //reCaptcha authentication
            if (null == $this->request->getSession()->read('Auth.username')){
                $recaptcha = $postData['g-recaptcha-response'];
                $google_url = "https://www.google.com/recaptcha/api/siteverify";
                $secret = Configure::consume('recaptcha_secret');
                $ip = $_SERVER['REMOTE_ADDR'];
                $url = $google_url . "?secret=" . $secret . "&response=" . $recaptcha ."&remoteip=" . $ip;
                $http = new Client();

                $res = $http->get($url);
                $json = $res->getJson();
                $validationSet = 'notloggedin';
            } else {
                $json['success'] = 'false';
                $validationSet = 'default';
            }

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
                            $postData['pronunciations'][$i]['sound_file'] = $finalname;
                        }
                    }
                }
                $i++;
            }


            if (null !== $this->request->getSession()->read('Auth.username')  && 'superuser' == $this->request->getSession()->read('Auth.role') && !empty($postData['pronunciations'])){
                $datefortimestamp = date('Y-m-d h:i:s', time());
                $i = 0;
                foreach ($postData['pronunciations'] as $p) {
                    $postData['pronunciations'][$i]['approved'] = 1;
                    $postData['pronunciations'][$i]['approved_date'] = $datefortimestamp;
                    $postData['pronunciations'][$i]['approving_user_id'] = $this->request->getSession()->read('Auth.id');
                    $i += 1;
                }
            }
            //debug($postData);
            $word = $this->Words->patchEntity($word, $postData,  ['validate' => $validationSet, 'associated' => $associated]);
            
            
            if ($json['success'] == "true" || null !== $this->request->getSession()->read('Auth.username')){   //reqiuring reCaptcha to be true or to be logged in
                if ($this->Words->save($word)) {
                    //$this->Flash->success(__('The word has been saved.'));
                    //Log::info('A word was added', ['scope' => ['events']]);
                    if (null !== $this->request->getSession()->read('Auth.username')) {
                        Log::info('Word \/\/ ' . $word->spelling. ' was added by ' .  $this->request->getSession()->read('Auth.username') . ' \/\/ ' . $word->id, ['scope' => ['events']]);
                        return $this->redirect(['action' => 'view' ,
                         $word->id]);
                    } else {
                        Log::info($word->spelling. ' was added by ' . $word->full_name . ' (' . $word->email . ')', ['scope' => ['events']]);
                        return $this->redirect(['action' => 'success']);
                    }
                }
                //$this->Flash->error(__('Authentication passed.'));
            }
            $this->Flash->error(__('The word could not be saved. Please, try again.'));
        }

        array_map([$this, 'loadModel'], ['Words', 'Origins', 'Regions', 'Types', 'Dictionaries']);

        $origins = $this->Origins->top_origins();
        $regions = $this->Regions->top_regions();
        $types = $this->Types->top_types();
        $dictionaries = $this->Dictionaries->top_dictionaries();

        $recaptcha_user = Configure::consume('recaptcha_user');
        $title = 'Add a Word';
        $this->set(compact('word', 'dictionaries', 'origins', 'regions', 'types', 'recaptcha_user', 'controllerName', 'title'));
    }

    public function checkforword(){
        $this->RequestHandler->renderAs($this, 'json');
        $response = [];
        //debug($this->request->getData());
        if( $this->request->is('post') ) {
            $data = $this->request->getData();
            //$doeswordexist = $data['spelling'];
            $doeswordexist = $this->Words->findWithSpelling($data['spelling']);
            //debug($data);
            $response['spelling'] = $doeswordexist;
            //debug($response['spelling']);
        } else {
            $response['success'] = 0;
        }

        //$spelling = $this->request->getData('spelling');
        //debug($spelling);
        //$data = $this->Words->findWithSpelling($spelling);
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', true);
        $this->RequestHandler->renderAs($this, 'json');
    }

    /**
     * Edit method
     *
     * @param string|null $id Word id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $wordResult = $this->Words->get_word_for_edit($id);
        $word = $wordResult[0];
        
        /*$word = $this->Words->get($id, [
            'contain' => ['Dictionaries', 'Origins', 'Regions', 'Types','Alternates','Languages','Definitions', 'Pronunciations', 'Sentences', 'Suggestions'],
        ]);*/

        if (null !== $this->request->getSession()->read('Auth.username') && 'superuser' == $this->request->getSession()->read('Auth.role')){
            
            
            $getRoute = explode("/", $this->request->getRequestTarget());
            $controllerName = $getRoute[2];
            //debug($controller);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();
                //process the WYSIWYG Quills
                $quillAssoc2 = ['definitions', 'sentences'];
                $processFields = ['definitions' => 'definition', 'sentences' => 'sentence'];
                try {
                    foreach ($quillAssoc2 as $quillAssoc){
                        $idsToDelete = [];
                        $i = 0;
                        while ($i < count($postData[$quillAssoc])){
                            $original = $postData[$quillAssoc][$i][$processFields[$quillAssoc]];
                            $jsonFromOriginal = json_decode($original);
                            //debug($jsonFromOriginal->ops[0]->insert);
                            $postData[$quillAssoc][$i][$processFields[$quillAssoc] . '_json'] = json_encode($jsonFromOriginal);
                            $quill = new \DBlackborough\Quill\Render($postData[$quillAssoc][$i][$processFields[$quillAssoc]]);
                            $defresult = $quill->render();
                            $postData[$quillAssoc][$i][$processFields[$quillAssoc]] = $defresult;
                            
                            //debug(preg_replace('/\s+/', '',$defresult));

                            if ('<p><br/></p>' == preg_replace('/\s+/', '',$postData[$quillAssoc][$i][$processFields[$quillAssoc]])){
                                array_push($idsToDelete, $postData[$quillAssoc][$i]['id']);
                                if ($i > 0){
                                    unset($postData[$quillAssoc][$i]);
                                }
                                else {
                                    unset($postData[$quillAssoc][$i]['id']);
                                    unset($postData[$quillAssoc][$i][$processFields[$quillAssoc]]);
                                    unset($postData[$quillAssoc][$i][$processFields[$quillAssoc] . '_json']);
                                }
                            }
                            $i += 1;
                        }
                        //debug($idsToDelete);
                        if(count($idsToDelete) > 0){
                            $getDeleteTable = $this->getTableLocator()->get(ucfirst($quillAssoc));
                            //$present = (new Collection($entity->comments))->extract('id')->filter()->toList();
                            $getDeleteTable->deleteAll([
                                'word_id' => $word->id,
                                'id IN' => $idsToDelete,
                            ]);
                        }

                    }
                    $original = $postData['etymology'];
                    $jsonFromOriginal = json_decode($original);
                    $postData['etymology_json'] = json_encode($jsonFromOriginal);
                    $quill = new \DBlackborough\Quill\Render($postData['etymology']);
                    $defresult = $quill->render();
                    $postData['etymology'] = $defresult;
                    if ('<p><br/></p>' == preg_replace('/\s+/', '',$postData['etymology'])){
                        $postData['etymology'] = null;
                    }
                    $original = $postData['notes'];
                    $jsonFromOriginal = json_decode($original);
                    $postData['notes_json'] = json_encode($jsonFromOriginal);
                    $quill = new \DBlackborough\Quill\Render($postData['notes']);
                    $defresult = $quill->render();
                    $postData['notes'] = $defresult;
                    if ('<p><br/></p>' == preg_replace('/\s+/', '',$postData['notes'])){
                        $postData['notes'] = null;
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }

                //account for non-subitted data
                $associated =  ['Alternates', 'Languages', 'Definitions', 'Pronunciations', 'Sentences', 'Dictionaries', 'Origins', 'Regions', 'Types'];
                $associatedforfilter =  ['Alternates', 'Definitions', 'Pronunciations', 'Sentences'];
                foreach ($associatedforfilter as $assoc){
                    //debug($postData[strtolower($assoc)]);
                    if (!array_filter($postData[strtolower($assoc)][0])) {
                        unset($postData[strtolower($assoc)]);  
                    }
                }

                $soundFiles = $this->request->getUploadedFiles();
                //$targetPath = WWW_ROOT. 'recordings'. DS . $finalname;
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
                                $postData['pronunciations'][$i]['sound_file'] = $finalname;
                            }
                        }
                    }
                    $i++;
                }
                

                $word = $this->Words->patchEntity($word, $postData,  ['associated' => $associated]);
                if ($this->Words->save($word)) {
                    Log::info('Word \/\/ ' . $this->request->getSession()->read('Auth.username') . ' edited ' . $word->spelling . ' \/\/ ' . $word->id, ['scope' => ['events']]);
                    $this->Flash->success(__('The word has been saved.'));
    
                    return $this->redirect(['action' => 'view', $word->id]);
                }
                $this->Flash->error(__('The word could not be saved. Please, try again.'));
            }
            
            $dictionaries = $this->Words->Dictionaries->find('list', ['limit' => 200]);
            $origins = $this->Words->Origins->find('list', ['limit' => 200]);
            $regions = $this->Words->Regions->find('list', ['limit' => 200]);
            $types = $this->Words->Types->find('list', ['limit' => 200]);
            //$alternates = $this->Words->Alternates->find('list');
            $title = 'Edit: ' . $word->spelling;
            $this->set(compact('word', 'dictionaries', 'origins', 'regions', 'types', 'controllerName', 'title'));
            $this->render('add');
        } else {
            return $this->redirect([
                'controller' => 'Suggestions',
                'action' => 'add',
                $word->id
            ]);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Word id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $word = $this->Words->get($id);
        if ($this->Words->delete($word)) {
            Log::info('Word \/\/ ' . $this->request->getSession()->read('Auth.username') . ' deleted ' . $word->spelling . ' \/\/ ', ['scope' => ['events']]);
            $this->Flash->success(__('The word has been deleted.'));
        } else {
            $this->Flash->error(__('The word could not be deleted. Please, try again.'));
        }

        return $this->redirect(['prefix' => 'Moderators', 'controller' => 'panel', 'action' => 'index']);
    }


    public function approve($id = null)
    {
        $this->request->allowMethod(['post']);
        $datefortimestamp = date('Y-m-d h:i:s', time());
        $word = $this->Words->get($id, [
            'contain' => ['Pronunciations']
        ]);
        $pronunciations = array();
        foreach ($word->pronunciations as $p){
            //debug(['id' => $p->id, 'appproved' => 1, 'approved_date' => $datefortimestamp]);
            array_push($pronunciations, ['id' => $p->id, 'approved' => 1, 'approved_date' => $datefortimestamp, 'approving_user_id' => $this->request->getSession()->read('Auth.id')]);
        }
        $data = ['approved' => 1,
                 'approved_date' => $datefortimestamp,
                 'user_id' => $this->request->getSession()->read('Auth.id'),
                 'pronunciations' => $pronunciations];
        
        
        //$word['pronunciations'] = $pronunciations;
        //debug($data);
        $this->Words->patchEntity($word, $data, ['associated' => ['Pronunciations']]);
        if ($this->Words->save($word)) {
            Log::info('Word \/\/ ' . $this->request->getSession()->read('Auth.username') . ' approved ' . $word->spelling . ' \/\/ ' . $word->id, ['scope' => ['events']]);
            $this->Flash->success(__('The word has been approved.'));
        } else {
            $this->Flash->error(__('The word could not be approved. Please, try again.'));
        }

        return $this->redirect(['prefix' => 'Moderators', 'controller' => 'panel', 'action' => 'index']);
    }

    public function success(){

    }

    public function wordnotfound(){

    }
}
