<?php /*                        Copyright 2005 Fl�vio Ribeiro

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

	$auth = new auth;
	$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);

	print "<form name='form1' action='".$_SERVER['PHP_SELF']."' method='post' onSubmit=\"return valida()\">"; 
	print "<table>";
	
	$urlid = "";
	$cod = "";
	if (isset($_GET['urlid'])){
		$urlid = $_GET['urlid'];
	} else
		$urlid = "";
	
	if (isset($_GET['numero'])){
		$cod = $_GET['numero'];
	} else
		$cod = "";	
	
	
	if (!isset($_POST['submit'])){
		print "<tr>";
		//print "<td>".TRANS('INSERT_COMMENT','Inserir comentário')."</td>";
		print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<TEXTAREA class='textarea' name='assentamento' id='idAssentamento'>".
				"".TRANS('INSERT_COMMENT')."</textarea>";
		print "</td>";
		print "<input type='hidden' name='numero' value='".$cod."'>";
		print "<input type='hidden' name='urlid' value='".$urlid."'>";
		print "<td><input type='submit' class='button' name='submit' value='".TRANS('BT_CAD')."'></td>";
		print "</tr>";
	} else
	
	if (isset($_POST['submit']) && $_POST['submit'] == TRANS('BT_CAD')) {
		
		$qry = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel, asset_privated) values ".
				"(".$_POST['numero'].", '".noHtml($_POST['assentamento'])."', '".date("Y-m-d H:i:s")."', ".$_SESSION['s_uid'].", 0 ) ";
		$exec = mysql_query($qry) or die ($qry);
		
		
		$qryfull = $QRY["ocorrencias_full_ini"]." WHERE o.numero = ".$_POST['numero']."";
		$execfull = mysql_query($qryfull) or die(TRANS('MSG_ERR_RESCUE_VARIA_SURROU').$qryfull);
		$rowfull = mysql_fetch_array($execfull);

		$VARS = array();
		$VARS['%numero%'] = $rowfull['numero'];
		$VARS['%usuario%'] = $rowfull['contato'];
		$VARS['%contato%'] = $rowfull['contato'];
		$VARS['%descricao%'] = $rowfull['descricao'];
		$VARS['%setor%'] = $rowfull['setor'];
		$VARS['%ramal%'] = $rowfull['telefone'];
		$VARS['%assentamento%'] = $_POST['assentamento'];
		//$VARS['%site%'] = "<a href='".$row_config['conf_ocomon_site']."'>".$row_config['conf_ocomon_site']."</a>";
		$VARS['%area%'] = $rowfull['area'];
		$VARS['%operador%'] = $rowfull['nome'];
		//$VARS['%editor%'] = $rowMailLogado['nome'];
		$VARS['%aberto_por%'] = $rowfull['aberto_por'];
		$VARS['%problema%'] = $rowfull['problema'];
		$VARS['%versao%'] = VERSAO;		
		
		$sqlMailArea = "select * from sistemas where sis_id = ".$rowfull['area_cod']."";
		$execMailArea = mysql_query($sqlMailArea);
		$rowMailArea = mysql_fetch_array($execMailArea);		
		
		
		$qryconfmail = "SELECT * FROM mailconfig";
		$execconfmail = mysql_query($qryconfmail) or die (TRANS('ERR_QUERY'));
		$rowconfmail = mysql_fetch_array($execconfmail);


		//if (isset($_POST['mailAR']) || isIn($_SESSION['s_area'],$rowconf['conf_custom_areas'])) {
			$event = 'edita-para-area';
			$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
			$execmsg = mysql_query($qrymsg) or die(TRANS('ERR_QUERY'));
			$rowmsg = mysql_fetch_array($execmsg);

			send_mail($event, $rowMailArea['sis_email'], $rowconfmail, $rowmsg, $VARS);
		//}		
		
		print "<script>redirect('mostra_consulta.php?numero=".$_POST['numero']."&id=".$_POST['urlid']."');</script>";
		
	
	}
	
	print "</table>";
	print "</form>";


?>
<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idAssentamento','ALFA','<?php print TRANS('INSERT_COMMENT')?>',1);

		return ok;
	}
-->
</script>
