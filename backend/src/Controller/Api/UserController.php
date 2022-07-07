<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Query\UserQuery;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

class UserController extends AbstractController
{
    #[Route(
        '/api/users',
        methods: ['GET'],
        name: 'api_user_list',
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns user list',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "currentPage", type: "integer", minimum: 1),
                new OA\Property(property: "lastPage", type: "integer", minimum: 1),
                new OA\Property(property: "pageSize", type: "integer", minimum: 1),
                new OA\Property(property: "previousPage", type: "integer", minimum: 1),
                new OA\Property(property: "nextPage", type: "integer", minimum: 1),
                new OA\Property(property: "toPaginate", type: "boolean"),
                new OA\Property(property: "numResults", type: "integer"),
                new OA\Property(property: "results",  type: 'array', items: new OA\Items(ref: new Model(type: User::class)))
            ],
        )
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'The current page number field',
        example: 1,
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Parameter(
        name: 'pageSize',
        in: 'query',
        description: 'The field of record nums per page',
        example: 10,
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Parameter(
        name: 'isActive',
        in: 'query',
        description: 'The field used to filter user is_active value',
        schema: new OA\Schema(type: 'int', enum: [0, 1])
    )]
    #[OA\Parameter(
        name: 'isMember',
        in: 'query',
        description: 'The field used to filter user is_member value',
        schema: new OA\Schema(type: 'int', enum: [0, 1])
    )]
    #[OA\Parameter(
        name: 'lastLoginFrom',
        in: 'query',
        description: 'The field used to filter user last_login_at value from, format: Y-m-d H:i:s',
        example: '2022-01-01 00:00:00',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'lastLoginTo',
        in: 'query',
        description: 'The field used to filter user last_login_at value to, format: Y-m-d H:i:s',
        example: '2022-01-01 00:00:00',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'userTypes',
        in: 'query',
        description: 'The field used to filter user user_type value, multiple values should be seperated by english comma `,`',
        example: "1,2",
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'users')]
    public function list(Request $request, UserQuery $userQuery, UserRepository $userRepository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('pageSize', 10);
        $userQuery->applyFromRequest($request);

        $result = $userRepository->findBySearchQuery($userQuery, $page, $pageSize);

        // when using data, frontend should do xss filter jobs
        return $this->json($result);
    }
}
