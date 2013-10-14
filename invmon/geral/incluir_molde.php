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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$cab = new headers;
	$cab->set_title($TRANS['html_title']);

	$hoje = date("Y-m-d H:i:s");
	$hojeLog = date("d-m-Y H:i:s");
	$nulo = null;

	print "<BR>";
	print "<B>".$TRANS["head_inc_mold_equip"].":";
	print "<BR><a href='model_config.php'>Lista os modelos de configuração já cadastrados</a>";

	print "<FORM name='form1' method='POST' action='".$_SERVER['PHP_SELF']."'  ENCTYPE='multipart/form-data'  onSubmit='return valida()'>";

	print "<TABLE border='0' colspace='3' width='100%'>";

		print "<tr><td colspan='4'></td><b>".$TRANS['dados_gerais'].":</b></td></tr>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".
				"<a title='Campo obrigatório - Defina o tipo de equipamento que está cadastrando'>".$TRANS['cx_tipo'].":</a></b>".
			"</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_tipo_equip' id='idTipo' size='1' ".
					"onChange=\"fillSelectFromArray(this.form.comp_marca, ((this.selectedIndex == -1) ? null : ".
					"team[this.selectedIndex-1]));\">";

					print "<option value=-1 selected>".$TRANS['cmb_selec_equip']."</option>";

			$query = "SELECT * from tipo_equip order by tipo_nome";
			$resultado = mysql_query($query);
			while ($rowTipo = mysql_fetch_array($resultado))
			{
				print "<option value='".$rowTipo['tipo_cod']."' ";
				//if (isset($row) && $rowTipo['tipo_cod'] == $row['equipamento_cod'])
					//print " selected";
				print ">".$rowTipo['tipo_nome']."</option>";
			}
			print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".
				"<a title='Campo obrigatório - Selecione o nome do fabricante do equipamento'>".$TRANS['cx_fab'].":*</a></b>".
			"</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_fab' size='1' id='idFab'>";

				print "<option value=-1>".$TRANS['cmb_selec_fab'].": </option>";
				$query = "SELECT * from fabricantes  order by fab_nome";
				$resultado = mysql_query($query);
				while ($rowFab = mysql_fetch_array($resultado))
				{
					print "<option value='".$rowFab['fab_cod']."' ";
						//if (isset($row) && $rowFab['fab_cod'] == $row['fab_cod'])
							//print " selected ";
					print ">".$rowFab['fab_nome']."</option>";
				}
			print "</SELECT>";
		print "</TD>";
		print "</tr>";


		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".
				"<a title='Campo obrigatório - Selecione o modelo do equipamento que está cadastrando'>".$TRANS['cx_modelo']."*:</a></b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_marca' size='1' id='idModelo' >";

					print "<option value=-1 selected>".$TRANS['cmb_selec_modelo']."</option>";

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
				print "<input type='button' name='modelo' value='Novo' class='minibutton' onClick=\"javascript:mini_popup('modelos.php?action=incluir&popup=true')\">";
		print "</td>";


                print "</tr>";

		print "<TR><td colspan='4'></td></TR>";
		print "<tr><td colspan='3'><b>".$TRANS['dados_config'].":</b></td><td class='line'>".
				"<input type='button' class='button' value='".$TRANS['bt_componente']."' ".
				"Onclick=\"return popup_alerta('itens.php?popup=true')\"></td>".
			"</tr>";
		print "<TR><td colspan='4'></td></TR>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_nome'].":</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='disable' disabled name='comp_nome' maxlength='15'></TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_mb'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select'  name='comp_mb' size=1>";

					print "<option value=null selected>".$TRANS['cmb_selec_modelo']."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 10 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				$sufixo = "";
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
					//if (isset($row) && $row['cod_mb'] == $rowA['mdit_cod'])
						//print " selected";
					print ">";
							print "".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."".
						"</option>";
				} // while

				print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
				print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_proc'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_proc' size='1'>";

					print "<option value=null selected>".$TRANS['cmb_selec_modelo']."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 11 order by mdit_fabricante, mdit_desc, mdit_desc_capacidade";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
