<?
function allowip($ip){
	global $allowed_ip;
	$allow=false;
	foreach ($allowed_ip as $a){
		if ($a==$ip){
			$allow=true;
		}
	}
	return $allow;
}

function get($url,$ref=false){
	global $useragent;
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	if ($ref){
		curl_setopt($ch, CURLOPT_REFERER, $ref);
	}
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}

function post($url,$ref,$params){
	global $useragent;
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_REFERER, $ref);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}

function davget($url){
	global $useragent, $base, $creds;
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $base.$url);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $creds); 
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}

function davput($url,$file,$size){
	global $useragent, $base, $creds;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base.$url);
	curl_setopt($ch, CURLOPT_FAILONERROR, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_PUT, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT','Accept: */*','Expect: 100-continue','Content-Type: application/binary'));
	curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_INFILE, $file);
	curl_setopt($ch, CURLOPT_INFILESIZE, $size);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $creds); 
	$res = curl_exec($ch);
	if(curl_errno($ch)){ $ret='Curl error: ' . curl_error($ch); } else {$ret=false;}
	curl_close($ch);
	if(!$ret){return $res;} else {return $ret;}
}
function davmkcol($url){
	global $useragent, $base, $creds;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base.$url);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "MKCOL");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: MKCOL','Accept: */*','Expect: 100-continue','Content-Type: application/binary'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $creds); 
	$res = curl_exec($ch);
	if(curl_errno($ch)){ $ret='Curl error: ' . curl_error($ch); } else {$ret=false;}
	curl_close($ch);
	if(!$ret){return $res;} else {return $ret;}
}

function propfind($url){
	global $useragent, $base, $creds;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $base.$url);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PROPFIND");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PROPFIND','Accept: */*','Depth: 1'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $creds); 
	$res = curl_exec($ch);
	if(curl_errno($ch)){ $ret='Curl error: ' . curl_errno($ch). ' - '. curl_error($ch); } else {$ret=false;}
	curl_close($ch);
	if(!$ret){return $res;} else {return $ret;}
}

function XMLtoArray($XML)
{
    $xml_parser = xml_parser_create();
    xml_parse_into_struct($xml_parser, $XML, $vals);
    xml_parser_free($xml_parser);
    $_tmp='';
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_level!=1 && $x_type == 'close') {
            if (isset($multi_key[$x_tag][$x_level]))
                $multi_key[$x_tag][$x_level]=1;
            else
                $multi_key[$x_tag][$x_level]=0;
        }
        if ($x_level!=1 && $x_type == 'complete') {
            if ($_tmp==$x_tag)
                $multi_key[$x_tag][$x_level]=1;
            $_tmp=$x_tag;
        }
    }
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_type == 'open')
            $level[$x_level] = $x_tag;
        $start_level = 1;
		//$xml_array=NULL;
        $php_stmt = '$xml_array';
        if ($x_type=='close' && $x_level!=1)
            $multi_key[$x_tag][$x_level]++;
        while ($start_level < $x_level) {
            $php_stmt .= '[$level['.$start_level.']]';
            if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
            $start_level++;
        }
        $add='';
        if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
            if (!isset($multi_key2[$x_tag][$x_level]))
                $multi_key2[$x_tag][$x_level]=0;
            else
                $multi_key2[$x_tag][$x_level]++;
            $add='['.$multi_key2[$x_tag][$x_level].']';
        }
        if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
            if ($x_type == 'open')
                $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
            else
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
            eval($php_stmt_main);
        }
        if (array_key_exists('attributes', $xml_elem)) {
            if (isset($xml_elem['value'])) {
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                eval($php_stmt_main);
            }
            foreach ($xml_elem['attributes'] as $key=>$value) {
                $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                eval($php_stmt_att);
            }
        }
    }
	return @is_null($xml_array) ? false : $xml_array;
}

function format_bytes($a_bytes)
{
    if ($a_bytes < 1024) {
        return $a_bytes .' Б';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024, 2) .' КБ';
    } elseif ($a_bytes < 1073741824) {
        return round($a_bytes / 1048576, 2) . ' МБ';
    } elseif ($a_bytes < 1099511627776) {
        return round($a_bytes / 1073741824, 2) . ' ГБ';
    } else{
        return round($a_bytes / 1099511627776, 2) .' ТБ';
    }
}

