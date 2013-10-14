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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$cab = new headers;
	//$cab->set_title($TRANS['html_title']);
	$cab->set_title(TRANS('html_title'));

	$hoje = date("Y-m-d H:i:s");
	$hojeLog = date("d-m-Y H:i:s");
	$nulo = null;

	$habilita = "disabled";

	if ((isset($_GET['LOAD']) && $_GET['LOAD'] !=-1)) {

		$queryA = "SELECT ".
			"mold.mold_marca as padrao, mold.mold_inv as etiqueta, mold.mold_sn as serial, mold.mold_nome as nome, ".
 			"mold.mold_nf as nota, mold.mold_coment as comentario, mold.mold_valor as valor, mold.mold_data_compra as ".
			"data_compra, mold.mold_ccusto as ccusto, ".

			"inst.inst_nome as instituicao, inst.inst_cod as cod_inst, ".
			"equip.tipo_nome as equipamento, equip.tipo_cod as equipamento_cod, ".
			"t.tipo_imp_nome as impressora, t.tipo_imp_cod as impressora_cod, ".
			"loc.local as local, loc.loc_id as local_cod, ".

			"proc.mdit_fabricante as fabricante_proc, proc.mdit_desc as processador, proc.mdit_desc_capacidade as clock, ".
			"proc.mdit_cod as cod_processador, hd.mdit_fabricante as fabricante_hd, hd.mdit_desc as hd, ".
			"hd.mdit_desc_capacidade as hd_capacidade,hd.mdit_cod as cod_hd, ".
			"vid.mdit_fabricante as fabricante_video, vid.mdit_desc as video, vid.mdit_cod as cod_video, ".
			"red.mdit_fabricante as rede_fabricante, red.mdit_desc as rede, red.mdit_cod as cod_rede, ".
			"md.mdit_fabricante as fabricante_modem, md.mdit_desc as modem, md.mdit_cod as cod_modem, ".
			"cd.mdit_fabricante as fabricante_cdrom, cd.mdit_desc as cdrom, cd.mdit_cod as cod_cdrom, ".
			"grav.mdit_fabricante as fabricante_gravador, grav.mdit_desc as gravador, grav.mdit_cod as cod_gravador, ".
			"dvd.mdit_fabricante as fabricante_dvd, dvd.mdit_desc as dvd, dvd.mdit_cod as cod_dvd, ".
			"mb.mdit_fabricante as fabricante_mb, mb.mdit_desc as mb, mb.mdit_cod as cod_mb, ".
			"memo.mdit_desc as memoria, memo.mdit_cod as cod_memoria, ".
			"som.mdit_fabricante as fabricante_som, som.mdit_desc as som, som.mdit_cod as cod_som, ".
			"fab.fab_nome as fab_nome, fab.fab_cod as fab_cod, ".
			"fo.forn_cod as fornecedor_cod, fo.forn_nome as fornecedor_nome, ".
			"model.marc_cod as modelo_cod, model.marc_nome as modelo, ".
			"pol.pole_cod as polegada_cod, pol.pole_nome as polegada_nome, ".
			"res.resol_cod as resolucao_cod, res.resol_nome as resol_nome ".
		"FROM ((((((((((((((((((moldes as mold ".
			"left join  tipo_imp as t on	t.tipo_imp_cod = mold.mold_tipo_imp) ".
			"left join polegada as pol on mold.mold_polegada = pol.pole_cod) ".
			"left join resolucao as res on mold.mold_resolucao = res.resol_cod) ".
			"left join fabricantes as fab on fab.fab_cod = mold.mold_fab) ".
			"left join fornecedores as fo on fo.forn_cod = mold.mold_fornecedor) ".

			"left join modelos_itens as proc on proc.mdit_cod = mold.mold_proc) ".
			"left join modelos_itens as hd on hd.mdit_cod = mold.mold_modelohd) ".
			"left join modelos_itens as vid on vid.mdit_cod = mold.mold_video) ".
			"left join modelos_itens as red on red.mdit_cod = mold.mold_rede) ".
			"left join modelos_itens as md on md.mdit_cod = mold.mold_modem) ".
			"left join modelos_itens as cd on cd.mdit_cod = mold.mold_cdrom) ".
			"left join modelos_itens as grav on grav.mdit_cod = mold.mold_grav) ".
			"left join modelos_itens as dvd on dvd.mdit_cod = mold.mold_dvd) ".
			"left join modelos_itens as mb on mb.mdit_cod = mold.mold_mb) ".
			"left join modelos_itens as memo on memo.mdit_cod = mold.mold_memo) ".
			"left join modelos_itens as som on som.mdit_cod = mold.mold_som) ".

			"left join instituicao as inst on inst.inst_cod = mold.mold_inst) ".
			"left join localizacao as loc on loc.loc_id = mold.mold_local), ".
			"marcas_comp as model, tipo_equip as equip ".
		"WHERE ".
			"(mold.mold_tipo_equip = equip.tipo_cod) and ".
			"(mold.mold_marca = '".$_GET['LOAD']."') and (mold.mold_marca = model.marc_cod)";

		$resultadoA = mysql_query($queryA) or die (TRANS('MSG_ERR_LOAD_MODEL').'<BR>');
		$linhasA = mysql_num_rows($resultadoA);
		$row = mysql_fetch_array($resultadoA);

/*		if (mysql_num_rows($resultadoA)>0)
		{
			$linhasA = mysql_num_rows($resultadoA)-1;
		}
		else
		{
			$linhasA = mysql_num_rows($resultadoA);
		}*/
	}


	print "<BR>";
	print "<B>".TRANS('TTL_CAD_EQUIP_SYSTEM').":";
	print "<BR>";

	print "<FORM name='form1' method='POST' action='".$_SERVER['PHP_SELF']."'  ENCTYPE='multipart/form-data'  onSubmit='return valida()'>";

	print "<TABLE border='0' colspace='3' width='100%'>";

		print "<tr><td colspan='4'></td><b>".TRANS('SUBTTL_INFO_GENERAL').":</b></td></tr>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".
				"<a title='".TRANS('MSG_REQUER_FIELD_EQUIP')."'>".TRANS('FIELD_TYPE_EQUIP').":</a></b>".
			"</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_tipo_equip' id='idTipo' size='1' ".
					"onChange=\"fillSelectFromArray(this.form.comp_marca, ((this.selectedIndex == -1) ? null : ".
					"team[this.selectedIndex-1]));\">";

					print "<option value=-1 selected>".TRANS('SEL_TYPE_EQUIP')."</option>";

			$query = "SELECT * from tipo_equip order by tipo_nome";
			$resultado = mysql_query($query);
			while ($rowTipo = mysql_fetch_array($resultado))
			{
				print "<option value='".$rowTipo['tipo_cod']."' ";
				if (isset($queryA) && $rowTipo['tipo_cod'] == $row['equipamento_cod'])
					print " selected";
				print ">".$rowTipo['tipo_nome']."</option>";
			}
			print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".
				"<a title='".TRANS('MSG_REQUER_FIELD_SEL_MANUF')."'>".TRANS('COL_MANUFACTURE').":*</a></b>".
			"</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_fab' size='1' id='idFab'>";

				print "<option value=-1>".TRANS('SEL_MANUFACTURE').": </option>";
				$query = "SELECT * from fabricantes  order by fab_nome";
				$resultado = mysql_query($query);
				while ($rowFab = mysql_fetch_array($resultado))
				{
					print "<option value='".$rowFab['fab_cod']."' ";
						if (isset($queryA) && $rowFab['fab_cod'] == $row['fab_cod'])
							print " selected ";
					print ">".$rowFab['fab_nome']."</option>";
				}
			print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".
				"<a title='".TRANS('MSG_REQUER_FIELD_TAG_EQUIP')."'>".
				"".TRANS('OCO_FIELD_TAG')."*:</a></b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='comp_inv'  ".
				" id='idEtiqueta'></TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_SN')."*: </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='comp_sn'></TD>";
		print "</TR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".
				"<a title='".TRANS('MSG_REQUER_FIELD_SEL_EQUIP_CAD')."'>".TRANS('COL_MODEL')."*:</a></b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_marca' size='1' id='idModelo' >";

					print "<option value=-1 selected>".TRANS('SEL_MODEL')."</option>";

				$query = "SELECT * from marcas_comp order by marc_nome";
				$resultado = mysql_query($query);
				while ($rowMarcas= mysql_fetch_array($resultado))
				{
					print "<option value='".$rowMarcas['marc_cod']."'";
						if ((isset($_POST['comp_marca']) && ($rowMarcas['marc_cod'] == $_POST['comp_marca'])) ||
							(isset ($_GET['LOAD']) && $_GET['LOAD'] == $rowMarcas['marc_cod']) )

								print " selected";
							print ">".$rowMarcas['marc_nome']."</option>";
				}

				print "</select>";
				print "<input type='button' name='modelo' value='".TRANS('NEW')."' class='minibutton' onClick=\"javascript:popup_alerta('modelos.php?popup=true')\">";
		print "</td>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".
				"<a title='".TRANS('MSG_REQUER_FIELD_SEL_EQUIP_LOCAL')."'>".
				"".TRANS('COL_LOCAL')."*:</a></b></TD>";
		print "<TD width='30%' align='left' valign='top' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_local' size='1' id='idLocal'>";
			print "<option value=-1 selected>".TRANS('OCO_SEL_LOCAL')."</option>";

			$query = "SELECT * from localizacao  order by local";
			$resultado = mysql_query($query);
			while ($rowLocal = mysql_fetch_array($resultado))
			{
				print "<option value='".$rowLocal['loc_id']."'>".$rowLocal['local']."</option>";
			}
			print "</SELECT>";
		print "</TD>";
		print "</tr>";
		print "<tr>";
		print "<td colspan='2' width='20%' align='left'><input type='button' class='button' value='".TRANS('BT_LOAD_CONFIG')."' name='ok2' ".
				"onClick=\"redirectLoad('".$_SERVER['PHP_SELF']."?LOAD=','idModelo');\" ".
				"title='".TRANS('MSG_LOAD_HW_MODEL_SEL')."'>".
				"<input type='button' class='button' name='configuracao' value='".TRANS('BT_CAD_CONFIG')."' ".
				"onClick=\"redirect('incluir_molde.php')\"></td>
				<td colspan='2' width='80'>".
			"</td>";

		print "</tr>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".
				"<a title='".TRANS('MSG_REQUER_FIELD_SEL_SIT_EQUIP')."'>".TRANS('COL_SITUAC')."*:</a></b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_situac' size='1' id='idSituac'>";
			print "<option value=-1 selected>".TRANS('SEL_SITUAC')."</option>";
				$query = "SELECT * from situacao order by situac_nome";
				$resultado = mysql_query($query);
				while ($rowSituac = mysql_fetch_array($resultado))
				{
					print "<option value='".$rowSituac['situac_cod']."'>".$rowSituac['situac_nome']."</option>";
				}
			print "</select>";
		print "</td>";

                print "<td width='20%' bgcolor='".TD_COLOR."'><b>".TRANS('OCO_FIELD_ATTACH_IMG')."</b></td>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='file' class='text' name='img' id='idImg'></TD>";
                print "</tr>";

		print "<TR><td colspan='4'></td></TR>";
		print "<tr><td colspan='3'><b>".TRANS('SUBTTL_INFO_CONFIG_EQUIP').":</b></td><td class='line'>".
				"<input type='button' class='button' value='".TRANS('TTL_INCLUDE_COMP')."' ".
				"Onclick=\"return popup_alerta('itens.php?action=incluir&cellStyle=true&popup=true')\"></td>".
			"</tr>";
		print "<TR><td colspan='4'></td></TR>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_NAME_COMPUTER').":</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='comp_nome' maxlength='15'></TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_MB').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select'  name='comp_mb' size=1>";

					print "<option value=null selected>".TRANS('SEL_MODEL')."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 10 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					if (isset($queryA) && $row['cod_mb'] == $rowA['mdit_cod'])
						print " selected";
					print ">";
							print "".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."".
						"</option>";
				} // while

				print "<option value=null>".TRANS('SEL_NONE')."</option>";
				print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_PROC').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_proc' size='1'>";

					print "<option value=null selected>".TRANS('SEL_MODEL')."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 11 order by mdit_fabricante, mdit_desc, mdit_desc_capacidade";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					if (isset($queryA) && $rowA['mdit_cod'] == $row['cod_processador'])
						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
					print "<option value=null>".TRANS('SEL_NONE')."</option>";
				print "</SELECT>";
		print "</TD>";


		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_MEMO').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<SELECT class='select' name='comp_memo' size=1>";

					print "<option value=null selected>".TRANS('SEL_MODEL')."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 7 order by mdit_fabricante, mdit_desc, mdit_desc_capacidade";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					if (isset($queryA) && $rowA['mdit_cod'] == $row['cod_memoria'])
						print " selected";
					print ">".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
					//print "<option value='".$rowA['mdit_cod']."'>".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".TRANS('SEL_NONE')."</option>";
				print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_VIDEO').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<SELECT class='select' name='comp_video' size=1>";

					print "<option value=null selected>".TRANS('SEL_MODEL')."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 2 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					if (isset($queryA) && $rowA['mdit_cod'] == $row['cod_video'])
						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".TRANS('SEL_NONE')."</option>";
				print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_SOM').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_som' size=1>";

					print "<option value=null selected>".TRANS('SEL_MODEL')."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 4 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					if (isset($queryA) && $rowA['mdit_cod'] == $row['cod_som'])
						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".TRANS('SEL_NONE')."</option>";
				print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_REDE').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_rede' size=1>";

					print "<option value=null selected>".TRANS('SEL_MODEL')."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 3 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					if (isset($queryA) && $rowA['mdit_cod'] == $row['cod_rede'])
						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".TRANS('SEL_NONE')."</option>";
				print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_MODEM').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_modem' size=1>";

					print "<option value=null selected>".TRANS('SEL_MODEL')."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 6 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					if (isset($queryA) && $rowA['mdit_cod'] == $row['cod_modem'])
						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".TRANS('SEL_NONE')."</option>";
				print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_HD').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_modelohd' size='1'>";

					print "<option value=null selected>".TRANS('SEL_MODEL')."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 1 order by mdit_fabricante, mdit_desc_capacidade";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					if (isset($queryA) && $rowA['mdit_cod'] == $row['cod_hd'])
						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".TRANS('SEL_NONE')."</option>";
				print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_RECORD_CD').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_grav' size='1'>";

					print "<option value=null selected>".TRANS('SEL_MODEL')."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 9 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					if (isset($queryA) && $rowA['mdit_cod'] == $row['cod_gravador'])
						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".TRANS('SEL_NONE')."</option>";

			print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_CDROM').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_cdrom' size='1'>";

					print "<option value=null selected>".TRANS('SEL_MODEL')."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 5 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					if (isset($queryA) && $rowA['mdit_cod'] == $row['cod_cdrom'])
						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".TRANS('SEL_NONE')."</option>";
			print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_DVD').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_dvd' size=1>";

					print "<option value=null selected>".TRANS('SEL_MODEL')."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 8 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					if (isset($queryA) && $rowA['mdit_cod'] == $row['cod_dvd'])
						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".TRANS('SEL_NONE')."</option>";
			print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR><td colspan='4'></td></TR>";
		print "<tr><td colspan='4'><b>".TRANS('SUBTTL_INFO_ITEM_ESPEC').":</b></td></tr>";
		print "<TR><td colspan='4'></td></TR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_TYPE_PRINTER').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_tipo_imp' size=1>";

				print "<option value=null selected>".TRANS('SEL_TYPE_PRINTER')." </option>";

			$query = "SELECT * from tipo_imp  order by tipo_imp_nome";
			$resultado = mysql_query($query);
			while ($rowImp = mysql_fetch_array($resultado))
			{
				print "<option value='".$rowImp['tipo_imp_cod']."' ";
				if (isset($queryA) && $rowImp['tipo_imp_cod'] == $row['impressora_cod'])
					print " selected";
				print ">".$rowImp['tipo_imp_nome']."</option>";
			}
				print "<option value=null>".TRANS('SEL_SEL_NONE')."</option>";
			print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_MONITOR').":</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_polegada' size=1>";

					print "<option value =null selected>".TRANS('SEL_SIZE_MONITOR')." </option>";

			$query = "SELECT * from polegada  order by pole_nome";
			$resultado = mysql_query($query);
			while ($rowPol = mysql_fetch_array($resultado))
			{
				print "<option value='".$rowPol['pole_cod']."' ";
				if (isset($queryA) && $rowPol['pole_cod'] == $row['polegada_cod'])
					print " selected";
				print ">".$rowPol['pole_nome']."</option>";
			}
			print "<option value=null>".TRANS('SEL_NONE')."</option>";
			print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<tr>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_SCANNER').":</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_resolucao' size=1>";

					print "<option value=null selected>".TRANS('SEL_RESOLUT_SCANNER')." </option>";

			$query = "SELECT * from resolucao  order by resol_nome";
			$resultado = mysql_query($query);
			while ($rowResol = mysql_fetch_array($resultado))
			{
				print "<option value='".$rowResol['resol_cod']."' ";
				if (isset($queryA) && $rowResol['resol_cod'] == $row['resolucao_cod'])
					print " selected";
				print ">".$rowResol['resol_nome']."</option>";
			}
			print "<option value=null>".TRANS('SEL_NONE')."</option>";
			print "</SELECT>";
		print "</TD>";
		print "</tr>";


		print "<TR><td colspan='4'></td></TR>";
		print "<tr><td colspan='4'><b> ".TRANS('SUBTTL_INFO_COUNT').":</b></td></tr>";
		print "<TR><td colspan='4'></td></TR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".
				"<a title='".TRANS('MSG_REQUER_FIELD_SEL_UNIT_PROP')."'>".TRANS('OCO_FIELD_UNIT')."*:</a>"."
				</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_inst' size='1' id='idUnidade'>";
					print "<option value=null selected>".TRANS('OCO_SEL_UNIT')." </option>";

				$query = "SELECT * from instituicao  order by inst_nome";
				$resultado = mysql_query($query);
				while ($rowInst = mysql_fetch_array($resultado))
				{
					print "<option value='".$rowInst['inst_cod']."' ";
					if (isset($queryA) && $rowInst['inst_cod'] == $row['cod_inst'])
						print " selected";
					print ">".$rowInst['inst_nome']."</option>";
				}
			print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_CENTER_COST').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_ccusto' size=1>";
				print "<option value =null selected>".TRANS('SEL_CENTER_COST')." </option>";

				//$query = "SELECT * from ".`DB_CCUSTO`.".".TB_CCUSTO."  order by ".CCUSTO_DESC.""; //where ano='2003'
				$query = "SELECT * from `".DB_CCUSTO."`.".TB_CCUSTO."  order by ".CCUSTO_DESC."";
				$resultado = mysql_query($query);
				while ($rowCcusto = mysql_fetch_array($resultado))
				{
					print "<option value='".$rowCcusto[CCUSTO_ID]."'>".$rowCcusto[CCUSTO_DESC]." - ".$rowCcusto[CCUSTO_COD]."</option>";
				}
			print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_VENDOR')."*: </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_fornecedor' size=1>";
					print "<option value=null selected>".TRANS('SEL_VENDOR')."</option>";

				$query = "SELECT * from fornecedores  order by forn_nome";
				$resultado = mysql_query($query);

				while ($rowForn = mysql_fetch_array($resultado))
				{
					print "<option value='".$rowForn['forn_cod']."' ";
					if (isset($queryA) && $rowForn['forn_cod'] == $row['fornecedor_cod'])
						print " selected";
					print ">".$rowForn['forn_nome']."</option>";
				}
			print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_FISCAL_NOTES')."*:</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='comp_nf'></TD>";
		print "</tr>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_VALUE')."*:</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='comp_valor' id='idValor'></TD>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_DATE_PURCHASE')."*:</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='comp_data_compra' id='idDataCompra'></TD>";
		print "</tr>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_TYPE_GUARAT').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_tipo_garant' size=1>";

				//print "<option value=null selected>".TRANS('SEL_TYPE')."</option>";

			$query = "SELECT * from tipo_garantia  order by tipo_garant_nome";
			$resultado = mysql_query($query);
			while ($rowGarant = mysql_fetch_array($resultado))
			{
				print "<option value='".$rowGarant['tipo_garant_cod']."'>".$rowGarant['tipo_garant_nome']."</option>";
			}
			print "<option value=null selected>".TRANS('SEL_TYPE')."</option>";
			print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_TIME_GUARANT').": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_garant_meses' size=1>";
				//print "<option value=null selected>".TRANS('SEL_TIME_GUARANT')."</option>";

				$query = "SELECT * from tempo_garantia  order by tempo_meses";
				$resultado = mysql_query($query);
				while ($rowTempo = mysql_fetch_array($resultado))
				{
					print "<option value='".$rowTempo['tempo_cod']."'>".$rowTempo['tempo_meses']." ".TRANS('TXT_MOUNTHS')."</option>";
				}
				print "<option value=null selected>".TRANS('SEL_TIME_GUARANT')."</option>";
			print "</SELECT>";
		print "</TD>";
		print "</tr>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_COMMENT').":</b></TD>";
		print "<TD width='30%' colspan='3' align='left' bgcolor='".BODY_COLOR."'>".
				"<TEXTAREA  class='textarea' name='comp_coment'></textarea>".//cols='75' rows='5'
			"</TD>";
		print "</TR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b".TRANS('COL_SUBSCRIBE_DATE').":</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>".datab($hoje)."</TD>";
		print "</TR>";

		print "<TR>";
		print "<TD colspan='2'  align='right' bgcolor='".BODY_COLOR."'><input type='submit' value='".TRANS('BT_CAD')."' name='submit' ".
				"class='button' title='".TRANS('MSG_CAD_INFO_SUPP')."'>";
		print "</TD>";
		print "<TD colspan='2' align='right' bgcolor='".BODY_COLOR."'><INPUT type='reset' value='".TRANS('BT_CANCEL')."' ".
				"class='button' onClick=\"javascript:history.back()\"></TD>";
		print "</TR>";

	print "</TABLE>";
	print "</FORM>";

		if (isset ($_POST['submit']))
		{
			$erro=false;

			$querySN = "SELECT c.* FROM equipamentos as c ".
						"WHERE c.comp_marca='".$_POST['comp_marca']."' and ".
							"c.comp_sn='".$_POST['comp_sn']."'";
			$resultadoSN = mysql_query($querySN);
			$linhasSN = mysql_numrows($resultadoSN);
			if ($linhasSN > 0)
			{
				$aviso = TRANS('MSG_SERIAL_NUMBER_CAD_IN_SYSTEM');
				$erro = true;
			}

                        $query2 = "SELECT c.* FROM equipamentos as c ".
						"WHERE ((c.comp_inv='".$_POST['comp_inv']."') and ".
							"(c.comp_inst = '".$_POST['comp_inst']."'))";

			$resultado2 = mysql_query($query2);
			$linhas = mysql_numrows($resultado2);
			if ($linhas > 0)
			{
				$aviso.= TRANS('MSG_COD_TAG_CAD_IN_SYSTEM');
				$erro = "sim";
			}

			$gravaImg = false;
			if (isset($_FILES['img']) and $_FILES['img']['name']!="") {
				$qryConf = "SELECT * FROM config";
				$execConf = mysql_query($qryConf) or die (TRANS('MSG_ERR_NOT_ACCESS_INFO_CONFIG'));
				$rowConf = mysql_fetch_array($execConf);
				$arrayConf = array();
				$arrayConf = montaArray($execConf,$rowConf);

				$upld = upload('img',$arrayConf);
				if ($upld =='OK') {
					$gravaImg = true;
				} else {
					$upld.="<br><a align='center' onClick=\"exibeEscondeImg('idAlerta');\'><img src='".ICONS_PATH."/stop.png' width='16px' height='16px'>&nbsp;".TRANS('LINK_CLOSE')."</a>";
					print "</table>";
					print "<div class='alerta' id='idAlerta'><table bgcolor='#999999'><tr><td colspan='2' bgcolor='yellow'>".$upld."</td></tr></table></div>";
					exit;
				}
			}

                        if (!$erro)
                        {

				if ($gravaImg) {
					//INSERÇÃO DA IMAGEM NO BANCO
					$fileinput=$_FILES['img']['tmp_name'];
					$tamanho = getimagesize($fileinput);

					if(chop($fileinput)!=""){
						// $fileinput should point to a temp file on the server
						// which contains the uploaded image. so we will prepare
						// the file for upload with addslashes and form an sql
						// statement to do the load into the database.
						$image = addslashes(fread(fopen($fileinput,"r"), 1000000));
						$SQL = "Insert Into imagens (img_nome, img_inst, img_inv, img_tipo, img_bin, img_largura, img_altura) values ".
								"('".$_FILES['img']['name']."', '".$comp_inst."', '".$comp_inv."', '".$_FILES['img']['type']."', '".$image."', ".$tamanho[0].", ".$tamanho[1].")";
						// now we can delete the temp file
						unlink($fileinput);
					}
					$exec = mysql_query($SQL);
					if ($exec == 0) $aviso.= TRANS('MSG_NOT_ATTACH_IMG')."<br>";
				}
				$sql = "INSERT INTO historico (hist_inv, hist_inst, hist_local, hist_data) ".
						"VALUES ('".$_POST['comp_inv']."', '".$_POST['comp_inst']."', '".$_POST['comp_local']."', '".date("Y-m-d H:i:s")."')";
				$resultadoSQL = mysql_query($sql);

				//$data = $hoje;
				$comp_valor = str_replace(",",".",$_POST['comp_valor']);

// 				$comp_data_compra = datam($_POST['comp_data_compra']);
// 				if ($_POST['comp_sn'] == -1) { $comp_sn = "null";};// else $comp_sn = "$comp_sn";
// 				if ($_POST['comp_mb'] == -1) { $comp_mb = "null";} else $comp_mb = "'$comp_mb'";
// 				if ($_POST['comp_proc'] == -1) { $comp_proc = "null";} else $comp_proc = "'$comp_proc'";
// 				if ($_POST['comp_memo'] == -1) { $comp_memo = "null";} else $comp_memo = "'$comp_memo'";
// 				if ($_POST['comp_video'] == -1) { $comp_video = "null";} else $comp_video = "'$comp_video'";
// 				if ($_POST['comp_som'] == -1) { $comp_som = "null";} else $comp_som = "'$comp_som'";
// 				if ($_POST['comp_rede'] == -1) { $comp_rede = "null";} else $comp_rede = "'$comp_rede'";
// 				if ($_POST['comp_modelohd'] == -1) { $comp_modelohd = "null";} else $comp_modelohd = "'$comp_modelohd'";
// 				if ($_POST['comp_modem'] == -1) { $comp_modem = "null";} else $comp_modem = "'$comp_modem'";
// 				if ($_POST['comp_cdrom'] == -1) { $comp_cdrom = "null";} else $comp_cdrom = "'$comp_cdrom'";
// 				if ($_POST['comp_dvd'] == -1) { $comp_dvd = "null";} else $comp_dvd = "'$comp_dvd'";
// 				if ($_POST['comp_grav'] == -1) { $comp_grav = "null";} else $comp_grav = "'$comp_grav'";
// 				if ($_POST['comp_nome'] == -1) { $comp_nome = "null";} ;//else $comp_nome = "'$comp_nome'";
// 				if ($_POST['comp_nf'] == -1) { $comp_nf = "null";};// else $comp_nf = "'$comp_nf'";
// 				if ($_POST['comp_coment'] == -1) { $comp_coment = "null";};// else $comp_coment = "'$comp_coment'";
// 				if ($_POST['comp_ccusto'] == -1) { $comp_ccusto = "null";} else $comp_ccusto = "'$comp_ccusto'";
// 				if ($_POST['comp_tipo_imp'] == -1) { $comp_tipo_imp = "null";} else $comp_tipo_imp = "'$comp_tipo_imp'";
// 				if ($_POST['comp_resolucao'] == -1) { $comp_resolucao = "null";} else $comp_resolucao = "'$comp_resolucao'";
// 				if ($_POST['comp_polegada'] == -1) { $comp_polegada = "null";} else $comp_polegada = "'$comp_polegada'";
// 				if ($_POST['comp_fornecedor'] == -1) { $comp_fornecedor = "null";} else $comp_fornecedor = "'$comp_fornecedor'";
//
// 				if ($_POST['comp_tipo_garant'] == -1) { $comp_tipo_garant = "null";} else $comp_tipo_garant = "'$comp_tipo_garant'";
// 				if ($_POST['comp_garant_meses'] == -1) { $comp_garant_meses = "null";} else $comp_garant_meses = "'$comp_garant_meses'";

				$query = "INSERT INTO equipamentos ".
							"(comp_inv, comp_sn, comp_marca, comp_mb, comp_proc, comp_memo, comp_video, comp_som, ".
							"comp_rede, comp_modelohd, comp_modem, comp_cdrom, comp_dvd, comp_grav, comp_nome, ".
							"comp_local, comp_fornecedor, comp_nf, comp_coment, comp_data, comp_valor, comp_data_compra, ".
							"comp_inst, comp_ccusto, comp_tipo_equip, comp_tipo_imp, comp_resolucao, comp_polegada, ".
							"comp_fab, comp_situac, comp_tipo_garant, comp_garant_meses) ".
						"VALUES ('".$_POST['comp_inv']."','".noHtml($_POST['comp_sn'])."','".$_POST['comp_marca']."',".
							"".$_POST['comp_mb'].", ".$_POST['comp_proc'].",".$_POST['comp_memo'].",".$_POST['comp_video'].",".
							"".$_POST['comp_som'].", ".$_POST['comp_rede'].", ".$_POST['comp_modelohd'].", ".
							"".$_POST['comp_modem'].", ".$_POST['comp_cdrom'].", ".$_POST['comp_dvd'].", ".
							"".$_POST['comp_grav'].",'".noHtml($_POST['comp_nome'])."', '".$_POST['comp_local']."', ".
							"".$_POST['comp_fornecedor'].",'".noHtml($_POST['comp_nf'])."','".noHtml($_POST['comp_coment'])."', ".
							"'".date("Y-m-d H:i:s")."', '".$comp_valor."', '".datam($_POST['comp_data_compra'])."', ".
							"'".$_POST['comp_inst']."', ".$_POST['comp_ccusto'].", '".$_POST['comp_tipo_equip']."', ".
							"".$_POST['comp_tipo_imp'].", ".$_POST['comp_resolucao'].", ".$_POST['comp_polegada'].", ".
							"'".$_POST['comp_fab']."', '".$_POST['comp_situac']."', ".$_POST['comp_tipo_garant'].", ".
							"".$_POST['comp_garant_meses']." ".
							")";
					$resultado = mysql_query($query) or die (TRANS('ERR_INSERT'). '<br>'.$query);
					if ($resultado == 0)
					{
						$aviso = TRANS('MSG_NOT_INCLUDE_DATA');
					}
					else
					{
						$numero = mysql_insert_id();
						$aviso = TRANS('MSG_OK_EQUIP_CAD_SUCESS');
						$texto = "[Etiqueta = ".$_POST['comp_inv']."], [Unidade = ".$_POST['comp_inst']."]";
						geraLog(LOG_PATH.'invmon.txt',date("d-m-Y H:i:s"),$_SESSION['s_usuario'], $_SERVER['PHP_SELF'],$texto);
					}
				}
				print "<script>mensagem('".$aviso."'); ".
					"redirect('mostra_consulta_inv.php?comp_inv=".$_POST['comp_inv']."&comp_inst=".$_POST['comp_inst']."');".
				"</script>";

		}

	$cab->set_foot();
