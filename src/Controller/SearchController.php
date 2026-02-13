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
        $qRaw = $this->request->getQuery('q', '');
        if (is_array($qRaw)) {
            $qRaw = '';
        }
        $q = trim((string)$qRaw);
        $displayType = (string)$this->request->getQuery('displayType', '');

        // If no query was provided, render an empty result set.
        // This avoids TypeErrors and prevents expensive "match everything" searches.
        if ($q === '') {
            $words = [];
            $originCounts = [];
            $countVal = 0;
            $isPaginated = false;
            $count = 0;
            $summaryVars = $this->getResultSummaryData($countVal, $originCounts);

            $this->set(compact('words', 'q', 'isPaginated', 'count', 'originCounts', 'countVal'));
            $this->set($summaryVars);
            return $this->render('results');
        }
        $wordsTable = $this->fetchTable('Words');
        $baseQuery = $wordsTable->find('searchResults', querystring: $q, langid: $sitelang->id)->contain(['Origins']);
        $allWords = $baseQuery->contain(['Origins'])->toArray();
        $originCounts = [];
        foreach ($allWords as $word) {
            if (!empty($word->origins)) {
                foreach ($word->origins as $origin) {
                    $originName = $origin->origin;
                    if (!isset($originCounts[$originName])) {
                        $originCounts[$originName] = 0;
                    }
                    $originCounts[$originName]++;
                }
            }
        }

        // Use full set count for the summary
        $countVal = count($allWords);

        // If displayType=all we need the full set in PHP; otherwise use pagination
        if ($displayType === 'all') {
            $words = $allWords;
            $isPaginated = false;
            $count = count($words);
        } else {
            $words = $this->paginate($baseQuery);
            $isPaginated = true;
            $count = 0;
        }

        $summaryVars = $this->getResultSummaryData($countVal, $originCounts);

        $this->set(compact('words', 'q', 'isPaginated', 'count', 'originCounts', 'countVal'));
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