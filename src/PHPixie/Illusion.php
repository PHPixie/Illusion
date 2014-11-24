<?php

namespace PHPixie;

class Illusion
{
    protected $client;
    
    public function setup($port, $host = 'localhost')
    {
        $input = $this->getSocketPair();
        $output = $this->getSocketPair();
        
        $pid = pcntl_fork();
        if($pid == 0) {
            list($input, $output) = $this->getSockets($input, $output, 0);
            $server = $this->server($port, $host, $input, $output);
            $server->run();
        
        }else{
            list($input, $output) = $this->getSockets($input, $output, 1);
            $this->client = $this->buildClient($input, $output);
            return $this->client;
        }
    }
    
    public function client()
    {
        return $this->client;
    }
    
    protected function getSockets($input, $output, $key)
    {
        $close = $key == 0 ? 1 : 0;
        socket_close($input[$close]);
        socket_close($output[$close]);
        
        $input = $this->socket($input[$key]);
        $output = $this->socket($output[$key]);
        
        return array($input, $output);
    }
    
    protected function getSocketPair()
    {
        $domain = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? AF_INET : AF_UNIX);
        socket_create_pair($domain, SOCK_STREAM, 0, $sockets);
        return $sockets;
    }
        
    protected function socket($socket)
    {
        return new \PHPixie\Illusion\Socket($socket);
    }
    
    protected function server($port, $host, $input, $output)
    {
        return new \PHPixie\Illusion\Server($port, $host, $input, $output);
    }
    
    protected function buildClient($input, $output)
    {
        return new \PHPixie\Illusion\Client($input, $output);
    }
    
    public static function start($port, $host = 'localhost')
    {
        $illusion = new static;
        $client =  $illusion->setup($port, $host);
        return $client;
    }
}