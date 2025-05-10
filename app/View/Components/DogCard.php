namespace App\View\Components;

use Illuminate\View\Component;

class DogCard extends Component
{
    public $dog;

    public function __construct($dog)
    {
        $this->$item = $item;
    }

    public function render()
    {
        return view('components.dog-card');
    }
}
