<?php

declare(strict_types=1);

namespace App\Tests\Battle\Entity;

use App\Battle\Entity\Effect;
use PHPUnit\Framework\TestCase;

class EffectTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $effect = new Effect();

        $this::assertNull($effect->getId());

        $effect->setCode(Effect::CODE_BADASS);
        $this::assertSame(Effect::CODE_BADASS, $effect->getCode());
    }
}
