<?php
// src/Model/Table/OriginsTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;

class OriginsTable extends Table
{
    public function initialize(array $config): void
    {
        //$this->addBehavior('Timestamp');
        $this->setDisplayField('origin');
        $this->setPrimaryKey('id');
        $this->belongsToMany('Words', [
            'joinTable' => 'origins_words',
            'foreignKey' => 'origin_id',
            'targetForeignKey' => 'word_id',
        ]);
        $this->belongsToMany('Languages', [
            'through' => 'OriginsLanguages'
        ]);
    }

    public function top_origins_for_home($langid){
        $query = $this->find('list', valueField: 'origin', order: 'Origins.id')
                        //->contain(['Languages'])
                        ->matching('Languages')
                        ->where(['OriginsLanguages.top' => 1, 'OriginsLanguages.language_id' => $langid]);
        //$query->disableHydration();
        $data = $query->toArray();
        return $data;
    }


    public function top_origins($langid){
        $query = $this->find('list', 
                        valueField: 'origin', 
                        order: ['Origins.id' => 'ASC'])
                        //->contain(['Languages'])
                        ->matching('Languages')
                        ->where(['OriginsLanguages.top' => 1, 'OriginsLanguages.language_id' => $langid]);

        $query2 = $this->find(type: 'list', options: ['valueField' => 'origin'])
                        ->where(['id' => 999]);
        $query = $query->union($query2);
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

    public function get_region_by_name($origin){
        $query = $this->find()->where(['origin' => $origin]);
        $nresults = $query->count();
        if ($nresults == 0) {
            $idarray = [];
        } else {
            foreach ($query as $q){
                $idarray[] = $q->id;
            }
        }
        return $idarray;
    }

    public function getIdIfExists(string $originValue): ?int
    {
        $matchingOrigin = $this->find()
            ->select(['id'])
            ->where(function (QueryExpression $exp, Query $q) use ($originValue) {
                return $exp->eq('LOWER(origin)', strtolower($originValue));
            })
            ->first();

        return $matchingOrigin ? $matchingOrigin->id : null;
    }

    /**
     * Query all origins for a given word id (via origins_words join table).
     * Returns a SelectQuery so it can be paginated.
     */
    public function getOriginsForWordIdQuery(int $wordId): \Cake\ORM\Query\SelectQuery
    {
        return $this->find()
            ->matching('Words', function (\Cake\ORM\Query\SelectQuery $q) use ($wordId) {
                return $q->where(['Words.id' => $wordId]);
            })
            ->distinct(['Origins.id'])
            ->orderBy(['Origins.id' => 'ASC']);
    }

    /**
     * (Optional) Keep the old convenience method, but build from the query.
     * Returns an array like: [origin_id => origin_name, ...]
     */
    public function getOriginsForWordId(int $wordId): array
    {
        return $this->getOriginsForWordIdQuery($wordId)
            ->find('list', valueField: 'origin')
            ->toArray();
    }

    /**
     * Custom finder so you can do: $this->Origins->find('byWordId', ['word_id' => 123])
     */
    public function findByWordId(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery
    {
        $wordId = $options['word_id'] ?? null;
        if (!$wordId) {
            return $query->where(['1 = 0']);
        }

        return $query
            ->matching('Words', function (\Cake\ORM\Query\SelectQuery $q) use ($wordId) {
                return $q->where(['Words.id' => $wordId]);
            })
            ->distinct(['Origins.id']);
    }

    /**
     * Returns junction rows from origins_words for a word, including the junction id
     * so you can delete that specific link.
     *
     * Result rows are OriginsWords entities (contain Origins).
     */
    public function getOriginLinksForWordIdQuery(int $wordId): \Cake\ORM\Query\SelectQuery
    {
        /** @var \App\Model\Table\OriginsWordsTable $OriginsWords */
        $OriginsWords = $this->fetchTable('OriginsWords');

        return $OriginsWords->find()
            ->select([
                'OriginsWords.id',
                'OriginsWords.word_id',
                'OriginsWords.origin_id',
            ])
            ->contain([
                'Origins' => function (\Cake\ORM\Query\SelectQuery $q) {
                    return $q->select(['Origins.id', 'Origins.origin']);
                }
            ])
            ->where(['OriginsWords.word_id' => $wordId])
            ->orderBy(['OriginsWords.id' => 'ASC']);
    }

}