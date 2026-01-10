<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class OriginsWordsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('origins_words');
        $this->setPrimaryKey('id');

        $this->belongsTo('Origins', [
            'foreignKey' => 'origin_id',
        ]);

        $this->belongsTo('Words', [
            'foreignKey' => 'word_id',
        ]);
    }
}