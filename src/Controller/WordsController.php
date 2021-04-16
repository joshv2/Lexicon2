<?php

namespace App\Controller;

class WordsController extends AppController {

	public function browse()
	{
			
		//$this->Word->recursive = 1;
		$this->Paginator->settings = $this->paginate;
		#$this->helpers[] = 'Paginator';
		$this->helpers[] = 'QueryString';

		/* Build filters */

		$filters = array('dictionary' => 'dictionaries', 'origin' => 'origins', 'region' => 'regions', 'use' => 'types');
		$filter_conditions = array();

		foreach ($filters as $k => $v)
		{
			if (!empty($_GET[$k]) && (ctype_digit($_GET[$k]) || $_GET[$k] == 'other'))
				$filter_conditions[$k] = $_GET[$k];
		}

		$dictionaries = $this->Word->Dictionary->showList();
		$origins = $this->Word->Origin->showList();
		$types = $this->Word->Type->showList();
		$regions = $this->Word->Region->showList();

		// lazy coding: check database
		$max['use'] = 12;
		$max['origin'] = 7;
		$max['dictionary'] = 6;
		$max['region'] = 4;

		foreach ($filter_conditions as $k => $v)
		{
			if ($v == 'other')
			{
				if (!isset($conditions)) $conditions = "WHERE ";
				else $conditions .= " AND ";
				$conditions .= "{$filters[$k]}.id > {$max[$k]}";
				$current_condition[$k] = 'Other';
			}
			else
			{
				if (!isset($conditions)) $conditions = "WHERE ";
				else $conditions .= " AND ";
				$conditions .= "{$filters[$k]}.id = $v\n";
				$int = intval($v);
				$current_condition[$k] = ${$filters[$k]}[$int];
			}
			// debug(${$filters[$k]});
		}

		

		$none_dictionary = false;
		if (isset($_GET['dictionary']) && $_GET['dictionary'] == 'none')
		{
			$none_dictionary = true;
			$current_condition['dictionary'] = 'None';
		}

		if (!isset($conditions)) $conditions = '';
		
		$this->set(compact('dictionaries', 'origins', 'types', 'current_condition', 'regions'));
		$this->set('title_for_layout', '');
		$this->set('words', $this->paginate($conditions, $none_dictionary));
		
	}

	
	public function alphabetical(){
		if(empty($this->request->getParam('letter')))
			$letter = 'a';
		else
			$letter = $this->request->getParam('letter');
	}
	
	public function alphabeticalold()
	{

		if (empty($this->request->params['letter']))
			$letter = 'a';
		else 
			$letter = $this->request->params['letter'];

		$words = $this->Word->find('all', array(
			'conditions' => "spelling LIKE '$letter%'",
			'order' => 'spelling')
		);

		foreach ($words as $k => &$word)
		{
			if (isset($word['Dictionary']))
			{
				$words[$k]['dictionaries'] = '';
				foreach ($word['Dictionary'] as $d)
				{
					$words[$k]['dictionaries'] .= ', '.$d['dictionary'];
				}
				$word['dictionaries'] = substr($word['dictionaries'], 2);
				unset($words[$k]['Dictionary']);
			}
			if (isset($word['Origin']))
			{
				$words[$k]['origins'] = '';
				foreach ($word['Origin'] as $o)
				{
					$words[$k]['origins'] .= ', '.$o['origin'];
				}
				$word['origins'] = substr($word['origins'], 2);
				unset($words[$k]['Origin']);
			}
			if (isset($word['Type']))
			{
				$words[$k]['uses'] = '';
				foreach ($word['Type'] as $t)
				{
					$words[$k]['uses'] .= ', '.$t['type'];
				}
				$word['uses'] = substr($word['uses'], 2);
				unset($words[$k]['Type']);
			}
		}

		$this->set('letter', strtoupper($letter));
		$this->set(compact('words'));
	}

