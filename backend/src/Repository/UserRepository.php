<?php

namespace App\Repository;

use App\Entity\User;
use App\Pagination\Paginator;
use App\Query\UserQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Paginator Returns an Paginator object
     */
    public function findBySearchQuery(UserQuery $query, $page, $pageSize): Paginator
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC');

        if (isset($query->isActive)) {
            $qb->andWhere('u.is_active = :is_active')
                ->setParameter('is_active', $query->isActive);
        }

        if (isset($query->isMember)) {
            $qb->andWhere('u.is_member = :is_active')
                ->setParameter('is_active', $query->isActive);
        }

        if (isset($query->lastLoginFrom)) {
            $qb->andWhere('u.last_login_at >= :last_login_from')
                ->setParameter('last_login_from', $query->lastLoginFrom);
        }

        if (isset($query->lastLoginTo)) {
            $qb->andWhere('u.last_login_at <= :last_login_to')
                ->setParameter('last_login_to', $query->lastLoginTo);
        }

        if (!empty($query->userTypes)) {
            $qb->andWhere($qb->expr()->in('u.user_type', ':user_types'))
                ->setParameter('user_types', $query->userTypes);
        }

        return (new Paginator($qb, $pageSize))->paginate($page);
    }
}
