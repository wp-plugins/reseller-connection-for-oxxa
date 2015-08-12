<?php defined('ABSPATH') or die("No script kiddies please!");

function rcfo_login_check(){

	if (is_user_logged_in() && get_user_meta(get_current_user_id(), "rcfo_nh_oxxaid", "true" ) != "") {
		return 'true';
	}else{ ?><script type="text/javascript"> 
		jQuery(document).ready(function() {
			 jQuery('#rcfo-nh-login-div1').load('<?php echo wp_login_url( get_permalink() ); ?> #loginform'); 
			 jQuery('#rcfo-nh-login-div2').load('<?php echo wp_login_url( get_permalink() ); ?> #nav'); 
		}); </script><div id="rcfo-nh-login-div1"></div><div id="rcfo-nh-login-div2"></div><?php
	}
}

function rcfo_owner_check($sld,$tld,$owner){
	$url = "http://api.oxxa.com/command.php";
	$url .= "?apiuser=".get_option('rcfo_nh_account');
	$url .= "&apipassword=MD5".get_option('rcfo_nh_key');
	$url .= "&command=domain_inf&sld=".$item."&sld=".$type;
	$xml = simplexml_load_file($url);
		if($owner == $xml->order[0]->details[0]->identity-registrant){
			return "TRUE";
		}else{ return "FALSE"; }
}

function rcfo_renew($domein,$value){
	$domein = explode(".", $domein);
	$url = "http://api.oxxa.com/command.php";
	$url .= "?apiuser=Ox14514";
	$url .= "&apipassword=MD5126852d17d4aab35057a74bd51b089a2";
	$url .= "&command=autorenew&autorenew=".$value."&sld=".$domein[0]."&tld=".$domein[1];
	$xml = simplexml_load_file($url);
	return $xml->order->order_complete[0];
}

function rcfo_domain_check($domein){
	$domein = explode(".", $domein);
	$url = "http://api.oxxa.com/command.php";
	$url .= "?apiuser=Ox14514";
	$url .= "&apipassword=MD5126852d17d4aab35057a74bd51b089a2";
	$url .= "&command=domain_check&tld=".$domein[1]."&sld=".$domein[0];
	$xml = simplexml_load_file($url);
		if($xml->order->status_description[0] == "Domeinnaam is vrij voor registratie"){
			return "vrij";
		}elseif($xml->order->status_description[0] == "Domeinnaam is bezet."){
			return "bezet";
		}elseif($xml->order->status_description[0] == "Deze extensie is niet toegestaan voor deze gebruiker"){
			return "error";
		}
}

function rcfo_calc_price($price){
	$url = "http://api.oxxa.com/command.php";
	$url .= "?apiuser=".get_option('rcfo_nh_account');
	$url .= "&apipassword=MD5".get_option('rcfo_nh_key');
	$url .= "&command=pricecheck&tld=".$item."&commandname=".$type;
	$xml = simplexml_load_file($url);

	if(get_option('rcfo_nh_price_type','1') == "1"){
		$return_price = $price;
	}elseif(get_option('rcfo_nh_price_type','1') == "2"){
		$return_price = bcadd($price,bcmul(get_option('rcfo_nh_price_percentage'),bcdiv($price, "100", 4),4),3);
	}elseif(get_option('rcfo_nh_price_type','1') == "3"){
		$return_price = bcadd($price, get_option('rcfo_nh_price_fixed'),3);
	}
	if(get_option('rcfo_nh_price_calc_tax','0') == "0"){
		$return_price = bcadd($return_price,bcmul(get_option('rcfo_nh_price_tax'),bcdiv($return_price, "100", 4),4),3);
	}
		return $return_price;
}

function rcfo_tld_price($item,$type){
	$url = "http://api.oxxa.com/command.php";
	$url .= "?apiuser=".get_option('rcfo_nh_account');
	$url .= "&apipassword=MD5".get_option('rcfo_nh_key');
	$url .= "&command=pricecheck&tld=".$item."&commandname=".$type;
	$xml = simplexml_load_file($url);

		return number_format(rcfo_calc_price($xml->order[0]->details[0]->id1->price),2, ',', '.');
}

function rcfo_eppcode($sld,$tld,$owner){
	if(rcfo_owner_check($sld,$tld,$owner) == TRUE){
	$url = "http://api.oxxa.com/command.php";
	$url .= "?apiuser=".get_option('rcfo_nh_account');
	$url .= "&apipassword=MD5".get_option('rcfo_nh_key');
	$url .= "&command=domain_epp&sld=".$sld."&tld=".$ltd;
	$xml = simplexml_load_file($url);
		if ($xml->order[0]->done == TRUE){
		return "send";
		}else{ return "error"; }
	}
}

?>