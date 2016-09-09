<?php

namespace CSVParceBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Ddeboer\DataImport\Reader;
use Ddeboer\DataImport\Writer;
use Ddeboer\DataImport\Step;
use Ddeboer\DataImport\Workflow\StepAggregator;


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
            ->addArgument('Test', InputArgument::OPTIONAL, 'enable test mode.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $filename = $input->getArgument('fileName');
        $test = $input->getArgument('Test');


        $em = $this->getContainer()->get('doctrine')->getManager();

        //read CSV file
        $file = new \SplFileObject($filename);
        $reader = new Reader\CsvReader($file);
        $reader->setHeaderRowNumber(0);
        $count = $reader->count();



        $workflow = new StepAggregator($reader);

        //selection Writer
        if ($test) {
            $writer = new Writer\CallbackWriter(function () {
            });
        } else {
            $writer = new Writer\DoctrineWriter($em, 'CSVParceBundle:Product');
            $writer->disableTruncate();
        }


        // Set filter
        $filter = new Step\FilterStep();
        $myfilter = function ($data) {
            if ($data['price'] < 1000) {
                if ($data['price'] > 5 || $data['stock'] > 10) {
                    return true;
                }
            }
            return false;
        };


        //Set convertor
        $convertor=new Step\ConverterStep();
        $myconvertor=function($product){
            $time=' 00:00:00';
            $product['discontinuedDate']=new \DateTime($product['discontinuedDate'].$time);
            if($product['discontinued']){
                $product['discontinuedDate']=new \DateTime('now');
            }
            return $product;
        };
        //The workflow

        $result = $workflow
            ->addWriter($writer)
            ->addStep($filter->add($myfilter))
            ->addStep($convertor->add($myconvertor))
            ->process();


        //output results
        $output->writeln('Result import: ' . $filename);
        $output->writeln('All items: ' . $count);
        $output->writeln('Were seccesfull: ' . $result->getSuccessCount());
        $output->writeln('Were skipped: ' . ($count - $result->getSuccessCount()));


    }
}