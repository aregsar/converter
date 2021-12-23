<x-acme-converter::layouts.converter>
    <h1>Converter View Result {{ $amount }}</h1>
    <x-acme-converter::converter.simple>
        This content is plugged into the simple.blade.php view file slot
    </x-acme-converter::converter.simple>
    <x-acmeconverter-converter :amount="$amount" />
</x-acme-converter::layouts.converter>