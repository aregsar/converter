<x-acme-converter::layouts.converter>
    <h1>Converter View Result {{ $amount }}</h1>
    <x-acme-converter::converter.simple :amount="$amount" />
    <x-acmeconverter-converter :amount="$amount" />
    <livewire:show-amount :amount="$amount" />
    <x-acme-converter-conversion-convert :amount="$amount" />
    @include('acme-converter::partials.message', ["message" => "rendering partial"])
    <x-acme-converter-simple :amount="$amount" />
</x-acme-converter::layouts.converter>