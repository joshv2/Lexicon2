<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Http\Client;
use Cake\Core\Configure;

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

        $origins = $this->Origins->top_origins();
        $regions = $this->Regions->top_regions();
        $types = $this->Types->top_types();
        $dictionaries = $this->Dictionaries->top_dictionaries();

        $originvalue = $this->request->getQuery('origin');
        $regionvalue = $this->request->getQuery('region');
        $typevalue = $this->request->getQuery('use');
        $dictionaryvalue = $this->request->getQuery('dictionary');

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
        

        $this->set(compact('words', 'current_condition', 'origins', 'regions', 'types', 'dictionaries'));
        $this->render('browse');
    }

    public function random() {
        $words = $this->Paginator->paginate($this->Words->get_random_words());
        $this->set(compact('words'));

    }

    public function alphabetical()
    {
        
        $letter = $this->request->getParam('pass')[0];
        
        $words = $this->Words->get_words_starting_with_letter($letter);
        $this->set(compact('letter', 'words'));
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
        $word = $this->Words->get($id, [
            'contain' => ['Dictionaries', 'Origins', 'Regions', 'Types', 'Languages', 'Alternates', 'Definitions', 'Sentences', 'Pronunciations' => ['sort' => ['Pronunciations.display_order' => 'ASC']]],
            //'contain' => ['Definitions'],
            'cache' => false
        ]);

        $contain = ['Dictionaries', 'Origins', 'Regions', 'Types', 'Alternates', 'Definitions', 'Sentences', 'Pronunciations']; //, 'Sentences', 'Pronunciations'
        $valuenames = ['Dictionaries' => ['dictionary'], 
                        'Origins' => ['origin'], 
                        'Regions' => ['region'], 
                        'Types' => ['type'],
                        'Alternates' => ['spelling'],
                        'Definitions' => ['definition'], 
                        'Sentences' => ['sentence'], 'Pronunciations' => ['spelling', 'pronunciation', 'sound_file', 'notes']];//'Sentences' => ['sentence'], 'Pronunciations' => ['spelling', 'pronunciation', 'sound_file', 'notes']
        
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
        //debug($arraysforcompact);
        $this->set($arraysforcompact);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $word = $this->Words->newEmptyEntity();
        if ($this->request->is('post')) {
            //assign the post data to a variable
            $postData = $this->request->getData();
            //process the WYSIWYG Quills
            try {
                $quill = new \DBlackborough\Quill\Render($postData['definitions'][0]['definition']);
                $defresult = $quill->render();
                $postData['definitions'][0]['definition'] = $defresult;
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

            //reCaptcha authentication
            $recaptcha = $postData['g-recaptcha-response'];
            $google_url = "https://www.google.com/recaptcha/api/siteverify";
			$secret = Configure::consume('recaptcha_secret');
			$ip = $_SERVER['REMOTE_ADDR'];
            $url = $google_url . "?secret=" . $secret . "&response=" . $recaptcha ."&remoteip=" . $ip;
            $http = new Client();

            $res = $http->get($url);
            $json = $res->getJson();
            //debug($json);
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
            if ($json['success'] == "true" || $this->Identity->isLoggedIn()){   //reqiuring reCaptcha to be true or to be logged in
                if ($this->Words->save($word)) {
                    //$this->Flash->success(__('The word has been saved.'));

                    return $this->redirect(['action' => 'success']);
                }
            }
            $this->Flash->error(__('The word could not be saved. Please, try again.'));
        }
        $dictionaries = $this->Words->Dictionaries->find('list', ['limit' => 200]);
        $origins = $this->Words->Origins->find('list', ['limit' => 200]);
        $regions = $this->Words->Regions->find('list', ['limit' => 200]);
        $types = $this->Words->Types->find('list', ['limit' => 200]);
        $recaptcha_user = Configure::consume('recaptcha_user');
        $this->set(compact('word', 'dictionaries', 'origins', 'regions', 'types', 'recaptcha_user'));
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
        $word = $this->Words->get($id, [
            'contain' => ['Dictionaries', 'Origins', 'Regions', 'Types','Alternates','Languages','Definitions'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $word = $this->Words->patchEntity($word, $this->request->getData());
            if ($this->Words->save($word)) {
                $this->Flash->success(__('The word has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The word could not be saved. Please, try again.'));
        }
        $dictionaries = $this->Words->Dictionaries->find('list', ['limit' => 200]);
        $origins = $this->Words->Origins->find('list', ['limit' => 200]);
        $regions = $this->Words->Regions->find('list', ['limit' => 200]);
        $types = $this->Words->Types->find('list', ['limit' => 200]);
        $alternates = $this->Words->Alternates->find('list');
        $this->set(compact('word', 'dictionaries', 'origins', 'regions', 'types','alternates'));
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
            $this->Flash->success(__('The word has been deleted.'));
        } else {
            $this->Flash->error(__('The word could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    public function success(){

    }
}
