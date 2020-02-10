<?php

namespace App\Command;

use App\Repository\HolidaysRepository;
use App\Service\ApiCrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CalendarFetchPublicHolidaysCommand extends Command
{
    protected static $defaultName = 'calendar:fetch:holidays';

    /** @var HolidaysRepository */
    private $holidayRepo;

    /** @var ApiCrawler */
    private $apiCrawler;

    public function __construct(string $name = null, HolidaysRepository $holidaysRepository, ApiCrawler $apiCrawler)
    {
        $this->holidayRepo = $holidaysRepository;
        $this->apiCrawler = $apiCrawler;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Fetches holidays from https://deutsche-feiertage-api.de to store in local file')
            ->addArgument('holidayTypes', InputArgument::REQUIRED, 'Which type to fetch - [public, school]')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->hasArgument('holidayTypes')) {
            $holidayTypes = explode(',', $input->getArgument('holidayTypes'));
        }

        if (in_array('public', $holidayTypes)) {
            $result = $this->apiCrawler->fetchFromDFAPI();
            $this->holidayRepo->saveHolidaysToPacked($result);

            $io->success('Successfully loaded data from https://deutsche-feiertage-api.de');
        }

        if (in_array('school', $holidayTypes)) {
            $io->error('Fetching school holiday not supported yet');
        }

        return 0;
    }
}
