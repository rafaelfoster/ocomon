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
	$cab->set_title($TRANS["html_title"]);
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

		if ($linhas == 0)
		{
			print "<script>mensagem('".$TRANS["alerta_nao_encontrado"]."')</script>";
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
							<br><B><a onClick= \"javascript: popup_alerta('ocorrencias.php?popup=true&comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."')\" title='Ocorrências para esse equipamento'>Ocorrências</a></B><br>
							</td>";
					}

					print " <td width='10%' align='center'>";
					if ($row['tipo'] == 1 || $row['tipo']== 2){//Se o equipamento não for do tipo computador não terá softwares
						print "<br><B><a class='botao' onClick= \"javascript: popup_alerta('comp_soft.php?popup=true&comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."')\" title='Softwares instalados'>Softwares</a></B><br>";
					}
					print "</td>";

					print "<td width='10%' align='center'><br><B><a class='botao' ".
							"onClick= \"javascript: popup_alerta('hw_historico.php?inv=".$row['etiqueta']."&inst=".$row['cod_inst']."')\" ".
							"title='Histórico de alteração de componentes'>Histórico de Trocas</a></B><br>";
					print "</td>";


					print "<td width='10%' align='center'><br><B><a class='botao' ".
							"onClick= \"javascript: popup_alerta('mostra_historico.php?popup=true&comp_inv=".$row['etiqueta']."".
							"&comp_inst=".$row['cod_inst']."')\" title='Histórico de localização física do equipamento'>Locais</a></B><br>";
					print "</td>";
					print "<td width='10%'  align='center'><br><B><a class='botao' ".
							"onClick= \"javascript: popup_alerta('consulta_garantia.php?popup=true&comp_inv=".$row['etiqueta']."".
							"&comp_inst=".$row['cod_inst']."')\" title='Informações sobre a garantia do equipamento'>Garantia</a></B><br>";
					print "</td>";

					print "<td width='10%'  align='center'><br><B><a class='botao' ".
							"onClick=\"javascript: popup_alerta('docs_assoc_model.php?popup=true&model=".$row['modelo_cod']." ')\" ".
							"title='Documentos associados a esse modelo de equipamento'>Docs</a></B><br>";
					print "</td>";

					if ($_SESSION['s_invmon']==1){
						print "<td width='10%'  align='center'>
							<br><B><a class='botao' href='altera_dados_computador.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."'>Alterar dados</a></B><br>
							</td>";
					}
					print "</tr>";
					print "</table>";

					print "<table width='100%'>";
					print "<tr><TD colspan='4' align='left'><br><B>Dados Gerais:</B></td></tr>";
					print "</table>";
				}


				print "<TABLE border='0'  align='left' width='100%' >";

				//print "<tr><td colspan='4'>";
				//print "<TABLE border='0' cellpadding='1' cellspacing='2' align='center' width='100%'>";


				print "<tr><td colspan='4'></td></tr>";
				print "<tr>";
                		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Tipo de equipamento:</b></TD>";
                		print "<TD class='borda' width='30%' align='left' >".
                				"<a href='mostra_consulta_comp.php?comp_tipo_equip=".$row['tipo']."'".
                				" title='Listar todos os equipamentos do ".
                				"tipo ".$row['equipamento']."  que estão cadastrados no sistema.'>".$row['equipamento']."</a>".
                			"</TD>";
                		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Fabricante:</b></TD>";
                		print "<TD class='borda' width='30%' align='left' >".
                				"<a href='mostra_consulta_comp.php?comp_fab=".$row['fab_cod']."'".
                				" title='Listar todos os equipamentos do fabricante ".$row['fab_nome']."  cadastrados ".
                				"no sistema.'>".$row['fab_nome']."</a>".
                			"</TD>";
    				print "</tr>";
				print "<tr>";
                		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Etiqueta:</b></TD>".
                			"<TD class='borda' width='30%' align='left' >".$row['etiqueta']."</TD>".
                			"<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Número de Série:</b></TD>".
					"<TD class='borda' width='30%' align='left' >".strtoupper($row['serial'])."</TD>";
    				print "</tr>";

				print "<tr>";
                		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Modelo:</b></TD>".
                			"<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
                				"comp_marca=".$row['modelo_cod']."' title='Listar todos os equipamentos do ".
                				"modelo ".$row['modelo']."  cadastrados no sistema.'>".$row['modelo']."</a>".
                			"</TD>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Local:</b></TD>".
                			"<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
                				"comp_local=".$row['tipo_local']."' ".
                				"title='Listar todos os equipamentos do(a) ".$row['local']." que estão cadastrados no ".
                				"sistema.'>".$row['local']."</a>".
                			"</TD>";
				print "</tr>";

				print "<tr>";
				print "<TD  width='20%' align='left' bgcolor='".TD_COLOR."'><b>Situação:</b></TD>";
				print "<TD  class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_situac=".$row['situac_cod']."' ".
						"title='Listar todos os equipamentos que estão em situação ".$row['situac_nome']." cadastrados ".
						"no sistema.'>".$row['situac_nome']."</a>".
					"</TD><td colspan='2'></td>";
				print "</tr>";

				//print "</table></td></tr>";

				if (($row['tipo']==1) or ($row['tipo']==2) or ($row['tipo']==12)or ($row['tipo']==16)) {

					print "<tr><td colspan='4'></td></tr>";
					print "<tr><td colspan='4'><IMG ID='imgconfig' SRC='../../includes/icons/close.png' width='9' height='9' ".
							"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('config')\">&nbsp;<b>Dados complementares - Configuração: </b></td></tr>";
					print "<tr><td colspan='4'></td></tr>";
					print "<tr><td colspan='4'><div id='config'>"; //style='{display:none}'	//style='{padding-left:5px;}'

					print "<TABLE border='0' cellpadding='1' cellspacing='2' align='center' width='100%'>";

					print "<TR>";
					print "<TD width='20%' align='lef't bgcolor='".TD_COLOR."'><b>Nome do computador:</b></TD>";
					print "<TD class='borda' width='30%' align='left' >".$row['nome']."</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>MB:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?comp_mb=".$row['cod_mb']."' ".
							"title='Listar todos os equipamentos com a MB ".$row['fabricante_mb']." ".$row['mb']." cadastrados no sistema.'>".
							"".$row['fabricante_mb']." ".$row['mb']."</a>".
						"</TD>";
					print "</TR>";

					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Processador:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_proc=".$row['cod_processador']."' ".
							"title='Listar todos os equipamentos com o processador ".$row['processador']." ".$row['clock']." ".
							"".$row['proc_sufixo']." cadastrados no sistema.'>".$row['processador']." ".$row['clock']." ".
							"".$row['proc_sufixo']."</a>".
						"</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Memória:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_memo=".$row['cod_memoria']."' title".
							"='Listar todos os equipamentos com ".$row['memoria']." ".$row['memo_sufixo']." de memória ".
							"cadastrados no sistema.'>".$row['memoria']." ".$row['memo_sufixo']."</a>".
						"</TD>";
					print "</TR>";


					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Placa de vídeo:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?comp_video".
							"=".$row['cod_video']."' title='Listar todos os equipamentos".
							" com a placa de vídeo ".$row['fabricante_video']." ".$row['video']." cadastrados no sistema.'>".
							"".$row['fabricante_video']." ".$row['video']."</a>".
						"</TD>";

					print "<TD width='20%' align='lef't bgcolor='".TD_COLOR."'><b>Placa de som:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_som=".$row['cod_som']."' title='Listar todos os ".
							"equipamentos com a placa de som ".$row['fabricante_som']." ".$row['som']." cadastrados no sistema.'>".
							"".$row['fabricante_som']." ".$row['som']."</a>".
						"</TD>";
					print "</TR>";

					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Placa de Rede:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_rede=".$row['cod_rede']."' title='Listar todos os ".
							"equipamentos com a placa de rede ".$row['rede_fabricante']." ".$row['rede']." cadastrados no ".
							"sistema.'>".$row['rede_fabricante']." ".$row['rede']."</a>".
						"</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Placa de Fax/Modem:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_modem=".$row['cod_modem']."' title='Listar todos ".
							"os equipamentos com o modem ".$row['fabricante_modem']." ".$row['modem']." cadastrados no".
							" sistema.'>".$row['fabricante_modem']." ".$row['modem']."</a>".
						"</TD>";
					print "</TR>";
					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>HD:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_modelohd=".$row['cod_hd']."' title='Listar todos os ".
							"equipamentos com HD ".$row['fabricante_hd']." de ".$row['hd_capacidade']." ".$row['hd_sufixo']." ".
							"cadastrados no sistema.'>".$row['fabricante_hd']." ".$row['hd']." ".$row['hd_capacidade']." ".$row['hd_sufixo']."</a>".
						"</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Unidade CD-ROM:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_cdrom=".$row['cod_cdrom']."' title='Listar todos ".
							"os equipamentos com CD-ROM ".$row['fabricante_cdrom']." ".$row['cdrom']." cadastrados no ".
							"sistema.'>".$row['fabricante_cdrom']." ".$row['cdrom']."</a>".
						"</TD>";
					print "</TR>";

					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Unidade Gravadora de CD:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_grav=".$row['cod_gravador']."' title='Listar todos os ".
							"equipamentos com gravador ".$row['fabricante_gravador']." ".$row['gravador']." cadastrados no sistema.'>".
							"".$row['fabricante_gravador']." ".$row['gravador']."</a>".
						"</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Unidade de DVD:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_dvd=".$row['cod_dvd']."' title='Listar todos os ".
							"equipamentos com DVD ".$row['fabricante_dvd']." ".$row['dvd']." cadastrados no sistema.'>".
							"".$row['fabricante_dvd']." ".$row['dvd']."</a>".
						"</TD>";
					print "</TR>";
					print "</table>";
					print "</div></td></tr>";
				}

				if (($row['tipo']!=1) AND ($row['tipo']!=2)) { // O equipamento não é computador!!
					print "<TR><TD colspan='4'></TD></TR>";
					print "<tr><TD colspan='4'><b>Dados complementares - Configuração:</b></TD></tr>";
					print "<TR><TD colspan=4></TD></TR>";

					print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Tipo de Impressora:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_tipo_imp=".$row['tipo_imp']."' title='Listar todas as ".
							"impressoras do tipo ".$row['impressora']." cadastradas no sistema.'>".$row['impressora']."</a>".
						"</TD>";

					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Monitor:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_polegada=".$row['tipo_pole']."' title='Listar todos os ".
							"monitores com ".$row['polegada_nome']." cadastrados no sistema.'>".$row['polegada_nome']."</a>".
						"</TD>";
					print "</tr>";
					print "<tr>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Scanner:</b></TD>";
					print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
							"comp_resolucao=".$row['tipo_resol']."' title='Listar todos os ".
							"scanners com resolução de ".$row['resol_nome']." cadastrados no sistema.'>".$row['resol_nome']."</a>".
						"</TD>";
					print "</TR>";
				}

				print "<tr><td colspan='4'></td></tr>";
				print "<tr><td colspan='4'><IMG ID='imgcontabeis' SRC='../../includes/icons/open.png' width='9' height='9' ".
						"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('contabeis')\">&nbsp;<b>Dados complementares - Contábeis: </b></td></tr>";

				print "<tr><td colspan='4'></td></tr>";
				print "<tr><td colspan='4'><div id='contabeis' style='{display:none}'>"; //style='{display:none}'	//style='{padding-left:5px;}'
				print "<TABLE border='0' cellpadding='1' cellspacing='2' align='center' width='100%'>";

				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Unidade:</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_inst[]=".$row['cod_inst']."' title='Listar todos os ".
						"equipamentos cadastrados para ".$row['instituicao'].".'>".$row['instituicao']."</a>".
					"</TD>";

				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Centro de Custo:</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_ccusto=".$row['ccusto']."' title='Listar todos".
						" os equipamentos cadastrados para o centro de custo ".$centroCusto.".'>".$centroCusto."</a>".
					"</TD>";
				print "</tr>";
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Fornecedor:</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_fornecedor=".$row['fornecedor_cod']."' ".
						"title='Listar todos os equipamentos com o fornecedor ".$row['fornecedor_nome']." castrados no sistema.'>".
						"".$row['fornecedor_nome']."</a>".
					"</TD>";

				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Nota Fiscal:</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_nf=".$row['nota']."' title='Listar todos os ".
						"equipamentos da nota fiscal ".$row['nota']." cadastrados no sistema.'>".$row['nota']."</a>".
					"</TD>";
				print "</tr>";
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Valor (R$):</b></TD>";
				print "<TD class='borda' width='30%' align='left' >R$ ".$row['valor']."</TD>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Data da Compra:</b></TD>";
				print "<TD class='borda' width='30%' align='left' >".$row['data_compra']."</TD>";
				print "</tr>";
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Reitoria:</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_reitoria=".$row['reitoria_cod']."'".
						">".$row['reitoria']."</a>".
					"</TD>";

				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Data do cadastro:</b></TD>";
				print "<TD class='borda' width='30%' align='left' >".$row['data_cadastro']."</TD>";
				print "</TR>";
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Assistência Técnica:</b></TD>";
				print "<TD class='borda' width='30%' align='left' ><a href='mostra_consulta_comp.php?".
						"comp_assist=".$row['assistencia_cod']."'".
						">".$row['assistencia']."</a>".
					"</TD>";
				print "</TR>";
				print "</table>";
				print "</div></td></tr>";
				print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' ><b>Comentário:</b></TD>";
				print "<TD class='borda' colspan='3' width='30%' align='left' >".$row['comentario']."</TD>";
				print "</TR>";
				print "<tr><td colspan='4'></td></tr>";
				print "<tr><td colspan='4'><IMG ID='imganexos' SRC='../../includes/icons/open.png' width='9' height='9' ".
					"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('anexos')\">&nbsp;<b>Imagens associadas: </b></td></tr>";

				$noImg = false;

				print "<tr><td colspan='4'></td></tr>";
				print "<tr><td colspan='4'><div id='anexos' style='{display:none}'>"; //style='{display:none}'	//style='{padding-left:5px;}'
				print "<TABLE border='0' cellpadding='1' cellspacing='2' align='center' width='100%'>";

				$qryTela3 = "select  i.* from imagens i  WHERE i.img_model ='".$row['modelo_cod']."'  order by i.img_inv ";
				$execTela3 = mysql_query($qryTela3) or die ("NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DA TABELA DE IMAGENS!");
				//$rowTela = mysql_fetch_array($execTela);
				$isTela3 = mysql_num_rows($execTela3);
				$cont = 0;

				while ($rowTela3 = mysql_fetch_array($execTela3)) {
					$cont++;
					print "<tr>";
					print "<TD  width='20%' bgcolor='".TD_COLOR."' >Imagem ".$cont." do modelo:</td>";
					print "<td colspan='3' ><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$rowTela3['img_cod']."&cod=".$rowTela3['img_cod']."',".$rowTela3['img_largura'].",".$rowTela3['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela3['img_nome']."</a></TD>";
					print "</tr>";
					$noImg = true;
				}
				$qryTela2 = "select  i.* from imagens i  WHERE i.img_inst ='".$row['cod_inst']."' and i.img_inv ='".$row['etiqueta']."'  order by i.img_inv ";
				$execTela2 = mysql_query($qryTela2) or die ("NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DA TABELA DE IMAGENS!");
				$isTela2 = mysql_num_rows($execTela2);
				$cont = 0;
				while ($rowTela2 = mysql_fetch_array($execTela2)) {
					$cont++;
					print "<tr>";
					print "<TD  width='20%' bgcolor='".TD_COLOR."' >Anexo de inventário ".$cont.":</td>";
					print "<td colspan='3' ><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$rowTela2['img_cod']."&cod=".$rowTela2['img_cod']."',".$rowTela2['img_largura'].",".$rowTela2['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela2['img_nome']."</a></TD>";
					print "</tr>";
					$noImg = true;
				}

				$qryTela = "select o.*, i.* from ocorrencias o , imagens i
							WHERE (i.img_oco = o.numero) and (o.equipamento ='".$row['etiqueta']."' and o.instituicao ='".$row['cod_inst']."')  order by o.numero ";
				$execTela = mysql_query($qryTela) or die ("NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DA TABELA DE IMAGENS!");
				$isTela = mysql_num_rows($execTela);
				$cont = 0;
				while ($rowTela = mysql_fetch_array($execTela)) {
					$cont++;
					print "<tr>";
					print "<TD  width='20%' bgcolor='".TD_COLOR."' >Anexo da ocorrência <a onClick= \"javascript:popup_alerta('../../ocomon/geral/mostra_consulta.php?popup=true&numero=".$rowTela['img_oco']."')\"><font color='blue'>".$rowTela['img_oco']."</font></a>:</td>";
					print "<td colspan='3' ><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$rowTela['img_oco']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela['img_nome']."</a></TD>";
					print "</tr>";
					$noImg = true;
				}

				if (!$noImg) {
					print "<tr><td width='40%' bgcolor='yellow'>&nbsp;NÃO EXISTEM IMAGENS ASSOCIADAS A ESSE EQUIPAMENTO!</td><td colspan='3' ></td></tr>";
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
		$aviso = "Dados incompletos, preencha todos os campos de consulta!";

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