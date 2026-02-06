<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class TypesWordsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('types_words');
        $this->setPrimaryKey('id');

        $this->belongsTo('Types', [
            'foreignKey' => 'type_id',
        ]);

        $this->belongsTo('Words', [
            'foreignKey' => 'word_id',
        ]);
    }
}