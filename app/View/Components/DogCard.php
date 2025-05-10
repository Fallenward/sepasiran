<?php
namespace App\View\Components;

use Illuminate\View\Component;

class DogCard extends Component
{
    public $item;

    public function __construct($item)
    {
        // Ensure that the passed data is properly assigned to the item
        $this->item = $item;
    }

    public function render()
    {
        // Render the correct view (could be a generic 'dog-card' view or another, depending on context)
        return view('components.dog-card');
    }
}

