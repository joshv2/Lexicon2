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
        $sitelang = $this->languageinfo();
        $q = trim($this->request->getQuery('q'));
        $displayType = $this->request->getQuery('displayType');

        // Always get the full set for per-language count (not dependent on pagination)
        $allWords = $this->fetchTable('Words')->find('searchResults', querystring: $q, langid: $sitelang->id)->contain(['Origins'])->toArray();
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
        // keep originCounts structured (language => count); do not build presentation text here

        if ($displayType === 'all') {
            $words = $allWords;
            $isPaginated = false;
            $count = count($words);
        } else {
            $words = $this->paginate($this->fetchTable('Words')->find('searchResults', querystring: $q, langid: $sitelang->id)->contain(['Origins']));
            $isPaginated = true;
            $count = 0;
        }

        // Use full set count for the summary
        $countVal = count($allWords);

        // Prepare structured summary data (controller returns variables only)
        $summaryVars = $this->getResultSummaryData($countVal, $originCounts);

        $this->set(compact('words', 'q', 'isPaginated', 'count', 'originCounts', 'countVal'));
        $this->set($summaryVars);
        $this->render('results');
	}
    /**
     * Return structured summary variables for the view.
     * - resultWord: 'result' or 'results'
     * - originParts: array of ['num' => int, 'lang' => string]
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