<x-mail::message>
<h1>VERIFICATION EMAIL</h1>

<p>Thank you for choosing {{ config('app.name') }}.</p>
<p>Use the OTP below to complete your Sign Up.</p>

<x-mail::panel>
<h2>OTP : {{ $otp }} </h2>
</x-mail::panel>

<small>OTP is valid for 15 minutes</small>

Thanks,<br>
{{ config('app.name') }}

</x-mail::message>
