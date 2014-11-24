<?php

namespace PHPixie\Illusion;

abstract class Test extends \PHPUnit_Framework_TestCase
{
    protected function getSockets()
    {
        $domain = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? AF_INET : AF_UNIX);
        socket_create_pair($domain, SOCK_STREAM, 0, $sockets);
        return $sockets;
    }
}