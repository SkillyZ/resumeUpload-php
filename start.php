<?php 
/**
 * This file is part of php-http-proxy.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
use \Workerman\Worker;
use \Workerman\Connection\AsyncTcpConnection;

// Autoload.
require_once __DIR__ . '/workerman-for-win/Autoloader.php';

// Create a TCP worker.
$worker = new Worker('tcp://0.0.0.0:8880');
// 6 processes
$worker->count = 6;
// Worker name.
$worker->name = 'php-http-proxy';

// Emitted when data received from client.
$worker->onMessage = function($connection, $buffer)
{
    // Parse http header.
    
    $connection->send("HTTP/1.1 200 Connection Established\r\n\r\n");
};

// Run.
Worker::runAll();