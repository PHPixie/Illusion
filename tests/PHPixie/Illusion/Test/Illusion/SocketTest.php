<?php

namespace PHPixie\Illusion\Test\Illusion;

class SocketTest extends \PHPixie\Illusion\Test
{
    protected $sockets;
    
    public function setUp()
    {
        $this->sockets = $this->getSockets();
    }
    
    public function testMessages()
    {
        $data = array('test' => 5);
        $pid = pcntl_fork();
        
        if($pid === 0) {
            socket_close($this->sockets[0]);
            $socket = new \PHPixie\Illusion\Socket($this->sockets[1]);
            $socket->write($data);
            die;
            
        }else{
            socket_close($this->sockets[1]);
            $socket = new \PHPixie\Illusion\Socket($this->sockets[0]);
            $this->assertEquals(array($data), $socket->read());
        }
    }
}