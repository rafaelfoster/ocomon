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
	$cab->set_title(TRANS('TTL_INVMON'));

	$auth = new auth;

	if (isset($_GET['popup'])) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);


	$hojeLog = date ("d-m-Y H:i:s");

	if (isset($_POST['submit']))
	{
		$erro=false;

		if ($_POST['software'] == -1)
		{
			$aviso = TRANS('MSG_EMPTY_DATA');
			$erro = true;
		} else {
			$qry_1 = "select * from softwares where soft_cod = ".$_POST['software']."";
			$exec_qry_1 = mysql_query($qry_1);
			$row_qry_1 = mysql_fetch_array($exec_qry_1);
			$fabricante = $row_qry_1["soft_fab"];
			$nome = $row_qry_1["soft_desc"];

			$qry_2 = "select * from hw_sw, softwares where hws_hw_cod = ".$_POST['comp_inv']." and hws_hw_inst =".$_POST['comp_inst']." and ".
						"soft_cod = hws_sw_cod and soft_desc = '".$nome."' and soft_fab = '".$fabricante."' ";
			$exec_qry_2 = mysql_query($qry_2);
			$cont = mysql_num_rows($exec_qry_2);
			if ($cont != 0) {
				$erro = true;
				$aviso = TRANS('MSG_SOFTWARE_INSTALL_EQUIP');
			}
		}

		if (!$erro)
		{
			$query = "insert into hw_sw (hws_sw_cod, hws_hw_cod, hws_hw_inst) values ".
					"(".$_POST['software'].", ".$_POST['comp_inv'].", ".$_POST['comp_inst'].")";
			$resultado = mysql_query($query);
			if ($resultado == 0)
			{
				print $query."<br>";
				$aviso = TRANS('MSG_ERR_INCLUDE_SW')." ".$query;
			}
			else
			{
				$aviso = TRANS('MSG_OK_INCLUDE_SW');
			}
		}

		print "<script>mensagem('".$aviso."'); window.opener.location.reload();  self.close();</script>";
	}
	else
	{


		print "<br>";
		print "<table class='corpo'>";
		print "<tr>";
		print "<TD width='400' align='left'><B>".TRANS('TXT_INCLUDE_SW_CAD_TAG')." ".$_GET['comp_inv']." ".TRANS('TXT_AND_UNIT').": ".$_GET['comp_inst']."</B></TD>";
		print "<td class='line'><input type='button' value='".TRANS('LINK_LIST')."' class='minibutton' onClick= \"javascript: popup_alerta('comp_soft.php?popup=true&comp_inv=".$_GET['comp_inv']."&comp_inst=".$_GET['comp_inst']."')\"></td>";
		print "<TD width='224' align='left'></td>";
		print "</tr>";
		print "</table><br>";

		print "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
		print "<table border='0' align='left' width='40%'>";

		print "<input type='hidden' name='comp_inv' value='".$_GET['comp_inv']."'>";
		print "<input type='hidden' name='comp_inst' value='".$_GET['comp_inst']."'>";
		print "<input type='hidden' name='popup' value='".$_GET['popup']."'>";

			print "<tr>";
				print "<td width='20%' align='right' bgcolor=".TD_COLOR.">".TRANS('COL_SOFT').":</TD>";
				print "<td width='80%' align='left' bgcolor=".BODY_COLOR.">";
				print "<select class='select' name='software'>";
				print "<option value=-1 selected>".TRANS('SEL_SOFT')."</option>";

				//retorna os softwares cadastrados para o equipamento
				$sql = "select s.*, l.*, c.*, f.*, h.* from softwares as s, licencas as l, categorias as c, ".
						"fabricantes as f, hw_sw as h ".
						"where s.soft_tipo_lic = l.lic_cod and s.soft_cat = c.cat_cod and s.soft_fab = f.fab_cod ".
						"and h.hws_sw_cod = s.soft_cod and h.hws_hw_cod = ".$_GET['comp_inv']." and h.hws_hw_inst = ".$_GET['comp_inst']." ".
						"order by f.fab_nome, s.soft_desc";
				$commit = mysql_query($sql);
				//$softs = "";
				while($rowA = mysql_fetch_array($commit)){
					$softs.= $rowA["soft_cod"].",";
				}
				if (isset($softs)) {
					$softs = substr($softs,0,-1);
				} else
					$softs = -1;

				//Retorna todos os softwares menos os cadastrados nesse equipamento
				$select = "select s.*, f.* from softwares s, fabricantes f where f.fab_cod = s.soft_fab ".
							"and s.soft_cod not in (".$softs.") ".
							"order by fab_nome, soft_desc";
				$exec = mysql_query($select);
				while($row = mysql_fetch_array($exec)){
					print "<option value='".$row['soft_cod']."'>".$row['fab_nome']." ".$row['soft_desc']." ".$row["soft_versao"]."</option>";
				} // while
				print "</select>";
			print "</tr>";



			print "<tr>";
				print "<td align='center' width='20%' bgcolor=".BODY_COLOR."><input type='submit' value='".TRANS('BT_ADD')."' name='submit' class='button'></td>";
				print "<td align='center' width='80%' bgcolor=".BODY_COLOR."><input type='reset' value='".TRANS('BT_CANCEL')."' onClick=\"javascript:self.close()\" name='cancelar' class='button'></td>";
			print "</tr>";
	}

	print "</table>";
	print "</form>";


	$cab->set_foot();

?>