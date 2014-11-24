<?php

namespace PHPixie\Illusion\Test;

class IllusionTest extends \PHPixie\Illusion\Test
{
    protected $client;
    
    public function setUp()
    {
        $this->client = \PHPixie\Illusion::start(4747);
    }
    
    public function tearDown()
    {
        $this->client->stopServer();
        sleep(2);
    }
    
    public function testServer()
    {
        $url = $this->client->route('/hello', 'world');
        $this->assertEquals('world', file_get_contents($url));
        
        $url = $this->client->route('/magical/pixie', 'fairy');
        $this->assertEquals('fairy', file_get_contents($url));
    }
}