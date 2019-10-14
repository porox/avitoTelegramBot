<?php
/**
 * Created by PhpStorm.
 * User: yury
 * Date: 2019-10-09
 * Time: 00:40
 */

namespace App\TelegramCommands;

use App\Entity\TelegramUser;
use App\Service\SearchQueryService;
use App\Service\UserService;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Telegram;

abstract  class AbstratUserCommand extends UserCommand
{
    /**
     * @var SearchQueryService
     */
    protected $issueService;

    /**
     * @var UserService
     */
    protected $userService;

    public function __construct(Telegram $telegram, UserService $userService, SearchQueryService $issueService, Update $update
    = null)
    {
        parent::__construct($telegram, $update);
        $this->issueService = $issueService;
        $this->userService = $userService;
    }

    protected function getUser(Message $message) : ?TelegramUser
    {
        $user = $this->userService->findUserByTelegramChatId($message->getChat()->getId());
        if (null === $user) {
            $user = $this->userService->createUser($message->getChat());
        }
        if ($user->isBlocked()) {
            $message = 'Вы не можете пользоваться командами бота.';

             Request::sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => $message,
            ]);
            return null;
        }

        return $user;
    }
}