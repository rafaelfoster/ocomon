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

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");


	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;

	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$hojeLog = date ("d-m-Y H:i:s");

	//print "<input type='hidden' name='comp_inv' value='$comp_inv'>";
	//print "<input type='hidden' name='comp_inst' value='$comp_inst'>";


	$qry= "select comp_local from equipamentos where comp_inv = ".$_GET['comp_inv']." and comp_inst=".$_GET['comp_inst']."";
	$exec = mysql_query($qry);
	$rowLocal = mysql_fetch_array($exec);

	$sql = "SELECT o.numero as numero, o.data_abertura as abertura, o.data_fechamento as fechamento, o.telefone as ramal, ".
			"p.problema as problema, s.status as status, l.local as local, l.loc_id as loc_id ".
			"FROM ocorrencias AS o left join problemas AS p on o.problema = p.prob_id, status AS s, localizacao as l ".
		"WHERE o.status = s.stat_id AND o.local=l.loc_id and o.equipamento = ".$_GET['comp_inv']." ".
		"AND o.instituicao = ".$_GET['comp_inst']." order by o.numero";
	$commit = mysql_query($sql);
	$linhas = mysql_num_rows($commit);

	if ($linhas == 0)
	{
		print "<b><p align=center>Nenhum chamado cadastrado no OCOMON para esse equipamento!</b></p>";
		print "<table width='100%'>";
		print "<tr><td align='left' width='80%'><a onClick= \"javascript:popup_alerta_wide('../../ocomon/geral/incluir.php?invTag=".$_GET['comp_inv']."&invInst=".$_GET['comp_inst']."&invLoc=".$rowLocal['comp_local']."')\">Abrir nova ocorrência para esse equipamento</a></td><td align='right'><input type='button' class='minibutton' value='Fechar' onClick=\"javascript:self.close()\"</td></tr>";
		print "</table>";
		exit;
	}
	else
	{
		print "<br>";
		print "<table class='corpo'>";
		print "<tr>";
		print "<TD width='500' align='left'><B>Equipamento ".$_GET['comp_inv'].": <font color='red'>".$linhas."</font> chamado(s) no OCOMON:</B></TD>";
		print "<TD width='100' align='left'></td>";
		print "<TD width='100' align='left'></TD>";
		print "<TD width='200' align='left'></TD>";
		print "</tr>";
		print "</table><br>";
	}

	print "<table class='corpo2'>";
	print "<TR class='header'><td class='line'>Número</TD><td class='line'>Problema</TD><td class='line'>Abertura</TD><td class='line'>Fechamento</TD><td class='line'>Situação</TD>";

	$j=2;
	$cont=0;
	while ($row = mysql_fetch_array($commit))
	{
		$cont++;
		if ($j % 2)
		{
			$trClass = "lin_par";
		}
		else
		{
			$trClass = "lin_impar";
		}
		$j++;
		print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";

		$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
		$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
		$regSub = mysql_num_rows($execSubCall);
		if ($regSub > 0) {
			#É CHAMADO PAI?
			$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
			$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
			$regSub = mysql_num_rows($execSubCall);
			$comDeps = false;
			while ($rowSubPai = mysql_fetch_array($execSubCall)){
				$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
				$execStatus = mysql_query($sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE STATUS DOS CHAMADOS FILHOS<br>'.$sqlStatus);
				$regStatus = mysql_num_rows($execStatus);
				if ($regStatus > 0) {
					$comDeps = true;
				}
			}
			if ($comDeps) {
				$imgSub = "<img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com vínculos pendentes'>";
			} else
				$imgSub =  "<img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com vínculos mas sem pendências'>";
		} else
			$imgSub = "";

		$qryImg = "select * from imagens where img_oco = ".$row['numero']."";
		$execImg = mysql_query($qryImg) or die ("ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE IMAGENS!");
		$rowTela = mysql_fetch_array($execImg);
		$regImg = mysql_num_rows($execImg);
		if ($regImg!=0) {
			$linkImg = "<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='".ICONS_PATH."attach2.png'></a>";
		} else $linkImg = "";


		print "<td class='line'><a onClick= \"javascript:popup_alerta('../../ocomon/geral/mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><font color='blue'>".$row['numero']."</font></a>".$imgSub."</TD>";
		print "<td class='line'>".$linkImg."&nbsp; ".$row['problema']."</TD>";
		print "<td class='line'>".$row['abertura']."</TD>";
		print "<td class='line'>".$row['fechamento']."</TD>";
		print "<td class='line'>".$row['status']."</TD>";
		print "</TR>";
		$invRamal = $row['ramal'];
	}
	print "</TABLE>";

	print "<table width='100%'>";
	print "<tr><td align='left' width='80%'><a onClick= \"javascript:popup_alerta_wide('../../ocomon/geral/incluir.php?invTag=".$_GET['comp_inv']."&invInst=".$_GET['comp_inst']."&invLoc=".$rowLocal['comp_local']."&telefone=".$invRamal."')\">Abrir nova ocorrência para esse equipamento</a></td><td align='right'><input type='button' class='minibutton' value='Fechar' onClick=\"javascript:self.close()\"</td></tr>";
	print "</table>";

	$cab->set_foot();
print "</body>";
print "</html>";
?>