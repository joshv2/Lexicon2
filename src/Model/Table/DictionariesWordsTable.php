<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class DictionariesWordsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('dictionaries_words');
        $this->setPrimaryKey('id');

        $this->belongsTo('Dictionaries', [
            'foreignKey' => 'dictionary_id',
        ]);

        $this->belongsTo('Words', [
            'foreignKey' => 'word_id',
        ]);
    }
}
