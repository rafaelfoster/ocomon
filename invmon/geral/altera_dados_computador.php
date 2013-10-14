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
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);


	$hoje = date("d-m-Y");
	$hoje2 = date("Y-m-d H:i:s");
	$hojeLog = date ("d-m-Y H:i:s");

        $query = $QRY["full_detail_ini"];
	$query.= " and (c.comp_inv = ".$_REQUEST['comp_inv'].") and (inst.inst_cod = ".$_REQUEST['comp_inst'].") ";
	$query.= $QRY["full_detail_fim"];

	$mostra = $query;

		//print "<br>".$query."<br>";

	$resultado = mysql_query($query);
	$linhas = mysql_num_rows($resultado);
	$row = mysql_fetch_array($resultado);

	if (mysql_num_rows($resultado)>0)
	{
		$linhas = mysql_num_rows($resultado)-1;
	}
	else
	{
		$linhas = mysql_num_rows($resultado);
	}

	print "<BR><b>Alteração de dados do equipamento:</b><BR><br>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."'  ENCTYPE='multipart/form-data' onSubmit='return valida()'>";
	print "<TABLE border='0'  align='left' width='100%'>";

		print "<tr> <td colspan='4'>Dados complementares - GERAIS:</td></tr>";

		print "<tr>";
			//TIPO DE EQUIPAMENTO
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Tipo de equipamento:</TD>";

			$qry = "SELECT * FROM tipo_equip order by tipo_nome";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='equipamentos' size='1' id='idEquipamento'>";
			print "<option value=-1>Selecione o tipo de equipamento</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['tipo_cod']."'";
				if ($rowqry['tipo_cod'] == $row['tipo']){
					print " selected";
				}
				print ">".$rowqry['tipo_nome']."</option>";
			}
			print "</SELECT>";
			print "</TD>";


			//FABRICANTE
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Fabricante:</TD>";

			$qry = "SELECT * FROM fabricantes ORDER BY fab_nome";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='fabricante' size=1 id='idFabricante'>";
			print "<option value=-1>Selecione o fabricante</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['fab_cod']."'";
				if ($rowqry['fab_cod'] == $row['fab_cod']){
					print " selected";
				}
				print ">".$rowqry['fab_nome']."</option>";
			}
			print "</SELECT>";
			print "</TD>";

		print "</tr>";

		print "<tr>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Inventário:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>".$row['etiqueta']."</TD>";
			$inventario = $row['etiqueta'];
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Número de Série:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='serial' value='".$row['serial']."' ></TD>";
		print "</tr>";

		print "<tr>";
			//MODELO DO EQUIPAMENTO
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Modelo do equipamento:</TD>";

			$qry = "SELECT * from marcas_comp order by marc_nome";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='modelo' size='1' id='idModelo'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['marc_cod']."'";
				if ($rowqry['marc_cod'] == $row['modelo_cod']){
					print " selected";
				}
				print ">".$rowqry['marc_nome']."</option>";
			}
			print "</SELECT>";
			print "</TD>";

			//LOCALIZAÇÃO
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Localização:</TD>";

			$qry = "SELECT * FROM localizacao ORDER BY local";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='local' size=1 id='idLocal'>";
			print "<option value=-1>Selecione o local</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['loc_id']."'";
				if ($rowqry['loc_id'] == $row['tipo_local']){
					print " selected";
				}
				print ">".$rowqry['local']."</option>";
			}
			print "</SELECT>";
			print "</TD>";

		print "</tr>";
		print "<tr>";
			//SITUAÇÃO
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Situação:</TD>";
			$qry = "SELECT * from situacao order by situac_nome";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='situacao' size=1 id='idSituacao'>";
			print "<option value=-1>Selecione a situação</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['situac_cod']."'";
				if ($rowqry['situac_cod'] == $row['situac_cod']){
					print " selected";
				}
				print ">".$rowqry['situac_nome']."</option>";
			}
			print "</SELECT>";
			print "</TD>";

			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Anexar imagem:</TD>";
			print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><INPUT type='file' class='text' name='img' id='idImg'></TD>";
		print "</tr>";

		print "<tr><td colspan='4'></td></tr>";
		print "<tr> <td colspan='4'> Dados complementares - (Esses campos só estarão preenchidos para equipamentos do tipo COMPUTADOR)</td></tr>";
		print "<tr><td colspan='4'></td></tr>";


		print "<tr>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Nome do computador:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text'  name='nome'  value='".$row['nome']."''></TD>";
			//MB
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>MB:</TD>";

			$qry = "select * from modelos_itens where mdit_tipo = 10 order by mdit_fabricante, mdit_desc";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='mb' size=1 id='idMb'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['mdit_cod']."'";
				if ($rowqry['mdit_cod'] == $row['cod_mb']){
					print " selected";
				}
				print ">".$rowqry['mdit_fabricante']." ".$rowqry['mdit_desc']." ".$rowqry['mdit_desc_capacidade']." ".$rowqry['mdit_sufixo']."</option>";
			}
			print "</SELECT>";
			print "</TD>";
		print "</tr>";

		print "<tr>";
			//PROCESSADOR
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Processador:</TD>";

			$qry = "select * from modelos_itens where mdit_tipo = 11 order by mdit_fabricante, mdit_desc";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='processador' size=1 id='idProcessador'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['mdit_cod']."'";
				if ($rowqry['mdit_cod'] == $row['cod_processador']){
					print " selected";
				}
				print ">".$rowqry['mdit_fabricante']." ".$rowqry['mdit_desc']." ".$rowqry['mdit_desc_capacidade']." ".$rowqry['mdit_sufixo']."</option>";
			}
			print "</SELECT>";
			print "</TD>";

			//MEMÓRIA
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Memória:</TD>";

			$qry = "select * from modelos_itens where mdit_tipo = 7 order by mdit_desc_capacidade";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='memoria' id='idMb'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['mdit_cod']."'";
				if ($rowqry['mdit_cod'] == $row['cod_memoria']){
					print " selected";
				}
				print ">".$rowqry['mdit_desc']." ".$rowqry['mdit_desc_capacidade']." ".$rowqry['mdit_sufixo']."</option>";
			}
			print "</SELECT>";
			print "</TD>";
		print "</tr>";

		print "<tr>";
			//PLACA DE VÍDEO
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Placa de vídeo:</TD>";

			$qry = "select * from modelos_itens where mdit_tipo = 2 order by mdit_fabricante, mdit_desc";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='video' size=1 id='idVideo'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['mdit_cod']."'";
				if ($rowqry['mdit_cod'] == $row['cod_video']){
					print " selected";
				}
				print ">".$rowqry['mdit_fabricante']." ".$rowqry['mdit_desc']." ".$rowqry['mdit_desc_capacidade']." ".$rowqry['mdit_sufixo']."</option>";
			}
			print "</SELECT>";
			print "</TD>";

			//PLACA DE SOM
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Placa de som:</TD>";

			$qry = "select * from modelos_itens where mdit_tipo = 4 order by mdit_fabricante, mdit_desc";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='som' size=1 id='idSom'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['mdit_cod']."'";
				if ($rowqry['mdit_cod'] == $row['cod_som']){
					print " selected";
				}
				print ">".$rowqry['mdit_fabricante']." ".$rowqry['mdit_desc']." ".$rowqry['mdit_desc_capacidade']." ".$rowqry['mdit_sufixo']."</option>";
			}
			print "</SELECT>";
			print "</TD>";
			print "</tr>";

			print "<tr>";
			//HD
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>HD:</TD>";

			$qry = "select * from modelos_itens where mdit_tipo = 1 order by mdit_fabricante, mdit_desc";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='hd' size=1 id='idHd'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['mdit_cod']."'";
				if ($rowqry['mdit_cod'] == $row['cod_hd']){
					print " selected";
				}
				print ">".$rowqry['mdit_fabricante']." ".$rowqry['mdit_desc']." ".$rowqry['mdit_desc_capacidade']." ".$rowqry['mdit_sufixo']."</option>";
			}
			print "</SELECT>";
			print "</TD>";

			//PLACA DE REDE
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Placa de rede:</TD>";

			$qry = "select * from modelos_itens where mdit_tipo = 3 order by mdit_fabricante, mdit_desc";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='rede' size=1 id='idRede'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['mdit_cod']."'";
				if ($rowqry['mdit_cod'] == $row['cod_rede']){
					print " selected";
				}
				print ">".$rowqry['mdit_fabricante']." ".$rowqry['mdit_desc']." ".$rowqry['mdit_desc_capacidade']." ".$rowqry['mdit_sufixo']."</option>";
			}
			print "</SELECT>";
			print "</TD>";
			print "</tr>";

			print "<tr>";
			//PLACA DE FAX/MODEM
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Placa de fax/modem:</TD>";

			$qry = "select * from modelos_itens where mdit_tipo = 6 order by mdit_fabricante, mdit_desc";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='modem' size=1 id='idModem'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['mdit_cod']."'";
				if ($rowqry['mdit_cod'] == $row['cod_modem']){
					print " selected";
				}
				print ">".$rowqry['mdit_fabricante']." ".$rowqry['mdit_desc']." ".$rowqry['mdit_desc_capacidade']." ".$rowqry['mdit_sufixo']."</option>";
			}
			print "</SELECT>";
			print "</TD>";

			//CD-ROM
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Unidade de CD-ROM:</TD>";

			$qry = "select * from modelos_itens where mdit_tipo = 5 order by mdit_fabricante, mdit_desc";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='cdrom' size='1' id='idCdrom'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['mdit_cod']."'";
				if ($rowqry['mdit_cod'] == $row['cod_cdrom']){
					print " selected";
				}
				print ">".$rowqry['mdit_fabricante']." ".$rowqry['mdit_desc']." ".$rowqry['mdit_desc_capacidade']." ".$rowqry['mdit_sufixo']."</option>";
			}
			print "</SELECT>";
			print "</TD>";
		print "</tr>";

		print "<tr>";
			//GRAVADOR DE CD
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Unidade Gravadora de CD:</TD>";

			$qry = "select * from modelos_itens where mdit_tipo = 9 order by mdit_fabricante, mdit_desc";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='gravador' size=1 id='idGravador'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['mdit_cod']."'";
				if ($rowqry['mdit_cod'] == $row['cod_gravador']){
					print " selected";
				}
				print ">".$rowqry['mdit_fabricante']." ".$rowqry['mdit_desc']." ".$rowqry['mdit_desc_capacidade']." ".$rowqry['mdit_sufixo']."</option>";
			}
			print "</SELECT>";
			print "</TD>";
			//DVD
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Unidade de DVD:</TD>";

			$qry = "select * from modelos_itens where mdit_tipo = 8 order by mdit_fabricante, mdit_desc";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='dvd' size=1 id='idDvd'>";
			print "<option value=-1>Selecione o modelo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['mdit_cod']."'";
				if ($rowqry['mdit_cod'] == $row['cod_dvd']){
					print " selected";
				}
				print ">".$rowqry['mdit_fabricante']." ".$rowqry['mdit_desc']." ".$rowqry['mdit_desc_capacidade']." ".$rowqry['mdit_sufixo']."</option>";
			}
			print "</SELECT>";
			print "</TD>";
		print "</tr>";

		print "<tr><td colspan='4'></td></tr>";
		print "<tr><td colspan='4'> Dados complementares - (Algum desses campos só estará preenchido se o equipamento for IMPRESSORA ou MONITOR ou SCANNER) </td></tr>";
		print "<tr><td colspan='4'></td></tr>";
		print "<tr>";
			//IMPRESSORA
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Tipo de Impressora:</TD>";

			$qry = "SELECT * from tipo_imp order by tipo_imp_nome";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='impressora' size=1 id='idImpressora'>";
			print "<option value=-1>Selecione o tipo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['tipo_imp_cod']."'";
				if ($rowqry['tipo_imp_cod'] == $row['tipo_imp']){
					print " selected";
				}
				print ">".$rowqry['tipo_imp_nome']."</option>";
			}
			print "</SELECT>";
			print "</TD>";

			//MONITOR
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Monitor:</TD>";

			$qry = "SELECT * from polegada order by pole_nome";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='monitor' size=1 id='idMonitor'>";
			print "<option value=-1>Selecione a tela</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['pole_cod']."'";
				if ($rowqry['pole_cod'] == $row['polegada_cod']){
					print " selected";
				}
				print ">".$rowqry['pole_nome']."</option>";
			}
			print "</SELECT>";
			print "</TD>";
		print "</tr>";

		print "<tr>";
			//SCANNER
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Scanner:</TD>";

			$qry = "SELECT * from resolucao order by resol_nome";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='scanner' size=1 id='idScanner'>";
			print "<option value=-1>Selecione a resolucao</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['resol_cod']."'";
				if ($rowqry['resol_cod'] == $row['resolucao_cod']){
					print " selected";
				}
				print ">".$rowqry['resol_nome']."</option>";
			}
			print "</SELECT>";
			print "</TD>";
			print "</tr>";

		print "<tr><td colspan='4'></td></tr>";
		print "<tr><td colspan='4'> Dados complementares - CONTÁBEIS</td></tr>";
		print "<tr><td colspan='4'></td></tr>";
		print "<tr>";
			//UNIDADE
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Unidade:</TD>";

			$qry = "SELECT * from instituicao order by inst_nome";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT  name='instituicao' size='1' id='idUnidade'"; //class='select'
