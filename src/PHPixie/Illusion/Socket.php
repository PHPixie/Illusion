<?php

namespace PHPixie\Illusion;

class Socket
{
    protected $socket;
    
    public function __construct($socket)
    {
        $this->socket = $socket;    
    }
    
    public function read($wait = true)
    {
        $body = '';
        while(true) {
            
            
            while(socket_recv($this->socket, $buffer, 1024, MSG_DONTWAIT) > 0) {
                $body .= $buffer;
            }
            
            if($body !== '' || !$wait)
                break;
        }
        
        $body = trim($body);
        
        if(empty($body))
            return array();
        
        $messages = array();
        
        foreach(explode("\n", $body) as $data) {
            $messages[] = json_decode($data, true);
        }
        
        return $messages;
    }
    
    public function write($array)
    {
        $body = json_encode($array)."\n";
        socket_write($this->socket, $body, strlen($body));
    }
    
}