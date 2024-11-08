<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * AlphabetAddendums Controller
 *
 * @property \App\Model\Table\AlphabetAddendumsTable $AlphabetAddendums
 * @method \App\Model\Entity\AlphabetAddendum[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AlphabetAddendumsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Languages'],
        ];
        $alphabetAddendums = $this->paginate($this->AlphabetAddendums);

        $this->set(compact('alphabetAddendums'));
    }

    /**
     * View method
     *
     * @param string|null $id Alphabet Addendum id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $alphabetAddendum = $this->AlphabetAddendums->get($id, [
            'contain' => ['Languages'],
        ]);

        $this->set(compact('alphabetAddendum'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $alphabetAddendum = $this->AlphabetAddendums->newEmptyEntity();
        if ($this->request->is('post')) {
            $alphabetAddendum = $this->AlphabetAddendums->patchEntity($alphabetAddendum, $this->request->getData());
            if ($this->AlphabetAddendums->save($alphabetAddendum)) {
                $this->Flash->success(__('The alphabet addendum has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The alphabet addendum could not be saved. Please, try again.'));
        }
        $languages = $this->AlphabetAddendums->Languages->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('alphabetAddendum', 'languages'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Alphabet Addendum id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $alphabetAddendum = $this->AlphabetAddendums->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $alphabetAddendum = $this->AlphabetAddendums->patchEntity($alphabetAddendum, $this->request->getData());
            if ($this->AlphabetAddendums->save($alphabetAddendum)) {
                $this->Flash->success(__('The alphabet addendum has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The alphabet addendum could not be saved. Please, try again.'));
        }
        $languages = $this->AlphabetAddendums->Languages->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('alphabetAddendum', 'languages'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Alphabet Addendum id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $alphabetAddendum = $this->AlphabetAddendums->get($id);
        if ($this->AlphabetAddendums->delete($alphabetAddendum)) {
            $this->Flash->success(__('The alphabet addendum has been deleted.'));
        } else {
            $this->Flash->error(__('The alphabet addendum could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
