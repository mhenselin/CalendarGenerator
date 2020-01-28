<?php

namespace App\Command;

use App\Calendar\Calendar;
use App\Renderer\YearplannerTwig;
use App\Repository\HolidaysRepository;
use App\Serializer\Normalizer\HolidaysNormalizer;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
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
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $this->holidayRepo->loadHolidays('Bayern');
        $calendar = new Calendar(new \DateTime('2020-01'));
        $calendar->setEvents($this->holidayRepo->getHolidays());
        $calendar->generateCalendarData();

        #var_dump($calendar->getData());
        $renderer = new YearplannerTwig($this->twig);
        $renderer->setCalendarData($calendar->getData());
        $renderer->renderData();



        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
