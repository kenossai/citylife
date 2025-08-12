<?php

namespace App\Livewire\Filament;

use Livewire\Component;
use App\Models\ContactSubmission;
use App\Models\VolunteerApplication;

class NotificationIcon extends Component
{
    public $mailCount = 0;
    public $volunteerCount = 0;
    public $totalCount = 0;

    public function mount()
    {
        $this->updateCounts();
    }

    public function updateCounts()
    {
        $this->mailCount = ContactSubmission::where('status', 'new')->count();
        $this->volunteerCount = VolunteerApplication::where('status', 'pending')->count();
        $this->totalCount = $this->mailCount + $this->volunteerCount;
    }

    public function render()
    {
        return view('livewire.filament.notification-icon');
    }

    // Polling every 30 seconds to update counts
    public function refreshCounts()
    {
        $this->updateCounts();
    }
}
