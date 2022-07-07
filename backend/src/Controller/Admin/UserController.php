<?php

namespace App\Controller\Admin;

use App\Query\UserQuery;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route(
        '/admin/users',
        methods: ['GET'],
        name: 'app_admin_user_list',
    )]
    public function list(Request $request, UserQuery $userQuery, UserRepository $userRepository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('pageSize', 10);
        $userQuery->applyFromRequest($request);

        $result = $userRepository->findBySearchQuery($userQuery, $page, $pageSize);

        return $this->json($result);
    }
}
