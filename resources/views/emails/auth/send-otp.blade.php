<x-mail::message>
<x-mail::layout>
<x-slot name="header">
  <x-mail::header :url="config('app.url')" style="background-color: #FF6B6B; color: white; padding: 20px; text-align: center;">
    🍳 <strong>ChefGenius</strong>
  </x-mail::header>
</x-slot>

# 🍽️ Verify Your Email

Hello,

Thank you for registering with **ChefGenius**!  
We’re excited to have you join our culinary community.

Please use the verification code below to activate your account. This code is valid for the next **10 minutes**.

<x-mail::panel>
<span style="font-size: 22px; color: #FF6B6B;"><strong>Your OTP Code: {{ $otp }}</strong></span>
</x-mail::panel>

If you did not request this, you can safely ignore this email.

<x-mail::button :url="config('app.url')" color="success">
Go to ChefGenius
</x-mail::button>

Thanks,  
The ChefGenius Team 👨‍🍳

<x-slot name="footer">
  <x-mail::footer style="background-color: #f8f9fa; padding: 10px; text-align: center; font-size: 12px;">
    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
  </x-mail::footer>
</x-slot>
</x-mail::layout>
</x-mail::message>