// 					if (isset($row) && $rowA['mdit_cod'] == $row['cod_processador'])
// 						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
					print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
				print "</SELECT>";
		print "</TD>";


		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_memo'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<SELECT class='select' name='comp_memo' size=1>";

					print "<option value=null selected>".$TRANS['cmb_selec_modelo']."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 7 order by mdit_fabricante, mdit_desc, mdit_desc_capacidade";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
// 					if (isset($row) && $rowA['mdit_cod'] == $row['cod_memoria'])
// 						print " selected";
					print ">".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
				print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_video'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<SELECT class='select' name='comp_video' size=1>";

					print "<option value=null selected>".$TRANS['cmb_selec_modelo']."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 2 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
// 					if (isset($row) && $rowA['mdit_cod'] == $row['cod_video'])
// 						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
				print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_som'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_som' size=1>";

					print "<option value=null selected>".$TRANS['cmb_selec_modelo']."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 4 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
// 					if (isset($row) && $rowA['mdit_cod'] == $row['cod_som'])
// 						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
				print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_rede'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_rede' size=1>";

					print "<option value=null selected>".$TRANS['cmb_selec_modelo']."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 3 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
// 					if (isset($row) && $rowA['mdit_cod'] == $row['cod_rede'])
// 						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
				print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_modem'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_modem' size=1>";

					print "<option value=null selected>".$TRANS['cmb_selec_modelo']."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 6 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
// 					if (isset($row) && $rowA['mdit_cod'] == $row['cod_modem'])
// 						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
				print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_hd'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_modelohd' size='1'>";

					print "<option value=null selected>".$TRANS['cmb_selec_modelo']."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 1 order by mdit_fabricante, mdit_desc_capacidade";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
// 					if (isset($row) && $rowA['mdit_cod'] == $row['cod_hd'])
// 						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
				print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_grav'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_grav' size='1'>";

					print "<option value=null selected>".$TRANS['cmb_selec_modelo']."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 9 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
// 					if (isset($row) && $rowA['mdit_cod'] == $row['cod_gravador'])
// 						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";

			print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_cdrom'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_cdrom' size='1'>";

					print "<option value=null selected>".$TRANS['cmb_selec_modelo']."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 5 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
// 					if (isset($row) && $rowA['mdit_cod'] == $row['cod_cdrom'])
// 						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
			print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_dvd'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_dvd' size=1>";

					print "<option value=null selected>".$TRANS['cmb_selec_modelo']."</option>";

				$query = "select * from modelos_itens where mdit_tipo = 8 order by mdit_fabricante, mdit_desc";
				$commit = mysql_query($query);
				while($rowA = mysql_fetch_array($commit)){
					print "<option value='".$rowA['mdit_cod']."' ";
// 					if (isset($row) && $rowA['mdit_cod'] == $row['cod_dvd'])
// 						print " selected";
					print ">".$rowA['mdit_fabricante']." ".$rowA['mdit_desc']." ".$rowA['mdit_desc_capacidade']."".$rowA['mdit_sufixo']."</option>";
				} // while
				print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
			print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<TR><td colspan='4'></td></TR>";
		print "<tr><td colspan='4'><b>".$TRANS['dados_extra'].":</b></td></tr>";
		print "<TR><td colspan='4'></td></TR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_impressora'].": </b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_tipo_imp' size=1>";

				print "<option value=null selected>".$TRANS['cmb_selec_imp'].": </option>";

			$query = "SELECT * from tipo_imp  order by tipo_imp_nome";
			$resultado = mysql_query($query);
			while ($rowImp = mysql_fetch_array($resultado))
			{
				print "<option value='".$rowImp['tipo_imp_cod']."' ";
// 				if (isset($row) && $rowImp['tipo_imp_cod'] == $row['impressora_cod'])
// 					print " selected";
				print ">".$rowImp['tipo_imp_nome']."</option>";
			}
				print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
			print "</SELECT>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_monitor'].":</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_polegada' size=1>";

					print "<option value =null selected>".$TRANS['cmb_selec_monitor'].": </option>";

			$query = "SELECT * from polegada  order by pole_nome";
			$resultado = mysql_query($query);
			while ($rowPol = mysql_fetch_array($resultado))
			{
				print "<option value='".$rowPol['pole_cod']."' ";
// 				if (isset($row) && $rowPol['pole_cod'] == $row['polegada_cod'])
// 					print " selected";
				print ">".$rowPol['pole_nome']."</option>";
			}
			print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
			print "</SELECT>";
		print "</TD>";
		print "</tr>";

		print "<tr>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_scanner'].":</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='comp_resolucao' size=1>";

					print "<option value=null selected>".$TRANS['cmb_selec_scanner'].": </option>";

			$query = "SELECT * from resolucao  order by resol_nome";
			$resultado = mysql_query($query);
			while ($rowResol = mysql_fetch_array($resultado))
			{
				print "<option value='".$rowResol['resol_cod']."' ";
// 				if (isset($row) && $rowResol['resol_cod'] == $row['resolucao_cod'])
// 					print " selected";
				print ">".$rowResol['resol_nome']."</option>";
			}
			print "<option value=null>".$TRANS['cmb_selec_nenhum']."</option>";
			print "</SELECT>";
		print "</TD>";
		print "</tr>";



		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>".$TRANS['cx_data_cadastro'].":</b></TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>".datab($hoje)."</TD>";
		print "</TR>";

		print "<TR>";
		print "<TD colspan='2'  align='right' bgcolor='".BODY_COLOR."'><input type='submit' value='".$TRANS['bt_cadastrar']."' name='submit' ".
				"class='button' title='Cadastrar as informações fornecidas.'>";
		print "</TD>";
		print "<TD colspan='2' align='right' bgcolor='".BODY_COLOR."'><INPUT type='reset' value='".$TRANS['bt_cancelar']."' ".
				"class='button' onClick='javascript:history.back()'></TD>";
		print "</TR>";

	print "</TABLE>";
	print "</FORM>";

