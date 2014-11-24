<?php

namespace PHPixie\Illusion\Server;

class Webserver extends \StupidHttp_WebServer
{
    public function __construct($documentRoot = null, $port = 8080, $address = 'localhost')
    {
        $log = null;
        $vfs = new \StupidHttp_VirtualFileSystem($documentRoot);
        $handler = new \StupidHttp_SocketNetworkHandler($address, $port);
        $this->driver = new Driver($this, $vfs, $handler, $log);
    }
    public function setup()
    {
        $options =  array(
                'list_directories' => true,
                'list_root_directory' => false, 
                'run_browser' => false,
                'keep_alive' => false,
                'timeout' => 4,
                'poll_interval' => 1,
                'show_banner' => true,
                'name' => null
            );
        
        $this->driver->register();
        $this->driver->setup($options);
    }
    
    public function runOnce()
    {
        try
        {
            $this->driver->cycle();
        }
        catch (Exception $e)
        {
            $this->getLog()->critical($e->getMessage());
            $this->getLog()->critical("The server will now shut down!");
        }
    }
}