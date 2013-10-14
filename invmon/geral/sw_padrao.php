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
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);

	$hojeLog = date ("d-m-Y H:i:s");

	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "redirect('".basename($_SERVER['PHP_SELF'])."')";
	}

	print "<BR><B>".TRANS('TTL_ADMIN_SW_STAND')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

	$query = "select s.*, l.*, c.*, f.*, p.* from softwares as s, licencas as l, categorias as c, fabricantes as f,
			sw_padrao p where s.soft_tipo_lic = l.lic_cod and s.soft_cat = c.cat_cod and s.soft_fab = f.fab_cod
			and s.soft_cod = p.swp_sw_cod ";
	if (isset($_GET['cod'])) {
		$query.= " AND soft_cod = ".$_GET['cod']." ";
	}
	$query .=" ORDER BY f.fab_nome, s.soft_desc, s.soft_versao";
	$resultado = mysql_query($query) or die(TRANS('MSG_ERR_QRY_CONS').'<br>'.$query);
	$registros = mysql_num_rows($resultado);



	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>".
				"".TRANS('LINK_INCLUDE_SW_IN_CONFIG')."</a></TD>".
			"<td class='line'><a href='softwares.php'>".TRANS('LINK_VIEW_SW_CAD')."</a></td>".
			"</TR>";


		if (mysql_num_rows($resultado) == 0)
		{
			print "<tr><td align='center'>";
			echo mensagem(TRANS('MSG_NOT_REG_CAD'));
			print "</td>";
			print "</tr>";
		}
		 else
		{
				print "<br>";
				print "<tr>";
				print "<TD width='400' align='left'><B>".TRANS('FOUND')." <font color=red>".$registros."</font> ".TRANS('TXT_SW_CAD_CONFIG_STAND')."</B></TD>";
				print "</tr>";
				print "<TR class='header'><td class='line'><b>".TRANS('COL_SOFT')."</TD><td class='line'><b>".TRANS('COL_CAT')."</TD><td class='line'><b>".TRANS('COL_LICENSE')."</TD><td class='line'><b>".TRANS('COL_NUMBER_OF_LICENSES')."</TD><td class='line'><b>".TRANS('COL_AVAILABLE')."</TD><td class='line'><b>".TRANS('COL_DEL')."</TD>";
		}

		//print "<table class=corpo2 >";


		   $j=2;
		   while ($row = mysql_fetch_array($resultado))
		   {
			  if ($j % 2)
			  {
					  $trClass = "lin_par";
			  }
			  else
			  {
					 $trClass = "lin_impar";
			  }
			  $j++;


			$sqlAux = "select count(*) total from hw_sw where hws_sw_cod = ".$row['soft_cod']." ";
			$commitAux = mysql_query($sqlAux);
			$rowAux = mysql_fetch_array($commitAux);
			$dispo = $row['soft_qtd_lic'] - $rowAux['total'];

		   print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				 print "<td class='line'>".$row['fab_nome']." ".$row['soft_desc']." ".$row['soft_versao']."</TD>";
				 print "<td class='line'>".$row['cat_desc']."</TD>";
				 print "<td class='line'>".$row['lic_desc']."</TD>";
				 print "<td class='line'>".$row['soft_qtd_lic']."</TD>";
				 print "<td class='line'>".$dispo."</TD>";
				 //print "<td class='line'><a onClick=\"javascript:confirmaAcao('Alterar ".$row['fab_nome']." ".$row['soft_desc']."?','".$PHP_SELF."', 'acao=alt&id=".$row['soft_cod']."')\"><img src='".ICONS_PATH."edit.png' title='Editar o registro'></TD>";
				 print "<td class='line'><a onClick=\"javascript:confirmaAcao('".TRANS('TXT_DELETE')." ".$row['fab_nome']." ".$row['soft_desc']."?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['soft_cod']."')\"><img src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></TD>";
			  print "</TR>";
		   }
		   print "</TABLE>";

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">".TRANS('COL_SOFT').":</TD>";
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR.">";
					print "<select class='select' name='software'>";
					print "<option value=-1 selected>".TRANS('SEL_SOFT')."</option>";

					$sql = "select * from sw_padrao;";
					$commit = mysql_query($sql);
					while($rowA = mysql_fetch_array($commit)){
						$softs.= $rowA["swp_sw_cod"].",";
					}
					if (isset($softs)) {
						$softs = substr($softs,0,-1);
					} else
						$softs = -1;

					//Retorna todos os softwares menos os já cadastrados como instalaçao padrão
					$select = "select s.*, f.* from softwares s, fabricantes f where f.fab_cod = s.soft_fab
								 and s.soft_cod not in ($softs) order by fab_nome, soft_desc";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['soft_cod'].">".$row['fab_nome']." ".$row['soft_desc']." ".$row["soft_versao"]."</option>";
					} // while
					print "</select>";
					print "&nbsp;<a href='' onClick=\"javascript:popup_alerta('softwares.php?action=incluir&popup=true')\">".TRANS('LINK_CAD_SW')."</a>";
					//onClick=\"javascript:popup_alerta('softwares.php?action=incluir&popup=true')\">
					print "</td>";
		print "</tr>";



		print "<tr>";
			print "<td align='center' width='20%' bgcolor=".BODY_COLOR."><input type='submit' class='button' value='".TRANS('BT_CAD')."' name='submit'></td>";
			print "<td align='left' width='80%' bgcolor=".BODY_COLOR."><input type='reset' class='button' value='".TRANS('BT_CANCEL')."' onClick=\"javascript:".$fecha."\" name='cancelar'></td>";
		print "</tr>";


	} else
	if (isset($_POST['submit']) &&  $_POST['submit']== TRANS('BT_CAD')){

                $erro="não";


		if ($_POST['software'] == -1)
                {
                	$aviso = TRANS('MSG_EMPTY_DATA');
        		$erro = "sim";
                }

                if ($erro == "não")
                {
			$query = "insert into sw_padrao (swp_sw_cod) values ".
				"(".$_POST['software'].")";
			$resultado = mysql_query($query) or die (TRANS('ERR_INSERT'). $query);
			if ($resultado == 0)
			{
				print $query."<br>";
				$aviso = TRANS('MSG_ERR_INCLUDE_SW'). $query;
			}
			else
			{
				$aviso = TRANS('MSG_OK_INCLUDE_SW');
			}
		}

		print "<script>mensagem('$aviso'); redirect('".$_SERVER['PHP_SELF']."'); window.opener.location.reload(); </script>";

	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$sql = "delete from sw_padrao where swp_sw_cod=".$_GET['cod']."";
		$commit = mysql_query($sql);
		if ($commit==0) {
		$aviso = TRANS('ERR_DEL');
		} else
			$aviso = TRANS('OK_DEL');
		print "<script>mensagem('$aviso'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}

	$cab->set_foot();

?>