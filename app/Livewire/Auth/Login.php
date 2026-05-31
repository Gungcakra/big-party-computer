<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.guest.index')]
class Login extends Component
{
    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('required|min:1')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Email atau password salah.');
            $this->password = '';
            return;
        }

        session()->regenerate();

        $route = match (Auth::user()->role) {
            'admin'   => 'admin.dashboard',
            'teknisi' => 'teknisi.dashboard',
            default   => 'admin.dashboard',
        };

        $this->redirect(route($route), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
