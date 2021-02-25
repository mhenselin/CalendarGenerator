<?php

namespace App\Command;

use App\Calendar\Calendar;
use App\Renderer\LandscapeYearMpdf;
use App\Repository\HolidaysRepository;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Twig\Environment;

class CalendarGenerateCommand extends Command
{
	/** @var string $defaultName */
    protected static $defaultName = 'calendar:generate';

	/** @var HolidaysRepository */
	private $holidayRepo;

	/**
	 * CalendarGenerateCommand constructor.
	 * @param HolidaysRepository $holidaysRepository
	 * @param Environment $twig
	 */
	public function __construct(HolidaysRepository $holidaysRepository, Environment $twig)
    {
        $this->holidayRepo = $holidaysRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate PDF calendar.')
            ->addArgument('startdate', InputArgument::REQUIRED, 'Argument description')
            ->addOption('publicholidays', null, InputOption::VALUE_OPTIONAL, 'Use public holidays for federal country')
            ->addOption('schoolholidays', null, InputOption::VALUE_OPTIONAL, 'Use school holidays for federal country')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('startdate');
        $publicHolidaysFor = $input->getOption('publicholidays');
        $schoolHolidaysFor = $input->getOption('schoolholidays');

        $startDate = Carbon::parse($arg1);
        $calendar = new Calendar($startDate);
        $io->title('Starting calender generation with start date ' . $startDate->format('Y-m-d'));

        if (!empty($publicHolidaysFor)) {
            $io->text('* loading holidays for ' . $publicHolidaysFor);
            $holidays = $this->holidayRepo->getPackedPublicHolidays($publicHolidaysFor);
            $calendar->addEvents($holidays);
        }

        if (!empty($schoolHolidaysFor)) {
            $io->text('* loading school vacations for ' . $schoolHolidaysFor);
            $vacations = $this->holidayRepo->getPackedSchoolHolidays($schoolHolidaysFor);
            $calendar->addEvents($vacations);
        }

        $io->text('* generating calendar');
        $calendar->generateCalendarData();

        $io->text('* rendering calendar');
        $io->newLine();
        $renderer = new LandscapeYearMpdf();
        $renderer->setCalendarData($calendar->getData());
        /** TODO: do not pass events through calendar - renderer can filter */
        $renderer->setCalendarEvents($calendar->getActiveCalendarEvents());
        $renderer->renderCalendar(dirname(__DIR__,2). '/test_direct.pdf');

        $io->success('You have a new calendar!');
        return 0;
    }
}
