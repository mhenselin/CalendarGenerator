<?php

namespace App\Command;

use App\Repository\HolidaysRepository;
use App\Service\ApiCrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CalendarFetchHolidaysCommand extends Command
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

	/**
	 * configuration of the command
	 */
    protected function configure(): void
    {
        $this
            ->setDescription('Fetches holidays from https://deutsche-feiertage-api.de to store in local file')
            ->addArgument('holidayTypes', InputArgument::REQUIRED, 'Which type to fetch - [public, school]')
            ->addOption('year', 'y',InputArgument::OPTIONAL,'The year to be fetched - default "this" year');
        ;
    }

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int
	 */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $holidayTypes = [];
        if ($input->hasArgument('holidayTypes')) {
            $holidayTypes = explode(',', $input->getArgument('holidayTypes'));
        }

        $years = !empty($input->getOption('year')) ?
            explode(',', $input->getOption('year')) :
            [date('Y')];

        if (in_array('public', $holidayTypes,TRUE)) {
            $result = [];
            foreach ($years as $year) {
                $result[] = $this->apiCrawler->fetchFromDFAPI($year);
            }
			$result = array_merge(...$result);
            $this->holidayRepo->savePublicHolidaysToPacked($result);

            $io->success('Successfully loaded data from https://deutsche-feiertage-api.de');
        }

        if (in_array('school',$holidayTypes,TRUE)) {
            $result = [];
            foreach ($years as $year) {
                $result[] = $this->apiCrawler->fetchDataFromSF($year);
            }
			$result = array_merge(...$result);
            if (!empty($result)) {
                $this->holidayRepo->saveSchoolHolidaysToPacked($result);
                $io->success('Successfully loaded data from https://schulferien.org');
            } else {
                $io->error('Returned data is empty - something went wrong!');
            }
        }

        return 0;
    }
}
