<?php
include('config.php');
if ((isset($_GET['id'])&&!empty($_GET['id']))&&(isset($_GET['descr'])&&!empty($_GET['descr']))&&(isset($_GET['min'])&&!empty($_GET['min']))&&(isset($_GET['max'])&&!empty($_GET['max']))) {
$id = $_GET['id'];
$descr = $_GET['descr'];
$min = $_GET['min'];
$max = $_GET['max'];
 /* CAT:Area Chart */

 /* SELECT DATE_FORMAT( `time` , '%d' ) AS time, ROUND( AVG( value ) , 2 ) AS value
FROM `temp`
WHERE name_id =1
GROUP BY DATE_FORMAT( `time` , '%d' )
LIMIT 0 , 30*/
 include("pChart/class/pData.class.php");
 include("pChart/class/pDraw.class.php");
 include("pChart/class/pImage.class.php");

$conn = mysql_connect($mysql['host'], $mysql['login'], $mysql['pass']) or die('error connect to base');
$sql = mysql_select_db($mysql['db']) or die('error select db');
mysql_query("set names utf8");

 /* Create and populate the pData object */
 $MyData = new pData();  

 $sql = mysql_query("SELECT DATE_FORMAT( `time` , '%d.%m' ) AS time, ROUND( AVG( value ) , 2 ) AS value FROM `temp` WHERE name_id ='$id' GROUP BY DATE_FORMAT( `time` , '%d.%m' )");
 //$sql = mysql_query("select value, time from temp where name_id=1");
 $labels = array();
 while ($row = mysql_fetch_array($sql)) {
   $MyData->addPoints($row["value"],"Probe 1");
   $labels[] =  $row['time'];
 }
 $MyData->addPoints($labels,"Labels");
 $MyData->setSerieDescription("Labels","Day");
 $MyData->setAbscissa("Labels");
 $MyData->setAxisName(0,"Температура");
 $MyData->setAxisUnit(0,"°C");


 $w = 1000;
 $h = 500;
 $myPicture = new pImage($w,$h,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 /* Add a border to the picture */
 $myPicture->drawGradientArea(0,0,1000,500,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
 $myPicture->drawGradientArea(0,0,1000,500,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>20));

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,999,499,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/verdana.ttf","FontSize"=>16));
 $myPicture->drawText(80,35,$descr,array("FontSize"=>16,"Align"=>TEXT_ALIGN_BOTTOMLEFT));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/verdana.ttf","FontSize"=>12));

 /* Define the chart area */
 $myPicture->setGraphArea(100,50,950,450);

 /* Draw the scale */
 $start = ($min>=0?$start=0:($min<40?$start=-60:$start=$min-10));
 $end=$max+10;
 $AxisBoundaries = array(0=>array("Min"=>$start,"Max"=>$end));
 $scaleSettings = array("XMargin"=>2,"YMargin"=>1,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"GridAlpha"=>100,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE,"Mode"=>SCALE_MODE_MANUAL, "ManualScale"=>$AxisBoundaries);
 $myPicture->drawScale($scaleSettings);

 /* Write the chart legend */
 //$myPicture->drawLegend(640,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Enable shadow computing */
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

 /* Draw the area chart */
 $Threshold = "";
 $Threshold[] = array("Min"=>$start,"Max"=>$min,"R"=>0,"G"=>187,"B"=>220,"Alpha"=>100);
 $Threshold[] = array("Min"=>$min,"Max"=>$max,"R"=>187,"G"=>220,"B"=>0,"Alpha"=>100);
 $Threshold[] = array("Min"=>$max,"Max"=>$end,"R"=>240,"G"=>91,"B"=>20,"Alpha"=>100);
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20));
 $myPicture->drawAreaChart(array("AroundZero"=>FALSE,"Threshold"=>$Threshold));

 /* Draw a line chart over */
 $myPicture->drawLineChart(array("ForceColor"=>TRUE,"ForceR"=>0,"ForceG"=>0,"ForceB"=>0));

 /* Draw a plot chart over */
 $myPicture->drawPlotChart(array("PlotBorder"=>TRUE,"BorderSize"=>1,"Surrounding"=>-255,"BorderAlpha"=>80));

 /* Write the thresholds */
 $myPicture->drawThreshold($min,array("WriteCaption"=>TRUE,"Caption"=>"Минимум","Alpha"=>70,"Ticks"=>2,"R"=>0,"G"=>0,"B"=>255));
 $myPicture->drawThreshold($max,array("WriteCaption"=>TRUE,"Caption"=>"Максимум","Alpha"=>70,"Ticks"=>2,"R"=>0,"G"=>0,"B"=>255));
 /* Render the picture (choose the best way) */
 $myPicture->autoOutput();
}
?>