?>
<script type='text/javascript'>
<!--

	function valida(){

		var ok = validaForm('idTipo','COMBO','<?php print TRANS('FIELD_TYPE_EQUIP');?>' ,1);
		if (ok) var ok = validaForm('idFab','COMBO','<?php print TRANS('COL_MANUFACTURE');?>',1);
		if (ok) var ok = validaForm('idEtiqueta','INTEIRO','<?php print TRANS('OCO_FIELD_TAG');?>',1);
		if (ok) var ok = validaForm('idModelo','COMBO','<?php print TRANS('COL_MODEL');?>',1);
		if (ok) var ok = validaForm('idLocal','COMBO','<?php print TRANS('FIELD_LOCALIZATION');?>',1);
		if (ok) var ok = validaForm('idSituac','COMBO','<?php print TRANS('COL_SITUAC');?>',1);
		if (ok) var ok = validaForm('idUnidade','COMBO','<?php print TRANS('OCO_FIELD_UNIT');?>',1);
		if (ok) var ok = validaForm('idValor','MOEDASIMP','<?php print TRANS('FIELD_EQUIP_VALUE');?>',0);
		if (ok) var ok = validaForm('idDataCompra','DATA','<?php print TRANS('FIELD_DATE_PURCHASE');?>',0);

		return ok;

	}


	function desabilita(v)
	{
		document.form1.ok.disabled=v;
	}

 	function desabilitaCarrega(v){
		document.form1.ok2.disabled=v;
	}

	function Habilitar(){
		var inventario = document.form1.comp_inv.value;
		var ind_tipo_equip = document.form1.comp_tipo_equip.selectedIndex;
		var sel_tipo_equip = document.form1.comp_tipo_equip.options[ind_tipo_equip].value;
		var ind_comp_marca = document.form1.comp_marca.selectedIndex;
		var sel_comp_marca = document.form1.comp_marca.options[ind_comp_marca].value;
		var ind_fab = document.form1.comp_fab.selectedIndex;
		var sel_fab = document.form1.comp_fab.options[ind_fab].value;
		var ind_local = document.form1.comp_local.selectedIndex;
		var sel_local = document.form1.comp_local.options[ind_local].value;
		var ind_sit = document.form1.comp_situac.selectedIndex;
		var sel_sit = document.form1.comp_situac.options[ind_sit].value;
		var ind_inst = document.form1.comp_inst.selectedIndex;
		var sel_inst = document.form1.comp_inst.options[ind_inst].value;


		if ((inventario =="")||(sel_tipo_equip==-1)||(sel_comp_marca==-1)||(sel_fab==-1)||(sel_local==-1)||(sel_sit==-1)||(sel_inst==-1))
		{
			desabilita(true);
			//desabilita(false);

		} else {
			desabilita(false);

		}

	}

	function HabilitarCarrega(){
		var ind_comp_marca = document.form1.comp_marca.selectedIndex;
		var sel_comp_marca = document.form1.comp_marca.options[ind_comp_marca].value;
		if (sel_comp_marca==-1) {
			desabilitaCarrega(true);
		} else{
			desabilitaCarrega(false);
		}

	}

	//window.setInterval("Habilitar()",100);
	//window.setInterval("HabilitarCarrega()",100);


