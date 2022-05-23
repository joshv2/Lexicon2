<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sentences Model
 *
 * @property \App\Model\Table\WordsTable&\Cake\ORM\Association\BelongsTo $Words
 * @property \App\Model\Table\SentenceRecordingsTable&\Cake\ORM\Association\HasMany $SentenceRecordings
 *
 * @method \App\Model\Entity\Sentence newEmptyEntity()
 * @method \App\Model\Entity\Sentence newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Sentence[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sentence get($primaryKey, $options = [])
 * @method \App\Model\Entity\Sentence findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Sentence patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Sentence[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sentence|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sentence saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sentence[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sentence[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sentence[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Sentence[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SentencesTable extends Table
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

        $this->setTable('sentences');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Words', [
            'foreignKey' => 'word_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('SentenceRecordings', [
            'foreignKey' => 'sentence_id',
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
            ->scalar('sentence')
            //->requirePresence('sentence', 'create')
            ->allowEmptyString('sentence', 'true');

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
        $rules->add($rules->existsIn(['word_id'], 'Words'), ['errorField' => 'word_id']);

        return $rules;
    }

    public function get_sentences($id){
        $query = $this->find()
                    ->where(['id' => $id]);
        $results = $query->all();
        return $results->toArray();
    }


    /*public function get_sentences_with_pending_recordings(){
        $query = $this->find()
                    ->where(['Words.approved' => 1])
                    ->contain(['Sentences', 'Sentences.SentenceRecordings.RecordingUsers', 
                    'Sentences.SentenceRecordings.ApprovingUsers']) 
                    ->matching('Sentences.SentenceRecordings', function ($q) {
                        return $q->where(['SentenceRecordings.approved' => 0]);
                    });
        return $query;
    }*/

    public function get_sentences_with_pending_recordings($langid){
        $query = $this->find()
                    //->where(['SentenceRecordings.approved' => 0])
                    
                    ->contain(['Words' => function (Query $q) use ($langid) {
                        return $q->where(['Words.language_id' => $langid]);
                    }])
                    ->contain(['SentenceRecordings.RecordingUsers', 'SentenceRecordings.ApprovingUsers']) 
                    ->matching('SentenceRecordings' , function (Query $query) {
                        return $query->distinct()->where(['SentenceRecordings.approved' => 0]);
                    });
        return $query->toArray();
    }
}
