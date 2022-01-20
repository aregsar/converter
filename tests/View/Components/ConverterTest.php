<?php

namespace Aregsar\Converter\Tests\View\Components;

use Aregsar\Converter\Tests\BaseTestCase;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;

class ConverterTest extends BaseTestCase
{
    use InteractsWithViews;

    /** @test */
    public function it_displays_amount()
    {
        $this->component(\Aregsar\Converter\View\Components\Converter::class, ['amount' => 42])
            ->assertSee("Conversion Component Result 42");
    }
}
