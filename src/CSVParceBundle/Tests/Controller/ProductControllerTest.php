<?php

namespace CSVParceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{

    public function testIndexAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
        $this->assertTrue($client->getResponse()->isSuccessful(), '200 OK');
    }


    public function testShowAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/show/2');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
        $this->assertTrue($client->getResponse()->isSuccessful(), '200');

//        $this->assertContains(
//            '{"product":{"id":2,"productName":"testedit","price":10,"stock":12,"description":"description1",
//            "discontinued":1,"discontinuedDate":null}}',
//            $client->getResponse()->getContent()
//        );

    }

    public function testNewAction()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'POST',
            '/product/new',
            array(),
            array(),
            array(
                'CONTENT_TYPE'          => 'application/json',
            ),
            '{"product":{"productName":"testedit","price":10,"stock":12,"description":"description1",
//            "discontinued":1}}'
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
       // $this->assertTrue($client->getResponse()->isRedirect(), '201');


    }



}
