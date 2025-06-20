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
        //array_map([$this, 'loadModel'], ['Words']);
        $sitelang = $this->languageinfo();
        $q = trim($this->request->getQuery('q'));
        $displayType = $this->request->getQuery('displayType');

        if ($displayType === 'all') {
            $words = $this->fetchTable('Words')->find('searchResults', querystring: $q, langid: $sitelang->id);
            $isPaginated = false;
            $words2 = $words->toArray();
            $count = count($words2);
        } else {
		    $words = $this->paginate($this->fetchTable('Words')->find('searchResults', querystring: $q, langid: $sitelang->id));
            $isPaginated = true;
            $count = 0;
        }

        $this->set(compact('words', 'q', 'isPaginated', 'count'));
        $this->render('results');
	}
}