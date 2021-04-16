<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
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
            ->requirePresence('etymology', 'create')
            ->notEmptyString('etymology');

        $validator
            ->scalar('notes')
            ->requirePresence('notes', 'create')
            ->notEmptyString('notes');

        $validator
            ->boolean('approved')
            ->requirePresence('approved', 'create')
            ->notEmptyString('approved');

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

        return $rules;
    }

    public function get_not_in_other_dictionary(){
        $query = $this->find()
            ->join([
                'd' => [
                    'table' => 'dictionaries_words',
                    'type' => 'LEFT',
                    'conditions' => 'id = d.word_id'
                ]
            ])->where(['d.word_id IS' => NULL]);
        return $query->count();
    }

    public function get_words_starting_with_letter($letter){
        
    }

}
