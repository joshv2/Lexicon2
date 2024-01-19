<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class LoadORTDComponent extends Component
{
    public function getORTD($sitelang){
        $this->Origins = TableRegistry::get('Origins');
        $this->Regions = TableRegistry::get('Regions');
        $this->Types = TableRegistry::get('Types');
        $this->TypeCategories = TableRegistry::get('TypeCategories');
        $this->Dictionaries = TableRegistry::get('Dictionaries');
        $this->Words = TableRegistry::get('Words');
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
                $no_dict_entries = $this->Words->get_not_in_other_dictionary($sitelang->id);
                $tagging['no_dict_entries'] = $no_dict_entries;
                $tagging['dictionaries'] = $dictionaries;
            }

        return $tagging;
        }
}