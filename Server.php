<?php

use \Workerman\Worker;
use \Workerman\Connection\AsyncTcpConnection;

// Autoload.
require_once __DIR__ . '/workerman-for-win/Autoloader.php';

$worker = new Worker('TextTransfer://0.0.0.0:8333');
// 保存文件到tmp下
$worker->onMessage = function($connection, $data)
{
	$path = 'E:/proxy/tmp/';
	$file_name = $data['file_name'];
	$file_size = $data['file_size'];
    $save_path = $path . $file_name;
	$length_file = $save_path . '.tmp';
	if (isset($data['type']) && $data['type'] == 'length') {
		$start = 0;
		if (file_exists($length_file)) {
			$start = file_get_contents($length_file);
		}
	    $connection->send($start);
	} else {
		
	    file_put_contents($save_path, $data['file_data'], FILE_APPEND);
	    clearstatcache();
	    $rel_file_size = filesize($save_path);
    	plog("file_size: {$file_size}, rel_file_size: {$rel_file_size}, length_file:{$length_file}");
	    if ($file_size > $rel_file_size) {
	    	file_put_contents($length_file, $rel_file_size);
	    	$connection->send("upload error. file_size $rel_file_size");
	    } else {
	    	file_exists($length_file) && unlink($length_file);
	    	$connection->send("upload success. save path $save_path");
	    }
	}
};

Worker::runAll();

function plog($data)
{
	echo $data . PHP_EOL;
	// file_put_contents('log.txt', $data, FILE_APPEND);
}