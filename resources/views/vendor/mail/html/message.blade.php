<x-mail::layout>
{{-- Header --}}
<x-slot:header>
    <x-mail::header :url="config('app.url')">
@if (isset($header))
    {{ $header }}
@else
    <img width="180" height="50" src="https://res.cloudinary.com/ddble5id6/image/upload/f_auto,q_auto/v1/pay/pay-logo" alt="Pay Logo">
@endif
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
<x-slot:subcopy>
<x-mail::subcopy>
    @if (isset($subcopy))
        {{ $subcopy }}
    @else
        Regards, <br>
        The {{ config('app.name') }} Team.
    @endif

</x-mail::subcopy>
</x-slot:subcopy>

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }}. @lang('Powered by ') <a href="https://www.curacel.co/"><img width="auto" height="13" src="https://res.cloudinary.com/ddble5id6/image/upload/q_auto/v1/pay/curacel-small" alt="curacel_logo"></a>
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
