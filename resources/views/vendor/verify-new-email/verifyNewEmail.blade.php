@component('mail::message')
# Verify New Email Address

Please click the button below to verify your new email address.

@component('mail::button', ['url' => $url])
Verify New Email Address
@endcomponent

If you did not update your email address, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent