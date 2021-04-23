<?php
declare(strict_types=1);

namespace App\Controller;

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
        
        $words = $this->paginate($this->Words->find());
        


        $originfilter = array();
        if(!is_null($originvalue)){
            foreach ($words as $w) {
                foreach ($w->origins as $wo){
                    if ($wo->id == $originvalue){
                        $originfilter[] = $w;
                    }
                }
            }
        } else {
            $originfilter = $words;
        }
        

        $regionfilter = array();
        if(!is_null($regionvalue)){
            foreach ($originfilter as $w) {
                foreach ($w->regions as $wr){
                    if ($wr->id == $regionvalue){
                        $regionfilter[] = $w;
                    }
                }
            }
        } else {
            $regionfilter = $originfilter;
        }

        $typefilter = array();
        if(!is_null($typevalue)){
            foreach ($regionfilter as $w) {
                foreach ($w->types as $wt){
                    if ($wt->id == $typevalue){
                        $typefilter[] = $w;
                    }
                }
            }
        } else {
            $typefilter = $regionfilter;
        }

        $dictionaryfilter = array();
        if(!is_null($dictionaryvalue)){
            foreach ($typefilter as $w) {
                foreach ($w->dictionaries as $wd){
                    if ($wd->id == $dictionaryvalue){
                        $dictionaryfilter[] = $w;
                    }
                }
            }
        } else {
            $dictionaryfilter = $typefilter;
        }

        $words2 = $dictionaryfilter;

        $this->set(compact('words', 'words2', 'current_condition', 'origins', 'regions', 'types', 'dictionaries'));
        $this->render('browse');
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
        $this->set(compact('word', 'dictionaries', 'origins', 'regions', 'types'));
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
            'contain' => ['Dictionaries', 'Origins', 'Regions', 'Types'],
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
        $this->set(compact('word', 'dictionaries', 'origins', 'regions', 'types'));
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
}
