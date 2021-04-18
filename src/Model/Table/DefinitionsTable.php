<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Definitions Model
 *
 * @property \App\Model\Table\WordsTable&\Cake\ORM\Association\BelongsTo $Words
 *
 * @method \App\Model\Entity\Definition newEmptyEntity()
 * @method \App\Model\Entity\Definition newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Definition[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Definition get($primaryKey, $options = [])
 * @method \App\Model\Entity\Definition findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Definition patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Definition[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Definition|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Definition saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Definition[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Definition[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Definition[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Definition[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DefinitionsTable extends Table
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

        $this->setTable('definitions');
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
            ->scalar('definition')
            ->requirePresence('definition', 'create')
            ->notEmptyString('definition');

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
