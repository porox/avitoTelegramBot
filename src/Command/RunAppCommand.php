<?php

namespace App\Command;

use App\Service\AvitoMobileParser;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class RunAppCommand.
 */
class RunAppCommand extends Command
{
    /**
     * @var Telegram
     */
    protected $service;

    /**
     * @var RegistryInterface
     */
    protected $registry;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * RunAppCommand constructor.
     *
     * @param RegistryInterface $registry
     * @param Telegram          $service
     * @param null              $name
     */
    public function __construct(RegistryInterface $registry, LoggerInterface $logger, Telegram $service, $name = null)
    {
        parent::__construct($name);
        $this->service  = $service;
        $this->registry = $registry;
        $this->logger   = $logger;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setName('app:run')->setDescription('Start bot application;');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (true) {
            try {
                $this->service->handleGetUpdates();
                $this->clearManagers();
            } catch (TelegramException $clientException) {
                $this->logger->error($clientException->getMessage(), $clientException->getTrace());
                VarDumper::dump($clientException); die;

            }
            sleep(1);
        }
    }


    /**
     *
     */
    protected function clearManagers()
    {
        foreach ($this->registry->getManagers() as $curManagerName => $curManager) {
            if (!$curManager->isOpen()) {
                $this->registry->resetManager($curManagerName);
            } else {
                $curManager->clear();
            }
        }
    }
}
