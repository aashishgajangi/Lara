<x-mail::message>
# Welcome to {{ config('app.name') }}!

Hello {{ $user->name }},

Thank you for registering with {{ config('app.name') }}! We're excited to have you as part of our community.

@if(!$user->hasVerifiedEmail())
**Please verify your email address** to activate your account and start shopping.

<x-mail::button :url="url('/email/verify/' . $user->id . '/' . sha1($user->email))">
Verify Email Address
</x-mail::button>
@endif

Here's what you can do now:
- Browse our products
- Add items to your cart
- Track your orders
- Manage your account

If you have any questions, feel free to contact our support team.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
