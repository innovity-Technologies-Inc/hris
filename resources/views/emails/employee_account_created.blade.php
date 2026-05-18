<x-mail::message>
# Welcome, {{ $name }}!

Your employee account has been created successfully. You can now log in to the HRMS system using the following credentials:

**Login URL:** [{{ $loginUrl }}]({{ $loginUrl }})
**Email:** {{ $email }}
**Password:** {{ $password }}

<x-mail::button :url="$loginUrl">
Login to Dashboard
</x-mail::button>

Please change your password after your first login for security purposes.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
