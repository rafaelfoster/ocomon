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

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	print "<BR><B>Administração de modelos de equipamentos</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' ENCTYPE='multipart/form-data' onSubmit=\"return valida()\">";

        $hoje = date("Y-m-d H:i:s");

        $cor  = TD_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";




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
		"(mold.mold_marca = model.marc_cod)";

		if (isset($_GET['cod'])) {
			$queryA.= " AND model.marc_cod = ".$_GET['cod']." ";
		}
	$queryA .=" ORDER BY fab_nome";
	$resultadoA = mysql_query($queryA) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DO REGISTRO!<BR>'.$queryA);
	$linhasA = mysql_num_rows($resultadoA);
	//$row = mysql_fetch_array($resultadoA);

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		print "<TR><TD bgcolor='".BODY_COLOR."'><a href='incluir_molde.php?&cellStyle=true'>Cadastrar modelo de configuração</a></TD></TR>";
		if ($linhasA == 0)
		{
			print "<tr><td align='center'>".mensagem("Não há nenhum modelo de configuração cadastrado no sistema.")."</td></tr>";

		} else {

			print "<tr><TD width='500' align='left' ><B>Existe(m) ".$linhasA." modelo(s) de configuração cadastrado(s) no sistema. </B></TD></tr>";
		}

		print "<TR class='header'><td class='line'>Modelo</TD><td class='line'><b>Tipo</TD><td class='line'>Editar</TD><td class='line'>Excluir</TD>";
		$j=2;
		while ($row = mysql_fetch_array($resultadoA)) {
			if ($j % 2)
			{
				$trClass = "lin_par";
			}
			else
			{
				$trClass = "lin_impar";
			}
			$j++;
			print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";

			print "<td class='line'>".$row['fab_nome']." ".$row['modelo']."</td>";
			print "<td class='line'>".$row['equipamento']."</td>";
			print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['padrao']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></td>";
			print "<td class='line'><a onClick=\"confirmaAcao('Tem Certeza que deseja excluir esse registro do sistema?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['padrao']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";

			print "</TR>";

		}
	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {
		$row = mysql_fetch_array($resultadoA);

		print "<BR><B>Edição do registro</B><BR>";

		print "<tr><td colspan='4'>&nbsp;</td></tr>";
		print "<tr><td colspan='4'>Dados complementares - GERAIS:</td></tr>";
		print "<tr><td colspan='4'>&nbsp;</td></tr>";

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
				if ($rowqry['tipo_cod'] == $row['equipamento_cod']){
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
			//MODELO DO EQUIPAMENTO
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Modelo do equipamento:</TD>";

			$qry = "SELECT * from marcas_comp order by marc_nome";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='disable' name='modelo' size='1' id='idModelo' disabled>";
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

		print "</tr>";

		print "<tr><td colspan='4'>&nbsp;</td></tr>";
		print "<tr> <td colspan='4'> Dados complementares - (Esses campos só estarão preenchidos para equipamentos do tipo COMPUTADOR)</td></tr>";
		print "<tr><td colspan='4'>&nbsp;</td></tr>";


		print "<tr>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Nome do computador:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='disable'  name='nome'  value='".$row['nome']."'' disabled></TD>";
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

			$qry = "select * from modelos_itens where mdit_tipo = 7 order by mdit_fabricante, mdit_desc_capacidade";
			$execqry = mysql_query($qry);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='memoria' size=1 id='idMb'>";
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

		print "<tr><td colspan='4'>&nbsp;</td></tr>";
		print "<tr><td colspan='4'> Dados complementares - (Algum desses campos só estará preenchido se o equipamento for IMPRESSORA ou MONITOR ou SCANNER) </td></tr>";
		print "<tr><td colspan='4'>&nbsp;</td></tr>";
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
				if ($rowqry['tipo_imp_cod'] == $row['impressora_cod']){
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


		print "<tr><td colspan='4'>&nbsp;</td></tr>";
		print "<tr>";
			print "<TD align='center' colspan='2' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='Alterar' name='submit'>";
				print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
				//print "<input type='hidden' name='comp_inst' value='".$_REQUEST['comp_inst']."'>";
			print "</TD>";
			print "<TD align='center' colspan='2' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='Cancelar' onClick=\"javascript:history.back();\" name='cancelar'></TD>";
		print "</tr>";


	} else
	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$query2 = "DELETE FROM moldes WHERE mold_marca='".$_GET['cod']."'";
		$resultado2 = mysql_query($query2);

		if ($resultado2 == 0)
		{
				$aviso = "ERRO NA TENTATIVA DE EXCLUIR O REGISTRO!";
		}
		else
		{
				$aviso = "OK. REGISTRO EXCLUÍDO COM SUCESSO!";
		}
		print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else
	if ($_POST['submit'] == "Alterar"){

		$query = "UPDATE moldes SET ".
					"mold_mb = ".$_POST['mb'].", mold_proc =".$_POST['processador'].", ".
					"mold_memo = ".$_POST['memoria'].", mold_video = ".$_POST['video'].", ".
					"mold_som = ".$_POST['som'].", mold_rede = ".$_POST['rede'].", ".
					"mold_modelohd = ".$_POST['hd'].", mold_modem = ".$_POST['modem'].", ".
					"mold_cdrom = ".$_POST['cdrom'].", mold_dvd = ".$_POST['dvd'].", ".
					"mold_grav = ".$_POST['gravador'].", mold_tipo_equip = ".$_POST['equipamentos'].", ".
					"mold_tipo_imp = ".$_POST['impressora'].", mold_resolucao = ".$_POST['scanner'].", ".
					"mold_polegada = ".$_POST['monitor'].", mold_fab = ".$_POST['fabricante']." ".
				"WHERE mold_marca = ".$_POST['cod']." ";
		$exec = mysql_query($query) or die ('ERRO NA TENTATIVA DE ATUALIZAR O REGISTRO!');

		$aviso =  "REGISTRO ALTERADO COM SUCESSO!";

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";


	}

?>
<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idEquipamento','COMBO','Tipo de equipamento',1);
		if (ok) var ok = validaForm('idFabricante','COMBO','Fabricante',1);

		return ok;
	}

//-->
</script>
<?

print "</TABLE>";
print "</BODY>";
print "</HTML>";
?>