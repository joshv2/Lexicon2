<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\ORM\Query\SelectQuery;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableLocator;
use Cake\ORM\Rule\IsUnique;
// Include use statements at the top of your file.
use Cake\Event\EventInterface;
use ArrayObject;
//use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Words Model
 *
 * @property \App\Model\Table\LanguagesTable&\Cake\ORM\Association\BelongsTo $Languages
 * @property \App\Model\Table\AlternatesTable&\Cake\ORM\Association\HasMany $Alternates
 * @property \App\Model\Table\DefinitionsTable&\Cake\ORM\Association\HasMany $Definitions
 * @property \App\Model\Table\PronunciationsTable&\Cake\ORM\Association\HasMany $Pronunciations
 * @property \App\Model\Table\SentencesTable&\Cake\ORM\Association\HasMany $Sentences
 * @property \App\Model\Table\DictionariesTable&\Cake\ORM\Association\BelongsToMany $Dictionaries
 * @property \App\Model\Table\OriginsTable&\Cake\ORM\Association\BelongsToMany $Origins
 * @property \App\Model\Table\RegionsTable&\Cake\ORM\Association\BelongsToMany $Regions
 * @property \App\Model\Table\TypesTable&\Cake\ORM\Association\BelongsToMany $Types
 *
 * @method \App\Model\Entity\Word newEmptyEntity()
 * @method \App\Model\Entity\Word newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Word[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Word get($primaryKey, $options = [])
 * @method \App\Model\Entity\Word findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Word patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Word[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Word|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Word saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Word[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Word[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Word[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Word[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WordsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('words');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Languages', [
            'foreignKey' => 'language_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'className' => 'CakeDC/Users.Users',
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT',
        ]);
        $this->hasMany('Alternates', [
            'foreignKey' => 'word_id',
        ]);
        $this->hasMany('Definitions', [
            'foreignKey' => 'word_id',
        ]);
        $this->hasMany('Pronunciations', [
            'foreignKey' => 'word_id',
        ]);
        $this->hasMany('Sentences', [
            'foreignKey' => 'word_id',
        ]);
        $this->hasMany('Suggestions', [
            'foreignKey' => 'word_id',
        ]);
        $this->belongsToMany('Dictionaries', [
            'foreignKey' => 'word_id',
            'targetForeignKey' => 'dictionary_id',
            'joinTable' => 'dictionaries_words',
        ]);
        $this->belongsToMany('Origins', [
            'foreignKey' => 'word_id',
            'targetForeignKey' => 'origin_id',
            'joinTable' => 'origins_words',
        ]);
        $this->belongsToMany('Regions', [
            'foreignKey' => 'word_id',
            'targetForeignKey' => 'region_id',
            'joinTable' => 'regions_words',
        ]);
        $this->belongsToMany('Types', [
            'foreignKey' => 'word_id',
            'targetForeignKey' => 'type_id',
            'joinTable' => 'types_words',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('spelling')
            ->maxLength('spelling', 255)
            ->requirePresence('spelling', 'create')
            ->notEmptyString('spelling');

        $validator
            ->scalar('etymology')
            //->requirePresence('etymology', 'create')
            ->allowEmptyString('etymology', 'true');

        $validator
            ->scalar('notes')
            //->requirePresence('notes', 'create')
            ->allowEmptyString('notes', 'true');

        /*$validator
            ->scalar('full_name');*/
            //->requirePresence('full_name', 'create')
            //->notEmptyString('full_name');

        /*$validator
            ->boolean('approved')
            ->requirePresence('approved', 'create')
            ->notEmptyString('approved');

        $validator
            ->email('email');*/


        return $validator;
    }

    public function validationNotloggedin(Validator $validator): Validator
    {
        $validator = $this->validationDefault($validator);

        $validator
            ->scalar('full_name')
            ->requirePresence('full_name', 'create')
            ->notEmptyString('full_name');

        $validator
            ->email('email');

        return $validator;
    }
    

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['language_id'], 'Languages'), ['errorField' => 'language_id']);
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);
        $rules->addCreate([$this, 'findWithSpelling'], 'uniqueSpelling', ['errorField' => 'spelling', 'message' => 'This word has already been submitted.']);
        return $rules;
    }


    public function get_not_in_other_dictionary($langid){
        $query = $this->find()
            ->join([
                'd' => [
                    'table' => 'dictionaries_words',
                    'type' => 'LEFT',
                    'conditions' => 'Words.id = d.word_id'
                ]
            ])->where(['d.word_id IS' => NULL, 'Words.approved' => 1,  'language_id' => $langid]);
        return $query->count();
    }

    public function get_words_with_no_pronunciations($langid){
        $query = $this->find()
            ->join([
                'p' => [
                    'table' => 'pronunciations',
                    'type' => 'LEFT',
                    'conditions' => 'Words.id = p.word_id'
                ]
            ])->where(['Words.approved' => 1, 'language_id' => $langid, 'p.word_id IS' => NULL])
            ->contain(['Users']);
        return $query;
    }

    public function get_words_starting_with_letter($letter, $langid){
        //need to add logic around approved words
        $query = $this->find()
                    ->where(['spelling LIKE' => $letter.'%', 'approved' => 1, 'language_id' => $langid])
                    ->contain(['Definitions'])
                    ->order(['spelling' => 'ASC']);
        return $query->all();
    }

    public function browse_words_filter($originvalue, $regionvalue, $typevalue, $dictionaryvalue, $returnjson, $langid, $index = FALSE){

        if ($originvalue == NULL && $regionvalue == NULL && $typevalue == NULL && $dictionaryvalue == NULL){
            $query = $this->find()
                        ->where(['language_id' => $langid, 'approved' => 1])
                        ->contain(['Definitions']);
        } else {

            $params = [];
            if (count($originvalue) == 0 OR 'none' == $originvalue || null == $originvalue[0]){
            }
            elseif (!is_null($originvalue) && 'other' !== $originvalue){
                $params['o.origin_id IN'] = $originvalue;
            } elseif ('other' == $originvalue) {
                $params['o.origin_id ='] = 999;
            }

            if (count($regionvalue) == 0 OR 'none' == $regionvalue || null == $regionvalue[0]){
            }
            elseif ((!is_null($regionvalue) && 'other' !== $regionvalue) || count($regionvalue) > 0){
                $params['r.region_id IN'] = $regionvalue;
            } elseif ('other' == $regionvalue) {
                $params['r.region_id ='] = 999;
            }

            if (count($typevalue) == 0 OR 'none' == $typevalue || null == $typevalue[0]){
            }
            elseif ((!is_null($typevalue) && 'other' !== $typevalue) || count($typevalue) > 0){
                $params['t.type_id IN'] = $typevalue;
            } elseif ('other' == $typevalue) {
                $params['t.type_id ='] = 999;
            }

            if (0 == count($dictionaryvalue) || 'none' == $dictionaryvalue || null == $dictionaryvalue[0]){
            }
            elseif ((!is_null($dictionaryvalue) && 'other' !== $dictionaryvalue && 'none' !== $dictionaryvalue)
            || count($dictionaryvalue) > 0) {
                $params['d.dictionary_id IN'] = $dictionaryvalue;
            } elseif ('other' == $dictionaryvalue) {
                $params['d.dictionary_id NOT IN'] = [1,2,3,4,5,6];
            } elseif ('none' == $dictionaryvalue) {
                
            }
            $params['approved ='] = 1; 
            if ($index === FALSE) {
            $query = $this->find()
                        ->select(['id'])
                        ->join([
                            'd' => [
                                'table' => 'dictionaries_words',
                                'type' => 'LEFT',
                                'conditions' => 'Words.id = d.word_id'
                            ],
                            't' => [
                                'table' => 'types_words',
                                'type' => 'LEFT',
                                'conditions' => 'Words.id = t.word_id'
                            ],
                            'r' => [
                                'table' => 'regions_words',
                                'type' => 'LEFT',
                                'conditions' => 'Words.id = r.word_id'
                            ],
                            'o' => [
                                'table' => 'origins_words',
                                'type' => 'LEFT',
                                'conditions' => 'Words.id = o.word_id'
                            ]
                        ])
                        ->where([$params, 'language_id' => $langid])
                        //->contain(['Definitions'])
                        ->distinct()
                        ->order(['spelling' => 'ASC']);
            } else {
                $query = $this->find()
                ->join([
                    'd' => [
                        'table' => 'dictionaries_words',
                        'type' => 'LEFT',
                        'conditions' => 'Words.id = d.word_id'
                    ],
                    't' => [
                        'table' => 'types_words',
                        'type' => 'LEFT',
                        'conditions' => 'Words.id = t.word_id'
                    ],
                    'r' => [
                        'table' => 'regions_words',
                        'type' => 'LEFT',
                        'conditions' => 'Words.id = r.word_id'
                    ],
                    'o' => [
                        'table' => 'origins_words',
                        'type' => 'LEFT',
                        'conditions' => 'Words.id = o.word_id'
                    ]
                ])
                ->where([$params, 'language_id' => $langid])
                ->contain(['Definitions'])
                ->distinct()
                ->order(['spelling' => 'ASC']);
            }

        }    
        if ($returnjson) {
            $results = $query->all();
            return json_encode($results);
        } else {
            return $query;
        }
    }

    public function browse_words_filter2($wordIds, $langid){
        $params['approved ='] = 1; 
        $query = $this->find()->where([$params, 'language_id' => $langid, 'id IN' => $wordIds])
                ->contain(['Definitions'])
                ->distinct()
                ->order(['spelling' => 'ASC']);
        return json_encode($query);
    }

    public function get_random_words($langid) {
        $query = $this->find()
                ->contain(['Definitions'])
                ->where(['approved' => 1, 'language_id' => $langid])
                ->order("rand()")
                ->limit(20);
        return $query;
    }

    public function get_word_for_view($id){
        $query = $this->find()
                    ->where(['Words.id' => $id])
                    ->contain(['Dictionaries', 'Origins', 'Regions', 'Types', 'Languages', 'Definitions'  => [
                        'sort' => ['id' => 'ASC']], 'Sentences'])
                    ->contain('Pronunciations', function (Query $q) {
                        return $q
                            ->where(['Pronunciations.approved' => 1])
                            ->where(['OR' => [['Pronunciations.sound_file !=' => ''],
                                            ['Pronunciations.pronunciation !=' => '']]])
                            ->order(['Pronunciations.display_order' => 'ASC']);
                    })
                    ->contain('Sentences.SentenceRecordings', function (Query $q) {
                        return $q
                            ->where(['SentenceRecordings.approved' => 1])
                            ->order(['SentenceRecordings.display_order' => 'ASC']);
                    })
                    ->contain('Alternates', function (Query $q) {
                        return $q
                            ->where(['Alternates.spelling !=' => '']);
                    });
        $results = $query->all();
        return $results->toArray();
    }

    public function get_word_for_edit($id){
        $query = $this->find()
                    ->where(['Words.id' => $id])
                    ->contain(['Dictionaries', 'Origins', 'Regions', 'Types', 'Languages', 'Alternates', 'Definitions'  => [
                        'sort' => ['id' => 'ASC']], 'Sentences', 'Pronunciations'])
                    ->contain('Suggestions', function (Query $q) {
                        return $q
                            ->where(['Suggestions.status' => 'unread'])
                            ->order(['Suggestions.created' => 'ASC']);
                    });
        //$results = $query->all();
        return $query->first();
    }

    public function get_pending_words($langid) {
        $query = $this->find()
                       ->where(['approved' => 0, 'language_id' => $langid])->order(['Words.created' => 'DESC'])
                       ->contain(['Users']);
        return $query;
    }

    public function findSearchResults(SelectQuery $query, $options){
               
        $querystring = addslashes($options['querystring']);
        $langid = $options['langid'];
        $query = $this->find()->contain(['Definitions']);
        $query = $query->join([
                        'd' => [
                            'table' => 'definitions',
                            'type' => 'LEFT',
                            'conditions' => 'Words.id = d.word_id'
                        ],
                        'a' => [
                            'table' => 'alternates',
                            'type' => 'LEFT',
                            'conditions' => 'Words.id = a.word_id'
                        ],
                        's' => [
                            'table' => 'sentences',
                            'type' => 'LEFT',
                            'conditions' => 'Words.id = s.word_id'
                        ]
                    ]);


        $spellingmatch = $query->newExpr()
                    ->case()
                    ->when(['Words.spelling LIKE' => $querystring])
                    ->then(2)
                    ->when(['a.spelling LIKE' => $querystring])
                    ->then(2)
                    ->when(['Words.spelling LIKE' => '%'.$querystring.'%'])
                    ->then(1)
                    ->when(['a.spelling LIKE' => '%'.$querystring.'%'])
                    ->then(1)
                    ->else(0);

        $query = $query->select(['id','spelling',
                                 'alternates'=> 'group_concat(a.spelling)', 
                                 'definitions' => 'group_concat(DISTINCT d.id)', 
                                 'spellingmatch' => $spellingmatch,
                                 'notesmatch' => "MATCH(Words.notes) AGAINST ('".$querystring."')",
                                 'definitionmatch' => "MATCH(d.definition) AGAINST ('".$querystring."')"])
                        ->where(['language_id' => $langid, 'OR' => [['Words.spelling LIKE' => '%'.$querystring.'%'],
                                         ['a.spelling LIKE' => '%'.$querystring.'%'],
                                         ["MATCH(Words.notes) AGAINST ('".$querystring."')"],
                                         ["MATCH(d.definition) AGAINST ('".$querystring."')"],
                                         ["MATCH(s.sentence) AGAINST ('".$querystring."')"],
                                         ['etymology LIKE' => '%'.$querystring.'%']], 'approved' => 1])
                        ->group(['Words.id'])
                        ->order(['spellingmatch' => 'DESC', 'definitionmatch' => 'DESC', 'notesmatch' => 'DESC', 'Words.spelling' => 'ASC']);

        //debug($query);
        return $query;
    }

    public function findWithSpelling($spelling){
        if(isset($spelling["spelling"])){
            $wordtosearch = $spelling["spelling"];
        } else {
            $wordtosearch = $spelling;
        }
        $alternates = $this->Alternates;

        $wordspellingquery = $this->find()
                                ->select(['spelling'])
                                ->where(['spelling =' => $wordtosearch, 'approved' => 1, 'language_id' => $spelling["language_id"]
                            ]);

        $altspellingquery = $alternates->find()
                                ->select(['spelling'])
                                ->contain('Words'
                                        )
                                ->where(['Alternates.spelling =' => $wordtosearch, 'Words.language_id' => $spelling["language_id"]]);

        $finalquery = $altspellingquery->union($wordspellingquery);
        //return $finalquery->toArray();
        if ($finalquery->count() > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function get_user_words($userid, $langid){
        $query = $this->find()->where(['user_id' => $userid, 'language_id' => $langid])->order(['created' => 'DESC']);
        return $query;
    }

    public function get_sentences_with_pending_recordings(){
        $query = $this->find()
                    ->distinct()
                    ->where(['Words.approved' => 1])
                    ->contain(['Sentences', 'Sentences.SentenceRecordings.RecordingUsers', 
                    'Sentences.SentenceRecordings.ApprovingUsers']) 
                    ->matching('Sentences.SentenceRecordings', function ($q) {
                        return $q->where(['SentenceRecordings.approved' => 0]);
                    });
        return $query;
    }

}
