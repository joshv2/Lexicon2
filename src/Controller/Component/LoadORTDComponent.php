<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class LoadORTDComponent extends Component
{
    public ?\App\Model\Table\OriginsTable $Origins = null;
    public ?\App\Model\Table\RegionsTable $Regions = null;
    public ?\App\Model\Table\TypesTable $Types = null;
    public ?\App\Model\Table\TypeCategoriesTable $TypeCategories = null;
    public ?\App\Model\Table\DictionariesTable $Dictionaries = null;
    public ?\App\Model\Table\WordsTable $Words = null;

    public function getORTD($sitelang){
        $this->Origins = TableRegistry::getTableLocator()->get('Origins');
        $this->Regions = TableRegistry::getTableLocator()->get('Regions');
        $this->Types = TableRegistry::getTableLocator()->get('Types');
        $this->TypeCategories = TableRegistry::getTableLocator()->get('TypeCategories');
        $this->Dictionaries = TableRegistry::getTableLocator()->get('Dictionaries');
        $this->Words = TableRegistry::getTableLocator()->get('Words');
        #array_map([$this, 'loadModel'], ['Words', 'Origins', 'Regions', 'Types', 'Dictionaries', 'TypeCategories']); //load Models so we can get for the homepage dropdown

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
                $typesWithCategory = $this->TypeCategories->top_types_for_home_by_cat($sitelang->id);
                $typesWithoutCategory = $this->Types->top_types_for_home_no_cat($sitelang->id);
                $types = array_merge($typesWithCategory, $typesWithoutCategory);
                $tagging['types'] = $types;
                $tagging['toptypes'] = $this->Types->top_types($sitelang->id);
            }
            if($sitelang->hasDictionaries) {
                $dictionaries = $this->Dictionaries->top_dictionaries($sitelang->id);
                $no_dict_entries = $this->Words->get_not_in_other_dictionary_count($sitelang->id);
                $no_dict_entries_words = $this->Words->get_not_in_other_dictionary_words($sitelang->id);
                $tagging['no_dict_entries'] = $no_dict_entries;
                $tagging['dictionaries'] = $dictionaries;
                $tagging['no_dict_entries_words'] = $no_dict_entries_words;
            }

        return $tagging;
        }
}