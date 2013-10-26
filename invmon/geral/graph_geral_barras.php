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
  */session_start();

	include ("../../includes/jpgraph/src/jpgraph.php");
	include ("../../includes/jpgraph/src/jpgraph_bar.php");

		if (!isset($GET['data2'])) {
			$data2=array();
			for ($i=0; $i<count($_GET['data']); $i++){
				$data2[]=0;
			}
		} else $data2 = $_GET['data2'];
		if (!isset($GET['data3'])) {
			$data3=array();
			for ($i=0; $i<count($_GET['data']); $i++){
				$data3[]=0;
			}
		} else $data3 = $_GET['data3'];


		$graph = new Graph(800,600,'auto');
		$graph->SetScale("textlin");
		$graph->SetShadow();


		//$graph->Set90AndMargin(100,20,50,30);

		$graph->img->SetMargin(45,30,40,160);
		//$graph->xaxis->SetTickLabels($gDateLocale->GetShortMonth());
		$graph->xaxis->SetTickLabels($_GET['legenda']);
		$graph->xaxis->SetLabelAngle(90); //
		//$graph->xaxis->SetLabelSide(SIDE_TOP);
		//$graph->xaxis->SetTickSide(SIDE_TOP);
		//$graph->xaxis->scale->ticks->SetSide(SIDE_UP);

		$graph->xaxis->title->Set('Equipamentos');
		$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
		$graph->xaxis->title->SetAngle(90);

		$graph->yaxis->title->Set('Quantidade');
		$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);


		$graph->title->Set($_GET['titulo']);
		$graph->subtitle->Set($_GET['instituicao']);

		$graph->title->SetFont(FF_FONT1,FS_BOLD);

		$bplot1 = new BarPlot($_GET['data']);
		$bplot2 = new BarPlot($data2);
		$bplot3 = new BarPlot($data3);

		$bplot1->SetFillColor("orange");
		$bplot2->SetFillColor("brown");
		$bplot3->SetFillColor("darkgreen");

		$bplot1->SetShadow();
		$bplot2->SetShadow();
		$bplot3->SetShadow();

		$bplot1->value->Show();
		$bplot1->value->SetFormat('%01.0f');

		$bplot2->value->Show();
		$bplot2->value->SetFormat('%01.0f');

		$bplot3->value->Show();
		$bplot3->value->SetFormat('%01.0f');

		$gbarplot = new GroupBarPlot(array($bplot1,$bplot2,$bplot3));
		$gbarplot->SetWidth(0.6);
		$graph->Add($gbarplot);

		//$graph->add($bplot1);
		$graph->Stroke();

?>
