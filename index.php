<?php
include('config.php');
date_default_timezone_set('Europe/Moscow');
$now = date("Y-m-d H:i:s");

function display($id,$descr,$min,$max) {
    ob_start();  ?>
        <div class="display"><img class="display-frame" alt="<?=$descr?>" src="display2.php?id=<?=$id?>&descr=<?=$descr?>&min=<?=$min?>&max=<?=$max?>"></div>
    <?
    $result=ob_get_clean();
    return $result;
}
function latest($id,$descr,$min,$max) {
  $sql = mysql_query("SELECT value FROM temp WHERE name_id='$id' AND time IN (SELECT max(time) FROM temp WHERE name_id='$id')");
  while($rq=mysql_fetch_assoc($sql)) {
    ob_start();  ?>
      <div class="latest">
        <div class="latest-descr"><?=$descr?></div>
        <div class="latest-value<?=($rq['value']<$min ? ' alert' : ($rq['value']>$max ? ' alert': '') )?>"><?=round($rq['value'],1)."&#176; C"?></div>
      </div>
    <?
    $result=ob_get_clean();
    return $result;
  }
}

$conn = mysql_connect($mysql['host'], $mysql['login'], $mysql['pass']) or die('error connect to base');
$sql = mysql_select_db($mysql['db']) or die('error select db');
mysql_query("set names utf8");
$sql = mysql_query("select * from temp_legend");
$latest="";$display="";
  while($rq=mysql_fetch_assoc($sql)) {
    $latest=$latest.latest($rq['id'], $rq['descr'], $rq['min'], $rq['max']);
//	if ($rq['id'] == 3) {echo $rq['min']; echo $rq['max'];}
    $display=$display.display($rq['id'], $rq['descr'],$rq['min'], $rq['max']);
  }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8" />
  <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  <title>Еще одна система мониторинга</title>
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <link rel="stylesheet" href="style.css" type="text/css" media="screen, projection" />
  <!--[if lte IE 6]><link rel="stylesheet" href="style_ie.css" type="text/css" media="screen, projection" /><![endif]-->
</head>
<body>
<div id="wrapper">
  <header id="header">
    <h1>Еще одна система мониторинга</h1>
    <span id="now"><?=date('d.m.Y H:m:s', strtotime($now))?></span>
  </header><!-- #header-->
  <section id="middle">
    <div id="container">
      <div id="content">
        <div id="latest-container">
          <?=$latest?>
        </div>
        <div id="display-container">
          <?=$display?>
        </div>
      </div><!-- #content-->
    </div><!-- #container-->
    <aside id="sideRight">
    </aside><!-- #sideRight -->
  </section><!-- #middle-->
</div><!-- #wrapper -->
<footer id="footer">
</footer><!-- #footer -->
</body>
</html>