	public function view()
	{
		$this->Word->recursive = 1;
		
		$id = $this->request->params['id'];
		
		$this->Word->id = $id;
		
		if (!$this->Word->exists())
		{
			throw new NotFoundException();
		}
		//$this->set('loggedIn', $this->Auth->user());
		$word = $this->Word->read(null, $id);

		//debug($word); exit;

		$origins = array();
		foreach ($word['Origin'] as $o)
			$origins[] = $o['origin'];
		$origins = implode(', ', $origins);
		
		$regions = array();
		foreach ($word['Region'] as $o)
			$regions[] = $o['region'];
		$regions = implode(', ', $regions);

		$alternates = array();
		foreach ($word['Alternate'] as $a)
			$alternates[] = $a['spelling'];
		$alternates = implode(', ', $alternates);

		$dictionaries = array();
		foreach ($word['Dictionary'] as $d)
			$dictionaries[] = $d['dictionary'];
		$dictionaries = implode('<br /> ', $dictionaries);
		
		//echo "<pre>"; print_r($dictionaries); exit;
		
		$uses = array();
		foreach ($word['Type'] as $d)
			$uses[] = $d['type'];
		$uses = implode('<br /> ', $uses);

		$this->helpers[] = 'Back';

		$this->set(compact('word', 'origins', 'alternates', 'dictionaries', 'uses', 'regions'));
		$this->set('title_for_layout', $word['Word']['spelling']);
	}

