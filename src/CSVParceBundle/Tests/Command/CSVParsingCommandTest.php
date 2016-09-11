<?php

namespace Tests\CSVParceBundle\Command;

use CSVParceBundle\Command\CSVParsingCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserCommandTest extends KernelTestCase
{

    public $filename;


    protected function createFile()
    {
        $this->filename = tempnam(sys_get_temp_dir(), 'csvparsing');
        $file = fopen($this->filename, 'w');
        fwrite($file, 'productName,price,stock,description,discontinued
                        test1,10.2,10,description1,0
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

// pass arguments to the helper
            'test' => 'test',
            'filename' => $this->filename,
        ));

        $output = $commandTester->getDisplay();
        $this->assertContains('Total        4', $output);
        $this->assertContains('Successful   2', $output);
        $this->assertContains('Skipped      2', $output);
    }
}
