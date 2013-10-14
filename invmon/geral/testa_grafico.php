<?php

// $Id: testa_grafico.php,v 1.1.1.1 2005/09/21 19:18:54 flavio_ribeiro Exp $
include ("../jpgraph/src/jpgraph.php");
include ("../jpgraph/src/jpgraph_bar.php");

$datay1=array(10,20,30,40,50,60);
$datay2=array(35,190,190,190,190,190);
$datay3=array(20,70,70,140,230,260);

$graph = new Graph(800,600,'auto');	
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->img->SetMargin(40,30,40,40);
$graph->xaxis->SetTickLabels($gDateLocale->GetShortMonth());

$graph->xaxis->title->Set('Year 2002');
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->title->Set('Group bar plot');
$graph->title->SetFont(FF_FONT1,FS_BOLD);

$bplot1 = new BarPlot($datay1);
$bplot2 = new BarPlot($datay2);
$bplot3 = new BarPlot($datay3);

$bplot1->SetFillColor("orange");
$bplot2->SetFillColor("brown");
$bplot3->SetFillColor("darkgreen");

$bplot1->SetShadow();
$bplot2->SetShadow();
$bplot3->SetShadow();

$bplot1->value->Show();
$bplot2->value->Show();
$bplot3->value->Show();

$gbarplot = new GroupBarPlot(array($bplot1,$bplot2,$bplot3));
$gbarplot->SetWidth(0.6);
$graph->Add($gbarplot);

$graph->Stroke();

?>