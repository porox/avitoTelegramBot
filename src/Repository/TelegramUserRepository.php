<?php
/**
 * Created by PhpStorm.
 * TelegramUser: yury
 * Date: 2019-08-27
 * Time: 13:57.
 */

namespace App\Repository;

use App\Entity\TelegramUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TelegramUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelegramUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelegramUser[]    findAll()
 * @method TelegramUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TelegramUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass = null)
    {
        parent::__construct($registry, TelegramUser::class);
    }
}
