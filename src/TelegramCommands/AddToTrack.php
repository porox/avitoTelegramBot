<?php
/**
 * Created by PhpStorm.
 * TelegramUser: yury
 * Date: 2019-08-21
 * Time: 14:25.
 */

namespace App\TelegramCommands;

use App\Service\SearchQueryService;
use App\Service\UserService;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Telegram;

/**
 * Class AddToTrack.
 */
class AddToTrack extends AbstratUserCommand
{
    /**
     * @var string
     */
    protected $name = 'add';

    /**
     * @var string
     */
    protected $description = 'Добавление поиска в отслеживание'.PHP_EOL;

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var string
     */
    protected $usage = '/add {строка поиска}';

    /**
     * @var SearchQueryService
     */
    protected $issueService;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @return \Longman\TelegramBot\Entities\ServerResponse
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \JsonMapper_Exception
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chatId = $message->getChat()->getId();

        $user = $this->getUser($message);
        if ($user === null ){
            return null;
        }
        $text = $message->getText(true);
        $searchQuery = trim($text);

        $issue = $this->issueService->addSearchQueryTrack($searchQuery, $user);
        if (null === $issue) {
        } else {
            $message = 'Поисковый запрос "'.$searchQuery.'" добавлена в отслеживание.';
        }

        return Request::sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }
}
