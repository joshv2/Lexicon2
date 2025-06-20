<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SentenceRecordings Model
 *
 * @property \App\Model\Table\SentencesTable&\Cake\ORM\Association\BelongsTo $Sentences
 *
 * @method \App\Model\Entity\SentenceRecording newEmptyEntity()
 * @method \App\Model\Entity\SentenceRecording newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SentenceRecording[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SentenceRecording get($primaryKey, $options = [])
 * @method \App\Model\Entity\SentenceRecording findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SentenceRecording patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SentenceRecording[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SentenceRecording|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SentenceRecording saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SentenceRecording[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SentenceRecording[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SentenceRecording[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SentenceRecording[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SentenceRecordingsTable extends Table
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
        $this->addBehavior('Timestamp');
        $this->setTable('sentence_recordings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Sentences', [
            'foreignKey' => 'sentence_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('RecordingUsers', [
            'className' => 'CakeDC/Users.Users',
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT',
            'propertyName' => 'submitting_user'
        ]);

        $this->belongsTo('ApprovingUsers', [
            'className' => 'CakeDC/Users.Users',
            'foreignKey' => 'approving_user_id',
            'joinType' => 'LEFT',
            'propertyName' => 'approving_user'
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
            ->scalar('sound_file')
            ->maxLength('sound_file', 4000)
            ->requirePresence('sound_file', 'create')
            ->notEmptyFile('sound_file');

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
        $rules->add($rules->existsIn(['sentence_id'], 'Sentences'), ['errorField' => 'sentence_id']);

        return $rules;
    }

    public function get_recordings($sentenceid){
        $query = $this->find()
                    ->where(['sentence_id' => $sentenceid])
                    ->contain(['RecordingUsers', 'ApprovingUsers'])
                    ->orderBy('display_order');
        return $query;
    }
}
