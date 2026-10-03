<?php

declare(strict_types=1);

namespace App\Tests\Battle\Infrastructure\HttpApi;

use App\Battle\Infrastructure\Messenger\MonsterSoldMessage;
use App\Battle\Port\DataContract\HitMonsterDto;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Check the JSON handled by the serializer configured in the Battle application.
 *
 * The QA bootstrap loads every application in the same process, so App\Kernel may belong to
 * another application: the kernel is built here from the Battle project directory.
 */
class JsonSerializationTest extends TestCase
{
    private KernelInterface $kernel;

    protected function setUp(): void
    {
        $this->kernel = new class ('test', false) extends Kernel {
            use MicroKernelTrait;

            public function getProjectDir(): string
            {
                return dirname(__DIR__, 4);
            }

            public function getCacheDir(): string
            {
                return $this->getProjectDir() . '/var/cache/phpunit';
            }
        };
        $this->kernel->boot();
    }

    protected function tearDown(): void
    {
        $this->kernel->shutdown();
    }

    public function testUuidIsScalar(): void
    {
        $id = Uuid::fromString('01a10233-fe0e-7c2d-930d-ff85a72e522c');

        $json = $this->serializer()->serialize(new HitMonsterDto($id, 8, -2, ['burn']), 'json');

        $this::assertJsonStringEqualsJsonString(
            '{"id":"01a10233-fe0e-7c2d-930d-ff85a72e522c","current_health":8,"health_diff":-2,"effects":["burn"]}',
            $json,
        );
    }

    public function testDecodeShopMessage(): void
    {
        $message = $this->serializer()->deserialize(
            '{"first_name":"Pip","last_name":"Squeak","category_code":"lol_cat","level":3}',
            MonsterSoldMessage::class,
            'json',
        );

        $this::assertEquals(new MonsterSoldMessage('Pip', 'Squeak', 'lol_cat', 3), $message);
    }

    private function serializer(): SerializerInterface
    {
        $serializer = $this->kernel->getContainer()->get('test.service_container')->get('serializer');
        $this::assertInstanceOf(SerializerInterface::class, $serializer);

        return $serializer;
    }
}
