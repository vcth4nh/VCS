<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Avatar extends Component
{

    public $avatar;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->avatar = $this->getAvatar();
    }

    public function getAvatar()
    {
        return User::info(['uid' => Auth::user()->uid])['avatar'];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.avatar');
    }
}
