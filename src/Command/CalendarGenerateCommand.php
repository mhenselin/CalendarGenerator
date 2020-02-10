<?php

namespace App\Command;

use App\Calendar\Calendar;
use App\Renderer\LandscapeYear;
use App\Renderer\LandscapeYearMpdf;
use App\Renderer\LandscapeYearTwig;
use App\Repository\HolidaysRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Twig\Environment;

class CalendarGenerateCommand extends Command
{
    protected static $defaultName = 'calendar:generate';

    public function __construct(HolidaysRepository $holidaysRepository, Environment $twig)
    {
        $this->holidayRepo = $holidaysRepository;
        $this->twig = $twig;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('startdate', InputArgument::REQUIRED, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
            ->addOption('publicholidays', null, InputOption::VALUE_OPTIONAL, 'IUse public holidays for federal country')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        setlocale(LC_TIME, 'de_DE');

        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('startdate');
        $publicHolidaysFor = $input->getOption('publicholidays');

        $startDate = new \DateTime($arg1);
        $calendar = new Calendar($startDate);
        $io->title('Starting calender generation with startdate ' . $startDate->format('Y-m-d'));

        $io->text('* loading holidays for ' . $publicHolidaysFor);
        $holidays = $this->holidayRepo->getPackedHolidays($publicHolidaysFor);
        $calendar->addEvents($holidays);

        //TODO: add argument/option for csv-files when not fetching data beforehand
        #$this->holidayRepo->loadHolidaysFromCsv('Bayern');
        #$calendar->setEvents($this->holidayRepo->getHolidays());
        $io->text('* generating calendar');
        $calendar->generateCalendarData();

        //TODO: add argument/option to decide which renderer
        #$renderer = new LandscapeYearTwig($this->twig);
        #$renderer->setCalendarData($calendar->getData());
        #$renderer->renderData(realpath(__DIR__ . '/../../') . '/test.pdf');

        $io->text('* rendering calendar');
        $io->newLine();
        $renderer = new LandscapeYearMpdf();
        $renderer->setCalendarData($calendar->getData());
        $renderer->setCalendarEvents($calendar->getCalendarEvents());
        $renderer->renderCalendar(realpath(__DIR__ . '/../../') . '/test_direct.pdf');

        //TODO: remove old calendar rendering when direct renderer is done
        #$cal2 = new LandscapeYear();
        #$cal2->initCalender(1, 1, 2020);
        #$cal2->render();


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