/*			if ($_SESSION['s_nivel'] !=1) {
				print "class='disable' disabled";
			} else*/
				print " class='select' ";
			print ">";
			print "<option value=-1>Selecione a Unidade</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['inst_cod']."'";
				if ($rowqry['inst_cod'] == $row['cod_inst']){
					print " selected";
				}
				print ">".$rowqry['inst_nome']."</option>";
			}
			print "</SELECT>";
			print "</TD>";

			//CENTRO DE CUSTO
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Centro de Custo:</TD>";

			$qry = "SELECT * from `".DB_CCUSTO."`.".TB_CCUSTO." order by ".CCUSTO_DESC."";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='ccusto' size=1 id='idCcusto'>";
			print "<option value=-1>Selecione o centro de custo</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry[CCUSTO_ID]."'";
				if ($rowqry[CCUSTO_ID] == $row['ccusto']){
					print " selected";
				}
				print ">".$rowqry[CCUSTO_DESC]."</option>";
			}
			print "</SELECT>";
			print "</TD>";

		print "</tr>";

		print "<tr>";
			//FORNECEDOR
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Fornecedor:</TD>";

			$qry = "SELECT * from fornecedores order by forn_nome";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='fornecedor' size=1 id='idFornecedor'>";
			print "<option value=-1>Selecione o fornecedor</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['forn_cod']."'";
				if ($rowqry['forn_cod'] == $row['fornecedor_cod']){
					print " selected";
				}
				print ">".$rowqry['forn_nome']."</option>";
			}
			print "</SELECT>";
			print "</TD>";


			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Nota Fiscal:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text'  name='nota' value='".$row['nota']."'></TD>";
		print "</tr>";


		print "<tr>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Valor:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text'  name='preco' id='idValor' value='".$row['valor']."";
				if(!strpos($row['valor'],'.')) print ",00'";
			print "'>";
			print "</TD>";

			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data da compra:</TD>";

			$dataCompra = $row['data_compra']; //datab2($row['data_compra'])
			if ($dataCompra == "0000-00-00 00:00:00") $dataCompra = ""; else $dataCompra = formatDate($row['data_compra']," ");

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text'  name='data' id='idDataCompra' value='".$dataCompra."'></TD>";
		print "</tr>";


		print "<tr>";
			//TIPO GARANTIA
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Tipo de garantia:</TD>";

			$qry = "SELECT * from tipo_garantia order by tipo_garant_nome";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='tipo_garantia' size=1 id='idGarantia'>";
			print "<option value=-1>Selecione o tipo de garantia</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['tipo_garant_cod']."'";
				if ($rowqry['tipo_garant_cod'] == $row['garantia_cod']){
					print " selected";
				}
				print ">".$rowqry['tipo_garant_nome']."</option>";
			}
			print "</SELECT>";
			print "</TD>";

			//TEMPO GARANTIA
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Tempo de garantia:</TD>";

			$qry = $query = "SELECT * from tempo_garantia order by tempo_meses";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='tempo_meses' size='1' id='idTempo'>";
			print "<option value=-1>Selecione o tempo de garantia</option>";
			while ($rowqry = mysql_fetch_array($execqry)){
				print "<option value='".$rowqry['tempo_cod']."'";
				if ($rowqry['tempo_cod'] == $row['tempo_cod']){
					print " selected";
				}
				print ">".$rowqry['tempo_meses']." meses</option>";
			}
			print "</SELECT>";
			print "</TD>";
			print "</tr>";

		print "<tr>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Assistência:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_assist' size='1'>";
				print "<option value= '-1'>Selecione o tipo de assistência</option>";
	                	$query = "SELECT * from assistencia order by assist_desc";
				$exec_assist = mysql_query($query);
				while ($row_assist = mysql_fetch_array($exec_assist))
				{
					print "<option value=".$row_assist['assist_cod']."";
					if ($row_assist['assist_cod'] == $row['assistencia_cod']) {
						print " selected";
					}
					print " >".$row_assist['assist_desc']." </option>";
				}
				print "</select>";
			print "</TD>";
		print "</tr>";

		print "<tr>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Comentário:</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'><TEXTAREA class='textarea' name='comentario'>".$row['comentario']."</textarea></TD>";
		print "</Tr>";

			$qryTela = "select * from imagens where img_inv = ".$row['etiqueta']." and img_inst=".$row['cod_inst']."";
			$execTela = mysql_query($qryTela) or die ("NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DA TABELA DE IMAGENS!");
			//$rowTela = mysql_fetch_array($execTela);
			$isTela = mysql_num_rows($execTela);
			$cont = 0;
			while ($rowTela = mysql_fetch_array($execTela)) {
			//if ($isTela !=0) {
				$cont++;
				print "<tr>";

				print "<TD  bgcolor='".TD_COLOR."' >Anexo  ".$cont." de inventário:</td>";
				print "<td colspan='5' bgcolor='".BODY_COLOR."'><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$rowTela['img_cod']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela['img_nome']."</a>";
				print "<input type='checkbox' name='delImg[".$cont."]' value='".$rowTela['img_cod']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";
				print "</tr>";
			}

		print "<tr><td colspan='4'>&nbsp;</td></tr>";
		print "<tr>";
			print "<TD align='center' colspan='2' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='Alterar' name='submit'>";
				print "<input type='hidden' name='cont' value='".$cont."'>";
				print "<input type='hidden' name='comp_inv' value='".$_REQUEST['comp_inv']."'>";
				print "<input type='hidden' name='comp_inst' value='".$_REQUEST['comp_inst']."'>";
			print "</TD>";
			print "<TD align='center' colspan='2' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='Cancelar' onClick=\"javascript:history.back();\" name='cancelar'></TD>";
		print "</tr>";


	if (isset($_POST['submit']))
	{
		//$depois = $_POST['status'];
		$erro = false;

		for ($j=1; $j<=$_POST['cont']; $j++) {
			if (isset($_POST['delImg'][$j])) {
				$qryDel = "DELETE FROM imagens WHERE img_cod = ".$_POST['delImg'][$j]."";
				$execDel = mysql_query($qryDel) or die ("NÃO FOI POSSÍVEL EXCLUIR A IMAGEM!");
			}
		}

		$gravaImg = false;
		if (isset($_FILES['img']) and $_FILES['img']['name']!="") {
			$qryConf = "SELECT * FROM config";
			$execConf = mysql_query($qryConf) or die ("NÃO FOI POSSÍVEL ACESSAR AS INFORMAÇÕES DE CONFIGURAÇÃO, A TABELA CONF FOI CRIADA?");
			$rowConf = mysql_fetch_array($execConf);
			$arrayConf = array();
			$arrayConf = montaArray($execConf,$rowConf);

			$upld = upload('img',$arrayConf);
			if ($upld =="OK") {
				$gravaImg = true;
			} else {

				$upld.="<br><a align='center' onClick=\"exibeEscondeImg('idAlerta');\"><img src='".ICONS_PATH."/stop.png' width='16px' height='16px'>&nbsp;Fechar</a>";
				print "</table>";
				print "<div class='alerta' id='idAlerta'><table bgcolor='#999999'><tr><td colspan='2' bgcolor='yellow'>".$upld."</td></tr></table></div>";
				exit;
			}
		}

		//Se a Localização do equipamento for alterada é gravado na tabela de histórico!!!
		if (isset($_POST['local']) && $_POST['local'] != $row['tipo_local']) {
			$sql = "INSERT INTO historico (hist_inv, hist_inst,  hist_local, hist_data)
					VALUES ('".$_POST['comp_inv']."', '".$_POST['instituicao']."', '".$_POST['local']."', '".date("Y-m-d H:i:s")."')";
			$resultadoSQL = mysql_query($sql);
		}

		if (!$erro)
		{
			if (isset($_POST['data'])) {
				$data = converte_dma_para_amd($_POST['data']);
			}
			if (isset($_POST['preco'])) {
				$preco = str_replace(",",".",$_POST['preco']);
			}

			$query = "UPDATE equipamentos SET comp_sn='".noHtml($_POST['serial'])."', comp_marca='".$_POST['modelo']."', comp_mb=".$_POST['mb'].",
						comp_proc=".$_POST['processador'].", comp_memo='".$_POST['memoria']."', comp_video=".$_POST['video'].", comp_som=".$_POST['som'].",
						comp_rede=".$_POST['rede'].", comp_modelohd=".$_POST['hd'].", comp_modem=".$_POST['modem'].", comp_cdrom=".$_POST['cdrom'].",
						comp_dvd=".$_POST['dvd'].", comp_grav=".$_POST['gravador'].", comp_nome='".noHtml($_POST['nome'])."', comp_local=".$_POST['local'].",
						comp_fornecedor=".$_POST['fornecedor'].", comp_nf='".noHtml($_POST['nota'])."', comp_coment='".noHtml($_POST['comentario'])."',
						comp_valor='".$preco."', comp_data_compra='".$data."', comp_inst=".$_POST['instituicao'].",
						comp_ccusto='".$_POST['ccusto']."', comp_tipo_equip=".$_POST['equipamentos'].", comp_tipo_imp=".$_POST['impressora'].",
						comp_resolucao=".$_POST['scanner'].", comp_polegada=".$_POST['monitor'].", comp_fab=".$_POST['fabricante'].",
						comp_situac=".$_POST['situacao'].", comp_tipo_garant=".$_POST['tipo_garantia'].",
						comp_garant_meses=".$_POST['tempo_meses'].", comp_assist=".$_POST['comp_assist']."
					WHERE comp_inv=".$_POST['comp_inv']." and comp_inst=".$_POST['instituicao']."";
			$resultado4 = mysql_query($query) or die('ERRO NA TENTATIVA DE GRAVAR AS ALTERAÇÕES DO REGISTRO!<BR>'.$query);
			if ($resultado4 == 0)
			{
				$aviso= "ERRO DE ACESSO. Um erro ocorreu ao tentar alterar ocorrência no sistema.".$query;
			}
			else
			{
				## VERIFICA SE HOUVE ALTERAÇÃO DE HARDWARE PARA GRAVAR NO BANCO
				if (($_POST['processador'] != $row['tipo_proc']) and ( $row['tipo_proc']!=-1 and $row['tipo_proc']!=0 and $row['tipo_proc']!='') ) {
					$qry = "INSERT INTO hw_alter (hwa_inst, hwa_inv, hwa_item, hwa_user, hwa_data)
							VALUES (".$row['cod_inst'].", ".$row['etiqueta'].", '".$row['tipo_proc']."', ".$_SESSION['s_uid'].", '".date('Y-m-d H:i:s')."')";
					$execQry = mysql_query($qry) or die ('ERRO NA TENTATIVA DE REGISTRAR A ALTERAÇÃO DE HW!<BR>'.$qry);
				}

				if (($_POST['mb'] != $row['tipo_mb'])and ( $row['tipo_mb']!=-1 and $row['tipo_mb']!=0 and $row['tipo_mb']!='') ) {
					$qry = "INSERT INTO hw_alter (hwa_inst, hwa_inv, hwa_item, hwa_user, hwa_data)
							VALUES (".$row['cod_inst'].", ".$row['etiqueta'].", '".$row['tipo_mb']."', ".$_SESSION['s_uid'].", '".date('Y-m-d H:i:s')."')";
					$execQry = mysql_query($qry) or die ('ERRO NA TENTATIVA DE REGISTRAR A ALTERAÇÃO DE HW!<BR>'.$qry);
				}

				if (($_POST['memoria'] != $row['tipo_memo'])and ( $row['tipo_memo']!=-1 and $row['tipo_memo']!=0 and $row['tipo_memo']!='') ) {
					$qry = "INSERT INTO hw_alter (hwa_inst, hwa_inv, hwa_item, hwa_user, hwa_data)
							VALUES (".$row['cod_inst'].", ".$row['etiqueta'].", '".$row['tipo_memo']."', ".$_SESSION['s_uid'].", '".date('Y-m-d H:i:s')."')";
					$execQry = mysql_query($qry) or die ('ERRO NA TENTATIVA DE REGISTRAR A ALTERAÇÃO DE HW!<BR>'.$qry);
				}

				if (($_POST['video'] != $row['tipo_video']) and  ( $row['tipo_video']!=-1 and $row['tipo_video']!=0 and $row['tipo_video']!='') ) {
					$qry = "INSERT INTO hw_alter (hwa_inst, hwa_inv, hwa_item, hwa_user, hwa_data)
							VALUES (".$row['cod_inst'].", ".$row['etiqueta'].", '".$row['tipo_video']."', ".$_SESSION['s_uid'].", '".date('Y-m-d H:i:s')."')";
					$execQry = mysql_query($qry) or die ('ERRO NA TENTATIVA DE REGISTRAR A ALTERAÇÃO DE HW!<BR>'.$qry);
				}

				if (($_POST['som'] != $row['tipo_som']) and ( $row['tipo_som']!=-1 and $row['tipo_som']!=0 and $row['tipo_som']!='') ) {
					$qry = "INSERT INTO hw_alter (hwa_inst, hwa_inv, hwa_item, hwa_user, hwa_data)
							VALUES (".$row['cod_inst'].", ".$row['etiqueta'].", '".$row['tipo_som']."', ".$_SESSION['s_uid'].", '".date('Y-m-d H:i:s')."')";
					$execQry = mysql_query($qry) or die ('ERRO NA TENTATIVA DE REGISTRAR A ALTERAÇÃO DE HW!<BR>'.$qry);
				}

				if (($_POST['rede'] != $row['tipo_rede']) and  ( $row['tipo_rede']!=-1 and $row['tipo_rede']!=0 and $row['tipo_rede']!='') ) {
					$qry = "INSERT INTO hw_alter (hwa_inst, hwa_inv, hwa_item, hwa_user, hwa_data)
							VALUES (".$row['cod_inst'].", ".$row['etiqueta'].", '".$row['tipo_rede']."', ".$_SESSION['s_uid'].", '".date('Y-m-d H:i:s')."')";
					$execQry = mysql_query($qry) or die ('ERRO NA TENTATIVA DE REGISTRAR A ALTERAÇÃO DE HW!<BR>'.$qry);
				}

				if (($_POST['modem'] != $row['tipo_modem']) and ( $row['tipo_modem']!=-1 and $row['tipo_modem']!=0 and $row['tipo_modem']!='') ) {
					$qry = "INSERT INTO hw_alter (hwa_inst, hwa_inv, hwa_item, hwa_user, hwa_data)
							VALUES (".$row['cod_inst'].", ".$row['etiqueta'].", '".$row['tipo_modem']."', ".$_SESSION['s_uid'].", '".date('Y-m-d H:i:s')."')";
					$execQry = mysql_query($qry) or die ('ERRO NA TENTATIVA DE REGISTRAR A ALTERAÇÃO DE HW!<BR>'.$qry);
				}

				if (($_POST['hd'] != $row['tipo_hd']) and ( $row['tipo_hd']!=-1 and $row['tipo_hd']!=0 and $row['tipo_hd']!='') ) {
					$qry = "INSERT INTO hw_alter (hwa_inst, hwa_inv, hwa_item, hwa_user, hwa_data)
							VALUES (".$row['cod_inst'].", ".$row['etiqueta'].", '".$row['tipo_hd']."', ".$_SESSION['s_uid'].", '".date('Y-m-d H:i:s')."')";
					$execQry = mysql_query($qry) or die ('ERRO NA TENTATIVA DE REGISTRAR A ALTERAÇÃO DE HW!<BR>'.$qry);
				}

				if (($_POST['cdrom'] != $row['tipo_cdrom']) and ( $row['tipo_cdrom']!=-1 and $row['tipo_cdrom']!=0 and $row['tipo_cdrom']!='') ) {
					$qry = "INSERT INTO hw_alter (hwa_inst, hwa_inv, hwa_item, hwa_user, hwa_data)
							VALUES (".$row['cod_inst'].", ".$row['etiqueta'].", '".$row['tipo_cdrom']."', ".$_SESSION['s_uid'].", '".date('Y-m-d H:i:s')."')";
					$execQry = mysql_query($qry) or die ('ERRO NA TENTATIVA DE REGISTRAR A ALTERAÇÃO DE HW!<BR>'.$qry);
				}

				if (($_POST['dvd'] != $row['tipo_dvd']) and ( $row['tipo_dvd']!=-1 and $row['tipo_dvd']!=0 and $row['tipo_dvd']!='') ) {
					$qry = "INSERT INTO hw_alter (hwa_inst, hwa_inv, hwa_item, hwa_user, hwa_data)
							VALUES (".$row['cod_inst'].", ".$row['etiqueta'].", '".$row['tipo_dvd']."', ".$_SESSION['s_uid'].", '".date('Y-m-d H:i:s')."')";
					$execQry = mysql_query($qry) or die ('ERRO NA TENTATIVA DE REGISTRAR A ALTERAÇÃO DE HW!<BR>'.$qry);
				}

				if (($_POST['gravador'] != $row['tipo_grav']) and ( $row['tipo_grav']!=-1 and $row['tipo_grav']!=0 and $row['tipo_grav']!='') ) {
					$qry = "INSERT INTO hw_alter (hwa_inst, hwa_inv, hwa_item, hwa_user, hwa_data)
							VALUES (".$row['cod_inst'].", ".$row['etiqueta'].", '".$row['tipo_grav']."', ".$_SESSION['s_uid'].", '".date('Y-m-d H:i:s')."')";
					$execQry = mysql_query($qry) or die ('ERRO NA TENTATIVA DE REGISTRAR A ALTERAÇÃO DE HW!<BR>'.$qry);
				}

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
								"('".$_FILES['img']['name']."', '".$_POST['instituicao']."', '".$_POST['comp_inv']."','".$_FILES['img']['type']."', '".$image."', ".$tamanho[0].", ".$tamanho[1].")";
						// now we can delete the temp file
						unlink($fileinput);
					} /*else {
						echo "NENHUMA IMAGEM FOI SELECIONADA!";
						exit;
					}*/
					$exec = mysql_query($SQL); //or die ("NÃO FOI POSSÍVEL GRAVAR O ARQUIVO NO BANCO DE DADOS! ");
					if ($exec == 0) $aviso.= "NÃO FOI POSSÍVEL ANEXAR A IMAGEM!<br>";
				}

				$aviso = "Equipamento alterado com sucesso.";

				$texto = "ETIQUETA: ".$_POST['comp_inv']." INSTITUIÇÃO: ".$row['cod_inst']."";

				$coment1=$row['comentario']; $coment2 = str_replace("'","",$_POST['comentario']);
				$equip1 =$row['tipo']; $equip2 = str_replace("'","",$_POST['equipamentos']);
				$serial1=$row['serial']; $serial2 = str_replace("'","",$_POST['serial']);
				$modelo1=$row['modelo_cod']; $modelo2= str_replace("'","",$_POST['modelo']);
				$mb1=$row['tipo_mb']; $mb2= str_replace("'","",$_POST['mb']);
				$proc1=$row['tipo_proc']; $proc2= str_replace("'","",$_POST['processador']);
				$memo1= $row['cod_memoria']; $memo2= str_replace("'","",$_POST['memoria']);
				$vid1 = $row['tipo_video']; $vid2 = str_replace("'","",$_POST['video']);
				$som1 = $row['tipo_som']; $som2 = str_replace("'","",$_POST['som']);
				$rede1 = $row['tipo_rede']; $rede2 = str_replace("'","",$_POST['rede']);
				$hd1 = $row['tipo_hd']; $hd2 = str_replace("'","",$_POST['hd']);
				$mod1 = $row['cod_modem']; $mod2 = str_replace("'","",$_POST['modem']);
				$cd1 = $row['tipo_cdrom']; $cd2 = str_replace("'","",$_POST['cdrom']);
				$dvd1 = $row['cod_dvd']; $dvd2 = str_replace("'","",$_POST['dvd']);
				$grav1 = $row['cod_gravador']; $grav2 = str_replace("'","",$_POST['gravador']);
				$nome1 = $row['nome']; $nome2 = str_replace("'","",$_POST['nome']);
				$local1 = $row['tipo_local']; $local2 = str_replace("'","",$_POST['local']);
				$forn1 = $row['fornecedor_cod']; $forn2 = str_replace("'","",$_POST['fornecedor']);
				$nota1 = $row['nota']; $nota2 = str_replace("'","",$_POST['nota']);
				$valor1 = $row['valor']; $valor2 = str_replace("'","",$preco);
				$data1 = $row['data_compra']; $data2 = str_replace("'","",$data);
				$inst1 = $row['cod_inst']; $inst2 = str_replace("'","",$_POST['instituicao']);
				$imp1 = $row['tipo_imp']; $imp2 = str_replace("'","",$_POST['impressora']);

				$resol1 = $row['resolucao_cod']; $resol2 = str_replace("'","",$_POST['scanner']);
				$pole1 = $row['polegada_cod']; $pole2 = str_replace("'","",$_POST['monitor']);
				$fab1 = $row['fab_cod']; $fab2 = str_replace("'","",$_POST['fabricante']);
				$situac1 = $row['situac_cod']; $situac2 = str_replace("'","",$_POST['situacao']);
				$garant1 = $row['garantia_cod']; $garant2 = str_replace("'","",$_POST['tipo_garantia']);
				$tempo1 = $row['tempo_cod']; $tempo2 = str_replace("'","",$_POST['tempo_meses']);
				$ccusto1 = $row['ccusto']; $ccusto2 = str_replace("'","",$_POST['ccusto']);


				if (($coment1!= $coment2)and ($coment2!="null")) {$texto.= " comentario alterado de '".$coment1."' para '".$coment2."' |";}
				if ($equip1!= $equip2) {$texto.= " Tipo de equipamento alterado de '".$row['equipamento']."' para '".$equip2."' |";}
				if (($serial1!= $serial2)and ($serial2!="null")) {$texto.= " NS alterado de '".$serial1."' para '".$serial2."' |";}
				if ($modelo1!= $modelo2) {$texto.= " modelo alterado de '".$row['modelo']."' para '".$modelo2."' |";}
				if (($mb1!= $mb2)and ($mb2!="null")) {$texto.= " mb alterada de '".$row['mb']."' para '".$mb2."' |";}
				if (($proc1!= $proc2)and ($proc2!="null")) {$texto.= " processador alterado de '".$row['processador']."' para '".$proc2."' |";}
				if (($memo1!= $memo2)and ($memo2!="null")) {$texto.= " memoria alterada de '".$row['memoria']."' para '".$memo2."' |";}
				if (($vid1!= $vid2)and ($vid2!="null")) {$texto.= " placa de vídeo alterada de '".$row['video']."' para '".$vid2."' |";}
				if (($som1!= $som2)and ($som2!="null")) {$texto.= " placa de som alterada de '".$row['som']."' para '".$som2."' |";}
				if (($rede1!= $rede2)and ($rede2!="null")) {$texto.= " placa de rede alterada de '".$row['rede_fabricante']." ".$row['rede']."' para '".$rede2."' |";}
				if (($hd1!= $hd2)and ($hd2!="null")) {$texto.= " HD alterado de '".$row['fabricante_hd']." ".$row['hd_capacidade']."' para '".$hd2."' |";}
				if (($mod1!= $mod2)and ($mod2!="null")) {$texto.= " modem alterado de '".$row['fabricante_modem']." ".$row['modem']."' para '".$mod2."' |";}
				if (($cd1!= $cd2)and ($cd2!="null")) {$texto.= " cdrom alterado de '".$row['fabricante_cdrom']." ".$row['cdrom']."' para '".$cd2."' |";}
				if (($dvd1!= $dvd2)and ($dvd2!="null")) {$texto.= " unidade de dvd alterada de '".$row['fabricante_dvd']." ".$row['dvd']."' para '".$dvd2."' |";}
				if (($grav1!= $grav2)and ($grav2!="null")) {$texto.= " unidade de gravador alterada de '".$row['fabricante_gravador']." ".$row['gravador']."' para '".$grav2."' |";}
				if (($nome1!= $nome2)and ($nome2!="null")) {$texto.= " nome do equipamento alterado de '".$nome1."' para '".$nome2."' |";}
				if ($local1!= $local2) {$texto.= " localização alterada de '".$row['local']."' para '".$local2."' |";}
				if (($forn1!= $forn2)and ($forn2!="null")) {$texto.= " fornecedor alterado de '".$row['fornecedor_nome']."' para '".$forn2."' |";}
				if (($nota1!= $nota2)and ($nota2!="null")) {$texto.= " NF alterada de '".$nota1."' para '".$nota2."' |";}
				if (($valor1!= $valor2)and ($valor2!="null")) {$texto.= " valor alterado de '".$valor1."' para '".$valor2."' |";}
				if (($data1!= $data2)and ($data2!="null")) {$texto.= " data de compra alterada de '".$data1."' para '".$data2."' |";}
				if ($inst1!= $inst2) {$texto.= " Unidade alterada de '".$row['instituicao']."' para '".$inst2."' |";}
				if (($imp1!= $imp2)and ($imp2!="null")) {$texto.= " tipo de impressora alterada de '".$row['impressora']."' para '".$imp2."' |";}

				if (($resol1!= $resol2)and ($resol2!="null")) {$texto.= " resolução alterada de '".$row['resol_nome']."' para '".$resol2."' |";}
				if (($pole1!= $pole2)and ($pole2!="null")) {$texto.= " dimensão do monitor alterada de '".$row['polegada_nome']."' para '".$pole2."' |";}
				if ($fab1!= $fab2) {$texto.= " fabricante alterado de '".$row['fab_nome']."' para '".$fab2."' |";}
				if ($situac1!= $situac2) {$texto.= " situação alterada de '".$row['situac_nome']."' para '".$situac2."' |";}

				if (($garant1!= $garant2)and ($garant2!="null")) {$texto.= " garantia alterada de '".$row['tipo_garantia']."' para '".$garant2."' |";}
				if (($tempo1!= $tempo2) and ($tempo2!="null")){$texto.= " tempo de garantia alterado de '".$row['tempo']." meses' para '".$tempo2."' |";}
				if (($ccusto1!= $ccusto2)and ($ccusto2!="null")) {$texto.= " centro de custo alterado de '".$row['ccusto']."' para '".$ccusto2."' |";}

				geraLog(LOG_PATH.'invmon.txt',date("d-m-Y H:i:s"),$_SESSION['s_usuario'],$_SERVER['PHP_SELF'],$texto);

			}
		}
		print "<script>mensagem('".$aviso."'); ";
		print "redirect('mostra_consulta_inv.php?comp_inv=".$_POST['comp_inv']."&comp_inst=".$_POST['comp_inst']."');</script>";

	}

        ?>
	<script type="text/javascript">
	<!--

		function valida(){
			var ok = validaForm('idValor','MOEDASIMP','Valor do equipamento',0);
			if (ok) var ok = validaForm('idDataCompra','DATA','Data da compra',0);

			return ok;

		}

		//-->
	</script>
	<?

print "</TABLE>";
print "</FORM>";
print "</body>";
print "</html>";
?>
