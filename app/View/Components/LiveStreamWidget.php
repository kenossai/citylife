<?php

namespace App\View\Components;

use App\Models\LiveStream;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LiveStreamWidget extends Component
{
    public $liveStreams;
    public $upcomingStream;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->liveStreams = LiveStream::getCurrentLiveStreams()->take(1);
        $this->upcomingStream = LiveStream::getUpcomingStreams(1)->first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.live-stream-widget');
    }
}
