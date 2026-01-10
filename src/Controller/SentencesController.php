<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Log\Log;

/**
 * Sentences Controller
 *
 * @property \App\Model\Table\SentencesTable $Sentences
 * @method \App\Model\Entity\Sentence[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SentencesController extends AppController
{
    /**
     * Index method
     *
     * If $wordId is provided, only show sentences for that word.
     *
     * @param int|null $wordId Word id to filter by.
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index(?int $wordId = null)
    {
        // Paginator settings should be paginator-only (limit/order/etc), not query options like contain.
        $this->paginate = [
            'limit' => 25,
            'order' => ['Sentences.id' => 'DESC'],
        ];

        $query = $this->Sentences->find()
            ->contain(['Words']);

        if ($wordId !== null) {
            $query->where(['Sentences.word_id' => $wordId]);
        }

        $sentences = $this->paginate($query);

        $this->set(compact('sentences', 'wordId'));
    }

    /**
     * View method
     *
     * @param string|null $id Sentence id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sentence = $this->Sentences->get($id, [
            'contain' => ['Words', 'SentenceRecordings'],
        ]);

        $this->set(compact('sentence'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $sentence = $this->Sentences->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Force word_id from the URL if provided
            if ($id !== null) {
                $data['word_id'] = (int)$id;
            }

            // Convert Quill delta JSON -> HTML + sentence_json
            $data = $this->processQuillSentence($data);

            $sentence = $this->Sentences->patchEntity($sentence, $data);
            if ($this->Sentences->save($sentence)) {
                $this->Flash->success(__('The sentence has been saved.'));

                // If word_id exists, go back to the word-scoped index
                return $this->redirect(['action' => 'index', $sentence->word_id]);
            }

            $this->Flash->error(__('The sentence could not be saved. Please, try again.'));
        }

        $wordId = $id;
        $words = $this->Sentences->Words->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('sentence', 'words', 'wordId'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sentence id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sentence = $this->Sentences->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Convert Quill delta JSON -> HTML + sentence_json
            $data = $this->processQuillSentence($data);

            $sentence = $this->Sentences->patchEntity($sentence, $data);
            if ($this->Sentences->save($sentence)) {
                $this->Flash->success(__('The sentence has been saved.'));
                return $this->redirect(['action' => 'index', $sentence->word_id]);
            }

            $this->Flash->error(__('The sentence could not be saved. Please, try again.'));
        }

        $words = $this->Sentences->Words->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('sentence', 'words'));
    }

    /**
     * If sentence is Quill Delta JSON, store it in sentence_json and render HTML into sentence.
     */
    private function processQuillSentence(array $data): array
    {
        $raw = $data['sentence'] ?? null;
        if (!is_string($raw)) {
            return $data;
        }

        $rawTrim = trim($raw);
        if ($rawTrim === '') {
            return $data;
        }

        $decoded = json_decode($rawTrim, true);

        // Only treat it as Quill if it decodes and has ops
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded) || !isset($decoded['ops'])) {
            return $data;
        }

        // Treat a blank delta as empty content
        if ($rawTrim === '{"ops":[{"insert":"\n"}]}') {
            $data['sentence'] = '';
            $data['sentence_json'] = $rawTrim;
            return $data;
        }

        $data['sentence_json'] = json_encode($decoded, JSON_UNESCAPED_UNICODE);

        $quill = new \nadar\quill\Lexer($rawTrim);
        $data['sentence'] = $quill->render() ?? '';

        return $data;
    }

    /**
     * Delete method
     *
     * @param string|null $id Sentence id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sentence = $this->Sentences->get($id);
        if ($this->Sentences->delete($sentence)) {
            $this->Flash->success(__('The sentence has been deleted.'));
        } else {
            $this->Flash->error(__('The sentence could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    public function ajaxdelete($id = null)
    {
        $this->RequestHandler->renderAs($this, 'json');
        $response = [];

        if( $this->request->is('post') ) {
            $sentence = $this->Sentences->get($id);
            if ($this->Sentences->delete($sentence)) {
                Log::info('Sentence \/\/ ' . $this->request->getSession()->read('Auth.username') . ' deleted ' . $sentence->sentence . ' \/\/', ['scope' => ['events']]);
                $response['success'] = 1;
            } else {
                $response['success'] = 0;
            }
            //debug($response['spelling']);
        } else {
            $response['success'] = 0;
        }

        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', true);
        $this->RequestHandler->renderAs($this, 'json');
    }
}
