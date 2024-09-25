<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Img extends Component
{
    public $src;
    public $alt;
    public $class;

    public function __construct($src, $alt = '', $class = '')
    {
        $this->src = $src;
        $this->alt = $alt;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.img');
    }
}
