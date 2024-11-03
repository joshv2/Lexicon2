<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Collection\Collection;
use Cake\Http\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Datasource\PaginatorInterface;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\TableRegistry;

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
        $this->loadComponent('LoadORTD');

    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $queryParams = $this->request->getQueryParams();
        if($queryParams === []){
            $queryParams['all'] = 'all';
        }
        
        $sitelang = $this->viewBuilder()->getVar('sitelang');

        $ortd = $this->LoadORTD->getORTD($sitelang);
        $originvalue = [$this->request->getQuery('origin')];
        $regionvalue = [$this->request->getQuery('region')];
        $typevalue = [$this->request->getQuery('type')];
        $dictionaryvalue = [$this->request->getQuery('dictionary')];
        $current_condition = ['origins' => $originvalue[0], //needs to remain an array for the browse_words_filter function
                              'regions' => $regionvalue[0],
                              'types' => $typevalue[0],
                              'dictionaries' => $dictionaryvalue[0]];
        
        $cc = [];
        foreach($current_condition as $ortdcat => $ortd2) {
            if ($ortd2 != null){
                $cc[$ortdcat] = $ortd2;

            }
        }

        $query = $this->Words->browse_words_simplified(
                        array_keys($queryParams)[0], 
                        array_values($queryParams)[0],
                        FALSE, 
                        $sitelang->id,
                        TRUE);
        
        
        $this->set('words', $this->paginate($query));


        $title = 'Browse';

        $this->set(compact('current_condition', 'cc', 'ortd', 'title', 'sitelang'));
        $this->render('browse');
    }
    

    public function random() {
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $words = $this->paginate($this->Words->get_random_words($sitelang->id));
        $title = 'Random Word Listing';
        $this->set(compact('words', 'title', 'sitelang'));

    }

    public function alphabetical()
    {
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $letter = $this->request->getParam('pass')[0];
        
        $language = $this->fetchTable('Languages');
        foreach(range(hexdec($sitelang->UTFRangeStart), hexdec($sitelang->UTFRangeEnd)) as $letter2) {
            $alphabet[] = html_entity_decode("&#$letter2;", ENT_COMPAT, "UTF-8");
        }
        
        
        if($sitelang->righttoleft){
            $alphabet = array_reverse($alphabet);
        }
        $words = $this->Words->get_words_starting_with_letter($letter, $sitelang->id);
        $title = 'Alphabetical Listing';
        $this->set(compact('letter', 'words', 'title', 'sitelang', 'alphabet'));
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
        
        
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $wordResult = $this->Words->get_word_for_view($id);
        $word = $wordResult[0];
        
        
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
                        $arraysforcompact[$finalarrayname] = $$finalarrayname;
                    }
                }
                $arraysforcompact['word'] = $word;
                $arraysforcompact['title'] = $word->spelling;
                $arraysforcompact['sitelang'] = $sitelang;
                $this->set($arraysforcompact);
            } else {
                return $this->redirect(['action' => 'wordnotfound']);
            }
        } elseif (!null == $this->request->getSession()->read('Auth.username')){
            $arraysforcompact = [];
            foreach ($contain as $assoc){ //for each association
                $lowerassoc = strtolower($assoc); //make a lowercase value of that name to use in retieving actual value
                foreach ($valuenames[$assoc] as $arrayname){ //loop through each set of values we want to retrieve from the word
                    $finalarrayname = $assoc . '_' . $arrayname;
                    $$finalarrayname = array();
                
                    foreach ($word->$lowerassoc as $toplevelassoc){ //gets the word level association, loop through all values in that association
                        if (!empty($toplevelassoc)) {
                            $$finalarrayname[] = $toplevelassoc->$arrayname;
                        }
                        
                    }
                    $arraysforcompact[$finalarrayname] = $$finalarrayname;
                }
            }
            $arraysforcompact['word'] = $word;
            $arraysforcompact['title'] = $word->spelling;
            $arraysforcompact['sitelang'] = $sitelang;
            $this->set($arraysforcompact);
        } else {
            return $this->redirect(['action' => 'wordnotfound']);
        }
    }

    private function process_others($ortd, $postData)
    {
        $processed = [];
            
        if($postData[$ortd]['_ids'] !== ''){
            foreach ($postData[$ortd]['_ids'] as $ortdid){
                array_push($processed, array('id' => $ortdid));
            }
            
            if ($postData[substr($ortd, 0, -1) .'_other_entry'] !== ''){
                
                foreach (explode(";", $postData[substr($ortd, 0, -1) . '_other_entry']) as $otherortd) {
                    $table = $this->fetchTable(ucfirst($ortd));
                    $idofOtherOrtd = $table->getIdIfExists($otherortd);
                    

                    if ($idofOtherOrtd !== null) {
                        $returnedIdArray = ['id' => $idofOtherOrtd];
                        $alreadySubmitted = 0;
                        foreach ($processed as $idpair){
                            if ($idpair == $returnedIdArray) {
                                $alreadySubmitted += 1;
                            }
                        }
                        if ($alreadySubmitted === 0) {
                            array_push($processed, array('id' => $idofOtherOrtd));
                        }
                    } else {
                        array_push($processed, [ substr($ortd, 0, -1) => $otherortd]);
                    }
                }
                
                //debug($processedTypes);
                unset($postData[substr($ortd, 0, -1) . '_other_entry']);
            } 
            //debug($processed);
            unset($postData[$ortd]['_ids']);
            //$processed = array_unique($processed);
            $postData[$ortd] = $processed;
            return $postData;
        } else {
            return $postData;
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
        
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        
        $getRoute = explode("/", $this->request->getRequestTarget());
        $controllerName = $getRoute[1];

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
                            $postData[$quillAssoc][$i][$processFields[$quillAssoc] . '_json'] = json_encode($jsonFromOriginal);
                            $quill = new \nadar\quill\Lexer($postData[$quillAssoc][$i][$processFields[$quillAssoc]]);
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
                    $quill = new \nadar\quill\Lexer($postData['etymology']);
                    $defresult = $quill->render();
                    $postData['etymology'] = $defresult;
                }
                $original = $postData['notes'];
                if ('{"ops":[{"insert":"\n"}]}' == $original){
                    unset($postData['notes']);
                } else {
                    $jsonFromOriginal = json_decode($original);
                    $postData['notes_json'] = json_encode($jsonFromOriginal);
                    $quill = new \nadar\quill\Lexer($postData['notes']);
                    $defresult = $quill->render();
                    $postData['notes'] = $defresult;
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            //account for non-submitted data
            $associated =  ['Alternates', 'Languages', 'Definitions', 'Pronunciations', 'Sentences', 'Dictionaries', 'Origins', 'Regions', 'Types'];
            $associatedforfilter =  ['Alternates', 'Pronunciations']; //'Definitions', 'Sentences'
            foreach ($associatedforfilter as $assoc){
                if (!array_filter($postData[strtolower($assoc)][0])) {
                    unset($postData[strtolower($assoc)]);  
                }
            }


            $ortdswithother = ['origins','types'];
            
            foreach ($ortdswithother as $ortdwithother){
                $postData = $this->process_others($ortdwithother, $postData);
            }

            /*$processedOrigins = [];
            if($postData['origins']['_ids'] !== ''){
                foreach ($postData['origins']['_ids'] as $originid){
                    array_push($processedOrigins, array('id' => $originid));
                }
                
                if ($postData['origin_other_entry'] !== ''){
                    if (count($this->fetchTable('Origins')->get_region_by_name($postData['origin_other_entry'])) == 0) {
                        array_push($processedOrigins, [ 'origin' => $postData['origin_other_entry']]);
                        unset($postData['origin_other_entry']);
                    } else {
                        array_push($processedOrigins, [ 'id' => $this->fetchTable('Origins')->get_region_by_name($postData['origin_other_entry'])[0] ]);
                        unset($postData['origin_other_entry']);
                    }
                    unset($postData['origins']['_ids']);
                    $postData['origins'] = $processedOrigins;
                }
            }
            
            $processedTypes = [];
            
            if($postData['types']['_ids'] !== ''){
                foreach ($postData['types']['_ids'] as $typeid){
                    array_push($processedTypes, array('id' => $typeid));
                }
                
                if ($postData['type_other_entry'] !== ''){
                    
                    foreach (explode(";", $postData['type_other_entry']) as $othertype) {
                        $typesTable = $this->fetchTable('Types');
                        $typeIdofOtherType = $typesTable->getTypeIdIfExists($othertype);
                        

                        if ($typeIdofOtherType !== null) {
                            array_push($processedTypes, array('id' => $typeIdofOtherType));
                        } else {
                            array_push($processedTypes, [ 'type' => $othertype ]);
                        }
                    }
                    
                    //debug($processedTypes);
                    unset($postData['type_other_entry']);
                } 
                unset($postData['types']['_ids']);
                $postData['types'] = $processedTypes;
            }*/


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
                $finalname = str_replace(array(' ','/','\\','<',';',':','>','"','|','?','*'), '', $postData['spelling']) . time() . $i . '.webm';
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
                if (null !== $this->request->getSession()->read('Auth.username')  && 'superuser' == $this->request->getSession()->read('Auth.role') && ('' !== $name || null != $name)){
                    $this->converttomp3($finalname);
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
            $word = $this->Words->patchEntity($word, $postData,  ['validate' => $validationSet, 'associated' => $associated]);
            
            
            if ($json['success'] == "true" || null !== $this->request->getSession()->read('Auth.username')){   //reqiuring reCaptcha to be true or to be logged in
                if ($this->Words->save($word)) {
                    if (null !== $this->request->getSession()->read('Auth.username') && 'superuser' == $this->request->getSession()->read('Auth.role')) {
                        Log::info('Word \/\/ ' . $word->spelling. ' was added by ' .  $this->request->getSession()->read('Auth.username') . ' \/\/ ' . $word->id, ['scope' => ['events']]);
                        return $this->redirect(['action' => 'view' , $word->id]);
                    } else {
                        Log::info($word->spelling. ' was added by ' . $word->full_name . ' (' . $word->email . ')', ['scope' => ['events']]);
                        return $this->redirect(['action' => 'success']);
                    }
                }
            }
            $this->Flash->error(__('The word could not be saved. Please, try again.'));
        }

        $specialother = '';
        $specialothervalue = '';
        $specialothertype = '';
        $specialothervaluetype = '';
        $origins = $this->fetchTable('Origins')->top_origins($sitelang->id);
        $regions = $this->fetchTable('Regions')->top_regions($sitelang->id);
        $types = $this->fetchTable('Types')->top_types($sitelang->id);
        $dictionaries = $this->fetchTable('Dictionaries')->top_dictionaries($sitelang->id);

        $recaptcha_user = Configure::consume('recaptcha_user');
        $title = 'Add a Word';
        $this->set(compact('word', 'dictionaries', 'origins', 'regions', 'types', 'recaptcha_user', 'controllerName', 'title', 'sitelang', 'specialother', 'specialothervalue', 'specialothertype', 'specialothervaluetype'));
    }

    public function checkforword(){
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $response = [];
        if( $this->request->is('post') ) {
            $data = $this->request->getData();
            $doeswordexist = $this->Words->findWithSpelling($data);
            $response['spelling'] = $doeswordexist;
        } else {
            $response['success'] = 0;
        }

        $this->response = $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
        return $this->response;
    }

    public function browsewords(){
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $response = [];
        $ortdarray["Origins"] = [];
        $ortdarray["Regions"] = [];
        $ortdarray["Types"] = [];
        $ortdarray["Dictionaries"] = [];

        if( $this->request->is('POST') ) {
            $data = $this->request->getData();
            if (isset($data['selectedOptions'])) {
                $resultArray = [];

                
                $sentValue = $data["selectedOptions"];
                array_push($ortdarray[explode('_',$sentValue)[0]], explode('_',$sentValue)[1]);

                
                $browsewords = $this->Words->browse_words_filter($ortdarray["Origins"], $ortdarray["Regions"], $ortdarray["Types"], $ortdarray["Dictionaries"], TRUE, $sitelang->id);

                $response_with_language['language'] = $sitelang->id;
                $response_with_language['words'] = $browsewords;
                $response['success'] = $response_with_language;
                $this->response = $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
                return $this->response;
            } else {
                // 'selectedOptions' key is missing in the posted data
                throw new InternalErrorException('Missing required data: selectedOptions');
            }
        } else {
            throw new NotFoundException('Invalid request method');
            $response['success'] = 0;
        }

        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', true);
        $this->RequestHandler->renderAs($this, 'json');
    }


    public function browsewords2(){
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $response = [];
        

        if( $this->request->is('post') ) {
            $data = $this->request->getData();
            
            
            if(sizeof($data["requestedWordIds"]) > 0){
                $browsewords = $this->Words->browse_words_filter2($data["requestedWordIds"], $sitelang->id);



                $response_with_language['language'] = $sitelang->id;
                $response_with_language['words'] = $browsewords;
                $response['success'] = $response_with_language;
                    
                } else {
                    $response_with_language['language'] = $sitelang->id;
                    $response_with_language['words'] = "[]";
                    $response['success'] = $response_with_language;
                }

                $this->response = $this->response->withType('application/json')
                    ->withStringBody(json_encode($response));
                return $this->response;
            }
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
        $word = $wordResult;
        $sitelang = $this->viewBuilder()->getVar('sitelang');

        if (null !== $this->request->getSession()->read('Auth.username') && in_array($this->request->getSession()->read('Auth.role'),['superuser','user'])){
            
            
            $getRoute = explode("/", $this->request->getRequestTarget());
            $controllerName = $getRoute[2];
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();
                //process the WYSIWYG Quills
                $quillAssoc2 = ['definitions', 'sentences'];
                $processFields = ['definitions' => 'definition', 'sentences' => 'sentence'];
                try {
                    foreach ($quillAssoc2 as $quillAssoc){
                        $idsToDelete = [];
                        $quillKeys = array_keys($postData[$quillAssoc]);
                        foreach ($quillKeys as $key => $i){
                            $original = $postData[$quillAssoc][$i][$processFields[$quillAssoc]];
                            $jsonFromOriginal = json_decode($original);
                            $postData[$quillAssoc][$i][$processFields[$quillAssoc] . '_json'] = json_encode($jsonFromOriginal);
                            $quill = new \nadar\quill\Lexer($postData[$quillAssoc][$i][$processFields[$quillAssoc]]);
                            $defresult = $quill->render();
                            $postData[$quillAssoc][$i][$processFields[$quillAssoc]] = $defresult;


                            if ('<p><br/></p>' == preg_replace('/\s+/', '',$postData[$quillAssoc][$i][$processFields[$quillAssoc]])){
                                if ($key > 0){
                                    unset($postData[$quillAssoc][$i]);
                                }
                                else {
                                    unset($postData[$quillAssoc][$i]['id']);
                                    unset($postData[$quillAssoc][$i][$processFields[$quillAssoc]]);
                                    unset($postData[$quillAssoc][$i][$processFields[$quillAssoc] . '_json']);
                                }
                            }

                        }

                    }
                    $original = $postData['etymology'];
                    $jsonFromOriginal = json_decode($original);
                    $postData['etymology_json'] = json_encode($jsonFromOriginal);
                    $quill = new \nadar\quill\Lexer($postData['etymology']);
                    $defresult = $quill->render();
                    $postData['etymology'] = $defresult;
                    if ('<p><br/></p>' == preg_replace('/\s+/', '',$postData['etymology'])){
                        $postData['etymology'] = null;
                    }
                    $original = $postData['notes'];
                    $jsonFromOriginal = json_decode($original);
                    $postData['notes_json'] = json_encode($jsonFromOriginal);
                    $quill = new \nadar\quill\Lexer($postData['notes']);
                    $defresult = $quill->render();
                    $postData['notes'] = $defresult;
                    if ('<p><br/></p>' == preg_replace('/\s+/', '',$postData['notes'])){
                        $postData['notes'] = null;
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }

                //account for non-submitted data
                $associated =  ['Alternates', 'Languages', 'Definitions', 'Pronunciations', 'Sentences', 'Dictionaries', 'Origins', 'Regions', 'Types'];
                $associatedforfilter =  ['Alternates', 'Definitions', 'Pronunciations', 'Sentences'];
                foreach ($associatedforfilter as $assoc){
                    if (0 == count($postData[strtolower($assoc)])) {
                        unset($postData[strtolower($assoc)]);  
                    }
                }

                $processedOrigins = [];
                $pattern2 = '/^origin_other_entry/';
                $postkeys = array_keys($postData);
                $otheroriginskeyarray = preg_grep($pattern2, $postkeys);
                $otheroriginskey = $otheroriginskeyarray[array_key_first($otheroriginskeyarray)];
                if($postData['origins']['_ids'] !== ''){
                    foreach ($postData['origins']['_ids'] as $originid){
                        array_push($processedOrigins, array('id' => $originid));
                    }
                    if ($postData[$otheroriginskey] !== ''){
                        if (count($this->fetchTable('Origins')->get_region_by_name($postData[$otheroriginskey])) == 0) {
                            array_push($processedOrigins, [ 'origin' => $postData[$otheroriginskey]]);
                            unset($postData[$otheroriginskey]);
                        } else {
                            array_push($processedOrigins, [ 'id' => $this->fetchTable('Origins')->get_region_by_name($postData[$otheroriginskey])[0] ]);
                            unset($postData[$otheroriginskey]);
                        }
                        unset($postData['origins']['_ids']);
                        $postData['origins'] = $processedOrigins;
                    }
                }

                $processedTypes = [];
                if($postData['types']['_ids'] !== ''){
                    foreach ($postData['types']['_ids'] as $typeid){
                        array_push($processedTypes, array('id' => $typeid));
                    }
                    
                    $pattern2 = '/^type_other_entry_/';
                    $postkeys = array_keys($postData);
                    $othertypeskey = preg_grep($pattern2, $postkeys);
                    if ($othertypeskey !== false){ //if there 
                        $othertypeidvalues = array_values($othertypeskey);
                        $othertypeid = array_shift($othertypeidvalues);
                        if(isset($othertypeid) && in_array(999,$postData['types']['_ids'])){
                            $gettypeid = explode("_",$othertypeid);
                            $gettypeidvalue = $gettypeid[3];
                            array_push($processedTypes, [ 'id' => $gettypeidvalue, 'type' => $postData[$othertypeid]]);
                            unset($postData[$othertypeid]);
                            unset($postData['types']['_ids']);
                            $postData['types'] = $processedTypes;
                        } elseif(isset($postData['type_other_entry']) && $postData['type_other_entry'] !== '') {
                            array_push($processedTypes, ['type' => $postData['type_other_entry']]);
                            unset($postData['type_other_entry']);
                            unset($postData['types']['_ids']);
                            $postData['types'] = $processedTypes;
                        } else {
                            unset($postData['type_other_entry']);
                        }
                    }
                }

                $soundFiles = $this->request->getUploadedFiles();
                $i = 0;
                foreach ($soundFiles as $soundFile) {
                    $name = $soundFile->getClientFilename();
                    $finalname = str_replace(array(' ','/','\\','<',';',':','>','"','|','?','*'), '', $postData['spelling']) . time() . $i . '.webm';
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
            

            $origins = $this->fetchTable('Origins')->top_origins($sitelang->id);

            $specialother = '';
            $specialothervalue = '';
            foreach($word->origins as $key => $originscan){
                if(!in_array($originscan->id,array_keys($origins))){
                    $specialother = '_' . $originscan->id;
                    $specialothervalue = $originscan->origin;
                }
            }

            $types = $this->fetchTable('Types')->top_types($sitelang->id);

            $specialothertype = '';
            $specialothervaluetype = '';

            foreach($word->types as $key => $typescan){
                if(!in_array($typescan->id,array_keys($types))){
                    $specialothertype = '_' . $typescan->id;
                    $specialothervaluetype = $typescan->type;
                }
            }

            $regions = $this->fetchTable('Regions')->top_regions($sitelang->id);
            
            $dictionaries = $this->fetchTable('Dictionaries')->top_dictionaries($sitelang->id);
            $title = 'Edit: ' . $word->spelling;
            $this->set(compact('word', 'dictionaries', 'origins', 'regions', 'types', 'controllerName', 'title', 'sitelang', 'specialother', 'specialothervalue', 'specialothertype', 'specialothervaluetype'));
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
        if (count($word->pronunciations) > 0) {
            foreach ($word->pronunciations as $p){
                if ('' !== $p->sound_file || null != $p->sound_file){
                    $this->converttomp3($p->sound_file);
                }
                array_push($pronunciations, ['id' => $p->id, 'approved' => 1, 'approved_date' => $datefortimestamp, 'approving_user_id' => $this->request->getSession()->read('Auth.id')]);
            }
            $data = ['approved' => 1,
                    'approved_date' => $datefortimestamp,
                    'user_id' => $this->request->getSession()->read('Auth.id'),
                    'pronunciations' => $pronunciations];
        }
            
        
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
