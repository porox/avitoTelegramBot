<?php
/**
 * Created by PhpStorm.
 * TelegramUser: yury
 * Date: 2019-08-28
 * Time: 18:52.
 */

namespace App\Service;

use App\Entity\TelegramUser;
use Longman\TelegramBot\Entities\Chat;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserService.
 */
class UserService
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $em;
    /**
     * @var \App\Repository\TelegramUserRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected $userRepo;

    /**
     * UserService constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->userRepo = $this->em->getRepository(TelegramUser::class);
    }

    /**
     * @param $telegramId
     *
     * @return TelegramUser|null
     */
    public function findUserByTelegramChatId($telegramId): ?TelegramUser
    {
        return $this->userRepo->findOneBy(['telegramChatId' => $telegramId]);
    }

    /**
     * @param Chat $chat
     *
     * @return TelegramUser
     */
    public function createUser(Chat $chat): TelegramUser
    {
        $user = new TelegramUser();
        $user->setTelegramChatId($chat->getId());
        $user->setBlocked(false);
        $user->setTelegramInfo((array) $chat);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
