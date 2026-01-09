<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class RegionsWordsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('regions_words');
        $this->setPrimaryKey('id');

        $this->belongsTo('Regions', [
            'foreignKey' => 'region_id',
        ]);

        $this->belongsTo('Words', [
            'foreignKey' => 'word_id',
        ]);
    }
}