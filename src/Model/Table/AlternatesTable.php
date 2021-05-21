<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Alternates Model
 *
 * @property \App\Model\Table\WordsTable&\Cake\ORM\Association\BelongsTo $Words
 *
 * @method \App\Model\Entity\Alternate newEmptyEntity()
 * @method \App\Model\Entity\Alternate newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Alternate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Alternate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Alternate findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Alternate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Alternate[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Alternate|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Alternate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Alternate[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Alternate[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Alternate[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Alternate[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AlternatesTable extends Table
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

        $this->setTable('alternates');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Words', [
            'foreignKey' => 'word_id',
            'joinType' => 'INNER',
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
            ->allowEmptyString('spelling', 'true');
            //->requirePresence('spelling');
            //->allowEmptyString('spelling', 'false')*/

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
}
