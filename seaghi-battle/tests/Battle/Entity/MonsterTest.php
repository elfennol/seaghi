<?php

declare(strict_types=1);

namespace App\Tests\Battle\Entity;

use App\Battle\Entity\Effect;
use App\Battle\Entity\Monster;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MonsterTest extends TestCase
{
    public function testGettersAndInitialHealth(): void
    {
        $monster = new Monster('Fang', 'Dragon', 100, 15);

        $this::assertNull($monster->getId());
        $this::assertSame('Fang', $monster->getFirstName());
        $this::assertSame('Dragon', $monster->getLastName());
        $this::assertSame('Fang Dragon', $monster->getFullName());
        $this::assertSame(100, $monster->getMaxHealth());
        $this::assertSame(100, $monster->getCurrentHealth());
        $this::assertSame(15, $monster->getDefense());
        $this::assertTrue($monster->isAlive());
        $this::assertFalse($monster->isDead());
        $this::assertSame([], $monster->getEffects());
    }

    public function testExplicitCurrentHealth(): void
    {
        $monster = new Monster('Fang', 'Dragon', 100, 15, 40);

        $this::assertSame(40, $monster->getCurrentHealth());
    }

    public function testApplyDamageReducesHealth(): void
    {
        $monster = new Monster('Fang', 'Dragon', 100, 15);
        $monster->applyDamage(30);

        $this::assertSame(70, $monster->getCurrentHealth());
        $this::assertTrue($monster->isAlive());
    }

    public function testApplyDamageClampsToZero(): void
    {
        $monster = new Monster('Fang', 'Dragon', 100, 15);
        $monster->applyDamage(150);

        $this::assertSame(0, $monster->getCurrentHealth());
        $this::assertFalse($monster->isAlive());
        $this::assertTrue($monster->isDead());
    }

    public function testApplyNegativeDamageThrows(): void
    {
        $monster = new Monster('Fang', 'Dragon', 100, 15);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Damage amount cannot be negative.');

        $monster->applyDamage(-10);
    }

    public function testHealIncreasesHealth(): void
    {
        $monster = new Monster('Fang', 'Dragon', 100, 15, 40);
        $monster->heal(25);

        $this::assertSame(65, $monster->getCurrentHealth());
    }

    public function testHealClampsToMaxHealth(): void
    {
        $monster = new Monster('Fang', 'Dragon', 100, 15, 90);
        $monster->heal(50);

        $this::assertSame(100, $monster->getCurrentHealth());
    }

    public function testHealNegativeThrows(): void
    {
        $monster = new Monster('Fang', 'Dragon', 100, 15);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Healing amount cannot be negative.');

        $monster->heal(-5);
    }

    public function testEffectsLifecycle(): void
    {
        $monster = new Monster('Fang', 'Dragon', 100, 15);
        $effect1 = new Effect();
        $effect1->setCode(Effect::CODE_BADASS);
        $effect2 = new Effect();
        $effect2->setCode(Effect::CODE_SERIOUS_INJURY);

        $monster->addEffect($effect1);
        $monster->addEffect($effect1); // Duplicate should not be added
        $this::assertCount(1, $monster->getEffects());
        $this::assertSame([$effect1], $monster->getEffects());

        $monster->removeEffect($effect1);
        $this::assertSame([], $monster->getEffects());

        $monster->replaceEffects([$effect1, $effect2]);
        $this::assertCount(2, $monster->getEffects());

        $monster->clearEffects();
        $this::assertSame([], $monster->getEffects());
    }
}
