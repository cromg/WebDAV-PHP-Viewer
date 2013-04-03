<?
ob_start();
error_reporting(E_ALL);

require('config.php');
require('vk.php');
require('functions.php');
require('template.php');

$Vk = new Vk(array(
		'appId'	   => $vkapiid,
		'secret'	  => $vksec
));

$cip=($_SERVER['REMOTE_ADDR']==$_SERVER['SERVER_ADDR'])?$_SERVER['HTTP_X_REAL_IP']:$_SERVER['REMOTE_ADDR'];

/**/
if (isset($_REQUEST['token']) || isset($_COOKIE['vktoken']) || (isset($_REQUEST['sec']) && $_REQUEST['sec']==$skey)  || (isset($forever_token) && $forever_token != '') || allowip($cip)){
	$dir=isset($_REQUEST['dir']) ? str_replace('**!*',"'",str_replace('*!**','%26',str_replace(' ','%20',$_REQUEST['dir']))) : '/';
	$ok=1;
	if (isset($_REQUEST['token']) || isset($_COOKIE['vktoken'])){
		/* security check */
		if (isset($_REQUEST['token'])){
			$t=$Vk->getUserInGroup($vk_group,false, $_REQUEST['token']);
		} else {
			$t=$Vk->getUserInGroup($vk_group,false, $_COOKIE['vktoken']);
		}
		if ($t==1){
			$ok=1;
		} else {
			$ok=0;
		}
	}
	if ($ok){
		$size=isset($_REQUEST['size']) ? $_REQUEST['size'] : 0;
		if (isset($_REQUEST['down'])){
			$ctype=isset($_REQUEST['type']) ? ($_REQUEST['type']=='html') ? 'html' : 'none' : false;
			$name=explode('/',$dir);
			$name=urldecode($name[count($name)-1]);
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public",FALSE);
			if ($ctype=='html'){
				header('Content-type: text/html');
			} else if ($ctype=='none'){
			} else {
				header("Content-Description: File Transfer");
				header("Content-type: application/octet-stream");
				 if (isset($_SERVER['HTTP_USER_AGENT']) && 
				  (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
				header('Content-Type: application/force-download'); //IE HEADER
				header("Accept-Ranges: bytes");
				header("Content-Disposition: attachment; filename=\"" . $name . "\";");
				header("Content-Transfer-Encoding: binary");
			}
			header("Content-Length: $size");
			echo davget($dir);
		} else if (isset($_REQUEST['image'])){
			$imgsrc="?down=1&size=$size&dir=$dir";
			$txsrc='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?sec=$skey&down=1&size=$size&dir=$dir";

			$sharelnk=urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?image=1&size=$size&dir=$dir");
			$content="<div class='well'><div style='text-align:center'><a href='$imgsrc'><img src='$imgsrc'></a></div></div>";
			
			tmpl_preview($dir,$size,$content,$sharelnk,$txsrc);
		} else if (isset($_REQUEST['swf'])){
			$imgsrc="?down=1&type=1&size=$size&dir=$dir";
			$txsrc='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?sec=$skey&down=1&type=1&size=$size&dir=$dir";

			$sharelnk=urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?image=1&size=$size&dir=$dir");
			
			$content="<div class='well'><div style='text-align:center'><object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0' width='100%' height='400' align='middle'><param name='allowScriptAccess' value='sameDomain' /><param name='allowFullScreen' value='true' /><param name='movie' value='$imgsrc' /><param name='loop' value='true' /><param name='quality' value='high' /><param name='bgcolor' value='#ffffff' /><embed src='$imgsrc' loop='true' quality='high' bgcolor='#ffffff' width='100%' height='400' align='middle' allowScriptAccess='sameDomain' allowFullScreen='true' type='application/x-shockwave-flash' pluginspage='http://www.adobe.com/go/getflashplayer_ru' /></object></div></div>";
			
			tmpl_preview($dir,$size,$content,$sharelnk,$txsrc);
		} else if (isset($_REQUEST['pltext'])){
			$txsrc='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?sec=$skey&down=1&size=$size&dir=$dir";
			
			$sharelnk=urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?pltext=1&size=$size&dir=$dir");
			$content="<div class='well'><pre>" . iconv('windows-1251','UTF-8//TRANSLIT//IGNORE',str_replace("\n","<br>",str_replace("\r","<br>",str_replace("\r\n","<br>",htmlspecialchars(file_get_contents($txsrc)))))) . "</pre></div>";
			
			tmpl_preview($dir,$size,$content,$sharelnk,$txsrc);
		} else if (isset($_REQUEST['gdocs'])){
			$txsrc='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?sec=$skey&down=1&size=$size&dir=$dir";
			$txsre=urlencode($txsrc);
			
			$sharelnk=urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?gdocs=1&size=$size&dir=$dir");
			$content="<div class='well'><iframe src='http://docs.google.com/viewer?url=$txsre&embedded=true' width='100%' height='780' style='border: none;'></iframe></div>";
			
			tmpl_preview($dir,$size,$content,$sharelnk,$txsrc);
		} else if (isset($_REQUEST['frame'])){
			$txsrc='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?sec=$skey&down=1&type=html&size=$size&dir=$dir";
			
			$sharelnk=urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?frame=1&size=$size&dir=$dir");
			$content="<div class='well'><iframe src='$txsrc' width='100%' height='780' style='border: none;'></iframe></div>";
			
			tmpl_preview($dir,$size,$content,$sharelnk,$txsrc);
		} else if (isset($_REQUEST['preview'])){
			$txsrc='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?sec=$skey&down=1&size=$size&dir=$dir";

			$sharelnk=urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?preview=1&size=$size&dir=$dir");
			$content="<div class='well'><div class='alert alert-info alert-block'><h4>Предпросмотр невозможен для файлов данного типа.</h4>Вы можете скачать этот файл, нажав на кнопку \"Скачать\".</div></div>";
			tmpl_preview($dir,$size,$content,$sharelnk,$txsrc);
		} else if (isset($_REQUEST['_close_greeting'])){
			setcookie('greeting_closed',1,time()+365*24*60*60,'/');
			//$redirto=$_REQUEST['redirto'];
			$ref=(isset($_SERVER['HTTP_REFERER']) && trim($_SERVER['HTTP_REFERER']!='')) ? $_SERVER['HTTP_REFERER'] : '/disk/';
			header("Location: $ref");
		}	else if (isset($_REQUEST['logout'])){
			header('Content-type: text/html; charset=utf-8');
			setcookie('vktoken','',time()-10000,'/');
			setcookie('vkuser','',time()-10000,'/');
			setcookie('vk_app_'.$vkapiid,'',time()-10000,'/');
			header('Content-type: text/html; charset=utf-8');
			echo 'Выход успешно произведён!<br>Не забудьте произвести выход из Вашей учётной записи В Контакте!';
		} else {
			header('Content-type: text/html; charset=utf-8');
			echo $head;
			$sharelnk=urlencode('http://'.$_SERVER['HTTP_HOST']."/disk$dir");
			echo toolbar($sharelnk,false);
			echo '<div class="container" style="margin-top:50px"><div class="row"><div class="offset2 span8">';
			if (isset($_COOKIE['greeting_closed']) && $_COOKIE['greeting_closed']==1){} else { echo $greeting; }
			echo navbar($dir);
			
			$cd=explode('/',str_replace('%20',' ',$dir));
			$cd=array_slice($cd,1,-1);
			$i=0;
			$upd='/';
			foreach($cd as $d){
				if ($i==count($cd)-1){
				} else {
					$upd.=$d.'/';
					$shup='/disk'.$upd;
				}
				$i++;
			}
			echo "</ul>";
			$curd=isset($d) ? $d : '/';
			$props=propfind($dir);
			$xml=XMLtoArray($props);
			if (!$xml || isset($xml['D:MULTISTATUS']['D:RESPONSE']['D:HREF']) || count($xml['D:MULTISTATUS']['D:RESPONSE'])==0){
				echo "	<ul class='nav nav-tabs nav-stacked'>
							<li><a href='$shup'><i class='icon-arrow-up'></i> Вверх</a></li>
							<li class='active'><a><i class='icon-warning-sign'></i> Папка пустая</a></li>
						</ul>
						";
			} else {
				$fls=$xml['D:MULTISTATUS']['D:RESPONSE'];
				echo "<ul class='nav nav-tabs nav-stacked'>";
				$files=array();
				$i=0;
				foreach($fls as $b){
					$href=$b['D:HREF'];
					$show=urldecode($b['D:PROPSTAT'][$i]['D:PROP'][$i]['D:DISPLAYNAME']);
					$show=($show==$curd && $i==0) ? '*dirup*' : $show;
					$href=($show=='*dirup*') ? $upd : $href;
					$href=str_replace("'",'**!*',$href);
					$href=str_replace("&",'*!**',$href);
					$href=str_replace("%26",'*!**',$href);
					$size=$b['D:PROPSTAT'][$i]['D:PROP'][$i]['D:GETCONTENTLENGTH'];
					$type=($size=='0') ? 'folder' : 'file';
					$files=array_merge($files,array($i=>array('href'=>$href,'show'=>$show,'size'=>$size,'type'=>$type)));
					$i++;
				}
				foreach($files as $b){
					$href=$b['href'];
					$show=$b['show'];
					$size=$b['size'];
					$type=$b['type'];
					
					if ($show=='disk'){
						$link='';
					} else if ($show=='*dirup*'){
						$link="<li><a href='/disk$href'><i class='icon-arrow-up'></i> Вверх</a></li>";
					} else if ($type=='folder'){
						$link="<li><a href='/disk$href'><i class='icon-folder-open'></i> $show</a></li>";
					} else {
						$link='';
					}
					echo $link;
					$i++;
				}
				
				foreach($files as $b){
					$href=$b['href'];
					$show=$b['show'];
					$size=$b['size'];
					$type=$b['type'];
					
					if ($show=='disk'){
						$link='';
					} else if ($show=='*dirup*'){
						$link='';
					} else if ($type=='folder'){
						$link='';
					} else if ($type=='file'){
						$stype=stype($type,$show);
						$sact=stype($type,$show,2);
						$sizen=format_bytes($size);
						$link="<li><a href='/disk$href?$sact=1&size=$size'><i class='icon-file'></i> $show<br><small class='muted'>$stype, $sizen</small></a></li>";
					}
					echo $link;
					$i++;
				}
				echo "</ul>";
			}
			echo "</div></div></div>$footern</body></html>";
		}
	} else {
		echo "Access denied!";
	}
} else if (isset($_REQUEST['code'])){
	header('Content-type: text/html; charset=utf-8');
	if (allowip($cip)){
		$t=1;
	} else {
		$user_id = $Vk->getUser();
		$user_token = $Vk->getLoginToken();
		echo "ID Пользователя: '$user_id'<br>Токен: '$user_token'<br><a href='/'>Назад</a><br>";
		$t=$Vk->getUserInGroup($vk_group,false);
	}
	
	if ($t==1){
		setcookie('vktoken',$user_token,time()+1800,'/');
		setcookie('vkuser',$user_id,time()+1800,'/');
		$redirto=isset($_SESSION['redirto']) ? $_SESSION['redirto'] : '';
		header("Location: ?login=1&token=$user_token&user_id=$user_id$redirto");
	} else {
		die('Доступ запрещён!');
	}
} else if (isset($_REQUEST['logout'])){
	header('Content-type: text/html; charset=utf-8');
	echo 'Выход успешно произведён!<br>Не забудьте произвести выход из Вашей учётной записи В Контакте!';
} else {
	if (allowip($cip)){
		$_SESSION['redirto']=(isset($_SERVER['QUERY_STRING'])) ? '&'.$_SERVER['QUERY_STRING'] : '';
		header('Location: ?login=1&code=-100');
	} else {
		$t=$Vk->getLoginUrl();
		$_SESSION['redirto']=(isset($_SERVER['QUERY_STRING'])) ? '&'.$_SERVER['QUERY_STRING'] : '';
		header('Content-type: text/html; charset=utf-8');
		header('Location: '.$t);
		echo '<a href="'.$t.'">Войти через VK</a>';
	}
}
/**/

ob_end_flush();
?>
