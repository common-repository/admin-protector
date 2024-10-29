<?php
$ver = crypt($_SERVER['REMOTE_ADDR'].date('dmy'),strtotime('NOW'));
?>
<meta http-equiv="refresh" content="1;url=../../../wp-login.php?aut=<? echo $ver;?>">
