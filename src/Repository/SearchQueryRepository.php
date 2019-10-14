<?php
/**
 * Created by PhpStorm.
 * TelegramUser: yury
 * Date: 2019-08-27
 * Time: 13:57.
 */

namespace App\Repository;

use App\Entity\SearchQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SearchQuery|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchQuery|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchQuery[]    findAll()
 * @method SearchQuery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchQueryRepository extends ServiceEntityRepository
{
    /**
     * IssueRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param string|null     $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = null)
    {
        parent::__construct($registry, SearchQuery::class);
    }

    /**
     * @return iterable
     */
    public function getActiveIssues(): iterable
    {
        $builder = $this->createQueryBuilder('i')
            ->where('i.blocked = false');

        return  $builder->getQuery()->iterate();
    }
}
