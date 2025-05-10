<?php
namespace App\View\Components;

use Illuminate\View\Component;

class PageHeader extends Component
{
    public $title;
    public $breadcrumbs;
    public $bgColor;
    public $extraClasses;

    public function __construct($title, $breadcrumbs = [], $bgColor = '#0D47A1', $extraClasses = 'text-center text-white rounded')
{
    $this->title = $title;
    $this->breadcrumbs = $breadcrumbs;
    $this->bgColor = $bgColor;
    $this->extraClasses = $extraClasses;
}

    public function render()
    {
        return view('components.page-header');
    }
}
