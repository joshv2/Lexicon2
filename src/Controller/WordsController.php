<?php
declare(strict_types=1);

namespace App\Controller;
use IntlChar;
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
class WordsController extends AppController {

    protected bool $loggedin = false;
    
    public function initialize(): void  {
        parent::initialize();
        $this->loadComponent('LoadORTD');
        $this->loadComponent('ProcessFile');

        $this->loggedin = (bool) $this->request->getSession()->read('Auth.username');
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index() {
        $sitelang = $this->request->getAttribute('sitelang');
        $ortd = $this->LoadORTD->getORTD($sitelang);
        $queryParams = $this->request->getQueryParams();

        if($queryParams === [] or (array_keys($queryParams) === ['page'] && count($queryParams) === 1)){
            $queryParams = array_merge(['displayType' => 'all'], $queryParams);
        }
        
        $allowed = ['origin', 'region', 'type', 'dictionary', 'displayType', 'page'];

        foreach (array_keys($queryParams) as $param) {
            if (!in_array($param, $allowed, true)) {
                // Invalid value detected
                
                $invalidValue = true;
            }
        }

        if (!isset($invalidValue) || $invalidValue !== true) {
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
            
            } else {
                $queryParams = []; 
                $queryParams = array_merge(['displayType' => 'all'], $queryParams);
                $current_condition = ['origins' => null, 'regions' => null, 'types' => null, 'dictionaries' => null];
                $cc = ['error' => 'You entered invalid query params. Displaying all words in the Lexicon. Please try again.'];
            }
        

        $query = $this->Words->browse_words_simplified(
                            array_keys($queryParams)[0], 
                            array_values($queryParams)[0],
                            FALSE, 
                            $sitelang->id,
                            TRUE);

        $displayType = $this->request->getQuery('displayType');

        if ($displayType === 'all') {
            $isPaginated = false;
            $words = $query->toArray();
            $count = count($words);
        } else {
            $isPaginated = true;
            $count = 0;
            $page = (int)$this->request->getQuery('page', 1);
            try {
                $words = $this->paginate($query);
            } catch (\Cake\Http\Exception\NotFoundException $e) {
                // Page out of bounds, show last page and set error message
                $paginator = $this->getRequest()->getAttribute('paging');
                $totalPages = isset($paginator['Words']['pageCount']) ? $paginator['Words']['pageCount'] : 1;
                $cc['error'] = 'You requested a page that is out of bounds. Displaying the last available page.';
                $this->request = $this->request->withQueryParams(['page' => $totalPages] + $this->request->getQueryParams());
                $words = $this->paginate($query, ['page' => $totalPages]);
            }
            $paginator = $this->getRequest()->getAttribute('paging');

            if (isset($paginator['Words'])) {
                $totalPages = $paginator['Words']['pageCount'];
                if ($page > $totalPages && $totalPages > 0) {
                    $cc['error'] = 'You requested a page that is out of bounds. Displaying from the first page.';
                    // Optionally, reset to last page or empty results
                    $this->request = $this->request->withQueryParams(['page' => $totalPages] + $this->request->getQueryParams());
                    $words = $this->paginate($query, ['page' => $totalPages]);
                }
            }
        }



        $title = 'Browse';
        $ortdarray = [];

        if ($sitelang->hasOrigins) {
            array_push($ortdarray, [$sitelang->hasOrigins, $ortd["origins"], "Origins"]);
        }
        if ($sitelang->hasRegions) {
            array_push($ortdarray, [$sitelang->hasRegions, $ortd["regions"], "Regions"]);
        }
        if ($sitelang->hasTypes) {
            array_push($ortdarray, [$sitelang->hasTypes, $ortd["types"], "Types"]);
        }
        if ($sitelang->hasDictionaries) {
            array_push($ortdarray, [$sitelang->hasDictionaries, $ortd["dictionaries"], "Dictionaries"]);
        }
        
       

        $this->set(compact('current_condition', 'words', 'isPaginated', 'cc', 'ortd', 'title', 'sitelang', 'ortdarray'));
        $this->render('browse');
    }
    

    public function random() {
        $sitelang = $this->request->getAttribute('sitelang');
        $words = $this->paginate($this->Words->get_random_words($sitelang->id));
        $title = 'Random Word Listing';
        $this->set(compact('words', 'title', 'sitelang'));

    }

    public function alphabetical() {
        $sitelang = $this->request->getAttribute('sitelang');
        $letter = $this->request->getParam('pass')[0];
        
        $language = $this->fetchTable('Languages');

        $alphabet = [];
        $start = hexdec($sitelang->UTFRangeStart);
        $end = hexdec($sitelang->UTFRangeEnd);

        foreach (range($start, $end) as $codePoint) {
            $character = html_entity_decode("&#$codePoint;", ENT_COMPAT, "UTF-8");

            // Check if the character is valid UTF-8 and mapped in Unicode
            if (mb_check_encoding($character, 'UTF-8') 
                && IntlChar::charName($character) !== null
                && IntlChar::isalnum($codePoint)) {
                    $alphabet[] = $character;
            }
        }


        
        if($sitelang->righttoleft){
            $alphabet = array_reverse($alphabet);
        }
        $words = $this->Words->get_words_starting_with_letter($letter, $sitelang->id);
        $title = 'Alphabetical Listing';
        $this->set(compact('letter', 'words', 'title', 'sitelang', 'alphabet'));
    }
    
    private function process_ortd(array $ortd, $ortdcategory): array {
        if (!empty($ortd)) {
            $newortd = [];
            $lenotherortd = 0;
            $otherortd = [];
            foreach ($ortd as $key => $ortdvalue) {
                
                if($ortdcategory == 'origin'){
                    if (strpos($ortd[$key][$ortdcategory], ",") !== false && $ortd[$key]['_joinData']['id'] > 999) {
                        $otherortd = explode(",", $ortd[$key][$ortdcategory]);
                        $lenotherortd = count($otherortd);
                    }
                } else {
                    $otherortd = [];
                    $lenotherortd = 0;
                }

                if ($ortd[$key]['_joinData']['id'] != 999 && $lenotherortd === 0) {
                    $newortd[$key] = __($ortd[$key][$ortdcategory]);
                }
            }

            $totalortd = count($newortd) + $lenotherortd;

            return [
                'newortd'    => $newortd,
                'otherortd'  => $otherortd,
                'lenotherortd' => $lenotherortd,
                'totalortd'  => $totalortd 
            ];
        }

        return [
            'newortd'    => [],
            'otherortd'  => [],
            'lenotherortd' => 0,
            'totalortd'  => 0
        ];
    }

    private function process_alternate_spellings(array $alternates): string {
        $spellingList = implode(', ', array_column($alternates, 'spelling'));
        return $spellingList;
    }

    private function get_word_data($word) {
        $word_id = $word['id'];
        $spelling = $word['spelling'];
        $sentences = $word['sentences'];
        $sentences_count = count($sentences);
        $pronunciations = $word['pronunciations'];
        $pronunciations_count = count($pronunciations);
        $definitions = $word['definitions'];
        $total_definitions = count($definitions);
        $ortd_origins = $this->process_ortd($word['origins'], 'origin');
        $new_origins = $ortd_origins['newortd'];
        $other_origins = $ortd_origins['otherortd'];
        #$len_other_origins = $ortd_origins['lenotherortd'];
        $total_origins = $ortd_origins['totalortd'];
        $etymology = $word['etymology'];
        $notes = $word['notes'];
        $ortd_types = $this->process_ortd($word['types'], 'type');
        $new_types = $ortd_types['newortd'];
        $other_types = $ortd_types['otherortd'];
        $total_types = $ortd_types['totalortd'];
        $ortd_regions = $this->process_ortd($word['regions'], 'region');
        $new_regions = $ortd_regions['newortd'];
        $other_regions = $ortd_regions['otherortd'];
        $total_regions = $ortd_regions['totalortd'];
        $ortd_dictionaries = $this->process_ortd($word['dictionaries'], 'dictionary');
        $new_dictionaries = $ortd_dictionaries['newortd'];
        $other_dictionaries = $ortd_dictionaries['otherortd'];
        $total_dictionaries = $ortd_dictionaries['totalortd'];
        $alternates = $word['alternates'];
        $spellingList = $this->process_alternate_spellings($word['alternates']);
        
        return compact('word_id', 'spelling', 'sentences', 'sentences_count', 
                        'pronunciations', 'pronunciations_count', 'definitions', 
                        'total_definitions', 'new_origins', 'other_origins', 'total_origins', 
                        'etymology', 'notes', 'new_types', 'other_types', 'total_types', 'new_regions', 
                        'other_regions', 'total_regions', 'new_dictionaries', 
                        'other_dictionaries', 'total_dictionaries', 'alternates', 'spellingList');
    }

    /**
     * View method
     *
     * @param string|null $id Word id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $sitelang = $this->request->getAttribute('sitelang');
        $word = $this->Words->get_word_for_view($id);
        
        if (empty($word)) {
            return $this->redirect(['action' => 'wordnotfound']);
        }

        $this->set('isEdit', false);
        if ($this->loggedin) {
            $this->set($this->get_word_data($word));
        } elseif (!$this->loggedin){
            if (1 == $word['approved']){
                $this->set($this->get_word_data($word));
            } else {
                return $this->redirect(['action' => 'wordnotfound']);
            }
        } else {
            return $this->redirect(['action' => 'wordnotfound']);
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $word = $this->Words->newEmptyEntity();
        $sitelang = $this->request->getAttribute('sitelang');
        $getRoute = explode("/", $this->request->getRequestTarget());
        $controllerName = $getRoute[1];

        if ($this->request->is('post')) {
            $postData = $this->request->getData();

            // ENFORCE spelling is required
            if (empty($postData['spelling'])) {
                $this->Flash->error(__('Spelling is required.'));
                return;
            }

            // Quill fields
            $postData = $this->processQuillFields($postData, ['definitions' => 'definition', 'sentences' => 'sentence']);
            $postData = $this->processSingleQuillField($postData, 'etymology');
            $postData = $this->processSingleQuillField($postData, 'notes');

            // Filter associations
            $postData = $this->filterAssociations($postData, ['Alternates', 'Pronunciations']);

            // Improved "other" logic
            foreach (['origins', 'types'] as $assoc) {
                $postData = $this->processOtherAssociations($postData, $assoc);
            }

            // reCaptcha
            if ($this->loggedin) {
                $json['success'] = 'false';
                $validationSet = 'default';
            } else {
                $recaptcha = $postData['g-recaptcha-response'];
                $google_url = "https://www.google.com/recaptcha/api/siteverify";
                $secret = \Cake\Core\Configure::consume('recaptcha_secret');
                $ip = $_SERVER['REMOTE_ADDR'];
                $url = $google_url . "?secret=" . $secret . "&response=" . $recaptcha ."&remoteip=" . $ip;
                $http = new \Cake\Http\Client();
                $res = $http->get($url);
                $json = $res->getJson();
                $validationSet = 'notloggedin';
            } 
                

            // Sound files
            $postData = $this->handleSoundFiles($postData, $this->request->getUploadedFiles());

            // Pronunciation approval
            $postData = $this->approvePronunciations($postData);

            $associated =  ['Alternates', 'Languages', 'Definitions', 'Pronunciations', 'Sentences', 'Dictionaries', 'Origins', 'Regions', 'Types'];
            $word = $this->Words->patchEntity($word, $postData,  ['validate' => $validationSet, 'associated' => $associated]);
            
            
            if ($json['success'] == "true" || $this->loggedin){   //reqiuring reCaptcha to be true or to be logged in
                if ($this->Words->save($word)) {
                    if ($this->loggedin && 'superuser' == $this->request->getSession()->read('Auth.role')) {
                        $this->logWordAction('add_superuser', $word);
                        return $this->redirect(['action' => 'view' , $word->id]);
                    } else {
                        $this->logWordAction('add_user', $word);
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

        $recaptcha_user = \Cake\Core\Configure::consume('recaptcha_user');
        $title = 'Add a Word';
        $this->set(compact('word', 
                           'dictionaries', 
                           'origins', 
                           'regions', 
                           'types', 
                           'recaptcha_user', 
                           'controllerName', 
                           'title', 
                           'sitelang', 
                           'specialother', 
                           'specialothervalue', 
                           'specialothertype', 
                           'specialothervaluetype'));
    }

    public function checkforword() {
        $sitelang = $this->request->getAttribute('sitelang');
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

    public function browsewords() {
        $sitelang = $this->request->getAttribute('sitelang');
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


    public function browsewords2() {
        $sitelang = $this->request->getAttribute('sitelang');
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
    public function edit($id = null){
        #$word = $this->Words->find()->where(['id' => $id])->first();
        $sitelang = $this->request->getAttribute('sitelang');
        
        $word = $this->Words->get_word_for_view($id);
        
        if (!$word) {
            throw new \Cake\Http\Exception\NotFoundException(__('Word not found'));
        }
        

        if ($this->loggedin && in_array($this->request->getSession()->read('Auth.role'),['superuser','user'])){
            
            $word = $this->Words->get($id);

            if ($this->request->is(['patch', 'post', 'put'])) {

                $data = $this->request->getData();

                // If Quill delta JSON was posted in the base fields (like the add flow),
                // convert it to HTML and store the delta in *_json.
                foreach (['etymology', 'notes'] as $field) {
                    $raw = $data[$field] ?? null;
                    if (is_string($raw) && preg_match('/^\s*\{.*"ops"\s*:\s*\[/s', $raw)) {
                        $data = $this->processSingleQuillField($data, $field);
                    }
                }

                // Handle Quill JSON fields from hidden inputs set by JavaScript.
                // Use array_key_exists so users can clear fields.
                if (array_key_exists('etymology_json', $data)) {
                    $word->etymology_json = $data['etymology_json'];
                }

                if (array_key_exists('notes_json', $data)) {
                    $word->notes_json = $data['notes_json'];
                }

                // Also allow normal plain-text versions
                $word->spelling  = $data['spelling'] ?? $word->spelling;
                $word->etymology = $data['etymology'] ?? $word->etymology;
                $word->notes     = $data['notes'] ?? $word->notes;

                if ($this->Words->save($word)) {
                    $this->Flash->success(__('The word has been updated.'));
                    return $this->redirect(['action' => 'view', $word->id]);
                }

                $this->Flash->error(__('The word could not be saved. Please, try again.'));
            }

            $this->set(compact('word'));
            $this->render('base_word_edit');

        } else {
            return $this->redirect([
                'controller' => 'Suggestions',
                'action' => 'add',
                $word['id']
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
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $word = $this->Words->get($id);
        if ($this->Words->delete($word)) {
            $this->logWordAction('delete', $word);
            $this->Flash->success(__('The word has been deleted.'));
        } else {
            $this->Flash->error(__('The word could not be deleted. Please, try again.'));
        }

        return $this->redirect(['prefix' => 'Moderators', 'controller' => 'panel', 'action' => 'index']);
    }


    public function approve($id = null) {
        $this->request->allowMethod(['post']);
        $datefortimestamp = date('Y-m-d h:i:s', time());
        //debug($id); 
        $word = $this->Words->get(primaryKey: $id, contain: ['Pronunciations']);
        $pronunciations = array();
        if (count($word->pronunciations) > 0) {
            foreach ($word->pronunciations as $p){
                if ('' !== $p->sound_file || null != $p->sound_file){
                    $this->Processfile->converttomp3($p->sound_file);
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
            $this->logWordAction('approve', $word);
            $this->Flash->success(__('The word has been approved.'));
        } else {
            $this->Flash->error(__('The word could not be approved. Please, try again.'));
        }

        return $this->redirect(['prefix' => 'Moderators', 'controller' => 'panel', 'action' => 'index']);
    }

    public function baseWordEdit($id = null) {
        $word = $this->Words->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->getData();

            foreach (['etymology', 'notes'] as $field) {
                $raw = $data[$field] ?? null;
                if (is_string($raw) && preg_match('/^\s*\{.*"ops"\s*:\s*\[/s', $raw)) {
                    $data = $this->processSingleQuillField($data, $field);
                }
            }

            // Handle Quill JSON fields from hidden inputs set by JavaScript.
            if (array_key_exists('etymology_json', $data)) {
                $word->etymology_json = $data['etymology_json'];
            }

            if (array_key_exists('notes_json', $data)) {
                $word->notes_json = $data['notes_json'];
            }

            // Also allow normal plain-text versions
            $word->spelling  = $data['spelling'] ?? $word->spelling;
            $word->etymology = $data['etymology'] ?? $word->etymology;
            $word->notes     = $data['notes'] ?? $word->notes;

            if ($this->Words->save($word)) {
                $this->Flash->success(__('The word has been updated.'));
                return $this->redirect(['action' => 'view', $word->id]);
            }

            $this->Flash->error(__('The word could not be saved. Please, try again.'));
        }

        $this->set(compact('word'));
    }
           

    public function success() {

    }

    public function wordnotfound() {

    }

    private function processQuillFields(array $postData, array $fields): array {
        foreach ($fields as $assoc => $field) {
            if (!isset($postData[$assoc])) continue;
            foreach ($postData[$assoc] as $i => $item) {
                $original = $item[$field] ?? '';
                if ('{"ops":[{"insert":"\n"}]}' === $original) {
                    unset($postData[$assoc][$i]);
                    continue;
                }
                $jsonFromOriginal = json_decode($original);
                $postData[$assoc][$i][$field . '_json'] = json_encode($jsonFromOriginal);
                $quill = new \nadar\quill\Lexer($original);
                $postData[$assoc][$i][$field] = $quill->render();
            }
        }
        return $postData;
    }

    private function processSingleQuillField(array $postData, string $field): array {
        $original = $postData[$field] ?? '';
    
        // Check if the original data is valid JSON
        if (json_decode($original) === null && json_last_error() !== JSON_ERROR_NONE) {
            // Handle invalid JSON case
            $postData[$field] = ''; // or set to a default value
            return $postData;
        }

        if ('{"ops":[{"insert":"\n"}]}' === $original) {
            unset($postData[$field]);
        } else {
            $jsonFromOriginal = json_decode($original);
            $postData[$field . '_json'] = json_encode($jsonFromOriginal);
            $quill = new \nadar\quill\Lexer($original);
            
            // Ensure the render method returns a valid value
            $rendered = $quill->render();
            if ($rendered !== null) {
                $postData[$field] = $rendered;
            } else {
                $postData[$field] = ''; // Handle the case where render returns null
            }
        }
        return $postData;
    }

    private function filterAssociations(array $postData, array $associations): array {
        foreach ($associations as $assoc) {
            if (!array_filter($postData[strtolower($assoc)][0] ?? [])) {
                unset($postData[strtolower($assoc)]);
            }
        }

        return $postData;
    }

    private function handleSoundFiles(array $postData, array $soundFiles): array {
        $i = 0;
        foreach ($soundFiles as $soundFile) {
            $name = $soundFile->getClientFilename();
            $finalname = str_replace([' ','/','\\','<',';',':','>','"','|','?','*'], '', $postData['spelling']) . time() . $i . '.webm';
            $targetPath = WWW_ROOT. 'recordings'. DS . $finalname;
            $type = $soundFile->getClientMediaType();
            if ($type == 'audio/webm' && !empty($name) && $soundFile->getSize() > 0 && $soundFile->getError() == 0) {
                $soundFile->moveTo($targetPath);
                $postData['pronunciations'][$i]['sound_file'] = $finalname;
            }
            if (null !== $this->request->getSession()->read('Auth.username') && 'superuser' == $this->request->getSession()->read('Auth.role') && ('' !== $name || null != $name)) {
                $this->Processfile->converttomp3($finalname);
            }
            $i++;
        }
        return $postData;
    }

    private function approvePronunciations(array $postData): array {
        if (null !== $this->request->getSession()->read('Auth.username') && 'superuser' == $this->request->getSession()->read('Auth.role') && !empty($postData['pronunciations'])) {
            $datefortimestamp = date('Y-m-d h:i:s', time());
            foreach ($postData['pronunciations'] as $i => $p) {
                $postData['pronunciations'][$i]['approved'] = 1;
                $postData['pronunciations'][$i]['approved_date'] = $datefortimestamp;
                $postData['pronunciations'][$i]['approving_user_id'] = $this->request->getSession()->read('Auth.id');
            }
        }
        return $postData;
    }

    /**
     * Improved "other" logic for origins/types
     * Handles both _ids and _other_entry fields, deduplicates, and ensures correct structure.
     */
    private function processOtherAssociations(array $postData, string $assoc): array {
        $processed = [];
        $ids = $postData[$assoc]['_ids'] ?? [];
        $otherEntry = $postData[$assoc . '_other_entry'] ?? '';

        // Add selected IDs
        foreach ($ids as $id) {
            if ($id !== '') {
                $processed[] = ['id' => $id];
            }
        }

        // Add "other" entries (semicolon separated)
        if ($otherEntry !== '') {
            $others = array_filter(array_map('trim', explode(';', $otherEntry)));
            $table = $this->fetchTable(ucfirst($assoc));
            foreach ($others as $otherValue) {
                $idOfOther = $table->getIdIfExists($otherValue);
                if ($idOfOther !== null) {
                    // Avoid duplicates
                    if (!in_array(['id' => $idOfOther], $processed)) {
                        $processed[] = ['id' => $idOfOther];
                    }
                } else {
                    $processed[] = [$assoc === 'origins' ? 'origin' : 'type' => $otherValue];
                }
            }
        }

        // Clean up
        unset($postData[$assoc]['_ids']);
        unset($postData[$assoc . '_other_entry']);
        if (!empty($processed)) {
            $postData[$assoc] = $processed;
        }
        return $postData;
    }

    /**
     * Centralized logging for word actions.
     */
    private function logWordAction(string $action, \App\Model\Entity\Word $word, array $extra = []): void
    {
        $username = $this->request->getSession()->read('Auth.username');
        $role = $this->request->getSession()->read('Auth.role');
        $logMsg = '';

        switch ($action) {
            case 'add_superuser':
                $logMsg = "Word \/\/ {$word->spelling} was added by {$username} \/\/ {$word->id}";
                break;
            case 'add_user':
                $logMsg = "{$word->spelling} was added by {$word->full_name} ({$word->email})";
                break;
            case 'edit':
                $logMsg = "Word \/\/ {$username} edited {$word->spelling} \/\/ {$word->id}";
                break;
            case 'delete':
                $logMsg = "Word \/\/ {$username} deleted {$word->spelling} \/\/ ";
                break;
            case 'approve':
                $logMsg = "Word \/\/ {$username} approved {$word->spelling} \/\/ {$word->id}";
                break;
            default:
                $logMsg = "{$action}: {$word->spelling}";
        }

        \Cake\Log\Log::info($logMsg, ['scope' => ['events']] + $extra);
    }
}
