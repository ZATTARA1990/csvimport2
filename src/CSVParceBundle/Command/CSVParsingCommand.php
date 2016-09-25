<?php

namespace CSVParceBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use CSVParceBundle\MyClass\WrapperWorkflow;


class CSVParsingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('app:parsing-file')
            // the short description shown while running "php app/console list"
            ->setDescription('parsing file.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to parsing of the selected file...")
            ->addArgument('fileName', InputArgument::REQUIRED, 'path to file.')
            ->addArgument('test', InputArgument::OPTIONAL, 'enable test mode');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // get the filename and option test
        $filename = $input->getArgument('fileName');
        $test = $input->getArgument('test');

        // get entity manager
        $em = $this->getContainer()->get('doctrine')->getManager();

        //set param for filter
        $myfilter=$this->getContainer()->get('CSVvalidator');

        //set param for converter
        $myconvertor=$this->getContainer()->get('reformat');

        $workflow=new  WrapperWorkflow($test);

        //workflow import csv file
        $result=$workflow->read($filename)
            ->write($em, 'CSVParceBundle:Product')
            ->setFilter($myfilter)
            ->setFormat($myconvertor)
            ->process();

        //output results
        $output->writeln('Result import: ' . $filename);
        $output->writeln('All items: ' . $workflow->count);
        $output->writeln('Were successful: ' . $result->getSuccessCount());
        $output->writeln('Were skipped: ' . ($workflow->count - $result->getSuccessCount()));


    }



}