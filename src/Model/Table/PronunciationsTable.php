<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pronunciations Model
 *
 * @property \App\Model\Table\WordsTable&\Cake\ORM\Association\BelongsTo $Words
 *
 * @method \App\Model\Entity\Pronunciation newEmptyEntity()
 * @method \App\Model\Entity\Pronunciation newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Pronunciation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Pronunciation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Pronunciation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Pronunciation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Pronunciation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Pronunciation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Pronunciation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Pronunciation[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Pronunciation[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Pronunciation[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Pronunciation[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class PronunciationsTable extends Table
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
        $this->setTable('pronunciations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Words', [
            'foreignKey' => 'word_id',
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
            ->scalar('spelling')
            ->maxLength('spelling', 255)
            //->requirePresence('spelling', 'create')
            ->allowEmptyString('spelling', 'true');

        $validator
            ->notEmptyString('spelling', 'Please enter a spelling if you made a recording', function ($context) {
                    //debug($context['data']['sound_file']);
                    return !empty($context['data']['sound_file']);
                }
            );
            //->maxLength('sound_file', 4000)
            //->requirePresence('sound_file', 'create')
            //->allowEmptyFile('sound_file', 'true');

        $validator
            ->scalar('pronunciation')
            ->maxLength('pronunciation', 4000)
            //->requirePresence('pronunciation', 'create')
            ->allowEmptyString('pronunciation', 'true');

        $validator
            ->scalar('notes')
            ->maxLength('notes', 4000)
            //->requirePresence('notes', 'create')
            ->allowEmptyString('notes');

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

    public function get_user_pronunciations($userid){
        $query = $this->find()
                    ->where(['Pronunciations.user_id' => $userid])
                    ->contain(['Words'])
                    ->order(['Pronunciations.created' => 'DESC']);
        return $query;
    }

    public function get_pending_pronunciations(){
        $query = $this->find()
                    ->where(['Pronunciations.approved' => 0])
                    ->contain(['Words' => function (Query $q) {
                        return $q->where(['Words.approved' => 1]);
                    }, 'RecordingUsers', 'ApprovingUsers'])
                    ->order(['Pronunciations.created' => 'DESC']);
        return $query;
    }

    public function get_all_pronunciations(){
        $query = $this->find()->contain(['Words', 'RecordingUsers', 'ApprovingUsers'])
                   ->order(['Pronunciations.approved_date' => 'DESC']);
        return $query;
    }
}
