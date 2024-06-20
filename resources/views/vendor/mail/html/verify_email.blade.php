@component('mail::message')
# Welcome to Uganda Printing and Publishing Corporation!

Dear Customer,

Thank you for registering with Uganda Printing and Publishing Corporation. To begin using our services, please verify your email address by clicking the button below:

@component('mail::button', ['url' => $verificationUrl])
Verify Email Address
@endcomponent

If you did not create an account, no further action is required.

Best regards,

Team Uganda Printing and Publishing Corporation

{{ config('app.name') }}
@endcomponent
