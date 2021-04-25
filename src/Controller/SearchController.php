<?php

App::uses('AppController', 'Controller');

class SearchController extends AppController {

	public $helpers = array('Paginator');

	public function index()
	{
		$this->loadModel('Word');
		$q = !empty($this->params->query['q']) ? $this->params->query['q'] : '';
		
		$this->set('q', $q);
		$regexp  = "#[\p{Hebrew} a-zA-Z]$#u";
		if (preg_match($regexp, $q))
		{
			$this->set('words', $this->paginate());
			$this->render('results');
		}
		else
		{
			$this->redirect('/');
		}
	}

	public function paginate()
	{
		$limit = 10;
		$count = $this->paginateCount();
		$pageCount = intval(ceil($count / $limit));

		if (!empty($_GET['page']) && ctype_digit($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $pageCount)
		{
			$page = $_GET['page'];
		}
		else
		{
			$page = 1;
		}

		$current = ($page - 1) * $limit;

		$paging = array(
			'page' => $page,
			'current' => $current,
			'count' => $count,
			'prevPage' => ($page > 1),
			'nextPage' => ($count > ($page * $limit)),
			'pageCount' => $pageCount,
			'order' => null,
			'limit' => $limit,
			'options' => array(),
			'paramType' => 'queryString',
		);

		$this->request->params['paging']['page'] = $paging;

		if ($count < 1)
		{
			return array();
		}

		$sql = <<<SQL
SELECT words.id, words.spelling,
GROUP_CONCAT(alternates.spelling SEPARATOR ', ') AS alternates,
GROUP_CONCAT(DISTINCT definitions.id) AS definitions,
CASE
WHEN words.spelling LIKE :query THEN 2
WHEN alternates.spelling LIKE :query THEN 2
WHEN words.spelling LIKE :lquery THEN 1
WHEN alternates.spelling LIKE :lquery THEN 1
ELSE 0
END as spellingmatch,
MATCH(words.notes) AGAINST (:query) AS notesmatch,
MATCH(definitions.definition) AGAINST (:query) AS definitionmatch
FROM words
LEFT JOIN definitions ON definitions.word_id = words.id
LEFT JOIN alternates ON alternates.word_id = words.id
WHERE words.spelling LIKE :lquery
OR alternates.spelling LIKE :lquery
OR MATCH(words.notes) AGAINST (:query)
OR MATCH(definitions.definition) AGAINST (:query)
OR words.etymology LIKE :lquery
GROUP BY words.id
ORDER BY spellingmatch DESC, definitionmatch DESC, notesmatch DESC, spelling ASC
LIMIT $current, $limit


SQL;
		$db = $this->Word->getDataSource();
		$words = $db->fetchAll($sql, array(':query' => $_GET['q'], ':lquery' => '%'.$_GET['q'].'%', ':lim' => $limit));

		$in = '';
		foreach ($words as $word){
			
				$in .= $word['words']['id'].',';
}
		$def = $this->Word->Definition->find('list', array('conditions' => 'word_id IN('.substr($in, 0, -1).')'));

		foreach ($words as &$word)
		{
			$word['alternates'] = $word[0]['alternates'];
			$word['definitions'] = array();
			$def_ids = explode(',', $word[0]['definitions']);
			unset($word[0]);
			foreach ($def_ids as $id)
			{
				$word['definitions'][] = $def[$id];
			}
		}

		return $words;
	}

	public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
		$sql = <<<SQL
SELECT COUNT(DISTINCT words.id) as count
FROM words
LEFT JOIN definitions ON definitions.word_id = words.id
LEFT JOIN alternates ON alternates.word_id = words.id
WHERE words.spelling LIKE :lquery
OR alternates.spelling LIKE :lquery
OR MATCH(words.notes) AGAINST (:query)
OR MATCH(definitions.definition) AGAINST (:query)
OR words.etymology LIKE :lquery
SQL;
		$db = $this->Word->getDataSource();
		$words = $db->fetchAll($sql, array(':query' => $_GET['q'], ':lquery' => '%'.$_GET['q'].'%'));
		return $words[0][0]['count'];
	}
}
