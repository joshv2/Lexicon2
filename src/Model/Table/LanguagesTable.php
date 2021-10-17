<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Languages Model
 *
 * @property \App\Model\Table\WordsTable&\Cake\ORM\Association\HasMany $Words
 *
 * @method \App\Model\Entity\Language newEmptyEntity()
 * @method \App\Model\Entity\Language newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Language[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Language get($primaryKey, $options = [])
 * @method \App\Model\Entity\Language findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Language patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Language[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Language|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Language saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Language[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Language[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Language[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Language[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class LanguagesTable extends Table
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

        $this->setTable('languages');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Words', [
            'foreignKey' => 'language_id',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('subdomain')
            ->maxLength('subdomain', 255)
            ->requirePresence('subdomain', 'create')
            ->notEmptyString('subdomain');

        /*$validator
            ->scalar('HeaderImage')
            ->maxLength('HeaderImage', 255)
            //->requirePresence('HeaderImage', 'create')
            //->notEmptyString('HeaderImage')
            ;

        $validator
            ->scalar('AboutSec1Header')
            //->requirePresence('AboutSec1Header', 'create')
            //->notEmptyString('AboutSec1Header')
            ;

        $validator
            ->scalar('AboutSec1Text')
            //->requirePresence('AboutSec1Text', 'create')
            //->notEmptyString('AboutSec1Text')
            ;

        $validator
            ->scalar('AboutSec2Header')
            //->requirePresence('AboutSec2Header', 'create')
            //->notEmptyString('AboutSec2Header')
            ;

        $validator
            ->scalar('AboutSec2Text')
            //->requirePresence('AboutSec2Text', 'create')
            //->notEmptyString('AboutSec2Text')
            ;

        $validator
            ->scalar('AboutSec3Header')
            //->requirePresence('AboutSec3Header', 'create')
            //->notEmptyString('AboutSec3Header')
            ;

        $validator
            ->scalar('AboutSec3Text')
            //->requirePresence('AboutSec3Text', 'create')
            //->notEmptyString('AboutSec3Text')
            ;

        $validator
            ->scalar('AboutSec4Header')
            //->requirePresence('AboutSec4Header', 'create')
            //->notEmptyString('AboutSec4Header')
            ;

        $validator
            ->scalar('AboutSec4Text')
            //->requirePresence('AboutSec4Text', 'create')
            //->notEmptyString('AboutSec4Text')
            ;

        $validator
            ->scalar('NotesSec1Header')
            //->requirePresence('NotesSec1Header', 'create')
            //->notEmptyString('NotesSec1Header')
            ;

        $validator
            ->scalar('NotesSec1Text')
            //->requirePresence('NotesSec1Text', 'create')
            //->notEmptyString('NotesSec1Text')
            ;*/

        return $validator;
    }

    public function get_language($subdomain){
        //need to add logic around approved words
        $query = $this->find()
                    ->where(['subdomain' => $subdomain]);
        return $query->first();
    }
}
