<?php

$fp = fopen('2.png' ,'r') or die("文件打开失败");
 
plog(ftell($fp));         //输出刚打开文件的指针默认位置，指针在文件的开头位置为0
plog(fread($fp, 10));        //读取文件中的前10个字符输出，指针位置发生了变化
plog(ftell($fp));           //读取文件的前10个字符之后，指针移动的位置在第10个字节处
 
fseek($fp, 100,SEEK_CUR);       //又将指针移动到倒数10个字节位置处
plog(ftell($fp)); //文件的位置在110个字节处
plog(fread($fp,10));     //读取110到120字节数位置的字符串，读取后指针的位置为120
 
fseek($fp,-10,SEEK_END);         //又将指针移动到倒数10个字节位置处
plog(fread($fp, 10));        //输出文件中最后10个字符
 
rewind($fp);          //又移动文件指针到文件的开头
plog(ftell($fp));           //指针在文件的开头位置，输出0
 
fclose($fp);

function plog($data)
{
	echo $data . PHP_EOL;
	// file_put_contents('log.txt', $data, FILE_APPEND);
}