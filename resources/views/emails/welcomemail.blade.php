@component('mail::message')
# Hello {{$user->name}}

Thank you for create an account. Please verify your email using this link:

@component('mail::button', ['url' => url('api/users/verify', $user->verification_token) ])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }},
Lionheartapps
@endcomponent