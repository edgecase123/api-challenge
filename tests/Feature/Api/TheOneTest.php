<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TheOneTest extends TestCase
{

    #[Test]
    public function itConsumerLorApi(): void
    {
        $this->assertEquals(2, 1+1);
    }
}
