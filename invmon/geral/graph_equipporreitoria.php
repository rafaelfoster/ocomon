<?php 
 /*                        Copyright 2005 Flávio Ribeiro
  
         This file is part of OCOMON.
  
         OCOMON is free software; you can redistribute it and/or modify
         it under the terms of the GNU General Public License as published by
         the Free Software Foundation; either version 2 of the License, or
         (at your option) any later version.
  
         OCOMON is distributed in the hope that it will be useful,
         but WITHOUT ANY WARRANTY; without even the implied warranty of
         MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         GNU General Public License for more details.
  
         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */
include ("../jpgraph/src/jpgraph.php");
include ("../jpgraph/src/jpgraph_bar.php");



$datay=array(20,30,50,80);
$datay2=array(430,645,223,690);
$datazero=array(0,0,0,0);

// Create the graph. 
$graph = new Graph(450,200);
$graph->title->Set('Example with 2 scale bars');

// Setup Y and Y2 scales with some "grace"	
$graph->SetScale("textlin");
$graph->SetY2Scale("lin");
$graph->yaxis->scale->SetGrace(30);
$graph->y2axis->scale->SetGrace(30);

//$graph->ygrid->Show(true,true);
$graph->ygrid->SetColor('gray','lightgray@0.5');

// Setup graph colors
$graph->SetMarginColor('white');
$graph->y2axis->SetColor('darkred');


// Create the "dummy" 0 bplot
$bplotzero = new BarPlot($datazero);

// Create the "Y" axis group
$ybplot1 = new BarPlot($datay);
$ybplot1->value->Show();
$ybplot = new GroupBarPlot(array($ybplot1,$bplotzero));

// Create the "Y2" axis group
$ybplot2 = new BarPlot($datay2);
$ybplot2->value->Show();
$ybplot2->value->SetColor('darkred');
$ybplot2->SetFillColor('darkred');
$y2bplot = new GroupBarPlot(array($bplotzero,$ybplot2));

// Add the grouped bar plots to the graph
$graph->Add($ybplot);
$graph->AddY2($y2bplot);

// .. and finally stroke the image back to browser
$graph->Stroke();
?>