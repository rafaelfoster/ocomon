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

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	$cab = new headers;
	$cab->set_title(TRANS('TTL_INVMON'));
	$auth = new auth;

	print "<html>";
	print "<body>";
	$auth = new auth;

/*	if (isset($_GET['popup'])) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
	} else*/
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);

	if ($_SESSION['s_invmon']!=1) {
		print "<script>window.open('../../index.php','_parent','')</script>";
		exit;
	}

 	if (isset($_REQUEST['comp_inv'])) {

 		$query = "";
 		$query = $QRY["full_detail_ini"];// ../includes/queries/
 		$query.=" and (c.comp_inv in (".$_REQUEST['comp_inv']."))"; //(c.comp_inv in ($comp_inv)))

		if ($_REQUEST['comp_inst']!=-1) {
			$query.= " and (inst.inst_cod in (".$_REQUEST['comp_inst']."))";
		}

        	$query.= $QRY["full_detail_fim"];
		$resultado = mysql_query($query);
		$linhas = mysql_num_rows($resultado);
		//dump ($query);
		//exit;
		if ($linhas == 0)
		{
			print "<script>mensagem('".TRANS('MSG_THIS_CONS_NOT_RESULT')."')</script>";
			print "<script>history.back()</script>";
			exit;
		}
		else
		{
			print "<FORM method='POST' action=".$_SERVER['PHP_SELF'].">";
			while ($row = mysql_fetch_array($resultado)) {
				if (!(empty($row['ccusto'])))
				{
					$CC =  $row['ccusto'];
					$query2 = "select * from `".DB_CCUSTO."`.".TB_CCUSTO." where ".CCUSTO_ID."= $CC "; //and ano=2003
					$resultado2 = mysql_query($query2);
					$row2 = mysql_fetch_array($resultado2);
					$centroCusto = $row2[CCUSTO_DESC];
					$custoNum = $row2[CCUSTO_COD];
				} else $centroCusto = '';

				//GERAÇÃO DE LOG DAS CONSULTAS EFETUADAS NO SISTEMA

				$inst = $row['instituicao'];
				$texto = "[Etiqueta = ".$_REQUEST['comp_inv']."], [Unidade = ".$row['instituicao']."]";

				geraLog(LOG_PATH.'invmon.txt',date ("d-m-Y H:i:s"),$_SESSION['s_usuario'],$_SERVER['PHP_SELF'],$texto);

        			if ($linhas == 1){

					print "<table width='100%'>";
					print "<tr>";
					if ($_SESSION['s_ocomon']==1){
						print "<td  width='10%' align='center'>
							<br><B><a onClick= \"javascript: popup_alerta('ocorrencias.php?popup=true&comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."')\" title='".TRANS('HNT_OCCO_EQUIP')."'>".TRANS('MNS_OCORRENCIAS')."</a></B><br>
							</td>";
					}

					print " <td width='10%' align='center'>";
					if ($row['tipo'] == 1 || $row['tipo']== 2){//Se o equipamento não for do tipo computador não terá softwares
						print "<br><B><a class='botao' onClick= \"javascript: popup_alerta('comp_soft.php?popup=true&comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."')\" title='".TRANS('HNT_SW_INSTALL')."'>".TRANS('MNL_SW')."</a></B><br>";
					}
					print "</td>";

					print "<td width='10%' align='center'><br><B><a class='botao' ".
							"onClick= \"javascript: popup_alerta('hw_historico.php?inv=".$row['etiqueta']."&inst=".$row['cod_inst']."')\" ".
							"title='".TRANS('HNT_HISTORY_ALTER_COMP')."'>".TRANS('LINK_HISTORY_EXCHANGE')."</a></B><br>";
					print "</td>";


					print "<td width='10%' align='center'><br><B><a class='botao' ".
							"onClick= \"javascript: popup_alerta('mostra_historico.php?popup=true&comp_inv=".$row['etiqueta']."".
							"&comp_inst=".$row['cod_inst']."')\" title='".TRANS('HNT_HISTORY_LOCAL_EQUIP')."'>".TRANS('MNL_LOCAIS')."</a></B><br>";
					print "</td>";
					print "<td width='10%'  align='center'><br><B><a class='botao' ".
							"onClick= \"javascript: popup_alerta('consulta_garantia.php?popup=true&comp_inv=".$row['etiqueta']."".
							"&comp_inst=".$row['cod_inst']."')\" title='".TRANS('HNT_INFO_GARANT_EQUIP')."'>".TRANS('LINK_GUARANT')."</a></B><br>";
					print "</td>";

					print "<td width='10%'  align='center'><br><B><a class='botao' ".
							"onClick=\"javascript: popup_alerta('docs_assoc_model.php?popup=true&model=".$row['modelo_cod']." ')\" ".
							"title='".TRANS('HNT_DOCS_ASSOC_EQUIP')."'>".TRANS('LINK_DOCUMENTS')."</a></B><br>";
					print "</td>";

					if ($_SESSION['s_invmon']==1){
						print "<td width='10%'  align='center'>
							<br><B><a class='botao' href='altera_dados_computador.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."'>".TRANS('LINK_ALTER_DATA')."</a></B><br>
							</td>";
					}
					print "</tr>";
					print "</table>";

					print "<table width='100%'>";
					print "<tr><TD colspan='4' align='left'><br><B>".TRANS('TXT_GENERAL_DATA').":</B></td></tr></table>";
				}


				print "<TABLE border='0'  align='left' width='100%' >";

				//print "<tr><td colspan='4'>";
				//print "<TABLE border='0' cellpadding='1' cellspacing='2' align='center' width='100%'>";


				print "<tr><td colspan='4'></td></tr>";
				print "<tr>";
                		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_TYPE_EQUIP').":</b></TD>";
                		print "<TD class='borda' width='30%' align='left' >".
                				"<a href='mostra_consulta_comp.php?comp_tipo_equip=".$row['tipo']."'".
                				" title='".TRANS('HNT_LIST_EQUIP_TYPE')." ".$row['equipamento']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['equipamento']."</a>".
                			"</TD>";
                		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_MANUFACTURE').":</b></TD>";
                		print "<TD class='borda' width='30%' align='left' >".
                				"<a href='mostra_consulta_comp.php?comp_fab=".$row['fab_cod']."'".
                				" title='".TRANS('HNT_LIST_EQUIP_MANUF')." ".$row['fab_nome']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['fab_nome']."</a>".
                			"</TD>";
    				print "</tr>";
				print "<tr>";
                		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('OCO_FIELD_TAG').":</b></TD>".
                			"<TD class='borda' width='30%' align='left' >".$row['etiqueta']."</TD>".
                			"<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_SN').":</b></TD>".
					"<TD class='borda' width='30%' align='left' >".strtoupper($row['serial'])."</TD>";
    				print "</tr>";

				print "<tr>";
                		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_MODEL').":</b></TD>".
                			"<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
                				"comp_marca=".$row['modelo_cod']."' title='".TRANS('HNT_LIST_EQUIP_MODEL')." ".$row['modelo']."  ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['modelo']."</a>".
                			"</TD>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('OCO_LOCAL').":</b></TD>".
                			"<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
                				"comp_local=".$row['tipo_local']."' ".
                				"title='".TRANS('HNT_LIST_EQUIP_LOCAL')." ".$row['local']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['local']."</a>".
                			"</TD>";
				print "</tr>";

				print "<tr>";
				print "<TD  width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_SITUAC').":</b></TD>";
				print "<TD  class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_situac=".$row['situac_cod']."' ".
						"title='".TRANS('HNT_LIST_EQUIP_SITUAC')." ".$row['situac_nome']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['situac_nome']."</a>".
					"</TD><td colspan='2'></td>";
				print "</tr>";

				//print "</table></td></tr>";

				if (($row['tipo']==1) or ($row['tipo']==2) or ($row['tipo']==12)or ($row['tipo']==16)) {

					print "<tr><td colspan='4'></td></tr>";
					print "<tr><td colspan='4'><IMG ID='imgconfig' SRC='../../includes/icons/close.png' width='9' height='9' ".
							"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('config')\">&nbsp;<b>".TRANS('SUBTTL_DATA_COMPLE_CONFIG').": </b></td></tr>";
					print "<tr><td colspan='4'></td></tr>";
					print "<tr><td colspan='4'><div id='config'>"; //style='{display:none}'	//style='{padding-left:5px;}'

					print "<TABLE border='0' cellpadding='1' cellspacing='2' align='center' width='100%'>";

					print "<TR>";
					print "<TD width='20%' align='lef't bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_NAME_COMPUTER').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' >".$row['nome']."</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_MB').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?comp_mb=".$row['cod_mb']."' ".
							"title='".TRANS('HNT_LIST_EQUIP_MOTHERBOARD')." ".$row['fabricante_mb']." ".$row['mb']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".
							"".$row['fabricante_mb']." ".$row['mb']."</a>".
						"</TD>";
					print "</TR>";

					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_PROC').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_proc=".$row['cod_processador']."' ".
							"title='".TRANS('HNT_LIST_EQUIP_PROCESSOR')." ".$row['processador']." ".$row['clock']." ".
							"".$row['proc_sufixo']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['processador']." ".$row['clock']." ".
							"".$row['proc_sufixo']."</a>".
						"</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_MEMO').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_memo=".$row['cod_memoria']."' title".
							"='".TRANS('HNT_LIST_EQUIP_WITH')." ".$row['memoria']." ".$row['memo_sufixo']." ".TRANS('HNT_LIST_EQUIP_OF_MEMORY')." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['memoria']." ".$row['memo_sufixo']."</a>".
						"</TD>";
					print "</TR>";


					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_VIDEO').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?comp_video".
							"=".$row['cod_video']."' title='".TRANS('HNT_LIST_EQUIP_VIDEO')." ".$row['fabricante_video']." ".$row['video']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".
							"".$row['fabricante_video']." ".$row['video']."</a>".
						"</TD>";

					print "<TD width='20%' align='lef't bgcolor='".TD_COLOR."'><b>".TRANS('MNL_SOM').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_som=".$row['cod_som']."' title='".TRANS('HNT_LIST_EQUIP_AUDIO')." ".$row['fabricante_som']." ".$row['som']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".
							"".$row['fabricante_som']." ".$row['som']."</a>".
						"</TD>";
					print "</TR>";

					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_REDE').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_rede=".$row['cod_rede']."' title='".TRANS('HNT_LIST_EQUIP_NETWORK')." ".$row['rede_fabricante']." ".$row['rede']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['rede_fabricante']." ".$row['rede']."</a>".
						"</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_MODEM').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_modem=".$row['cod_modem']."' title='HNT_LIST_EQUIP_MODEM ".$row['fabricante_modem']." ".$row['modem']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['fabricante_modem']." ".$row['modem']."</a>".
						"</TD>";
					print "</TR>";
					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_HD').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_modelohd=".$row['cod_hd']."' title='HNT_LIST_EQUIP_HARDDISK ".$row['fabricante_hd']." ".TRANS('TXT_OF')." ".$row['hd_capacidade']." ".$row['hd_sufixo']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['fabricante_hd']." ".$row['hd']." ".$row['hd_capacidade']." ".$row['hd_sufixo']."</a>".
						"</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_CDROM').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_cdrom=".$row['cod_cdrom']."' title='".TRANS('HNT_LIST_EQUIP_CDROM')." ".$row['fabricante_cdrom']." ".$row['cdrom']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['fabricante_cdrom']." ".$row['cdrom']."</a>".
						"</TD>";
					print "</TR>";

					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_RECORD_CD').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_grav=".$row['cod_gravador']."' title='".TRANS('HNT_LIST_EQUIP_RECORD')." ".$row['fabricante_gravador']." ".$row['gravador']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".
							"".$row['fabricante_gravador']." ".$row['gravador']."</a>".
						"</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('MNL_DVD').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_dvd=".$row['cod_dvd']."' title='".TRANS('HNT_LIST_EQUIP_DVD')." ".$row['fabricante_dvd']." ".$row['dvd']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".
							"".$row['fabricante_dvd']." ".$row['dvd']."</a>".
						"</TD>";
					print "</TR>";
					print "</table>";
					print "</div></td></tr>";
				}

				if (($row['tipo']!=1) AND ($row['tipo']!=2)) { // O equipamento não é computador!!
					print "<TR><TD colspan='4'></TD></TR>";
					print "<tr><TD colspan='4'><b>".TRANS('SUBTTL_DATA_COMPLE_CONFIG').":</b></TD></tr>";
					print "<TR><TD colspan=4></TD></TR>";

					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_TYPE_PRINTER').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_tipo_imp=".$row['tipo_imp']." title='".TRANS('HNT_LIST_TYPE_PRINTER')." ".$row['impressora']."".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['impressora']."</a>".
						"</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_MONITOR').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_polegada=".$row['tipo_pole']."' title='".TRANS('HNT_LIST_MONITOR')." ".$row['polegada_nome']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['polegada_nome']."</a>".
						"</TD>";
					print "</tr>";
					print "<tr>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_SCANNER').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_resolucao=".$row['tipo_resol']."' title='".TRANS('HNT_LIST_RESOLUTION_SCANNER')." ".$row['resol_nome']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['resol_nome']."</a>".
						"</TD>";
					print "</TR>";
				}

				NL(4);

				$qryPieces = "";
				$qryPieces = $QRY["componenteXequip_ini"];// ../includes/queries/
				$qryPieces.=" and eqp.eqp_equip_inv in (".$_REQUEST['comp_inv'].") and eqp.eqp_equip_inst=".$_REQUEST['comp_inst']."";
				$qryPieces.= $QRY["componenteXequip_fim"];

				$execQryPieces = mysql_query($qryPieces) or die (TRANS('ERR_QUERY')."<br>".$qryPieces);

				print "<TR><TD colspan='4'></TD></TR>";
				print "<tr><TD colspan='4'><b>".TRANS('SUBTTL_DATA_COMPLE_PIECES').":</b></TD></tr>";
				print "<TR><TD colspan=4></TD></TR>";


				while ($rowPiece = mysql_fetch_array($execQryPieces)){


					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$rowPiece['item_nome'].":</b></TD>";
					print "<TD class='borda' width='30%' align='left' >".
						//"<a href='mostra_consulta_comp.php?piece=".$rowPiece['estoq_desc']."'>".
						$rowPiece['fabricante']." ".$rowPiece['modelo']." ".$rowPiece['capacidade']." ".$rowPiece['sufixo']."".
						//"</a>".
						"</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_SN').":</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a onClick=\"popup('estoque.php?action=details&cod=".$rowPiece['estoq_cod']."&cellStyle=true')\">".$rowPiece['estoq_sn']."</a></TD>";

					print "</tr>";

						//"<a href='mostra_consulta_comp.php?comp_dvd=".$row['cod_dvd']."' title='".TRANS('HNT_LIST_EQUIP_DVD')." ".$row['fabricante_dvd']." ".$row['dvd']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>";

				}





				print "<tr><td colspan='4'></td></tr>";
				print "<tr><td colspan='4'><IMG ID='imgcontabeis' SRC='../../includes/icons/open.png' width='9' height='9' ".
						"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('contabeis')\">&nbsp;<b>".TRANS('TXT_OBS_DATA_COMPLEM_2').": </b></td></tr>";

				print "<tr><td colspan='4'></td></tr>";
				print "<tr><td colspan='4'><div id='contabeis' style='{display:none}'>"; //style='{display:none}'	//style='{padding-left:5px;}'
				print "<TABLE border='0' cellpadding='1' cellspacing='2' align='center' width='100%'>";

				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('OCO_FIELD_UNIT').":</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_inst[]=".$row['cod_inst']."' title='".TRANS('HNT_LIST_EQUIP_CAD_TO')." ".$row['instituicao'].".'>".$row['instituicao']."</a>".
					"</TD>";

				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_CENTER_COST').":</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_ccusto=".$row['ccusto']."' title='".TRANS('HNT_LIST_EQUIP_CAD_TO_CENTER_COST')." ".$centroCusto.".'>".$centroCusto."</a>".
					"</TD>";
				print "</tr>";
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_VENDOR').":</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_fornecedor=".$row['fornecedor_cod']."' ".
						"title='".TRANS('HNT_LIST_EQUIP_SUPPLIER')." ".$row['fornecedor_nome']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".
						"".$row['fornecedor_nome']."</a>".
					"</TD>";

				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_FISCAL_NOTES').":</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_nf=".$row['nota']."' title='".TRANS('HNT_LIST_EQUIP_FISCAL_NOTES')." ".$row['nota']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>".$row['nota']."</a>".
					"</TD>";
				print "</tr>";
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_VALUE').":</b></TD>";
				print "<TD class='borda' width='30%' align='left' >R$ ".valueSeparator($row['valor'],',')."</TD>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_DATE_PURCHASE').":</b></TD>";
				print "<TD class='borda' width='30%' align='left' >".$row['data_compra']."</TD>";
				print "</tr>";
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_MAJOR').":</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_reitoria=".$row['reitoria_cod']."'".
						">".$row['reitoria']."</a>".
					"</TD>";

				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_SUBSCRIBE_DATE').":</b></TD>";
				print "<TD class='borda' width='30%' align='left' >".$row['data_cadastro']."</TD>";
				print "</TR>";
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".TRANS('FIELD_TECH_ASSIST').":</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_assist=".$row['assistencia_cod']."'".
						">".$row['assistencia']."</a>".
					"</TD>";
				print "</TR>";
				print "</table>";
				print "</div></td></tr>";
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' ><b>".TRANS('COL_COMMENT').":</b></TD>";
				print "<TD class='borda' colspan='3' width='30%' align='left' >".$row['comentario']."</TD>";
				print "</TR>";
				print "<tr><td colspan='4'></td></tr>";
				print "<tr><td colspan='4'><IMG ID='imganexos' SRC='../../includes/icons/open.png' width='9' height='9' ".
					"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('anexos')\">&nbsp;<b>".TRANS('FIELD_IMAGE_ASSOC').": </b></td></tr>";

				$noImg = false;

				print "<tr><td colspan='4'></td></tr>";
				print "<tr><td colspan='4'><div id='anexos' style='{display:none}'>"; //style='{display:none}'	//style='{padding-left:5px;}'
				print "<TABLE border='0' cellpadding='1' cellspacing='2' align='center' width='100%'>";

				$qryTela3 = "select  i.* from imagens i  WHERE i.img_model ='".$row['modelo_cod']."'  order by i.img_inv ";
				$execTela3 = mysql_query($qryTela3) or die (TRANS('MSG_ERR_NOT_INFO_IMAGE'));
				//$rowTela = mysql_fetch_array($execTela);
				$isTela3 = mysql_num_rows($execTela3);
				$cont = 0;

				while ($rowTela3 = mysql_fetch_array($execTela3)) {
					$cont++;
					print "<tr>";
					print "<TD  width='20%' bgcolor='".TD_COLOR."' >".TRANS('TXT_IMAGE')." ".$cont." ".TRANS('TXT_OF_MODEL').":</td>";
					print "<td colspan='3' ><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$rowTela3['img_cod']."&cod=".$rowTela3['img_cod']."',".$rowTela3['img_largura'].",".$rowTela3['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela3['img_nome']."</a></TD>";
					print "</tr>";
					$noImg = true;
				}
				$qryTela2 = "select  i.* from imagens i  WHERE i.img_inst ='".$row['cod_inst']."' and i.img_inv ='".$row['etiqueta']."'  order by i.img_inv ";
				$execTela2 = mysql_query($qryTela2) or die (TRANS('MSG_ERR_NOT_INFO_IMAGE'));
				$isTela2 = mysql_num_rows($execTela2);
				$cont = 0;
				while ($rowTela2 = mysql_fetch_array($execTela2)) {
					$cont++;
					print "<tr>";
					print "<TD  width='20%' bgcolor='".TD_COLOR."' >".TRANS('TXT_INV_ATTACH')." ".$cont.":</td>";
					print "<td colspan='3' ><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$rowTela2['img_cod']."&cod=".$rowTela2['img_cod']."',".$rowTela2['img_largura'].",".$rowTela2['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela2['img_nome']."</a></TD>";
					print "</tr>";
					$noImg = true;
				}

				$qryTela = "select o.*, i.* from ocorrencias o , imagens i
							WHERE (i.img_oco = o.numero) and (o.equipamento ='".$row['etiqueta']."' and o.instituicao ='".$row['cod_inst']."')  order by o.numero ";
				$execTela = mysql_query($qryTela) or die (TRANS('MSG_ERR_NOT_INFO_IMAGE'));
				$isTela = mysql_num_rows($execTela);
				$cont = 0;
				while ($rowTela = mysql_fetch_array($execTela)) {
					$cont++;
					print "<tr>";
					print "<TD  width='20%' bgcolor='".TD_COLOR."' >".TRANS('TXT_OCCO_ATTACH')." <a onClick= \"javascript:popup_alerta('../../ocomon/geral/mostra_consulta.php?popup=true&numero=".$rowTela['img_oco']."')\"><font color='blue'>".$rowTela['img_oco']."</font></a>:</td>";
					print "<td colspan='3' ><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$rowTela['img_oco']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela['img_nome']."</a></TD>";
					print "</tr>";
					$noImg = true;
				}

				if (!$noImg) {
					print "<tr><td width='40%' bgcolor='yellow'>&nbsp;".TRANS('MSG_NO_IMAGE_ASSOC_EQUIP')."</td><td colspan='3' ></td></tr>";
				}

				print "</table>";
				print "</div></td></tr>";

				print "<TR><TD colspan='4' align='left' bgcolor= ".TD_COLOR.">&nbsp</TD></TR>";
				print "<tr><td colspan='4'><img src='tesoura.png'> - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td></tr>";

				print "<TR><TD colspan='4'></TD><TD colspan='4'></TD></TR>";
				print "</table>";
				print "</FORM>";
			} //while $row

		} //linhas != 0
	}
	else
	{ //Se não for passado o código de inventário e a Unidade como parâmetro!!
		$aviso = TRANS('MSG_EMPTY_DATA');

		print "<script>mensagem('".$aviso."'); redirect('consulta_inv.php'); </script>";
	}

?>

<SCRIPT LANGUAGE='javaScript'>
<!--

	function invertView(id) {
		var element = document.getElementById(id);
		var elementImg = document.getElementById('img'+id);
		var address = '../../includes/icons/';

		if (element.style.display=='none'){
			element.style.display='';
			elementImg.src = address+'close.png';
		} else {
			element.style.display='none';
			elementImg.src = address+'open.png';
		}
	}

	desabilitaLinks(<?print $_SESSION['s_invmon'];?>);
//-->
</script>
<?
	print "</body>";
	print "</html>";
?>