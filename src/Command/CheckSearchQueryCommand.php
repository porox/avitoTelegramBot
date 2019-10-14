<?php

namespace App\Command;

use App\Service\IssueService;
use App\Service\SearchQueryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckIssuesCommand.
 */
class CheckSearchQueryCommand extends Command
{
    /**
     * @var SearchQueryService
     */
    protected $service;

    /**
     * CheckIssuesCommand constructor.
     *
     * @param SearchQueryService $service
     * @param null         $name
     */
    public function __construct(SearchQueryService $service, $name = null)
    {
        parent::__construct($name);
        $this->service = $service;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setName('app:searchQuery')
            ->setDescription('check search query');
    }


    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->service->checkSearchQueries();
    }
}
