<?php

namespace PHPixie\Illusion\Server;

class Driver extends \StupidHttp_Driver
{
    public function setup($options)
    {
        $this->options = $options;
        $this->connections = array();
        $this->handler->setLog($this->log);
    }
    
    public function cycle()
    {
        $this->runOnce();
    }
}