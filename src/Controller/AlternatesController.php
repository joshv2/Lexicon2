<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Alternates Controller
 *
 * @property \App\Model\Table\AlternatesTable $Alternates
 * @method \App\Model\Entity\Alternate[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AlternatesController extends AppController
{
    protected bool $loggedin = false;
    protected bool $isSuperuser = false;

    public function initialize(): void
    {
        parent::initialize();
        $this->loggedin = (bool)$this->request->getSession()->read('Auth.username');
        $this->isSuperuser = ($this->request->getSession()->read('Auth.role') === 'superuser');
    }

    private function requireSuperuser()
    {
        if ($this->loggedin && $this->isSuperuser) {
            return null;
        }

        $this->Flash->error(__('You must be a superuser to do that.'));
        $defaultUrl = \Cake\Routing\Router::url(['prefix' => false, 'controller' => 'Words', 'action' => 'index']);
        return $this->redirect($this->referer($defaultUrl, true));
    }

    private function normalizeSpelling(string $value): string
    {
        return function_exists('mb_strtolower') ? mb_strtolower($value) : strtolower($value);
    }

    /**
     * Manage alternate spellings for a single word.
     *
     * Allows adding multiple spellings at once and provides edit/delete links.
     *
     * @param string|int|null $wordId Word id.
     * @return \Cake\Http\Response|null|void
     */
    public function manage($wordId = null)
    {
        if ($resp = $this->requireSuperuser()) {
            return $resp;
        }

        if (empty($wordId)) {
            $this->Flash->error(__('No word id provided.'));
            return $this->redirect(['controller' => 'Words', 'action' => 'index']);
        }

        $word = $this->fetchTable('Words')->get($wordId, [
            'fields' => ['Words.id', 'Words.spelling'],
        ]);

        if ($this->request->is('post')) {
            $raw = (string)($this->request->getData('alternate_spellings') ?? '');
            $raw = str_replace("\r\n", "\n", $raw);
            $raw = str_replace("\r", "\n", $raw);

            $pieces = preg_split('/\n+/', $raw) ?: [];
            $candidates = [];
            foreach ($pieces as $piece) {
                $piece = trim((string)$piece);
                if ($piece === '') {
                    continue;
                }
                // Allow comma-separated values on a line as a convenience.
                foreach (preg_split('/\s*,\s*/', $piece) as $sub) {
                    $sub = trim((string)$sub);
                    if ($sub !== '') {
                        $candidates[] = $sub;
                    }
                }
            }

            // Deduplicate case-insensitively within the submission.
            $seen = [];
            $uniqueCandidates = [];
            foreach ($candidates as $candidate) {
                $candidate = trim((string)$candidate);
                if ($candidate === '') {
                    continue;
                }
                $key = $this->normalizeSpelling($candidate);
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $uniqueCandidates[] = $candidate;
            }
            $candidates = $uniqueCandidates;

            if (count($candidates) === 0) {
                $this->Flash->error(__('No alternate spellings were submitted.'));
                return $this->redirect(['action' => 'manage', $word->id]);
            }

            $existing = $this->Alternates->find()
                ->select(['spelling'])
                ->where(['word_id' => $word->id])
                ->all();
            $existingSet = [];
            foreach ($existing as $row) {
                $existingSet[$this->normalizeSpelling((string)$row->spelling)] = true;
            }

            $added = 0;
            $skipped = 0;
            $failed = 0;

            foreach ($candidates as $spelling) {
                $spelling = trim($spelling);
                if ($spelling === '') {
                    $skipped += 1;
                    continue;
                }
                $spellingLen = function_exists('mb_strlen') ? mb_strlen($spelling) : strlen($spelling);
                if ($spellingLen > 255) {
                    $failed += 1;
                    continue;
                }

                $key = $this->normalizeSpelling($spelling);
                if ($key === $this->normalizeSpelling((string)$word->spelling)) {
                    $skipped += 1;
                    continue;
                }
                if (isset($existingSet[$key])) {
                    $skipped += 1;
                    continue;
                }

                $alternate = $this->Alternates->newEmptyEntity();
                $alternate = $this->Alternates->patchEntity($alternate, [
                    'word_id' => $word->id,
                    'spelling' => $spelling,
                ]);

                if ($this->Alternates->save($alternate)) {
                    $added += 1;
                    $existingSet[$key] = true;
                } else {
                    $failed += 1;
                }
            }

            if ($added > 0) {
                $this->Flash->success(__('{0} alternate spelling(s) added.', $added));
            }
            if ($skipped > 0) {
                $this->Flash->success(__('{0} item(s) skipped (blank/duplicate/same as main spelling).', $skipped));
            }
            if ($failed > 0) {
                $this->Flash->error(__('{0} item(s) could not be saved.', $failed));
            }

            return $this->redirect(['action' => 'manage', $word->id]);
        }

        $alternates = $this->Alternates->find()
            ->where(['word_id' => $word->id])
            ->orderBy(['spelling' => 'ASC', 'id' => 'ASC'])
            ->all();

        $this->set(compact('word', 'alternates'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Alternates->find()->contain(['Words']);
        $alternates = $this->paginate($query);

        $this->set(compact('alternates'));
    }

    /**
     * View method
     *
     * @param string|null $id Alternate id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $alternate = $this->Alternates->get($id, [
            'contain' => ['Words'],
        ]);

        $this->set(compact('alternate'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($resp = $this->requireSuperuser()) {
            return $resp;
        }
        $alternate = $this->Alternates->newEmptyEntity();
        if ($this->request->is('post')) {
            $alternate = $this->Alternates->patchEntity($alternate, $this->request->getData());
            if ($this->Alternates->save($alternate)) {
                $this->Flash->success(__('The alternate has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The alternate could not be saved. Please, try again.'));
        }
        $words = $this->Alternates->Words->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('alternate', 'words'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Alternate id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($resp = $this->requireSuperuser()) {
            return $resp;
        }
        $alternate = $this->Alternates->get($id, [
            'contain' => [],
        ]);
        $wordId = $this->request->getParam('pass.1') ?? $alternate->word_id;
        $word = $this->fetchTable('Words')->get($alternate->word_id, [
            'fields' => ['Words.id', 'Words.spelling'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $alternate = $this->Alternates->patchEntity($alternate, $this->request->getData());

            $submittedSpelling = trim((string)($alternate->spelling ?? ''));
            $submittedKey = $this->normalizeSpelling($submittedSpelling);
            $wordKey = $this->normalizeSpelling((string)$word->spelling);

            if ($submittedSpelling !== '' && $submittedKey === $wordKey) {
                $this->Flash->error(__('Alternate spelling cannot match the main word spelling.'));
                return $this->redirect(['action' => 'edit', $alternate->id, $wordId]);
            }

            if ($submittedSpelling !== '') {
                $existing = $this->Alternates->find()
                    ->select(['id', 'spelling'])
                    ->where(['word_id' => $alternate->word_id])
                    ->all();
                foreach ($existing as $row) {
                    if ((int)$row->id === (int)$alternate->id) {
                        continue;
                    }
                    if ($this->normalizeSpelling((string)$row->spelling) === $submittedKey) {
                        $this->Flash->error(__('That alternate spelling already exists for this word.'));
                        return $this->redirect(['action' => 'edit', $alternate->id, $wordId]);
                    }
                }
            }

            if ($this->Alternates->save($alternate)) {
                $this->Flash->success(__('The alternate has been saved.'));

                if (!empty($wordId)) {
                    return $this->redirect(['action' => 'manage', $wordId]);
                }
                return $this->redirect(['action' => 'manage', $alternate->word_id]);
            }
            $this->Flash->error(__('The alternate could not be saved. Please, try again.'));
        }
        $words = $this->Alternates->Words->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('alternate', 'words', 'word', 'wordId'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Alternate id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ($resp = $this->requireSuperuser()) {
            return $resp;
        }
        $this->request->allowMethod(['post', 'delete']);
        $wordId = $this->request->getParam('pass.1') ?? null;
        $alternate = $this->Alternates->get($id);
        if ($this->Alternates->delete($alternate)) {
            $this->Flash->success(__('The alternate has been deleted.'));
        } else {
            $this->Flash->error(__('The alternate could not be deleted. Please, try again.'));
        }

        if (!empty($wordId)) {
            return $this->redirect(['action' => 'manage', $wordId]);
        }
        return $this->redirect(['action' => 'manage', $alternate->word_id]);
    }
}
