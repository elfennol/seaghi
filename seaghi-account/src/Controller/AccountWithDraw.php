<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AccountWithDraw
{
    #[Route('/account/withdraw')]
    public function __invoke(): JsonResponse
    {
        // No spending limit! \o/
        return new JsonResponse(['status' => true]);
    }
}