	public function paginate($conditions = '', $none_dictionary)
	{

		$not = '';
		if ($none_dictionary)
		{
			$not = 'AND words.id NOT IN(SELECT word_id FROM `words_dictionaries` WHERE 1 GROUP BY word_id)';
		}

		$limit = 1000;
		$count = $this->paginateCount($conditions, $none_dictionary);

		$pageCount = intval(ceil($count / $limit));

		if (!empty($_GET['page']) && ctype_digit($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $pageCount)
		{
			$page = $_GET['page'];
		}
		else
		{
			$page = 1;
		}

		$current = $page * $limit;
		$limit_current = $current - $limit;

		$paging = array(
			'page' => $page,
			'current' => 0,
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
GROUP_CONCAT(DISTINCT definitions.id) as definitions,
GROUP_CONCAT(DISTINCT dictionaries.dictionary ORDER BY dictionaries.dictionary ASC SEPARATOR ', ') as dictionaries, 
GROUP_CONCAT(DISTINCT origins.origin ORDER BY origins.origin ASC SEPARATOR ', ') as origins, 
GROUP_CONCAT(DISTINCT types.type ORDER BY types.type ASC SEPARATOR ', ') as types,
GROUP_CONCAT(DISTINCT regions.region ORDER BY regions.region ASC SEPARATOR ', ') as regions
FROM words
LEFT JOIN `definitions` ON `definitions`.`word_id` = `words`.`id`
LEFT JOIN `words_dictionaries` ON `words_dictionaries`.`word_id` = `words`.`id`
LEFT JOIN `dictionaries` ON `dictionaries`.`id` = `words_dictionaries`.`dictionary_id`
LEFT JOIN `words_origins` ON `words_origins`.`word_id` = `words`.`id`
LEFT JOIN `origins` ON `origins`.`id` = `words_origins`.`origin_id`
LEFT JOIN `words_types` ON `words_types`.`word_id` = `words`.`id`
LEFT JOIN `types` ON `types`.`id` = `words_types`.`type_id`
LEFT JOIN `words_regions` ON `words_regions`.`word_id` = `words`.`id`
LEFT JOIN `regions` ON `regions`.`id` = `words_regions`.`region_id`
WHERE words.id IN(
	SELECT words.id
	FROM words
	LEFT JOIN `words_dictionaries` ON `words_dictionaries`.`word_id` = `words`.`id`
	LEFT JOIN `dictionaries` ON `dictionaries`.`id` = `words_dictionaries`.`dictionary_id`
	LEFT JOIN `words_origins` ON `words_origins`.`word_id` = `words`.`id`
	LEFT JOIN `origins` ON `origins`.`id` = `words_origins`.`origin_id`
	LEFT JOIN `words_types` ON `words_types`.`word_id` = `words`.`id`
	LEFT JOIN `types` ON `types`.`id` = `words_types`.`type_id`
	LEFT JOIN `words_regions` ON `words_regions`.`word_id` = `words`.`id`
	LEFT JOIN `regions` ON `regions`.`id` = `words_regions`.`region_id`
	$conditions) $not
GROUP BY words.id 	
ORDER BY words.spelling
LIMIT $limit_current, $limit
SQL;

		/*$sql = <<<SQL
SELECT words.id, words.spelling,
GROUP_CONCAT(DISTINCT definitions.id) as definitions,
GROUP_CONCAT(DISTINCT dictionaries.dictionary ORDER BY dictionaries.dictionary ASC SEPARATOR ', ') as dictionaries, 
GROUP_CONCAT(DISTINCT origins.origin ORDER BY origins.origin ASC SEPARATOR ', ') as origins, 
GROUP_CONCAT(DISTINCT regions.region ORDER BY regions.region ASC SEPARATOR ', ') as regions,
GROUP_CONCAT(DISTINCT types.type ORDER BY types.type ASC SEPARATOR ', ') as types
FROM words
LEFT JOIN `definitions` ON `definitions`.`word_id` = `words`.`id`
LEFT JOIN `words_dictionaries` ON `words_dictionaries`.`word_id` = `words`.`id`
LEFT JOIN `dictionaries` ON `dictionaries`.`id` = `words_dictionaries`.`dictionary_id`
LEFT JOIN `words_origins` ON `words_origins`.`word_id` = `words`.`id`
LEFT JOIN `origins` ON `origins`.`id` = `words_origins`.`origin_id`
LEFT JOIN `regions` ON `regions`.`id` = `words_regions`.`region_id`
LEFT JOIN `words_types` ON `words_types`.`word_id` = `words`.`id`
LEFT JOIN `types` ON `types`.`id` = `words_types`.`type_id`
WHERE words.id IN(
	SELECT words.id
	FROM words
	LEFT JOIN `words_dictionaries` ON `words_dictionaries`.`word_id` = `words`.`id`
	LEFT JOIN `dictionaries` ON `dictionaries`.`id` = `words_dictionaries`.`dictionary_id`
	LEFT JOIN `words_origins` ON `words_origins`.`word_id` = `words`.`id`
	LEFT JOIN `origins` ON `origins`.`id` = `words_origins`.`origin_id`
	LEFT JOIN `regions` ON `regions`.`id` = `words_regions`.`region_id`
	LEFT JOIN `words_types` ON `words_types`.`word_id` = `words`.`id`
	LEFT JOIN `types` ON `types`.`id` = `words_types`.`type_id`
	$conditions) $not
GROUP BY words.id 	
ORDER BY words.spelling
LIMIT $limit_current, $limit
SQL;*/

		$db = $this->Word->getDataSource();
		$results = $db->fetchAll($sql);


		$in = '';
		$i = 0;
		foreach ($results as &$word)
		{
			$in .= $word['words']['id'].',';
			++$i;
		}

		$this->request->params['paging']['page']['current'] = $i;
		
		$def = $this->Word->Definition->find('list', array('conditions' => 'word_id IN('.substr($in, 0, -1).')'));

		foreach ($results as &$word)
		{
			$word['dictionaries'] = $word[0]['dictionaries'];
			$word['origins'] = $word[0]['origins'];
			$word['regions'] = $word[0]['regions'];
			$word['uses'] = $word[0]['types'];
			$word['definitions'] = array();
			$def_ids = explode(',', $word[0]['definitions']);
			unset($word[0]);
			foreach ($def_ids as $id)
			{
				$word['definitions'][] = $def[$id];
			}
		}
		unset($def, $in, $sql, $db, $filters, $filters_conditions);

		return $results;
	}

	public function paginateCount($conditions, $none_dictionary)
	{

		$db = $this->Word->getDataSource();

		$not = '';
		if ($none_dictionary)
		{
			if ($conditions == '')
				$not .= 'WHERE ';
			else
				$not .= 'AND ';

			$not .= 'words.id NOT IN(SELECT word_id FROM `words_dictionaries` WHERE 1 GROUP BY word_id)';
		}

		$sql = <<<SQL
SELECT COUNT(DISTINCT words.id) as count
FROM words
LEFT JOIN `words_dictionaries` ON `words_dictionaries`.`word_id` = `words`.`id`
LEFT JOIN `dictionaries` ON `dictionaries`.`id` = `words_dictionaries`.`dictionary_id`
LEFT JOIN `words_origins` ON `words_origins`.`word_id` = `words`.`id`
LEFT JOIN `origins` ON `origins`.`id` = `words_origins`.`origin_id`
LEFT JOIN `words_regions` ON `words_regions`.`word_id` = `words`.`id`
LEFT JOIN `regions` ON `regions`.`id` = `words_regions`.`region_id`
LEFT JOIN `words_types` ON `words_types`.`word_id` = `words`.`id`
LEFT JOIN `types` ON `types`.`id` = `words_types`.`type_id`
$conditions $not
SQL;
		$words = $db->fetchAll($sql);
		return $words[0][0]['count'];
	}


	/*

	/moderators/words/:id/edit 
	/moderators/words/add

	*/

	public function moderators_add()
	{
		/* Editing an existing word */
		$existing = false;

		if (!empty($this->params->id))
		{
			$word_id = intval($this->params->id);
			
			$this->Word->id = $word_id;

			if (!$this->Word->exists())
			{
				throw new NotFoundException();
			}

			$existing = true;
		}

		/* same as in editscontroller moderators_edit */
		if ($this->request->is('post'))
		{
			$data = $this->request->data;

			$errors = array();

			# Remove empty fields
			foreach ($data['Alternate'] as $k => $v)
				if (empty($data['Alternate'][$k]['spelling']))
					unset($data['Alternate'][$k]);
			foreach ($data['Definition'] as $k => $v)
				if (empty($data['Definition'][$k]['definition']))
					unset($data['Definition'][$k]);
			foreach ($data['Sentence'] as $k => $v)
				if (empty($data['Sentence'][$k]['sentence']))
					unset($data['Sentence'][$k]);


			# Spelling (validated by Word model)
			$word_save['Word'] = $data['Word'];
			
			# Alternates (alphabetical check)
			if (empty($data['Alternate']))
			{
				# Remove empty array
				unset($data['Alternate']);
			}
			else
			{
				if(!$this->Word->Alternate->saveAll($data['Alternate'], array('validate' => 'only')))
					$errors['Alternate'] = $this->Word->Alternate->validationErrors; // is an array for each alternate

				$word_save['Alternate'] = $data['Alternate'];
			}

			# Definition (at least one required)
			if (empty($data['Definition']))
			{
				# Remove empty array
				unset($data['Definition']);
				$errors['definition'] = 'Please enter a definition.';
			}
			else
			{
				$word_save['Definition'] = $data['Definition'];
			}

			# Sentence (no validation)
			if (empty($data['Sentence']))
			{
				# Remove empty array
				unset($data['Sentence']);
			}
			else
			{
				$word_save['Sentence'] = $data['Sentence'];
			}


			# Deal with tags

			# 1. Origins

			$originsInDb = $this->Word->Origin->find('list', array('fields' => array('origin', 'id')));
			
			$allOrigins = array_unique(array_merge(
				isset($data['Origin'])? $data['Origin'] : array(), 
				isset($data['NewOrigin'])? $data['NewOrigin'] : array()
			));

			$originSave = array();
			$i = 0;
			foreach ($allOrigins as $k => $o)
			{
				if (trim($o) == '')
				{
					unset($allOrigins[$k]);
					continue;
				}

				if (isset($originsInDb[$o]))
					$originSave[$i]['Origin']['id'] = $originsInDb[$o];
				else
					$originSave[$i]['Origin']['origin'] = $o;

				++$i;
			}
			unset($originsInDb);


			# 2. Uses / types

			$typesInDb = $this->Word->Type->find('list', array('fields' => array('type', 'id')));
			
			$allTypes = array_unique(array_merge(
				isset($data['Type'])? $data['Type'] : array(), 
				isset($data['NewType'])? $data['NewType'] : array()
			));

			$typeSave = array();
			$i = 0;
			foreach ($allTypes as $k => $o)
			{
				if (trim($o) == '')
				{
					unset($allTypes[$k]);
					continue;
				}

				if (isset($typesInDb[$o]))
					$typeSave[$i]['Type']['id'] = $typesInDb[$o];
				else
					$typeSave[$i]['Type']['type'] = $o;

				++$i;
			}
			unset($typesInDb);


			# 3. Dictionaries

			$dictsInDb = $this->Word->Dictionary->find('list', array('fields' => array('dictionary', 'id')));
			
			$allDicts = array_unique(array_merge(
				isset($data['Dictionary'])? $data['Dictionary'] : array(), 
				isset($data['NewDictionary'])? $data['NewDictionary'] : array()
			));

			$dictSave = array();
			$i = 0;
			foreach ($allDicts as $k => $o)
			{
				if (trim($o) == '')
				{
					unset($allDicts[$k]);
					continue;
				}

				if (isset($dictsInDb[$o]))
					$dictSave[$i]['Dictionary']['id'] = $dictsInDb[$o];
				else
					$dictSave[$i]['Dictionary']['dictionary'] = $o;

				++$i;
			}
			unset($dictsInDb);

			# 4. Regions

			$regionsInDb = $this->Word->Region->find('list', array('fields' => array('region', 'id')));
			
			$allRegions = array_unique(array_merge(
				isset($data['Region'])? $data['Region'] : array(), 
				isset($data['NewRegion'])? $data['NewRegion'] : array()
			));

			$regionSave = array();
			$i = 0;
			foreach ($allRegions as $k => $o)
			{
				if (trim($o) == '')
				{
					unset($allRegions[$k]);
					continue;
				}

				if (isset($regionsInDb[$o]))
					$regionSave[$i]['Region']['id'] = $regionsInDb[$o];
				else
					$regionSave[$i]['Region']['region'] = $o;

				++$i;
			}
			unset($regionsInDb);
			
			/* --------------------- */

			# Other validations:

			# Spelling - validated by Word model
			$validate1 = $this->Word->saveAll($word_save, array('validate' => 'only'));

			# Tags - validated by each corresponding model
			
			$validate2 = true;
			if(!empty($originSave)) {
			
				foreach($originSave as $originSaveKey => $originSaveEntry) {
					
					$this->Word->Origin->create();

					if(empty($originSave[$originSaveKey]['Origin']['id'])) {
						if($this->Word->Origin->save($originSaveEntry)) {
							$originSave[$originSaveKey]['Origin']['id'] = $this->Word->Origin->getLastInsertID();
						}
						else {
							$validate2 = false;
						}
						
					}
					
				}
				
			}


			$validate3 = true;
			if(!empty($typeSave)) {
			
				foreach($typeSave as $typeSaveKey => $typeSaveEntry) {
					
					$this->Word->Type->create();

					if(empty($typeSave[$typeSaveKey]['Type']['id'])) {
						if($this->Word->Type->save($typeSaveEntry)) {
							$typeSave[$typeSaveKey]['Type']['id'] = $this->Word->Type->getLastInsertID();
						}
						else {
							$validate3 = false;
						}
						
					}
					
				}
				
			}
			
			$validate4 = true;
			if(!empty($dictSave)) {
			
				foreach($dictSave as $dictSaveKey => $dictSaveEntry) {
					
					$this->Word->Dictionary->create();

					if(empty($dictSave[$dictSaveKey]['Dictionary']['id'])) {
						if($this->Word->Dictionary->save($dictSaveEntry)) {
							$dictSave[$dictSaveKey]['Dictionary']['id'] = $this->Word->Dictionary->getLastInsertID();
						}
						else {
							$validate4 = false;
						}
						
					}
					
				}
				
			}
			
			$validate5 = true;
			if(!empty($regionSave)) {
			
				foreach($regionSave as $regionSaveKey => $regionSaveEntry) {
					
					$this->Word->Region->create();

					if(empty($regionSave[$regionSaveKey]['Region']['id'])) {
						if($this->Word->Region->save($regionSaveEntry)) {
							$regionSave[$regionSaveKey]['Region']['id'] = $this->Word->Region->getLastInsertID();
						}
						else {
							$validate5 = false;
						}
						
					}
					
				}
				
			}
			
			//$validate2 = empty($originSave)? true : $this->Word->Origin->saveAll($originSave); 
			//$validate3 = empty($typeSave)? true : $this->Word->Type->saveAll($typeSave);
			//$validate4 = empty($dictSave)? true : $this->Word->Dictionary->saveAll($dictSave);
			//$validate5 = empty($regionSave)? true : $this->Word->Region->saveAll($regionSave);
			
			
			if (!$errors && $validate1 && $validate2 && $validate3 && $validate4 && $validate5)
			{
				if ($existing)
				{
					$this->Word->id = $word_id;
					$word_save['Word']['id'] = $word_id;

					$this->Word->query("DELETE FROM alternates WHERE word_id = $word_id");
					$this->Word->query("DELETE FROM definitions WHERE word_id = $word_id");
					$this->Word->query("DELETE FROM sentences WHERE word_id = $word_id");
					$this->Word->query("DELETE FROM words_origins WHERE word_id = $word_id");
					$this->Word->query("DELETE FROM words_types WHERE word_id = $word_id");
					$this->Word->query("DELETE FROM words_dictionaries WHERE word_id = $word_id");
					$this->Word->query("DELETE FROM words_regions WHERE word_id = $word_id");
				}
				else
					$this->Word->create();
				
				$this->Word->saveAll($word_save);

				if (!$existing)
					$word_id = $this->Word->getLastInsertID();

				foreach ($originSave as &$o)
					$o['Word']['id'] = $word_id;
				foreach ($typeSave as &$o)
					$o['Word']['id'] = $word_id;
				foreach ($dictSave as &$o)
					$o['Word']['id'] = $word_id;
				foreach ($regionSave as &$o)
					$o['Word']['id'] = $word_id;
				
				if(!empty($originSave)) $this->Word->saveAll($originSave);
				if(!empty($typeSave)) $this->Word->saveAll($typeSave);
				if(!empty($dictSave)) $this->Word->saveAll($dictSave);
				if(!empty($regionSave)) $this->Word->saveAll($regionSave);
				
				
				
				$this->Session->setFlash('The word has been saved.');
				$this->redirect('/words/'.$word_id);
			}
			else
			{
				$this->Session->setFlash('The word could not be saved. Please correct the errors and try again.');
				$errors = array_merge($errors, $this->Word->validationErrors);
				if(!empty($this->Word->Origin->validationErrors)) $errors['Origin'] = $this->Word->Origin->validationErrors;
				if(!empty($this->Word->Type->validationErrors)) $errors['Type'] = $this->Word->Type->validationErrors;
				if(!empty($this->Word->Dictionary->validationErrors)) $errors['Dictionary'] = $this->Word->Dictionary->validationErrors;
				if(!empty($this->Word->Region->validationErrors)) $errors['Region'] = $this->Word->Region->validationErrors;
				$this->set('errors', $errors);
				debug($errors);
			}
		}

		$regions = $this->Word->Region->showList();
		$dictionaries = $this->Word->Dictionary->showList();
		$origins = $this->Word->Origin->showList();
		$uses = $this->Word->Type->showList();

		# Populate fields for edit
		if ($existing && !$this->request->is('post'))
		{
			$results = $this->Word->findById($this->params->id);
			$data['Word'] = $results['Word'];
			$data['Alternate'] = $results['Alternate'];
			$data['Definition'] = $results['Definition'];
			$data['Sentence'] = $results['Sentence'];

			foreach ($results['Type'] as $id => &$use)
				if (!in_array($use['type'], $uses))
					$data['NewType'][] = $use['type'];
				else
					$data['Type'][] = $use['type'];

			foreach ($results['Origin'] as $id => &$origin)
				if (!in_array($origin['origin'], $origins))
					$data['NewOrigin'][] = $origin['origin'];
				else
					$data['Origin'][] = $origin['origin'];

			foreach ($results['Region'] as $id => &$region)
				if (!in_array($region['region'], $regions))
					$data['NewRegion'][] = $region['region'];
				else
					$data['Region'][] = $region['region'];

			foreach ($results['Dictionary'] as $id => &$dict)
				if (!in_array($dict['dictionary'], $dictionaries))
					$data['NewDictionary'][] = $dict['dictionary'];
				else
					$data['Dictionary'][] = $dict['dictionary'];
		}
		$this->layout = 'moderators';
		$this->set(compact('data', 'uses', 'dictionaries', 'origins', 'regions'));

		if ($existing)
		{
			$this->set('existing', $this->Word->findById($this->params->id, array('id', 'spelling')));
			$this->set('submit_text', 'Make Changes');
			$this->render('moderators_edit');
		}
		else
			$this->set('submit_text', 'Add to Database');
	}

	public function moderators_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Word->id = $id;
		if (!$this->Word->exists()) {
			throw new NotFoundException('Invalid word');
		}
		if ($this->Word->delete($id, false)) {
			$this->Word->query("DELETE FROM alternates WHERE word_id = $id");
			$this->Word->query("DELETE FROM definitions WHERE word_id = $id");
			$this->Word->query("DELETE FROM sentences WHERE word_id = $id");
			$this->Word->query("DELETE FROM words_origins WHERE word_id = $id");
			$this->Word->query("DELETE FROM words_types WHERE word_id = $id");
			$this->Word->query("DELETE FROM words_dictionaries WHERE word_id = $id");
			$this->Word->query("DELETE FROM words_regions WHERE word_id = $id");
			$this->Session->setFlash('Word deleted');
			$this->redirect('/words');
		}
		$this->Session->setFlash('Word was not deleted');
		$this->redirect('/words/'.$id);
	}

}
