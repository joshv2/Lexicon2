<?php
namespace App\View\Cell;

use Cake\View\Cell;

class ShareCell extends Cell
{
    public function display($word, $dropdown = false)
    {
        $this->set(compact('word', 'dropdown'));
    }
}
