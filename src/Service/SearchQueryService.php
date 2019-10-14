<?php
/**
 * Created by PhpStorm.
 * TelegramUser: yury
 * Date: 2019-08-27
 * Time: 14:29.
 */

namespace App\Service;

use App\Entity\Advertisement;
use App\Entity\SearchQuery;
use App\Entity\TelegramUser;
use App\Object\AvitoItem;
use App\Repository\SearchQueryRepository;
use Doctrine\ORM\EntityManager;
use Longman\TelegramBot\Request;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class IssueService.
 */
class SearchQueryService
{
    /**
     * @var SearchQueryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected $serchQueryRepository;
    /**
     * @var \App\Repository\TelegramUserRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected $userRepository;
    /**
     * @var EntityManager
     */
    protected $em;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var AvitoParser
     */
    protected $parser;


    /**
     * SearchQueryService constructor.
     *
     * @param RegistryInterface $registry
     * @param LoggerInterface   $logger
     * @param AvitoParser       $parser
     */
    public function __construct(RegistryInterface $registry, LoggerInterface $logger, AvitoParser $parser)
    {
        $this->em                   = $registry->getManager();
        $this->serchQueryRepository = $this->em->getRepository(SearchQuery::class);
        $this->userRepository       = $this->em->getRepository(TelegramUser::class);
        $this->logger               = $logger;
        $this->parser               = $parser;
    }


    /**
     * @param string $searchQuery
     *
     * @return SearchQuery|null
     */
    public function findSearchQuery(string $searchQuery): ?SearchQuery
    {
        return $this->serchQueryRepository->findOneBy(['query' => $searchQuery]);
    }


    /**
     * @param string       $searchString
     * @param TelegramUser $user
     *
     * @return SearchQuery|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addSearchQueryTrack(string $searchString, TelegramUser $user): ?SearchQuery
    {
        $searchQuery = $this->findSearchQuery($searchString);
        if (null === $searchQuery) {
            $searchQuery = $this->createSearchQuery($searchString);
        }
        $users = $searchQuery->getUsers();
        if (!$users->contains($user)) {
            $users->add($user);
            $searchQuery->setUsers($users);
            $this->em->persist($searchQuery);
        }
        $this->em->flush();

        return $searchQuery;
    }


    /**
     * @param AvitoItem     $item
     * @param Advertisement $adv
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateAdvertisement(AvitoItem $item, Advertisement $adv)
    {
        $adv->setPrice($item->getPrice());
        $adv->setLink($item->getLink());
        $adv->setHash($item->getHash());
        $adv->setId($item->getId());
        $this->em->merge($adv);
        $this->em->flush();
    }

    /**
     * @param AvitoItem $item
     *
     * @return Advertisement
     */
    protected function createAdvertisement(AvitoItem $item): Advertisement
    {
        $adv = new Advertisement();
        $adv->setPrice($item->getPrice());
        $adv->setLink($item->getLink());
        $adv->setHash($item->getHash());
        $adv->setId($item->getId());

        return $adv;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function checkSearchQueries()
    {
        foreach ($this->serchQueryRepository->getActiveIssues() as $searchQuery) {
            /**
             * @var SearchQuery $searchQuery
             */
            $searchQuery = $searchQuery[0];
            foreach ($this->parser->parse($searchQuery->getQuery()) as $avitoItem) {
                if ($avitoItem instanceof AvitoItem) {
                    $needNotify = false;
                    $adv = $this->em->find(Advertisement::class, $avitoItem->getId());

                    if ($adv === null) {
                        $adv = $this->createAdvertisement($avitoItem);
                        $needNotify = true;
                    }
                    $querys = $adv->getSearchQuery();
                    if (!$querys->contains($searchQuery)) {
                        $querys->add($searchQuery);
                        $adv->setSearchQuery($querys);
                        $this->em->persist($adv);
                    }
                    $this->em->flush();
                    if ($adv->getHash() !== $avitoItem->getHash()) {
                        $this->updateAdvertisement($avitoItem, $adv);
                        $needNotify =true;
                    }

                    if ($needNotify){
                        $this->notifyListeners($searchQuery, $avitoItem->__toString());
                    }

                }
            }


        }
    }


    /**
     * @param SearchQuery $searchQuery
     * @param string      $message
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    protected function notifyListeners(SearchQuery $searchQuery, string $message)
    {
        foreach ($searchQuery->getUsers() as $user) {
            if (!$user->isBlocked()) {
                Request::sendMessage([
                    'chat_id' => $user->getTelegramChatId(),
                    'text'    => $message,
                ]);
                sleep(1);
            }
        }
    }


    /**
     * @param string $searchString
     *
     * @return SearchQuery
     */
    protected function createSearchQuery(string $searchString): SearchQuery
    {
        $searchQuery = new SearchQuery();
        $searchQuery->setQuery($searchString);
        $searchQuery->setBlocked(false);

        return $searchQuery;
    }
}
