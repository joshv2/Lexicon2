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
        $displayType = $this->request->getQuery('displayType');
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

        // If no entry matches, search definitions for the term (approved words, current language only)
        $definitionWords = [];
        $definitionCount = 0;
        if ($countVal === 0) {
            $definitionWords = $wordsTable
                ->find('searchResultsByDefinition', querystring: $q, langid: $sitelang->id)
                ->all()
                ->toArray();
            $definitionCount = count($definitionWords);
        }

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

        $this->set(compact('words', 'q', 'isPaginated', 'count', 'originCounts', 'countVal', 'definitionWords', 'definitionCount'));
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