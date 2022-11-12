<?php

namespace App\Http\Livewire;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ValidateAccount extends Component implements HasForms
{
    use InteractsWithForms;

    public User $user;

    public function mount()
    {
        $this->form->fill();
    }

    public function render(): View
    {
        return view('livewire.validate-account');
    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('password')
                ->password()
                ->required()
                ->confirmed()
                ->label(__('Account password'))
                ->placeholder(__('Choose your account password')),

            TextInput::make('password_confirmation')
                ->password()
                ->required()
                ->label(__('Password confirmation'))
                ->placeholder(__('Confirm your chosen password')),
        ];
    }

    public function validateAccount(): void
    {
        $data = $this->form->getState();
        $this->user->creation_token = null;
        $this->user->password = bcrypt($data['password']);
        $this->user->email_verified_at = now();
        $this->user->save();
        auth()->login($this->user);
        Filament::notify('success', __('Account verified'), true);
        redirect()->to(route('filament.pages.dashboard'));
    }
}
