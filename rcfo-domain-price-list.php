<?php defined('ABSPATH') or die("No script kiddies please!");

function rcfo_nh_page_list() {
 if (rcfo_login_check() == "true") { 

$url = "http://api.oxxa.com/command.php";
$url .= "?apiuser=".get_option('rcfo_nh_account');
$url .= "&apipassword=MD5".get_option('rcfo_nh_key');
$url .= "&command=domain_list&identity=".get_user_meta(get_current_user_id(), "rcfo_nh_oxxaid", "true" )."&sortname=sld&records=".urlencode("-1");
$xml = simplexml_load_file($url);

echo '<div class="table-responsive"><table class="table table-hover" style="width:100%;"><tr><th>'. __('Domain name', 'rcfo-nh').'</th><th>'. __('Auto renew', 'rcfo-nh').'</th><th>'. __('Price', 'rcfo-nh').'</th><th>'. __('Expire date', 'rcfo-nh').'</th><th>'. __('EPP code', 'rcfo-nh').'</th></tr>';

foreach($xml->order->details->domain as $item)
{
  $tld = explode(".", $item->domainname);
	echo '<tr><td>'.$item->domainname.'</td>';
     if($item->autorenew == "Y"){
	echo '<td style="color: #008000; font-weight: bold;">&#10004;</td>';
     }elseif($item->autorenew == "N"){
	echo '<td style="color: #990000; font-weight: bold;">&#10007;</td>';
     }
	echo '<td>&euro; '.rcfo_tld_price($tld[1].''.$tld[2],"renew").'</td>';
	echo '<td>'.$item->expire_date.'</td>';
	echo '<td><a href="#">'. __('send', 'rcfo-nh').'</a></td></tr>';

}

echo '</table></div>';

 }
}

?>