<?php
namespace App\Controller;

class OriginsWordsController extends AppController
{
    
    
    public function index(int $wordId)
    {
        $query = $this->OriginsWords->find()
            ->contain(['Origins'])
            ->where(['OriginsWords.word_id' => $wordId])
            ->orderBy(['OriginsWords.id' => 'ASC']);

        $originLinks = $this->paginate($query);

        $this->set(compact('originLinks', 'wordId'));

    }

    public function delete(int $id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $link = $this->OriginsWords->get($id);
        $wordId = $link->word_id;

        if ($this->OriginsWords->delete($link)) {
            $this->Flash->success('Origin link deleted.');
        } else {
            $this->Flash->error('Could not delete origin link.');
        }

        return $this->redirect(['action' => 'indexByWord', $wordId]);
    }

    public function addByWord(int $wordId)
    {
        $link = $this->OriginsWords->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $otherEntry = (string)($data['origins_other_entry'] ?? $data['origin_other_entry'] ?? '');

            if (trim($otherEntry) !== '') {
                $languageId = (int)$this->request->getAttribute('sitelang')->id;
                $rawOthers = array_filter(array_map('trim', explode(';', $otherEntry)));
                $others = [];
                $seen = [];
                foreach ($rawOthers as $v) {
                    $normalized = mb_strtolower($v);
                    if ($normalized === '' || isset($seen[$normalized])) {
                        continue;
                    }
                    $seen[$normalized] = true;
                    $others[] = $v;
                }

                $resolvedIds = [];
                $originsTable = $this->OriginsWords->Origins;
                $originsLanguages = $this->fetchTable('OriginsLanguages');

                foreach ($others as $value) {
                    $originId = method_exists($originsTable, 'getIdIfExists') ? $originsTable->getIdIfExists($value) : null;
                    if ($originId === 999) {
                        $originId = null;
                    }

                    if ($originId === null) {
                        $originEntity = $originsTable->newEmptyEntity();
                        $originEntity = $originsTable->patchEntity($originEntity, ['origin' => $value]);
                        $saved = $originsTable->save($originEntity);
                        $originId = $saved ? (int)$saved->id : null;
                    }

                    if ($originId === null) {
                        continue;
                    }

                    $resolvedIds[] = (int)$originId;

                    // Ensure it appears for this language in future dropdowns.
                    if ($languageId > 0) {
                        $exists = $originsLanguages->find()
                            ->where(['origin_id' => $originId, 'language_id' => $languageId])
                            ->count();
                        if ($exists === 0) {
                            $langLink = $originsLanguages->newEmptyEntity();
                            $langLink = $originsLanguages->patchEntity($langLink, [
                                'origin_id' => $originId,
                                'language_id' => $languageId,
                                'top' => 0,
                            ]);
                            $originsLanguages->save($langLink);
                        }
                    }

                    // Link origin to word if not already linked.
                    $alreadyLinked = $this->OriginsWords->find()
                        ->where(['word_id' => $wordId, 'origin_id' => $originId])
                        ->count();
                    if ($alreadyLinked === 0) {
                        $join = $this->OriginsWords->newEmptyEntity();
                        $join = $this->OriginsWords->patchEntity($join, [
                            'word_id' => $wordId,
                            'origin_id' => $originId,
                        ]);
                        $this->OriginsWords->save($join);
                    }
                }

                // If we successfully linked real origins, remove any legacy sentinel (999) link.
                if (!empty($resolvedIds)) {
                    $sentinelLinks = $this->OriginsWords->find()
                        ->where(['word_id' => $wordId, 'origin_id' => 999])
                        ->all();
                    foreach ($sentinelLinks as $sentinel) {
                        $this->OriginsWords->delete($sentinel);
                    }

                    $this->Flash->success('Origin link(s) added.');
                    return $this->redirect(['action' => 'index', $wordId]);
                }

                $this->Flash->error('Could not add origin link(s).');
            } else {
                $link = $this->OriginsWords->patchEntity($link, $data);
                $link->word_id = $wordId;

                // Prevent persisting the sentinel (999) without a typed value.
                if ((int)($link->origin_id ?? 0) === 999) {
                    $this->Flash->error('Please enter an origin when choosing Other.');
                } elseif ($this->OriginsWords->save($link)) {
                    $this->Flash->success('Origin link added.');
                    return $this->redirect(['action' => 'index', $wordId]);
                } else {
                    $this->Flash->error('Could not add origin link.');
                }
            }
        }

        $languageId = (int)$this->request->getAttribute('sitelang')->id;

        $origins = $this->OriginsWords->Origins
            ->find('list', valueField: 'origin')
            ->matching('Languages', function (\Cake\ORM\Query\SelectQuery $q) use ($languageId) {
                return $q->where(['OriginsLanguages.language_id' => $languageId]);
            })
            ->distinct(['Origins.id'])
            ->orderBy(['Origins.origin' => 'ASC'])
            ->toArray();

        $origins[999] = __('Other');

        $this->set(compact('link', 'wordId', 'origins'));
    }
}