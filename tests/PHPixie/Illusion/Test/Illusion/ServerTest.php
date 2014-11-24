<?php

namespace PHPixie\Illusion\Test\Illusion;

class ServerTest extends \PHPixie\Illusion\Test
{
    protected $input;
    protected $output;
    
    public function setUp()
    {
        $this->input = $this->getSockets();
        $this->output = $this->getSockets();
    }
    
    public function testServer()
    {
        $pid = pcntl_fork();
        if($pid == 0) {
            socket_close($this->input[0]);
            socket_close($this->output[0]);
            
            $input = new \PHPixie\Illusion\Socket($this->input[1]);
            $output = new \PHPixie\Illusion\Socket($this->output[1]);
            
            
            $server = new \PHPixie\Illusion\Server(4747, 'localhost', $input, $output); 
            $server->run();
            
        }else{
            socket_close($this->input[1]);
            socket_close($this->output[1]);
            
            $input = new \PHPixie\Illusion\Socket($this->input[0]);
            $output = new \PHPixie\Illusion\Socket($this->output[0]);
            
            $message = array(
                'action' => 'route',
                'method' => 'GET',
                'headers' => array('Content-Type: text/plain'),
                'path'   => '/hello',
                'response' => 'world'
            );
            
            $input->write($message);
                        
            $url = 'http://localhost:4747/hello';
            $response = $output->read(true);
            $this->assertEquals(array(array('url' => $url)), $response);
            $contents = file_get_contents($url);
            $this->assertEquals('world', $contents);
            $input->write(array('action' => 'stop'));
            sleep(2);
        }
    }
}