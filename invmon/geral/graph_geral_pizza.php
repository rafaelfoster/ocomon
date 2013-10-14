<?
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
		include ("../../includes/jpgraph/src/jpgraph_pie.php");
		include ("../../includes/jpgraph/src/jpgraph_pie3d.php");

		$graph = new PieGraph(800,500,"auto");
		$graph->SetShadow();
		$graph->SetAntiAliasing();
		//$titulo=$titulo.$instituicao;

		$graph->title->Set($_GET['titulo']);
		$graph->subtitle->Set($_GET['instituicao']);
		$graph->title->SetFont(FF_FONT1,FS_BOLD);

		$p1 = new PiePlot3D($_GET['data']);
		$p1->ExplodeAll();
		$p1->SetSize(0.45);
		$p1->SetCenter(0.35);

		$p1->SetLegends($_GET['legenda']);

		$graph->Add($p1);
		$graph->Stroke();
?>