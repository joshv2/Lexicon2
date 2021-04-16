<?php
// src/Model/Table/WordsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;

class WordsTable extends Table
{
    public function initialize(array $config): void
    {
        //$this->addBehavior('Timestamp');
        $this->belongsToMany('Dictionaries', ['joinTable' => 'dictionaries_words']);
        $this->belongsToMany('Origins', ['joinTable' => 'origins_words']);
        $this->belongsToMany('Regions', ['joinTable' => 'regions_words']);
        $this->belongsToMany('Types', ['joinTable' => 'types_words']);
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