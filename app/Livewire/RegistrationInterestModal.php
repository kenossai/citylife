<?php

namespace App\Livewire;

use App\Models\RegistrationInterest;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class RegistrationInterestModal extends Component
{
    public $email = '';
    public $showModal = false;
    public $successMessage = '';
    public $errorMessage = '';

    protected $rules = [
        'email' => 'required|email|unique:registration_interests,email',
    ];

    protected $messages = [
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email has already been submitted.',
    ];

    protected $listeners = ['openModal'];

    public function openModal()
    {
        $this->showModal = true;
        $this->reset(['email', 'successMessage', 'errorMessage']);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['email', 'successMessage', 'errorMessage']);
    }

    public function submit()
    {
        $this->validate();

        try {
            RegistrationInterest::create([
                'email' => $this->email,
                'status' => 'pending',
            ]);

            $this->successMessage = 'Thank you for your interest! We\'ll review your request and send you a registration link soon.';
            $this->email = '';

            // Close modal after 3 seconds
            $this->dispatch('interest-submitted');
        } catch (\Exception $e) {
            $this->errorMessage = 'An error occurred. Please try again.';
        }
    }

    public function render()
    {
        return view('livewire.registration-interest-modal');
    }
}
