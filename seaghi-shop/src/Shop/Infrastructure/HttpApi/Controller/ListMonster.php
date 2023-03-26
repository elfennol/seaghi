<?php

declare(strict_types=1);

namespace App\Shop\Infrastructure\HttpApi\Controller;

use App\Shop\Infrastructure\HttpApi\Dto\MonsterListRequest;
use App\Shop\Port\In\DataContract\SearchMonsterDto;
use App\Shop\Port\In\ListItemPort;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class ListMonster
{
    public function __construct(
        private ValidatorInterface $validator,
        private ListItemPort $listMonster,
    ) {
    }

    /**
     * @return SearchMonsterDto[]
     */
    #[Route('/monster/list', methods: ['GET'])]
    public function __invoke(Request $request): iterable
    {
        $monsterListRequest = new MonsterListRequest((int)$request->get('level_min'), (int)$request->get('level_max'));
        $errors = $this->validator->validate($monsterListRequest);

        if (count($errors) > 0) {
            throw new BadRequestHttpException((string)$errors);
        }

        return $this->listMonster->list($monsterListRequest->levelMin, $monsterListRequest->levelMax);
    }
}
