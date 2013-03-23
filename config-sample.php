<?
$useragent="Mozilla/5.0 (Windows NT 6.1; rv:14.0) Gecko/20100101 Firefox/14.0";
$base='https://webdav.yandex.ru'; // WebDAV Path
$creds='username:password'; // WebDAV Credentials
$skey=md5('abcdefghijklmnopqrstuvwxyz'.date("dmY")); // secret key for anti-leeching

$vkapiid=''; // VK API App ID
$vksec=''; // VK API App Secret

$vk_group='12345'; // VK Group which have an access

$allowed_ip=array(); // IP's with unlimited access

$shortener='//domain.com/redirect?url='; // URL Shortener
?>