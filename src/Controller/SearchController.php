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
        $originSummary = '';
        if (!empty($originCounts)) {
            $parts = [];
            foreach ($originCounts as $lang => $ocount) {
                $parts[] = $ocount . ' were from ' . $lang;
            }
            $originSummary = implode(', ', $parts);
        }

        if ($displayType === 'all') {
            $words = $allWords;
            $isPaginated = false;
            $count = count($words);
        } else {
            $words = $this->paginate($this->fetchTable('Words')->find('searchResults', querystring: $q, langid: $sitelang->id)->contain(['Origins']));
            $isPaginated = true;
            $count = 0;
        }

        $this->set(compact('words', 'q', 'isPaginated', 'count', 'originSummary'));
        $this->render('results');
	}
}