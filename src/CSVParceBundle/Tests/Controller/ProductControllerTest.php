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

        $crawler = $client->request('GET', '/product/show/5');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
        $this->assertTrue($client->getResponse()->isSuccessful(), '200');

//        $this->assertContains(
//                            '{
//                  "product1": {
//                    "id": 5,
//                    "productName": "ZATTARA",
//                    "price": 10.1,
//                    "stock": 5,
//                    "description": "test1",
//                    "discontinued": 1,
//                    "discontinuedDate": null
//                  }
//                }',
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
            "discontinued":1}}'
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
        $this->assertEquals(201, $client->getResponse()->getStatusCode());


    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'PUT',
            '/product/edit/3',
            array(),
            array(),
            array(
                'CONTENT_TYPE'          => 'application/json',
            ),
            '{"product":{"productName":"ZATTARA1","price":10.1,"stock":5,"description":"test1","discontinued":1}}'
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

    }


}
