<?php
declare(strict_types=1);

namespace App\Controller;


class SearchController extends AppController {
	public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

	public function index()
    {
        array_map([$this, 'loadModel'], ['Words']);
        $sitelang = $this->languageinfo();
        $q = $this->request->getQuery('q');

        $this->paginate = [
            'contain' => [
                'Definitions',
                'Origins',
                'Regions',
                'Types',
                'Dictionaries'
            ]];
		$words = $this->Paginator->paginate($this->Words->search_results($q, $sitelang->id));

        $this->set(compact('words', 'q'));
        $this->render('results');
	}
}