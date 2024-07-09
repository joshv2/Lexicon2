<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Languages Controller
 *
 * @property \App\Model\Table\LanguagesTable $Languages
 * @method \App\Model\Entity\Language[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LanguagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $languages = $this->paginate($this->Languages);

        $this->set(compact('languages'));
    }

    public function about() {
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $this->set(compact('sitelang'));
    }

    public function notes() {
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        $this->set(compact('sitelang'));
    }

    /**
     * View method
     *
     * @param string|null $id Language id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $language = $this->Languages->get($id, [
            'contain' => ['Words'],
        ]);

        $this->set(compact('language'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $language = $this->Languages->newEmptyEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $header_image = $postData['HeaderImage'];

            if(!empty($header_image->getClientFilename())){ 
                
                $name = $header_image->getClientFilename();
                
                $targetPath = WWW_ROOT. 'img' . DS . $name;
                $header_image->moveTo($targetPath);
                $postData['HeaderImage'] = $name;
            } else {
                $this->Flash->error(__('Please include a header image.'));
            }

            $logo_image = $postData['LogoImage'];
            if(!empty($logo_image->getClientFilename())){ 
                
                $name = $logo_image->getClientFilename();
                
                $targetPath = WWW_ROOT. 'img' . DS . $name;
                $header_image->moveTo($targetPath);
                $postData['LogoImage'] = $name;
            } else {
                $this->Flash->error(__('Please include a logo image.'));
            }

            $translation_file = $postData['translationfile'];
            if(!empty($translation_file->getClientFilename())){ 
                
                $name = $translation_file->getClientFilename();
                $finalname = 'default.po';
                if (!is_dir(ROOT . DS . 'resources' . DS . 'locales' . DS . $postData['i18nspec'])){
                    mkdir(ROOT. DS .  'resources' . DS . 'locales' . DS .  $postData['i18nspec'], 0777, true);
                }
                $targetPath = ROOT . DS . 'resources' . DS . 'locales' . DS . $postData['i18nspec'] . DS . $finalname;
                $translation_file->moveTo($targetPath);
                $postData['translationfile'] = $name;
            } else {
                $this->Flash->error(__('Please include a translation file.'));
            }


            $language = $this->Languages->patchEntity($language, $postData);

            

            if ($this->Languages->save($language)) {
                $this->Flash->success(__('The language has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The language could not be saved. Please, try again.'));
        }
        $this->set(compact('language'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Language id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sitelang = $this->viewBuilder()->getVar('sitelang');
        //$this->set(compact('sitelang'));
        $language = $this->Languages->get($sitelang['id'], [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            

            $quillFields = ['AboutSec1Text', 'AboutSec2Text','AboutSec3Text','AboutSec4Text','NotesSec1Text'];
            foreach ($quillFields as $quillField) {
                $original = $postData[$quillField];
                $jsonFromOriginal = json_decode($original);
                $postData[$quillField . '_json'] = json_encode($jsonFromOriginal);
                $quill = new \DBlackborough\Quill\Render($postData[$quillField]);
                $defresult = $quill->render();
                $postData[$quillField] = $defresult;
           

                if ('<p><br/></p>' == preg_replace('/\s+/', '',$postData[$quillField])){
                    $postData[$quillField] = '';
                    $postData[$quillField . '_json'] = '{}';
                }
                 
            
            }
            
            

            $language = $this->Languages->patchEntity($language, $postData);
            if ($this->Languages->save($language)) {
                $this->Flash->success(__('The language has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The language could not be saved. Please, try again.'));
        }
        $this->set(compact('language'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Language id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $language = $this->Languages->get($id);
        if ($this->Languages->delete($language)) {
            $this->Flash->success(__('The language has been deleted.'));
        } else {
            $this->Flash->error(__('The language could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
