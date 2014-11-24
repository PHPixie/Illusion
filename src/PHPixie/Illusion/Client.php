<?php

namespace PHPixie\Illusion;

class Client
{
    protected $serverInput;
    protected $serverOutput;
    protected $serverStopped= false;
    
    public function __construct($serverInput, $serverOutput)
    {
        $this->serverInput = $serverInput;
        $this->serverOutput = $serverOutput;
    }
    
    public function stopServer()
    {
        $this->serverStopped = true;
        $this->serverInput->write(array('action' => 'stop'));
    }
    
    public function route($path, $response, $method = 'GET')
    {
        $message = array(
            'action'   => 'route',
            'method'   => $method,
            'path'     => $path,
            'response' => $response
        );
            
        $this->serverInput->write($message);
        $messages = $this->serverOutput->read(true);
        return $messages[0]['url'];
    }
    
    public function __destruct()
    {
        if(!$this->serverStopped)
            $this->stopServer();
    }
}