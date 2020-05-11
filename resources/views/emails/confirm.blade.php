Hello {{$user->name}}

You changed your email, So we need to verify this new email using this link:
{{url('api/users/verify', $user->verification_token)}}