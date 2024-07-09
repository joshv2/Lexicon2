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
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $q = $this->request->getQuery('q');


		$words = $this->paginate($this->fetchTable('Words')->find('searchResults', querystring: $q, langid: $sitelang->id));

        $this->set(compact('words', 'q'));
        $this->render('results');
	}
}