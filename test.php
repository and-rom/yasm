<?php
 include("pChart/class/pData.class.php");
 include("pChart/class/pDraw.class.php");
 include("pChart/class/pImage.class.php");
$MyData = new pData();  
 
/* Prepare some nice data & axis config */
$MyData->addPoints(array(24,-25,26,25,25),"Temperature");
$MyData->addPoints(array(1,2,VOID,9,10),"Humidity 1");
$MyData->addPoints(array(1,VOID,7,-9,0),"Humidity 2");
$MyData->addPoints(array(-1,-1,-1,-1,-1),"Humidity 3");
$MyData->addPoints(array(0,0,0,0,0),"Vide");
$MyData->setSerieOnAxis("Temperature",0);
$MyData->setSerieOnAxis("Humidity 1",1);
$MyData->setSerieOnAxis("Humidity 2",1);
$MyData->setSerieOnAxis("Humidity 3",1);
$MyData->setSerieOnAxis("Vide",2);
$MyData->setAxisPosition(2,AXIS_POSITION_RIGHT);
$MyData->setAxisName(0,"Temperature");
$MyData->setAxisName(1,"Humidity");
$MyData->setAxisName(2,"Empty value");
 
/* Bind a data serie to the X axis */
$MyData->addPoints(array("Jan","Feb","Mar","Apr","May","Jun"),"Labels");
$MyData->setSerieDescription("Labels","My labels");
$MyData->setAbscissa("Labels");
 
/* Define the graph area and do some makeup */
$myPicture = new pImage(700,230,$MyData); 
$myPicture->setFontProperties(array("FontName"=>"fonts/verdana.ttf","FontSize"=>11));
$myPicture->setGraphArea(90,60,660,190);
$myPicture->drawText(350,55,"My chart title",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
$myPicture->drawFilledRectangle(90,60,660,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
 
/* Compute and draw the scale */
$myPicture->drawScale(array("DrawYLines"=>array(0)));
?>
