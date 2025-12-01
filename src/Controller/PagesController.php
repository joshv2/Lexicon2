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
use Cake\Event\EventInterface;
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

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);  // <== This is essential
        // Your PagesController specific code here, if any
    }


    public function index(): void{
        $sitelang = $this->request->getAttribute('sitelang');
        $wordsTable = $this->fetchTable('Words');
        $originsTable = $this->fetchTable('Origins');
        $regionsTable = $this->fetchTable('Regions');
        $typesTable = $this->fetchTable('Types');
        $dictionariesTable = $this->fetchTable('Dictionaries');
        $typeCategoriesTable  = $this->fetchTable('TypeCategories');
        $sitelang = $this->request->getAttribute('sitelang');
        $total_entries = $wordsTable->find()->where(['approved' => 1, 'language_id' => $sitelang->id])->count();
        $tagging = [];
        if($sitelang->hasOrigins) {
            $origins = $originsTable->top_origins_for_home($sitelang->id);
            $tagging['origins'] = $origins;
        }
        if($sitelang->hasRegions) {
            $regions = $regionsTable->top_regions_for_home($sitelang->id);
            $tagging['regions'] = $regions;
        }
        if($sitelang->hasTypes) {
            $typesWithCategory = $typeCategoriesTable->top_types_for_home_by_cat($sitelang->id);
            $typesWithoutCategory = $typesTable->top_types_for_home_no_cat($sitelang->id);
            $types = array_merge($typesWithCategory, $typesWithoutCategory);
            #$types = $typesTable->top_types_for_home($sitelang->id);
            $tagging['types'] = $types;
        }
        if($sitelang->hasDictionaries) {
            $dictionaries = $dictionariesTable->top_dictionaries($sitelang->id);
            $no_dict_entries = $wordsTable->get_not_in_other_dictionary_count($sitelang->id);
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