function toolbar($sharelnk,$downlnk){
	global $shortener;
	$tb="<div class='navbar navbar-fixed-top'><div class='navbar-inner'><div class='container'>
		<a class='brand' href='/disk/'>Диск</a>
		<ul class='nav'>
			<li><a href='//helpcast.ru/'>Блог</a></li>
			<li><a href='//vk.com/club46640318'>Группа В Контакте</a></li>
			<li><a href='$shortener$sharelnk'>Короткая ссылка</a></li>";
	if ($downlnk){
			$tb.="<li><button onClick=\"location.href='$downlnk'\" class='btn btn-success'><i class='icon-arrow-down'></i> Скачать</button></li>";
	}
	$tb.="
		</ul>
		<ul class='nav pull-right'>
			<li><a href='/?logout' class='pull-right'>Выйти</a></li>
		</ul>
	</div></div></div>";
	return $tb;
}

function navbar($dir){
	$cd=explode('/',str_replace('%20',' ',$dir));
	$cd=array_slice($cd,1);
	$nav="<ul class='breadcrumb'><li><a href='/disk/'>Диск</a> <span class='divider'>/</span></li>";
	$i=0;
	$upd='/';
	foreach($cd as $d){
		if ($i==count($cd)-1){
			$nav.="<li class='active'>$d</li>";
		} else {
			$upd.=$d.'/';
			$shup='/disk'.$upd;
			$nav.="<li><a href='$shup'>$d</a> <span class='divider'>/</span></li>";
		}
		$i++;
	}
	$nav.="</ul>";
	return $nav;
}

function tmpl_preview($dir,$size,$content,$sharelnk,$downlnk){
	global $head, $footerp, $greeting;
	header('Content-type: text/html; charset=utf-8');
	echo $head;
	echo toolbar($sharelnk,$downlnk);
	echo "<div class='container' style='margin-top:50px'><div class='row'><div class='offset1 span10'>";
	if (isset($_COOKIE['greeting_closed']) && $_COOKIE['greeting_closed']==1){} else { echo $greeting; }
	echo navbar($dir);
	echo $content;
	echo '</div></div></div>' . $footerp .'</body></html>';
}

function stype($type,$name,$answer=1){
	$s='Неизвестный';
	$t='preview';
	if ((isset($name)) && ($name!='') && ($name!='*dirup*')){
		$ext=explode('.',$name);
		$ext=$ext[count($ext)-1];
		$ext=strtolower($ext);
		switch($ext){
			case 'xbmp':
			case 'bmp': $s='Изображение BMP';$t='image'; break;
			
			case 'gif': $s='Изображение GIF';$t='image'; break;
			
			case 'png': $s='Изображение PNG';$t='image'; break;
			
			case 'jpg':
			case 'jpe':
			case 'jpeg': $s='Изображение JPEG';$t='image'; break;
			
			case 'tif':
			case 'tiff': $s='Изображение TIFF';$t='image'; break;
			
			case 'djvu': $s='Документ DJVU';$t='preview'; break;
			
			case 'pdf': $s='Документ PDF';$t='gdocs'; break;
			
			case 'xls':
			case 'xlsx': $s='Документ MS Excel';$t='gdocs'; break;
			
			case 'doc':
			case 'docx': $s='Документ MS Word';$t='gdocs'; break;
			
			case 'odf': $s='Open Document Format';$t='gdocs'; break;
			
			case 'ppt':
			case 'pptx': $s='Презентация MS Power Point';$t='gdocs'; break;
			
			case '7z':
			case '7zip': $s='Архив 7Z';$t='preview'; break;
			
			case 'zip': $s='Архив ZIP';$t='preview'; break;
			
			case 'rar': $s='Архив RAR';$t='preview'; break;
			
			case 'txt':
			case 'ini':
			case 'cfg':
			case 'pas':
			case 'c':
			case 'h':
			case 'cpp':
			case 'php':
			case 'md':
			case 'css': $s='Текстовый документ';$t='pltext'; break;

			case 'xmcd': $s='Документ Mathcad';$t='preview'; break;
			case 'accdb': $s='База данных Microsoft Access';$t='preview'; break;
			case 'xml': $s='XML документ';$t='pltext'; break;
			case 'htm':
			case 'html': $s='HTML документ';$t='frame'; break;
			case 'css': $s='Таблица стилей';$t='pltext'; break;
			case 'sql': $s='Запрос SQL';$t='pltext'; break;
			case 'ini': 
			case 'cfg': $s='Файл клнфигурации';$t='pltext'; break;
			
			case 'fla': $s='Проект Flash';$t='preview'; break;
			case 'swf': $s='Файл Adobe Flash';$t='preview'; break;
			case 'psd': $s='Документ Adobe Photoshop';$t='gdocs'; break;
			case 'ai':  $s='Документ Adobe Illustrator';$t='preview'; break;
			default: $s='Неизвестный';$t='preview';
		}
	}
	if ($answer==1){ return $s; } else { return $t; }
}
?>