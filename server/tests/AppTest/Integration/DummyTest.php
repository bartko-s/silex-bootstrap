<?php
namespace AppTest\Integration;

use AppTest\AppWebTestCase;

class DummyTest
    extends AppWebTestCase
{
    public function testSomething() {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/example/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('h1:contains("Silex Bootstrap")'));
    }
}