<?php
namespace App\View\Cell;

use Cake\View\Cell;

class LogoCell extends Cell
{
    public function languageinfo(){
        array_map([$this, 'loadModel'], ['Languages']);
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $urlparts1 = explode('//', $actual_link);
        $urlparts2 = explode('.', $urlparts1[1]);
        $reqsubdomain = $urlparts2[0];

        $sitelangvalues = $this->Languages->get_language($reqsubdomain);
        return $sitelangvalues;
    }

    
    public function display()
    {
        $sitelang = $this->languageinfo();
        $this->set('logofilename', $sitelang->LogoImage);
    }
}