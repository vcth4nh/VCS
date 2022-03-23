<?php

namespace Tests;

use Illuminate\View\Component;
use function view;

class BodyRowUsers extends Component
{
    public int $action;
    public string $role;
    public array $user_list;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user_list, $action, $role)
    {
        $this->action = $action;
        $this->role = $role;
        $this->user_list = $user_list;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table.body-row-users', ['user_list' => $this->user_list]);
    }
}
