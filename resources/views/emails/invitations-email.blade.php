<p>Hi {{ $user->first_name." ".$user->last_name }}</p>

<p>You've been invited to join the Titan Hoardings portal. To set up your account, please click the link below.</p>

<p><a href="{{ request()->root() }}/invitations/accept/{{ $invitation->token }}">{{ request()->root() }}/invitations/accept/{{ $invitation->token }}</a></p>

<p>
    Titan Hoardings portal
</p>