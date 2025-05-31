<?php
// src/Model/Table/TypeCategoriesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class TypeCategoriesTable extends Table
{
    public function initialize(array $config): void
    {
        //$this->addBehavior('Timestamp');
        $this->setDisplayField('category');
        $this->setPrimaryKey('id');
        $this->belongsToMany('Users', ['className' => 'CakeDC/Users.Users']);
        $this->belongsToMany('Languages', [
            'through' => 'TypesLanguages'
        ]);
        $this->belongsToMany('Types', [
            'through' => 'TypesLanguages',
            'targetForeignKey' => 'type_id']);
    }

    public function top_types_for_home_by_cat($langid){
        $query = $this->find()->select(['TypeCategories.id'])
                        ->group('TypeCategories.id')
                        ->contain(['Types'])
                        ->matching('Languages')
                        ->where(['TypesLanguages.top' => 1, 'TypesLanguages.language_id' => $langid]);
        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }
}