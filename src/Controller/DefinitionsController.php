<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Log\Log;
/**
 * Definitions Controller
 *
 * @property \App\Model\Table\DefinitionsTable $Definitions
 * @method \App\Model\Entity\Definition[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DefinitionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index(?int $wordId = null)
    {
        $word = null;
        $query = $this->Definitions
            ->find()
            ->contain(['Words']);

        if ($wordId) {
            $query->where(['Definitions.word_id' => $wordId]);
            // Provide the word context expected by the index template.
            $word = $this->Definitions->Words->get($wordId, fields: ['id', 'spelling']);
        }
        $definitions = $this->paginate($query);

        $this->set(compact('definitions', 'wordId', 'word'));
    }

    /**
     * View method
     *
     * @param string|null $id Definition id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $definition = $this->Definitions->get($id, contain: ['Words']);

        $this->set(compact('definition'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $wordId = $id;
        $definition = $this->Definitions->newEmptyEntity();
        if ($this->request->is('post')) {
            // If this add form is word-scoped (e.g. /definitions/add/123), lock the word_id.
            if ($wordId !== null) {
                $definition->word_id = (int)$wordId;
            }
            $definition = $this->Definitions->patchEntity($definition, $this->request->getData());
            if ($this->Definitions->save($definition)) {
                $this->Flash->success(__('The definition has been saved.'));

                // Redirect back to the word-scoped list so the index template has $word.
                return $this->redirect(['action' => 'index', $definition->word_id]);
            }
            $this->Flash->error(__('The definition could not be saved. Please, try again.'));
        }
        $words = $this->Definitions->Words->find(type: 'list', options: ['limit' => 200]);
        $this->set(compact('definition', 'words', 'wordId'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Definition id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $definition = $this->Definitions->get($id, contain: ['Words']);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $definition = $this->Definitions->patchEntity($definition, $this->request->getData());
            if ($this->Definitions->save($definition)) {
                $this->Flash->success(__('The definition has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The definition could not be saved. Please, try again.'));
        }

        // If you still need the list of words for the <select>
        $words = $this->Definitions->Words->find('list')->all();

        $this->set(compact('definition', 'words'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Definition id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null, $wordid = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $definition = $this->Definitions->get($id);
        if ($this->Definitions->delete($definition)) {
            Log::info('Definition \/\/ ' . $this->request->getSession()->read('Auth.username') . ' deleted ' . $definition->definition . ' \/\/', ['scope' => ['events']]);
            $this->Flash->success(__('The definition has been deleted.'));
        } else {
            $this->Flash->error(__('The definition could not be deleted. Please, try again.'));
        }

        if ($wordid !== null) {
            return $this->redirect(['controller' => 'Words', 'action' => 'edit', $wordid]);
        }

        return $this->redirect(['action' => 'index']);
    }

    public function word(int $wordId)
    {
        // Fetch the word record (for the page title, etc.)
        $word = $this->Definitions->Words->get($wordId, fields: ['id', 'spelling']);

        // Use the custom finder
        $query = $this->Definitions->find('byWordId', [
            'word_id' => $wordId
        ]);

        $definitions = $this->paginate($query);

        $this->set(compact('definitions', 'word'));
        $this->render('index');
    }


    public function ajaxdelete($id = null)
    {
        $this->RequestHandler->renderAs($this, 'json');
        $response = [];

        if( $this->request->is('post') ) {
            $definition = $this->Definitions->get($id);
            if ($this->Definitions->delete($definition)) {
                Log::info('Definition \/\/ ' . $this->request->getSession()->read('Auth.username') . ' deleted ' . $definition->definition . ' \/\/', ['scope' => ['events']]);
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

        /*$this->request->allowMethod(['post', 'delete']);
        $definition = $this->Definitions->get($id);
        if ($this->Definitions->delete($definition)) {
            Log::info('Definition \/\/ ' . $this->request->getSession()->read('Auth.username') . ' deleted ' . $definition->definition . ' \/\/', ['scope' => ['events']]);
            $this->Flash->success(__('The definition has been deleted.'));
        } else {
            $this->Flash->error(__('The definition could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller' => 'Words', 'action' => 'edit', $wordid]);*/
    }
}
