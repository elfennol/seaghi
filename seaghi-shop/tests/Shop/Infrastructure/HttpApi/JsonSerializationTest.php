<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\HttpApi;

use App\Shop\Port\DataContract\BuyItemDto;
use App\Shop\Port\DataContract\SaleRejectionReason;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Check the JSON produced by the serializer configured in the Shop application.
 *
 * The QA bootstrap loads every application in the same process, so App\Kernel may belong to
 * another application: the kernel is built here from the Shop project directory.
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

    public function testUuidAndEnumAreScalars(): void
    {
        $id = Uuid::fromString('01a0ee62-5e9e-7f25-a82e-405e450ba3ee');

        $json = $this->serializer()->serialize(
            BuyItemDto::rejected($id, SaleRejectionReason::NOT_READY_TO_FIGHT),
            'json',
        );

        $this::assertJsonStringEqualsJsonString(
            '{"id":"01a0ee62-5e9e-7f25-a82e-405e450ba3ee","can_buy":false,"msg_code":"not_ready_to_fight"}',
            $json,
        );
    }

    private function serializer(): SerializerInterface
    {
        $serializer = $this->kernel->getContainer()->get('test.service_container')->get('serializer');
        $this::assertInstanceOf(SerializerInterface::class, $serializer);

        return $serializer;
    }
}
