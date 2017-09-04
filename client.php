<?php
/**
 * @author: skilly
 * @date:   2017-09-01 14:59:13
 * @email: 1254109699@qq.com
 */

function clientSend()
{

	$connection = stream_socket_client('tcp://127.0.0.1:3270', $err_no, $err_msg);
	if (!$connection) {
	    throw new Exception("can not connect to $address , $err_no:$err_msg");
	}

	stream_set_blocking($connection, true);
	stream_set_timeout($connection, 10);


	$bin_data = 'tests';
	if (fwrite($connection, $bin_data) !== strlen($bin_data)) {
	    throw new \Exception('Can not send data');
	}

    $ret = fgets($this->connection);

	fclose($connection);
	$connection = null;

}

clientUpload();
function clientUpload()
{
	/** 上传文件客户端 **/
	// 上传地址
	$address = "127.0.0.1:8333";
	// 检查上传文件路径参数
	// if(!isset($argv[1]))
	// {
	//    exit("use php client.php \$file_path\n");
	// }
	// 上传文件路径
	$file_to_transfer = trim('2.png');
	// 上传的文件本地不存在
	if(!is_file($file_to_transfer))
	{
	    exit("$file_to_transfer not exist\n");
	}
	// 建立socket连接
	$client = stream_socket_client($address, $errno, $errmsg);
	if(!$client)
	{
	    exit("$errmsg\n");
	}
	stream_set_blocking($client, 1);
	stream_set_timeout($client, 10);

	//先获取上次传输的位置
	$start = 0;
	$file_name = basename($file_to_transfer);
	$file_size = filesize($file_name);
	$package_data = array(
	    'start' => $start,
	    'file_size' => $file_size,
	    'file_name' => $file_name,
	    'type' => 'length',
	);
	// 协议包 json+回车
	$package = json_encode($package_data)."\n";

	if (fwrite($client, $package) !== strlen($package)) {
	    throw new \Exception('Can not send data');
	}
    $start = (int)fgets($client);
    
	// 文件二进制数据
	// $file_data = file_get_contents($file_to_transfer);
	$file_data = file_get_contents($file_to_transfer, NULL, NULL, $start, $file_size - $start); //40852
	// base64编码
	$file_data = base64_encode($file_data);
	// 数据包
	$package_data = array(
	    'file_size' => $file_size,
	    'file_name' => $file_name,
	    'file_data' => $file_data,
	    'type' => 'upload',
	);
	// 协议包 json+回车
	$package = json_encode($package_data)."\n";
	// 执行上传
	fwrite($client, $package);
	// 打印结果
	echo fread($client, 8192),"\n";
}

function readP($file, $length = 100)
{
	return fread($fp, $length);
}


function clientDownload()
{
	// 上传地址
	$address = "127.0.0.1:8333";
	// 检查上传文件路径参数
	// 上传文件路径
	$file_to_transfer = trim('2.png');
	// 上传的文件本地不存在
	if(!is_file($file_to_transfer))
	{
	    exit("$file_to_transfer not exist\n");
	}
	// 建立socket连接
	$client = stream_socket_client($address, $errno, $errmsg);
	if(!$client)
	{
	    exit("$errmsg\n");
	}
	stream_set_blocking($client, 1);
	// 文件名
	$file_name = basename($file_to_transfer);
	// 文件二进制数据
	$file_data = file_get_contents($file_to_transfer);
	// base64编码
	$file_data = base64_encode($file_data);
	// 数据包
	$package_data = array(
	    'start' => 0,
	    'file_name' => $file_data,
	);
	// 协议包 json+回车
	$package = json_encode($package_data)."\n";
	// 执行上传
	fwrite($client, $package);
	// 打印结果
	echo fread($client, 8192),"\n";
}

