    <x-mail::message>
    # Email Verification Code

    Hello,

    Thank you for registering with ChefGenius!

    Please use the following verification code to complete your registration. This code is valid for 10 minutes.

    <x-mail::panel>
    Your OTP Code: **{{ $otp }}**
    </x-mail::panel>

    If you did not create an account, no further action is required.

    Thanks,<br>
    {{ config('app.name') }}

    </x-mail::message>
    