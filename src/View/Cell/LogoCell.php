<?php
namespace App\View\Cell;

use Cake\View\Cell;

class LogoCell extends Cell
{
    /* public function languageinfo(){
        $languagesTable = $this->fetchTable('Languages');
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $urlparts1 = explode('//', $actual_link);
        $urlparts2 = explode('.', $urlparts1[1]);
        $reqsubdomain = $urlparts2[0];

        $sitelangvalues = $languagesTable->get_language($reqsubdomain);
        return $sitelangvalues;
    }*/

    
    public function display()
    {
        //$sitelang = $this->request->getAttribute('sitelang');
        $sitelang = $this->languageinfo();
        debug($sitelang);
        $this->set('sitei18n', $sitelang->i18nspec);
    }
}