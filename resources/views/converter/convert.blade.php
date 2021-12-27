<x-acme-converter::layouts.converter>
    <h1>Converter View Result {{ $amount }}</h1>
    <x-acme-converter::converter.simple :amount="$amount" />
    <x-acmeconverter-converter :amount="$amount" />
    @include('acme-converter::partials.message', ["message" => "rendering partial"])
</x-acme-converter::layouts.converter>