<?php

namespace Aregsar\Converter\Tests\Resources\Views\Components\Converter;

use Aregsar\Converter\Tests\BaseTestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

class SimpleTest extends BaseTestCase
{
    use InteractsWithViews;

    /** @test */
    public function it_displays_amount()
    {
        $this->blade('<x-acme-converter::converter.simple :amount="$amount" />', ['amount' => 42])
            ->assertSee('Simple Component Result 42');
    }
}
