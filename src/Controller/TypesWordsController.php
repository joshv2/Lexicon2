<?php

namespace App\Controller;

class TypesWordsController extends AppController
{
    public function indexByWord(int $wordId)
    {
        $query = $this->TypesWords->find()
            ->contain(['Types'])
            ->where(['TypesWords.word_id' => $wordId])
            ->orderBy(['TypesWords.id' => 'ASC']);

        $typeLinks = $this->paginate($query);

        $this->set(compact('typeLinks', 'wordId'));
    }

    public function addByWord(int $wordId)
    {
        $link = $this->TypesWords->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $otherEntry = (string)($data['types_other_entry'] ?? $data['type_other_entry'] ?? '');

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
                $typesTable = $this->TypesWords->Types;
                $typesLanguages = $this->fetchTable('TypesLanguages');

                foreach ($others as $value) {
                    $typeId = method_exists($typesTable, 'getIdIfExists') ? $typesTable->getIdIfExists($value) : null;
                    if ($typeId === 999) {
                        $typeId = null;
                    }

                    if ($typeId === null) {
                        $typeEntity = $typesTable->newEmptyEntity();
                        $typeEntity = $typesTable->patchEntity($typeEntity, ['type' => $value]);
                        $saved = $typesTable->save($typeEntity);
                        $typeId = $saved ? (int)$saved->id : null;
                    }

                    if ($typeId === null) {
                        continue;
                    }

                    $resolvedIds[] = (int)$typeId;

                    if ($languageId > 0) {
                        $exists = $typesLanguages->find()
                            ->where(['type_id' => $typeId, 'language_id' => $languageId])
                            ->count();
                        if ($exists === 0) {
                            $langLink = $typesLanguages->newEmptyEntity();
                            $langLink = $typesLanguages->patchEntity($langLink, [
                                'type_id' => $typeId,
                                'language_id' => $languageId,
                                'top' => 0,
                                'type_category_id' => 0,
                            ]);
                            $typesLanguages->save($langLink);
                        }
                    }

                    $alreadyLinked = $this->TypesWords->find()
                        ->where(['word_id' => $wordId, 'type_id' => $typeId])
                        ->count();
                    if ($alreadyLinked === 0) {
                        $join = $this->TypesWords->newEmptyEntity();
                        $join = $this->TypesWords->patchEntity($join, [
                            'word_id' => $wordId,
                            'type_id' => $typeId,
                        ]);
                        $this->TypesWords->save($join);
                    }
                }

                if (!empty($resolvedIds)) {
                    $sentinelLinks = $this->TypesWords->find()
                        ->where(['word_id' => $wordId, 'type_id' => 999])
                        ->all();
                    foreach ($sentinelLinks as $sentinel) {
                        $this->TypesWords->delete($sentinel);
                    }

                    $this->Flash->success('Type link(s) added.');
                    return $this->redirect(['action' => 'indexByWord', $wordId]);
                }

                $this->Flash->error('Could not add type link(s).');
            } else {
                $link = $this->TypesWords->patchEntity($link, $data);
                $link->word_id = $wordId;

                if ((int)($link->type_id ?? 0) === 999) {
                    $this->Flash->error('Please enter a type when choosing Other.');
                } elseif ($this->TypesWords->save($link)) {
                    $this->Flash->success('Type link added.');
                    return $this->redirect(['action' => 'indexByWord', $wordId]);
                } else {
                    $this->Flash->error('Could not add type link.');
                }
            }
        }

        $languageId = (int)$this->request->getAttribute('sitelang')->id;

        $types = $this->TypesWords->Types
            ->find('list', valueField: 'type')
            ->matching('Languages', function (\Cake\ORM\Query\SelectQuery $q) use ($languageId) {
                return $q->where(['TypesLanguages.language_id' => $languageId]);
            })
            ->distinct(['Types.id'])
            ->orderBy(['Types.type' => 'ASC'])
            ->toArray();

        $types[999] = __('Other');

        $this->set(compact('link', 'wordId', 'types'));
    }

    public function delete(int $id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $link = $this->TypesWords->get($id);
        $wordId = $link->word_id;

        if ($this->TypesWords->delete($link)) {
            $this->Flash->success('Type link deleted.');
        } else {
            $this->Flash->error('Could not delete type link.');
        }

        return $this->redirect(['action' => 'indexByWord', $wordId]);
    }
}