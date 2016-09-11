<?php
/**
 * Created by PhpStorm.
 * User: zattara
 * Date: 10.9.16
 * Time: 17.35
 */

namespace CSVParceBundle\MyClass;

use Ddeboer\DataImport\Reader;
use Ddeboer\DataImport\Writer;
use Ddeboer\DataImport\Step;
use Ddeboer\DataImport\Workflow\StepAggregator;


/**
 * Class wrapper over workflow use data-import bundle
 *
 */
class WrapperWorkflow
{
    private $test;
    private $workflow;
    public $count;

    /**
     *
     * @param bool $test
     *
     */
    public function __construct($test=null)
    {

        $this->test=$test;
    }
    /**
     * Set the filename to parsing and create object StepAggregator
     *
     * @param string $filename   path to file
     *
     * @return object WrapperWorkflow
     */
    public function read ($filename)
    {
        $file = new \SplFileObject($filename);
        $reader = new Reader\CsvReader($file);
        $reader->setHeaderRowNumber(0);
        $this->count = $reader->count();

        $this->workflow = new StepAggregator($reader);

        return $this;
    }

    /**
     * Set entity to write
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param string $entity                    Entity name
     *
     * @return object WrapperWorkflow
     */
    public function write(\Doctrine\ORM\EntityManager $em, $entity)
    {
        if ($this->test) {
            $writer = new Writer\CallbackWriter(function () {
            });


        } else {
            $writer = new Writer\DoctrineWriter($em, $entity);
            $writer->disableTruncate();


        }
        $this->workflow=$this->workflow->addWriter($writer);

        return $this;

    }
    /**
     * Set entity for filter
     *
     * @param callable $myfilter
     *
     * @return object WrapperWorkflow
     */
    public function setFilter(callable $myfilter)
    {
        $filter = new Step\FilterStep();

        $this->workflow=$this->workflow->addStep($filter->add($myfilter));

        return $this;

        
    }
    /**
    * Set entity for format
    *
    * @param callable $myconvertor
    *
    * @return object WrapperWorkflow
    */
    public function setFormat(callable $myconvertor)
    {
        $convertor=new Step\ConverterStep();

        $this->workflow=$this->workflow->addStep($convertor->add($myconvertor));

        return $this;
    }
    /**
     * Wrapper over method process class StepAggregator
     *
     * @return object class Result
     */
    public function process()
    {
        $result=$this->workflow->process();

        return $result;
    }
}