<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dictionaries Model
 *
 * @property \App\Model\Table\WordsTable&\Cake\ORM\Association\BelongsToMany $Words
 *
 * @method \App\Model\Entity\Dictionary newEmptyEntity()
 * @method \App\Model\Entity\Dictionary newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Dictionary[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Dictionary get($primaryKey, $options = [])
 * @method \App\Model\Entity\Dictionary findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Dictionary patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Dictionary[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Dictionary|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dictionary saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Dictionary[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class DictionariesTable extends Table
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

        $this->setTable('dictionaries');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Words', [
            'foreignKey' => 'dictionary_id',
            'targetForeignKey' => 'word_id',
            'joinTable' => 'dictionaries_words',
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
            ->scalar('dictionary')
            ->maxLength('dictionary', 255)
            ->requirePresence('dictionary', 'create')
            ->notEmptyString('dictionary');

        return $validator;
    }

    public function top_dictionaries(){
        $query = $this->find('list', ['valueField' => 'dictionary', 'limit' => 6, 'order' => 'id']);
        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }

    public function get_all_ids(){
        $query = $this->find()->all()->extract('id');
        foreach ($query as $q){
            $idarray[] = $q;
        }
        $typeids = implode(',',$idarray);
        return $typeids;
    }
}
