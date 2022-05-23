<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /**
     * Displays a view
     *
     * @param string ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\View\Exception\MissingTemplateException When the view file could not
     *   be found and in debug mode.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found and not in debug mode.
     * @throws \Cake\View\Exception\MissingTemplateException In debug mode.
     */
    public function display(string ...$path): ?Response
    {
        if (!$path) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }

        $title = ucfirst($page);
        $this->set(compact('page', 'subpage', 'title'));

        try {
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }

    public function index(){
        $words = $this->getTableLocator()->get('Words');
        $sitelang = $this->languageinfo();
        $total_entries = $words->find()->where(['approved' => 1, 'language_id' => $sitelang->id])->count();
        array_map([$this, 'loadModel'], ['Words', 'Origins', 'Regions', 'Types', 'Dictionaries']); //load Models so we can get for the homepage dropdown
        //$this->loadModel('Words', 'Origins', 'Regions', 'Types');
        $tagging = [];
        if($sitelang->hasOrigins) {
            $origins = $this->Origins->top_origins_for_home($sitelang->id);
            $tagging['origins'] = $origins;
        }
        if($sitelang->hasRegions) {
            $regions = $this->Regions->top_regions_for_home($sitelang->id);
            $tagging['regions'] = $regions;
        }
        if($sitelang->hasTypes) {
            $types = $this->Types->top_types_for_home($sitelang->id);
            $tagging['types'] = $types;
        }
        if($sitelang->hasDictionaries) {
            $dictionaries = $this->Dictionaries->top_dictionaries($sitelang->id);
            $no_dict_entries = $this->Words->get_not_in_other_dictionary($sitelang->id);
            $tagging['no_dict_entries'] = $no_dict_entries;
            $tagging['dictionaries'] = $dictionaries;
        }
        
        
        //$total_entries = 200000;
        $title = __('Home');
        $tagging['title'] = $title;
        $tagging['total_entries'] = $total_entries;
        //$tagging['no_dict_entries'] = $no_dict_entries;
        $tagging['sitelang'] = $sitelang;

        $this->set($tagging); //compact('total_entries', 'title', 'no_dict_entries', $tags, 'sitelang')
        $this->render('home');
    }

    public function googleadspage() {
        $this->viewBuilder()->setLayout('googleads');
        $this->render('gads');

    }

}