?>
<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idTipo','COMBO','Tipo de equipamento',1);
		if (ok) var ok = validaForm('idFab','COMBO','Fabricante',1);
		if (ok) var ok = validaForm('idModelo','COMBO','Modelo',1);

		return ok;
	}

//-->
</script>
<?

	if (isset($_POST['submit']))
	{
		$erro=false;
		$query2 = "SELECT m.* FROM moldes as m WHERE (m.mold_marca='".$_POST['comp_marca']."')";
		$resultado2 = mysql_query($query2);
		$linhas = mysql_numrows($resultado2);
		if ($linhas > 0)
		{
			$aviso = "Este modelo já possui configuração cadastrada no sistema!";
			$erro = true;
		}
		if (($_POST['comp_marca']==-1))
		{
			$aviso = "Selecione o modelo associar essa configuração";
			$erro = true;
		}

		if (!$erro)
		{
				$query = "INSERT INTO moldes ".
							"(mold_marca, mold_mb, mold_proc, mold_memo, mold_video, mold_som, ".
							"mold_rede, mold_modelohd, mold_modem, mold_cdrom, mold_dvd, mold_grav,  ".
							"mold_data, ".
							"mold_tipo_equip, mold_tipo_imp, mold_resolucao, mold_polegada, ".
							"mold_fab) ".
						"VALUES ('".$_POST['comp_marca']."',".
							"".$_POST['comp_mb'].", ".$_POST['comp_proc'].",".$_POST['comp_memo'].",".$_POST['comp_video'].",".
							"".$_POST['comp_som'].", ".$_POST['comp_rede'].", ".$_POST['comp_modelohd'].", ".
							"".$_POST['comp_modem'].", ".$_POST['comp_cdrom'].", ".$_POST['comp_dvd'].", ".
							"".$_POST['comp_grav'].", ".
							"".
							"'".date("Y-m-d H:i:s")."', ".
							"'".$_POST['comp_tipo_equip']."', ".
							"".$_POST['comp_tipo_imp'].", ".$_POST['comp_resolucao'].", ".$_POST['comp_polegada'].", ".
							"'".$_POST['comp_fab']."' ".
							")";
				$resultado = mysql_query($query) or die ('ERRO NA TENTATIVA DE CADASTRAR AS INFORMAÇÕES DO REGISTRO<br>'.$query);
				if ($resultado == 0)
				{
					print $query;
					$aviso = "ERRO na inclusão dos dados.";
				}
				else
				{
					$numero = mysql_insert_id();
					$aviso = "OK. Configuração do modelo cadastrada com sucesso!";
				}
			}
			print "<script>mensagem('".$aviso."'); redirect('model_config.php');</script>";
	}

	$cab->set_foot();
?>