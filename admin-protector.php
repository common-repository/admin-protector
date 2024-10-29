<?php
/*
Plugin Name: Admin Protector
Plugin URI: http://herukurniawan.com/2009/10/admin-protector-1-0-wordpress-plugin/
Description: This plugin is useful to keep everyone can access into the admin menu. This plugin uses httaccess file to check user and password. If the user and the password is entered correctly, then the wp-admin pages can be accessed only
Author: Heru Kurniawan
Version: 1.0
Author URI: http://herukurniawan.com/
*/

add_action('admin_menu', 'secure_admin_load');
function header_admin_login()
{
	//global $status,$myVar;
	$myVar = "disini";
	if (is_user_logged_in() != 1)
	{ 
		if(crypt($_SERVER['REMOTE_ADDR'].date('dmy'),strtotime('NOW')) != $_GET['aut'])
		{
	?>
<meta http-equiv="refresh" content="1;url=<?php bloginfo('url');?>/wp-content/plugins/admin-protector">
	<?
		}
	}
}
add_action('login_head', 'header_admin_login');

function secure_admin_load() {
	add_options_page("Admin Protector", "Admin Protector", 1, "Admin Protector", "secure_form");
}
function my_explode($delim, $str, $lim = 1)
{
    if ($lim > -2) return explode($delim, $str, abs($lim));

    $lim = -$lim;
    $out = explode($delim, $str);
    if ($lim >= count($out)) return $out;

    $out = array_chunk($out, count($out) - $lim + 1);

    return array_merge(array(implode($delim, $out[0])), $out[1]);
}

if ( isset($_POST[user]) && isset($_POST[password1]))
{
 if( $_POST[password1] == $_POST[password2] )
  {
	$path = my_explode('/',$_SERVER['DOCUMENT_ROOT'], -2);
	$pfad = $path[0] . "/.htpasswd";
    $safe= dirname ($PHPSELF);

$htaccess_text = "AuthName \"Password Protected Directory\"
AuthType Basic
AuthUserFile $path[0]/.htpasswd
require valid-user";
    
    $user = $_POST[user];
    $password1 = $_POST[password1];

    for ($i = 0; $i < count ($user); $i++)
    {
     $htpasswd_text .= "$user[$i]:".crypt($password1[$i],CRYPT_STD_DES)."";
    }

		$config = nl2br($htpasswd_text);
		
		$fp = fopen($path[0].'/.htpasswd', 'w');
		fwrite($fp, $config); 
		fclose($fp);
		
		$config_htacces = $htaccess_text;
		$fp = fopen("../wp-content/plugins/admin-protector/.htaccess", 'w');
		fwrite($fp, $config_htacces); 
		fclose($fp);
  }
  else
  {

      echo "<p><hr></p>";
      echo "<b>Passwords do not match</b>";
      echo "<p><hr></p>";

  }
}
function secure_form()
{

?>
<script language="javascript">
<!--
function ValidateForm() {

for (i = 0; i < document.forms[0].elements.length; i++) {
       if (document.forms[0].elements[i].value == "") {
     switch (document.forms[0].elements[i].type) {
       case "text":
         alert('Please complete all fields before submitting');
         document.all.submit.style.visibility='visible';
         return false;
         break;

       case "textarea":
         alert('Please complete all fields before submitting');
         document.all.submit.style.visibility='visible';
         return false;
         break;

       case "file":
         alert('Please complete all fields before submitting');
         document.all.submit.style.visibility='visible';
         return false;
         break;
     }
       }
     }
    return true;
    }
//-->
</script>
<div class="wrap">
<h2>Admin Protector By BujanQWorkS</h2><br />
<FORM METHOD="POST" ACTION="<? echo $PHP_SELF; ?>" onSubmit='return ValidateForm()'>
<table>
<tr><td>Username:</td><td><INPUT TYPE="TEXT" NAME="user[]"></td></tr>
<tr><td>Password:</td><td><INPUT TYPE="PASSWORD" NAME="password1[]"></td></tr>
<tr><td>Password again:</td><td><INPUT TYPE="PASSWORD" NAME="password2[]"></td></tr>
<tr><td><center><p class="submit"><INPUT type=submit name="submit" VALUE="Save Option" onclick="document.all.submit.style.visibility='hidden'"></p>
</center></td></tr>
</table>
</FORM>
<hr />
<p>
<b>Admin Protector</b> plugin is useful to keep everyone can access into the admin menu. This plugin uses httaccess file to check user and password. If the user and the password is entered correctly, then the wp-admin pages can be accessed only. if the username and password is entered incorrectly it will appear 401 pages and can not access wp-admin page 
<br /><br />
<b>Admin Protector</b> plugin is still under development by <b><a href="http://herukurniawan.com">BujanQWorkS</a></b>, for the next version will be more refined</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
	">
	<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
</div>
<?
}
?>
