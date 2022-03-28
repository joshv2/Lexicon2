<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * SentenceRecordings Controller
 *
 * @property \App\Model\Table\SentenceRecordingsTable $SentenceRecordings
 * @method \App\Model\Entity\SentenceRecording[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SentenceRecordingsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Sentences'],
        ];
        $sentenceRecordings = $this->paginate($this->SentenceRecordings);

        $this->set(compact('sentenceRecordings'));
    }

    /**
     * View method
     *
     * @param string|null $id Sentence Recording id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sentenceRecording = $this->SentenceRecordings->get($id, [
            'contain' => ['Sentences'],
        ]);

        $this->set(compact('sentenceRecording'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $sentenceRecording = $this->SentenceRecordings->newEmptyEntity();
        if ($this->request->is('post')) {
            $sentenceRecording = $this->SentenceRecordings->patchEntity($sentenceRecording, $this->request->getData());
            if ($this->SentenceRecordings->save($sentenceRecording)) {
                $this->Flash->success(__('The sentence recording has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sentence recording could not be saved. Please, try again.'));
        }
        array_map([$this, 'loadModel'], ['Sentences']);
        $sentences = $this->Sentences->get_sentences($id);
        //$sentences = $this->SentenceRecordings->Sentences->find('list', ['limit' => 200]);
        $this->set(compact('sentenceRecording', 'sentences'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sentence Recording id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sentenceRecording = $this->SentenceRecordings->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sentenceRecording = $this->SentenceRecordings->patchEntity($sentenceRecording, $this->request->getData());
            if ($this->SentenceRecordings->save($sentenceRecording)) {
                $this->Flash->success(__('The sentence recording has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sentence recording could not be saved. Please, try again.'));
        }
        $sentences = $this->SentenceRecordings->Sentences->find('list', ['limit' => 200]);
        $this->set(compact('sentenceRecording', 'sentences'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sentence Recording id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sentenceRecording = $this->SentenceRecordings->get($id);
        if ($this->SentenceRecordings->delete($sentenceRecording)) {
            $this->Flash->success(__('The sentence recording has been deleted.'));
        } else {
            $this->Flash->error(__('The sentence recording could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function choose($id = null)
    {
        if( $this->request->is('post') ) {
            $data = $this->request->getData();
            $this->redirect('/sentenceRecordings/add/'.$data['sentenceToRecord']);
        } else {
        array_map([$this, 'loadModel'], ['Words', 'Sentences']);
        $wordResult = $this->Words->get_word_for_view($id);
        $word = $wordResult[0];
        $this->set(compact('word'));
        }
    }
}
