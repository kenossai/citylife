<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class AuthHelper extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Share authentication state with all views
        View::share('memberAuth', [
            'check' => Auth::guard('member')->check(),
            'user' => Auth::guard('member')->user(),
            'id' => Auth::guard('member')->id(),
        ]);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return '';
    }
}
