<?php

namespace PHPixie\Illusion;

class Server
{
    protected $port;
    protected $host;
    protected $webserver;
    protected $input;
    protected $output;
    
    public function __construct($port, $host, $input, $output)
    {
        $this->port = $port;
        $this->host = $host;
        $this->input = $input;
        $this->output = $output;
        $this->webserver = new \PHPixie\Illusion\Server\Webserver(null, $port, $host);
    }
    
    public function run()
    {
        $this->webserver->setup();
        while(true)
            $this->cycle();
    }
    
    protected function cycle()
    { 
        $messages = $this->input->read(false);
        
        foreach($messages as $message) {
            $this->handleMessage($message);
        }
        
        $this->webserver->runOnce();
    }
    
    protected function handleMessage($message)
    {
        if($message['action'] == 'stop') {
            die;
        }
        
        if($message['action'] == 'route') {
            $this->webserver
                    ->on($message['method'], $message['path'])
                    ->call(function ($r) use($message){
                        foreach($message['headers'] as $header) {
                            try {
                                header($header);
                            }catch(\Exception $e){
                            
                            }
                        }
                        echo $message['response'];
                    });
            
            $this->output->write(array('url' => $this->buildUrl($message['path'])));
        }
    }
    
    protected function buildUrl($path)
    {
        return 'http://'.$this->host.':'.$this->port.$path;
    }
}