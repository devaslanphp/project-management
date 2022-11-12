<?php

namespace App\Http\Livewire;

use Illuminate\Support\HtmlString;
use JeffGreco13\FilamentBreezy\Pages\MyProfile as BaseProfile;

class Profile extends BaseProfile
{

    protected static ?string $slug = 'my-profile';

    protected function getUpdateProfileFormSchema(): array
    {
        $fields = parent::getUpdateProfileFormSchema();
        $fields[1]->helperText(function () {
            $pendingEmail = $this->user->getPendingEmail();
            if ($pendingEmail) {
                return new HtmlString(
                    '<span>' .
                    __('You have a pending email verification for :email.', [
                        'email' => $pendingEmail
                    ])
                    . '</span> <a wire:click="resendPending"
                                   class="hover:cursor-pointer hover:text-primary-500 hover:underline">
                    ' . __('Click here to resend') . '
                </a>'
                );
            } else {
                return '';
            }
        });
        return $fields;
    }

    public function updateProfile()
    {
        $data = $this->updateProfileForm->getState();
        $loginColumnValue = $data[$this->loginColumn];
        unset($data[$this->loginColumn]);
        $this->user->update($data);
        $this->user->refresh();
        $this->updateProfileForm->fill([
            'name' => $this->user->name,
            'email' => $this->user->email
        ]);
        if ($loginColumnValue != $this->user->{$this->loginColumn}) {
            $this->user->newEmail($loginColumnValue);
        }
        $this->notify("success", __('filament-breezy::default.profile.personal_info.notify'));
    }

    public function resendPending(): void
    {
        $this->user->resendPendingEmailVerificationMail();
        $this->notify('success', __('Email verification sent'));
    }
}
