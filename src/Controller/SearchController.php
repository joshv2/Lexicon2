<?php
declare(strict_types=1);

namespace App\Controller;


class SearchController extends AppController {
	public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Paginator');
    }

	public function index()
    {
        $sitelang = $this->request->getAttribute('sitelang');
        $q = trim((string)$this->request->getQuery('q'));
        // Normalize query text so searches work with HTML entities and common punctuation variants.
        $q = html_entity_decode($q, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $q = str_replace(["\u{2019}", "\u{2018}", "\u{02BC}"], "'", $q);

        $section = (string)$this->request->getQuery('section');
        if (!in_array($section, ['', 'entries', 'definitions', 'elsewhere'], true)) {
            $section = '';
        }

        $displayType = $this->request->getQuery('displayType');
        $previewLimit = 5;

        $wordsTable = $this->fetchTable('Words');

        $entriesQuery = $wordsTable->find('searchResults', querystring: $q, langid: $sitelang->id);
        $entryIdQuery = $wordsTable->find('searchResults', querystring: $q, langid: $sitelang->id)
            ->select(['Words.id'], true)
            ->distinct(['Words.id'])
            ->orderBy([], true);

        $entryCountQuery = $wordsTable->find('searchResults', querystring: $q, langid: $sitelang->id)
            ->select(['Words.id'], true)
            ->distinct(['Words.id'])
            ->orderBy([], true);
        $entryCount = (int)$entryCountQuery->count();

        $definitionsQuery = $wordsTable->find('searchResultsByDefinition', querystring: $q, langid: $sitelang->id)
            ->andWhere(['Words.id NOT IN' => $entryIdQuery]);

        $definitionIdQuery = $wordsTable->find('searchResultsByDefinition', querystring: $q, langid: $sitelang->id)
            ->andWhere(['Words.id NOT IN' => $entryIdQuery])
            ->select(['Words.id'], true)
            ->distinct(['Words.id'])
            ->orderBy([], true);
        $definitionCountQuery = $wordsTable->find('searchResultsByDefinition', querystring: $q, langid: $sitelang->id)
            ->andWhere(['Words.id NOT IN' => $entryIdQuery])
            ->select(['Words.id'], true)
            ->distinct(['Words.id'])
            ->orderBy([], true);
        $definitionCount = (int)$definitionCountQuery->count();

        $elsewhereQuery = $wordsTable->find('searchResultsElsewhere', querystring: $q, langid: $sitelang->id)
            ->andWhere(['Words.id NOT IN' => $entryIdQuery])
            ->andWhere(['Words.id NOT IN' => $definitionIdQuery]);

        $elsewhereIdQuery = $wordsTable->find('searchResultsElsewhere', querystring: $q, langid: $sitelang->id)
            ->andWhere(['Words.id NOT IN' => $entryIdQuery])
            ->andWhere(['Words.id NOT IN' => $definitionIdQuery])
            ->select(['Words.id'], true)
            ->distinct(['Words.id'])
            ->orderBy([], true);
        $elsewhereCountQuery = $wordsTable->find('searchResultsElsewhere', querystring: $q, langid: $sitelang->id)
            ->andWhere(['Words.id NOT IN' => $entryIdQuery])
            ->andWhere(['Words.id NOT IN' => $definitionIdQuery])
            ->select(['Words.id'], true)
            ->distinct(['Words.id'])
            ->orderBy([], true);
        $elsewhereCount = (int)$elsewhereCountQuery->count();

        $countVal = $entryCount + $definitionCount + $elsewhereCount;

        $originCounts = [];
        if ($countVal > 0) {
            $allIdQuery = (clone $entryIdQuery)->union($definitionIdQuery)->union($elsewhereIdQuery);

            $originQuery = $wordsTable->find();
            $originRows = $originQuery
                ->select([
                    'origin' => 'Origins.origin',
                    'cnt' => $originQuery->func()->count('DISTINCT Words.id'),
                ])
                ->innerJoinWith('Origins')
                ->where(['Words.id IN' => $allIdQuery])
                ->groupBy(['Origins.origin'])
                ->orderBy(['cnt' => 'DESC', 'Origins.origin' => 'ASC'])
                ->enableHydration(false)
                ->toArray();

            foreach ($originRows as $row) {
                if (!empty($row['origin'])) {
                    $originCounts[(string)$row['origin']] = (int)$row['cnt'];
                }
            }
        }

        $words = [];
        $definitionWords = [];
        $elsewhereWords = [];
        $isPaginated = false;
        $count = 0;

        if ($section === '') {
            $words = $entriesQuery->limit($previewLimit)->all()->toArray();
            $definitionWords = $definitionsQuery->limit($previewLimit)->all()->toArray();
            $elsewhereWords = $elsewhereQuery->limit($previewLimit)->all()->toArray();
        } elseif ($section === 'entries') {
            if ($displayType === 'all') {
                $words = $entriesQuery->all()->toArray();
                $count = count($words);
            } else {
                $words = $this->paginate($entriesQuery);
                $isPaginated = true;
            }
        } elseif ($section === 'definitions') {
            if ($displayType === 'all') {
                $definitionWords = $definitionsQuery->all()->toArray();
                $count = count($definitionWords);
            } else {
                $definitionWords = $this->paginate($definitionsQuery);
                $isPaginated = true;
            }
        } else {
            if ($displayType === 'all') {
                $elsewhereWords = $elsewhereQuery->all()->toArray();
                $count = count($elsewhereWords);
            } else {
                $elsewhereWords = $this->paginate($elsewhereQuery);
                $isPaginated = true;
            }
        }

        $summaryVars = $this->getResultSummaryData($countVal, $originCounts);

        $this->set(compact(
            'q',
            'section',
            'previewLimit',
            'isPaginated',
            'displayType',
            'count',
            'countVal',
            'originCounts',
            'entryCount',
            'definitionCount',
            'elsewhereCount',
            'words',
            'definitionWords',
            'elsewhereWords'
        ));
        $this->set($summaryVars);
        $this->render('results');
	}

    /**
     * Return structured summary variables for the view.
     */
    private function getResultSummaryData(int $countVal, array $originCounts = []): array
    {
        $resultWord = ($countVal === 1) ? __('result') : __('results');

        $originParts = [];
        foreach ($originCounts as $lang => $num) {
            $originParts[] = ['num' => (int)$num, 'lang' => $lang];
        }

        return ['resultWord' => $resultWord, 'originParts' => $originParts];
    }
}