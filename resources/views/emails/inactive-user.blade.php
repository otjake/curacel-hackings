<x-mail::message>

Dear {{ $user->name }},

We hope this is a good time to check on you?

We noticed your account has been inactive for the over 60 days. We know how busy life can get, but we wanted to make sure you're not missing out on the latest features and benefits that Curacel Pay has to offer.

From streamlined transactions to competitive fees, your account is packed with tools designed to make your payments and operations easier.

<x-mail::small-button color="payblue" :url="route('login')">
Login now
</x-mail::small-button>

</x-mail::message>
