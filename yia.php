<?php

/*
Plugin Name: Yazı İçi Reklam (Advertisment)
Plugin URI: http://www.evrenweb.net/blog/proje/wordpress-icin-yazi-ici-reklam-eklentisi.html
Description: Yazdığımız yazıların içerisine önceden tanımladığımız reklamları basitce eklememizi sağlar. Reklam eklemek için bu eklentiyi editör ile açın, reklamınızı eklentide tanımlayın. Hangi id li reklamı yazıda göstermek istiyorsanız [yia id] şeklinde yazınıza ekleyin. id değerine 0 verirseniz sistem rastgele bir reklam gösterir. <br /><br /> It allows you to place your ad easily into the written articles. To add an advert you need to open the add-on in an editor and define the ad. Whichever ad you want to see in the article place the id like [yia id]. If you place id as 0 than it will display the ads in random order.
Version: 1.0
Author: Evren Bayraktar
Author URI: http://www.evrenweb.net/

Uygulama:

Bu dosyayı wp-content/plugin dosyasına kopyalayın. Wordpress admin panelinde Eklentiler menüsünden
eklentiyi aktifleştirin. Yazılarınızın içerisinde [yia id] şeklinde kullanacağınız etiketteki id 
değerini değiştirmeyi unutmayın. Bu id değerlerini siz bu sayfaya ekleyeceksiniz. #$r[] hazır (Satır 55) olarak koyduğum 
satırlardaki # işaretlerini silip, tırnaklar arasına php ye uyumlu şekilde(*) reklamınızın kodlarını yerleştirin. 
$r ların sırayla artmasına özen gösteriniz. Bundan sonrası ise yazılarınızın içine reklamlarınızı [yia 1] şeklinde 
id leri ile beraber eklemek. [yia 0] yaparsanız sistem rastgele bir reklam gösterecektir.

* Html şeklindeki reklam kodlarınızı www.evrenweb.net/#htmltophp adresinden php ye çevirebilirsiniz.

Trackback olayı Hakan Demiray'ın (dmry.net) benzer yazılar eklentisinden uyarlanmıştır.

*/

if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
	if (!get_option('wp_yazi_ici_adv')) {	
		$trackback_url = (WPLANG=='tr_TR' || WPLANG=='tr') ? 'http://www.evrenweb.net/blog/proje/wordpress-icin-yazi-ici-reklam-eklentisi.html/trackback' : 'http://www.evrenweb.net/blog/proje/wordpress-icin-yazi-ici-reklam-eklentisi.html/trackback';
		
		$trackback_title = (WPLANG=='tr_TR' || WPLANG=='tr') ? 'Eklentinizi yükledim' : 'I installed your plugin';
		
		$trackback_text = (WPLANG=='tr_TR' || WPLANG=='tr') ? "Web siteme \"%s\" eklentinizi yükledim ve denedim" : "I installed and tried your plugin on my site \"%s\"";
		
		$trackback_body = sprintf($trackback_text, get_bloginfo('name'));
		
		tracback($trackback_url, $trackback_title, $trackback_body);	
		
		update_option('wp_yazi_ici_adv', 'evet');
	}
}

define("YIA_REG", "/\[yia ([[:print:]]+)\]/");

function yia_callback($match)
{
$r[1] = '<script type=\"text/javascript\"><!--
google_ad_client = \"pub-6121140592314231\";
/* 468x15, oluşturulma 07.08.2009 */
google_ad_slot = \"6502053825\";
google_ad_width = 468;
google_ad_height = 15;
//-->
</script>
<script type=\"text/javascript\"
src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">
</script>';

$r[2] = '<script type="text/javascript"><!--
google_ad_client = "pub-6121140592314231";
google_ad_slot = "6982717448";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
#$r[3] = '';
#$r[4] = '';
#$r[5] = '';
#$r[6] = '';	
	
	if(isset($r[$match[1]]) || $match[1] == 0 ) {
	if($match[1] == 0) { 
		$reklam = $r[array_rand($r)];
	} else { 
		$reklam = $r[$match[1]];
	}

	return ($reklam);
} else { 
	return false;
	}
}

function yia_plugin($content)
{
	return (preg_replace_callback(YIA_REG, 'yia_callback', $content));
}
echo yia_plugin($a);

function tracback($trackback_url, $title, $excerpt) {
	global $wpdb, $wp_version;

	$title = urlencode($title);
	$excerpt = urlencode($excerpt);
	$blog_name = urlencode(get_settings('blogname'));
	$tb_url = $trackback_url;
	$url = urlencode(get_settings('home'));
	$query_string = "title=$title&url=$url&blog_name=$blog_name&excerpt=$excerpt";
	$trackback_url = parse_url($trackback_url);
	$http_request = 'POST ' . $trackback_url['path'] . ($trackback_url['query'] ? '?'.$trackback_url['query'] : '') . " HTTP/1.0\r\n";
	$http_request .= 'Host: '.$trackback_url['host']."\r\n";
	$http_request .= 'Content-Type: application/x-www-form-urlencoded; charset='.get_settings('blog_charset')."\r\n";
	$http_request .= 'Content-Length: '.strlen($query_string)."\r\n";
	$http_request .= "User-Agent: WordPress/" . $wp_version;
	$http_request .= "\r\n\r\n";
	$http_request .= $query_string;
	if ( '' == $trackback_url['port'] )
		$trackback_url['port'] = 80;
	$fs = @fsockopen($trackback_url['host'], $trackback_url['port'], $errno, $errstr, 4);
	@fputs($fs, $http_request);
	@fclose($fs);
}

add_filter('the_content', 'yia_plugin');


?>