<?php

namespace Acme\Converter\Tests\Http\Livewire;

use Aregsar\Converter\Tests\BaseTestCase;
use Aregsar\Converter\Http\Livewire\ShowAmount;
use Livewire\Livewire;

class ShowAmountTest extends BaseTestCase
{

    //Note: If we use:
    //assertSeeLivewire(\Aregsar\Converter\Http\Livewire\ShowAmount::class)
    //assertSeeLivewire("show-amount")
    //the test fails with:
    // Cannot find Livewire component [acme.converter.http.livewire.show-amount] rendered on page.
    // this is because live wire tries to chech for the string:
    // "&quot;name&quot;:&quot;aregsar.converter.http.livewire.show-amount&quot;"
    // where the component only renders:
    // "&quot;name&quot;:&quot;show-amount&quot;"
    // which does not include the namespace of the component class
    // so the assertion fails
    // This might be an issue with components included in packages
    // Therefore we use the standard assertSee('"name":"show-amount"') method to just check
    // for the component name without the namespace.

    /**
     * @test
     */
    public function verify_livewire_component_exists_in_rendered_view()
    {
        // $this->withoutExceptionHandling();

        $routeUrlPrefix = config('acme-converter.route_url_prefix');

        //mock the service in the route closure
        \Converter::shouldReceive("convert")
            ->with("EUR")
            ->once()
            ->andReturn("42");

        // invoke the route closure that returns the view with the Livewire component
        // $this->get(route("convert",["currency" => "EUR"]))
        $this->get("{$routeUrlPrefix}convert/EUR")
            //$this->get("{$routeUrlPrefix}conversion/convert/EUR")
            ->assertOK()
            //assert we are rendering the view that should contain the livewire component
            ->assertViewIs("acme-converter::converter.convert")
            //assert that the Livewire component exists in the view
            //example using the component class (does not work in package)
            //->assertSeeLivewire(\Aregsar\Converter\Http\Livewire\ShowAmount::class)
            //assert that the Livewire component exists in the view
            //altrenative example using the component tag string (does not work in package)
            //->assertSeeLivewire("show-amount")
            //forced to manually check for existance using assertSee (the inner quotes must be double quotes)
            ->assertSee('"name":"show-amount"');
    }

    //===================================================
    //Component Unit tests


    /** @test **/
    public function verify_can_pass_ammount()
    {
        Livewire::test(ShowAmount::class, ['amount' => 42])
            //assert the amount property is set to 42
            ->assertSet('amount', 42)
            //assert that the value 42 is rendered in the components view
            ->assertSee("42");
    }

    /** @test **/
    public function verify_can_set_ammount()
    {
        Livewire::test(ShowAmount::class, ['amount' => 42])
            //set the amount property value to 42
            ->set('amount', '43')
            //assert the amount property is set to 42
            ->assertSet('amount', '43')
            //assert that the value 42 is rendered in the components view
            ->assertSee("43");
    }

    /** @test **/
    public function verify_can_change_amount()
    {
        Livewire::test(ShowAmount::class, ['amount' => 42])
            //call the fix method to change the value to 43
            ->call('fix')
            //assert the amount property is set to 43
            ->assertSet('amount', '43')
            //assert that the value 43 is rendered in the components view
            ->assertSee("43");
    }
}
