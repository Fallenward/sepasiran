<php
namespace App\View\Components;

use Illuminate\View\Component;

class FilterForm extends Component
{
    public $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function render()
    {
        return view('components.filter-form');
    }
}
