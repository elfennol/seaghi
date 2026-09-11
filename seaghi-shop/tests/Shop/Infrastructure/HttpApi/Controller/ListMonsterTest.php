<?php

declare(strict_types=1);

namespace App\Tests\Shop\Infrastructure\HttpApi\Controller;

use App\Shop\Infrastructure\HttpApi\Controller\ListMonster;
use App\Shop\Port\In\DataContract\SearchMonsterDto;
use App\Shop\Port\In\ListItemPort;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validation;

class ListMonsterTest extends TestCase
{
    private ListItemPort $listItemPort;

    private ListMonster $controller;

    public function testInvokeWithoutQueryParametersCallsPortWithNull(): void
    {
        $request = new Request();

        $this->listItemPort
            ->expects($this->once())
            ->method('list')
            ->with(null, null)
            ->willReturn([]);

        $result = ($this->controller)($request);

        $this::assertSame([], $result);
    }

    public function testInvokeWithValidFilterParameters(): void
    {
        $request = new Request(['level_min' => '2', 'level_max' => '5']);

        $expected = [new SearchMonsterDto(1, 'gargoyle', 2, 100, 'Stone', 'Gargoyle', true, false)];
        $this->listItemPort
            ->expects($this->once())
            ->method('list')
            ->with(2, 5)
            ->willReturn($expected);

        $result = ($this->controller)($request);

        $this::assertSame($expected, $result);
    }

    public function testInvokeWithNonPositiveLevelThrowsBadRequest(): void
    {
        $request = new Request(['level_min' => '0']);

        $this->listItemPort
            ->expects($this->never())
            ->method('list');

        $this->expectException(BadRequestHttpException::class);
        ($this->controller)($request);
    }

    protected function setUp(): void
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        $this->listItemPort = $this->createMock(ListItemPort::class);
        $this->controller = new ListMonster($validator, $this->listItemPort);
    }
}
