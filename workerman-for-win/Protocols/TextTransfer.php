<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Workerman\Protocols;

class TextTransfer
{
    public static function input($recv_buffer)
    {
        $recv_len = strlen($recv_buffer);
        if($recv_buffer[$recv_len-1] !== "\n")
        {
            return 0;
        }
        return strlen($recv_buffer);
    }

    public static function decode($recv_buffer)
    {
        // 解包
        $package_data = json_decode(trim($recv_buffer), true);
        // 取出文件名
        $file_name = $package_data['file_name'];
        $file_size = $package_data['file_size'];
        $type = $package_data['type'];
        $start = isset($package_data['start']) ? $package_data['start'] : 0;
        // 取出base64_encode后的文件数据
        $file_data = isset($package_data['file_data']) ? $package_data['file_data'] : 0;
        // base64_decode还原回原来的二进制文件数据
        $file_data = $file_data !== 0 ? base64_decode($file_data) : $file_data;
        // 返回数据
        return array(
             'start' => $start,
             'file_size' => $file_size,
             'file_name' => $file_name,
             'file_data' => $file_data,
             'type' => $type,
         );
    }

    public static function encode($data)
    {
        // 可以根据自己的需要编码发送给客户端的数据，这里只是当做文本原样返回
        return $data . "\n";
    }
}