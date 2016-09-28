<?php

namespace Tests\CSVParceBundle\Command;

use CSVParceBundle\Command\CSVParsingCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;


class CSVParsingCommandTest extends KernelTestCase
{


    public $filename;


    public function __construct()
    {
        $this->filename = tempnam(sys_get_temp_dir(), 'csvparsing2');
        $file = fopen($this->filename, 'w');
        fwrite($file, 'productName,price,stock,description,discontinued
                        test1,1.0,9,description1,0
                        test2,1,1,description2,1
                        test3,10.8,17,description3,0
                        test4,4,3,description4,0');
        fclose($file);
    }

    public function testExecute()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $application->add(new CSVParsingCommand());

        $command = $application->find('app:parsing-file');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command' => $command->getName(),
            'test' => 'test',
            'fileName' => $this->filename,
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('All items: 4', $output);
        $this->assertContains('Were successful: 1', $output);
        $this->assertContains('Were skipped: 3', $output);
    }

}