team = new Array(
<?php 
$conta = 0;
$conta_sub = 0;
$sql="select * from tipo_equip order by tipo_nome";//Somente as áreas ativas
$sql_result=mysql_query($sql);
echo mysql_error();
$num=mysql_numrows($sql_result);
while ($row_A=mysql_fetch_array($sql_result)){
$conta=$conta+1;
	$cod_item=$row_A['tipo_cod'];
		echo "new Array(\n";
		$sub_sql="select * from marcas_comp where marc_tipo='$cod_item' order by marc_nome";
		$sub_result=mysql_query($sub_sql);
		$num_sub=mysql_numrows($sub_result);
		if ($num_sub>=1){
			echo "new Array(\"".TRANS('SEL_MODEL')."\", -1),\n";
			while ($rowx=mysql_fetch_array($sub_result)){
				$codigo_sub=$rowx['marc_cod'];
				$sub_nome=$rowx['marc_nome'];
			$conta_sub=$conta_sub+1;
				if ($conta_sub==$num_sub){
					echo "new Array(\"$sub_nome\", $codigo_sub)\n";
					$conta_sub="";
				}else{
					echo "new Array(\"$sub_nome\", $codigo_sub),\n";
				}
			}
		}else{
			echo "new Array(\"".TRANS('OCO_SEL_ANY')."\", -1)\n";
		}
	if ($num>$conta){
		echo "),\n";
	}
}
echo ")\n";
echo ");\n";
?>

function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
	var i, j;
	var prompt;
	// empty existing items
	for (i = selectCtrl.options.length; i >= 0; i--) {
		selectCtrl.options[i] = null;
	}
	prompt = (itemArray != null) ? goodPrompt : badPrompt;
	if (prompt == null) {
		j = 0;
	}
	else {
		selectCtrl.options[0] = new Option(prompt);
		j = 1;
	}
	if (itemArray != null) {
		// add new items
		for (i = 0; i < itemArray.length; i++) {
			selectCtrl.options[j] = new Option(itemArray[i][0]);
			if (itemArray[i][1] != null) {
				selectCtrl.options[j].value = itemArray[i][1];
			}
			j++;
		}
	// select first item (prompt) for sub list
	selectCtrl.options[0].selected = true;
   }
}


//-->
</script>

