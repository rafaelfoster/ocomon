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

	//$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<head>";
	print "</head>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);




	print "<BR><B>".TRANS('TTL_SQUEMAS','ESQUEMAS').": &nbsp;</b><a href='".$_SERVER['PHP_SELF']."?action=list'>".TRANS('LIST_SQUEMAS','Lista esquemas')."</a><BR>";
	print "<form name='form1' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
	print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='60%' >";

	if (!isset($_POST['submit']) && $_GET['action'] == 'list') {
		$sql = "SELECT * FROM temas ORDER BY tm_nome";
		$exec = mysql_query($sql);
		$j = 2;

		print "<tr class='header'><td class='line'>".TRANS('OPT_SQUEMA','ESQUEMA')."</td><td class='line'>".TRANS('OPT_EDIT','EDITAR')."</td><td class='line'>".TRANS('OPT_DEL','EXCLUIR')."</td></tr>";
		while($row = mysql_fetch_array($exec)){
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

			print "<td class='line'>".$row['tm_nome']."</TD>";
			print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&id=".$row['tm_id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Alterar o registro'></a></td>";
			//$msg=str_replace('','\"',TRANS('SURE_DEL','Tem certeza que deseja deletar',0));
			print "<td class='line'><a onClick=\"javascript:confirmaAcao('Tem certeza que deseja deletar&nbsp;".$row['tm_nome']."?','".$_SERVER['PHP_SELF']."','action=excluir&id=".$row['tm_id']."');\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";

			print "</TR>";

		}
		print "<tr><td colspan='3'></b><input type='button' class='button' value='".TRANS('BT_LOAD','Carrega',0)."' onclick=\"redirect('".$_SERVER['PHP_SELF']."?action=LOAD');\"></td></tr>";
	} else
	if (!isset($_POST['submit']) && $_GET['action'] == 'save') {

		print "<tr>";
		print "<td>".TRANS('SQUEMA_NAME','Nome do para o esquema').":</td>";
		print "<td><input type='text' class='text' name='nomeTema' id='idNomeTema'></td>";
		print "</tr>";
		print "<tr><td colspan='2'>".TRANS('UPDATE_SQUEMA','Atualiza esquema caso já exista')."&nbsp;<input type='checkbox'  class='checkbox' name='update'></td></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr>";
		print "<input type='hidden' name='action' value='save'>";
		print "<input type='hidden' name='cor_lin_par' value='".str_replace('|', '#', $_GET['cor_lin_par'])."'>";
		print "<input type='hidden' name='cor_lin_impar' value='".str_replace('|', '#', $_GET['cor_lin_impar'])."'>";
		print "<input type='hidden' name='cor_destaca' value='".str_replace('|', '#', $_GET['cor_destaca'])."'>";
		print "<input type='hidden' name='cor_marca' value='".str_replace('|', '#', $_GET['cor_marca'])."'>";
		print "<input type='hidden' name='cor_body' value='".str_replace('|', '#', $_GET['cor_body'])."'>";
		print "<input type='hidden' name='cor_td' value='".str_replace('|', '#', $_GET['cor_td'])."'>";
		print "<input type='hidden' name='borda' value='".str_replace('|', '#', $_GET['borda'])."'>";
		print "<input type='hidden' name='borda_color' value='".str_replace('|', '#', $_GET['borda_color'])."'>";
		print "<input type='hidden' name='tr_header' value='".str_replace('|', '#', $_GET['tr_header'])."'>";
		print "<input type='hidden' name='topo' value='".str_replace('|', '#', $_GET['topo'])."'>";
		print "<input type='hidden' name='barra' value='".str_replace('|', '#', $_GET['barra'])."'>";
		print "<input type='hidden' name='menu' value='".str_replace('|', '#', $_GET['menu'])."'>";

		print "<input type='hidden' name='fonteNormal' value='".str_replace('|', '#', $_GET['fonteNormal'])."'>";
		print "<input type='hidden' name='fonteHover' value='".str_replace('|', '#', $_GET['fonteHover'])."'>";
		print "<input type='hidden' name='fonteDestaque' value='".str_replace('|', '#', $_GET['fonteDestaque'])."'>";
		print "<input type='hidden' name='fundoDestaque' value='".str_replace('|', '#', $_GET['fundoDestaque'])."'>";
		print "<input type='hidden' name='fonteHeader' value='".str_replace('|', '#', $_GET['font_tr_header'])."'>";
		print "<input type='hidden' name='tm_color_borda_header_centro' value='".str_replace('|', '#', $_GET['tm_color_borda_header_centro'])."'>";
		print "<input type='hidden' name='fonteTopo' value='".str_replace('|', '#', $_GET['fonteTopo'])."'>";

		print "<td align='center'><input type='submit' name='submit' class='button' value='".TRANS('BT_SAVE','Salvar',0)."'></td>".
				"<td align='center'><input type='button' name='cancelar' class='button' value='".TRANS('BT_CANCEL','Cancelar',0)."' onClick=\"javascript:self.close();\"></td>";
		print "</tr>";
	} else
	if (isset($_POST['submit']) && $_POST['action'] == 'save' ) {

		$sqlCheck = "SELECT * FROM temas WHERE tm_nome = '".$_POST['nomeTema']."' ";
		$execCheck = mysql_query($sqlCheck) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE ESQUEMAS!<br>'.$sqlCheck);
		$rowExist = mysql_fetch_array($execCheck);
		$exist = mysql_num_rows($execCheck);
		if ($exist!=0){
			if (isset($_POST['update'])) {

				//print "<script>if (!confirm('Já existe um tema com esse nome! Você deseja atualizá-lo?')) {self.close();}</script>";//{self.close();
				$sql = "UPDATE temas SET ".
					"tm_nome = '".$_POST['nomeTema']."' ,".
					"tm_color_destaca = '".$_POST['cor_destaca']."',".
					"tm_color_marca = '".$_POST['cor_marca']."',".
					"tm_color_lin_par = '".$_POST['cor_lin_par']."',".
					"tm_color_lin_impar = '".$_POST['cor_lin_impar']."',".
					"tm_color_body = '".$_POST['cor_body']."',".
					"tm_color_td = '".$_POST['cor_td']."',".
					"tm_borda_width = '".$_POST['borda']."', ".
					"tm_borda_color = '".$_POST['borda_color']."',".
					"tm_tr_header = '".$_POST['tr_header']."', ".
					"tm_color_topo = '".$_POST['topo']."', ".
					"tm_color_barra = '".$_POST['barra']."', ".
					"tm_color_menu = '".$_POST['menu']."', ".
					"tm_color_barra_font = '".$_POST['fonteNormal']."', ".
					"tm_color_barra_hover = '".$_POST['fonteHover']."', ".
					"tm_barra_fundo_destaque = '".$_POST['fundoDestaque']."', ".
					"tm_barra_fonte_destaque = '".$_POST['fonteDestaque']."', ".
					"tm_color_font_tr_header = '".$_POST['fonteHeader']."', ".
					"tm_color_borda_header_centro = '".$_POST['tm_color_borda_header_centro']."', ".
					"tm_color_topo_font = '".$_POST['fonteTopo']."' ".
					"WHERE tm_id = ".$rowExist['tm_id']."".
					"";
			} else {
				print "<script>mensagem('Já existe um esquema com esse nome! Se deseja atualizá-lo marque a opção de atualização!'); self.close();</script>";
				exit;
			}
		} else {

			$sql = "INSERT INTO temas (tm_nome, tm_color_destaca, tm_color_marca, tm_color_lin_par, tm_color_lin_impar, tm_color_body, ".
								"tm_color_td, tm_borda_width, tm_borda_color, tm_tr_header, tm_color_topo, ".
								"tm_color_barra, tm_color_menu, tm_color_barra_font, tm_color_barra_hover, ".
								"tm_barra_fundo_destaque, tm_barra_fonte_destaque, tm_color_font_tr_header, ".
								"tm_color_borda_header_centro, tm_color_topo_font".
								") ".
				"values ".
				"('".$_POST['nomeTema']."', '".$_POST['cor_destaca']."', '".$_POST['cor_marca']."', '".$_POST['cor_lin_par']."', ".
				"'".$_POST['cor_lin_impar']."', '".$_POST['cor_body']."', '".$_POST['cor_td']."', '".$_POST['borda']."', ".
				"'".$_POST['borda_color']."', '".$_POST['tr_header']."', '".$_POST['topo']."', '".$_POST['barra']."', '".$_POST['menu']."', ".
				"'".$_POST['fonteNormal']."', '".$_POST['fonteHover']."', '".$_POST['fundoDestaque']."', '".$_POST['fonteDestaque']."', ".
				"'".$_POST['fonteHeader']."', '".$_POST['tm_color_borda_header_centro']."', '".$_POST['fonteTopo']."'".
				")";
		}
		$exec = mysql_query($sql) or die ('ERRO NA TENTATIVA DE INCLUIR AS INFORMAÇÕES DE CONFIGURAÇÃO!<BR>'.$sql);

		print "<script>mensagem('Esquema cadastrado/atualizado com sucesso!');  window.self.close(); </script>";
	} else
	if (isset($_GET['action']) && $_GET['action'] == 'LOAD' ) {

		$sql = "SELECT * FROM temas ORDER BY tm_nome";
		$exec = mysql_query($sql) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE ESQUEMAS!');
		$qtd = mysql_num_rows($exec);
		if ($qtd==0){
			print "<script>mensagem('Não há esquemas salvos no sistema!'); window.self.close();</script>";
			exit;
		}

		print "<tr><td>".TRANS('SQUEMA','ESQUEMA').":</td><td>";
		print "<select name='tema' class='text'>";
			print "<option value='-1'>".TRANS('OPT_SEL_SQUEMA','Selecione o Esquema')."</option>";
			while ($rowTema = mysql_fetch_array($exec)){
				print "<option value=".$rowTema['tm_id'].">".$rowTema['tm_nome']."</option>";
			}
		print "</select>";
		print "</td>";
		print "</tr>";
		//print "</tr><tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr>";
		print "<tr>";
		print "<input type='hidden' name='action' value='LOAD'>";
		print "<td align='center'><input type='submit' name='submit' class='button' value='".TRANS('BT_LOAD','Carregar',0)."'></td>".
				"<td align='center'><input type='button' name='cancelar' class='button' value='".TRANS('BT_CANCEL','Cancelar',0)."' onClick=\"javascript:self.close();\"></td>";
		print "</tr>";

	} else
	if (isset($_POST['submit']) && $_POST['action']=='LOAD') {
		if ($_POST['tema'] == -1){
			print "<script>mensagem('É necessário escolher um esquema!'); history.back();</script>";
			exit;
		}

		$sql = "SELECT * FROM temas WHERE tm_id = ".$_POST['tema']."";
		$exec = mysql_query($sql) or die ('ERRO NA TENTATIVA DE CARREGAR O ESQUEMA!<br>'.$sql);
		$rowTema = mysql_fetch_array($exec);

		$sqlInsert = "UPDATE styles set ".
					"tm_color_destaca = '".$rowTema['tm_color_destaca']."',".
					"tm_color_marca = '".$rowTema['tm_color_marca']."',".
					"tm_color_lin_par = '".$rowTema['tm_color_lin_par']."',".
					"tm_color_lin_impar = '".$rowTema['tm_color_lin_impar']."',".
					"tm_color_body = '".$rowTema['tm_color_body']."',".
					"tm_color_td = '".$rowTema['tm_color_td']."',".
					"tm_borda_width = '".$rowTema['tm_borda_width']."', ".
					"tm_borda_color = '".$rowTema['tm_borda_color']."',".
					"tm_tr_header = '".$rowTema['tm_tr_header']."', ".
					"tm_color_topo = '".$rowTema['tm_color_topo']."', ".
					"tm_color_barra = '".$rowTema['tm_color_barra']."', ".
					"tm_color_menu = '".$rowTema['tm_color_menu']."', ".
					"tm_color_barra_font = '".$rowTema['tm_color_barra_font']."', ".
					"tm_color_barra_hover = '".$rowTema['tm_color_barra_hover']."', ".
					"tm_barra_fundo_destaque = '".$rowTema['tm_barra_fundo_destaque']."', ".
					"tm_barra_fonte_destaque = '".$rowTema['tm_barra_fonte_destaque']."', ".
					"tm_color_font_tr_header = '".$rowTema['tm_color_font_tr_header']."', ".
					"tm_color_borda_header_centro = '".$rowTema['tm_color_borda_header_centro']."', ".
					"tm_color_topo_font = '".$rowTema['tm_color_topo_font']."' ".
					"";
		$execInsert = mysql_query($sqlInsert) or die ('ERRO NA TENTATIVA DE ATUALIZAR AS INFORMAÇÕES DE ESQUEMAS!<BR>'.$sqlInsert);

		$_SESSION['s_colorDestaca'] = $rowTema['tm_color_destaca'];
		$_SESSION['s_colorMarca'] = $rowTema['tm_color_marca'];
		$_SESSION['s_colorLinPar'] = $rowTema['tm_color_lin_par'];
		$_SESSION['s_colorLinImpar'] = $rowTema['tm_color_lin_impar'];


		//print "<script>mensagem('Tema carregado com sucesso! Tecle F5 para atualizar a página!'); window.opener.location.reload(); window.self.close(); </script>";
		print "<script>mensagem('O Esquema será carregado agora!'); window.opener.open('../../index.php?LOAD=ADMIN','_parent',''); window.self.close(); </script>"; //?LOAD=ADMIN

	} else
	if (isset($_GET['action']) && $_GET['action'] == 'excluir') {
		$sql = "DELETE FROM temas WHERE tm_id = ".$_GET['id']." ";
		$exec = mysql_query($sql) or die('ERRO NA TENTATIVA DE EXCLUIR O REGISTRO!<br>'.$sql);
		print "<script>mensagem('Registro excluído com sucesso!'); redirect('".$_SERVER['PHP_SELF']."?action=list');</script>";
		exit;

	} else
	if (isset($_GET['action']) && $_GET['action']== 'alter') {
		$sql = "SELECT * FROM temas WHERE tm_id = ".$_GET['id']."";
		$exec = mysql_query($sql) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DO REGISTRO!<br>'.$sql);
		$row = mysql_fetch_array($exec);

		print "<tr>";
		print "<td>".TRANS('SQUEMA_NAME','Nome do para o esquema').":</td>";
		print "<td><input type='text' class='text' name='nomeTema' id='idNomeTema' value='".$row['tm_nome']."'></td>";
		print "</tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr>";
		print "<input type='hidden' name='id' value='".$_GET['id']."'>";
		print "<input type='hidden' name='action' value='alter'>";
		print "<td align='center'><input type='submit' name='submit' class='button' value='".TRANS('BT_ALTER','Alterar',0)."'></td>".
				"<td align='center'><input type='button' name='cancelar' class='button' value='".TRANS('BT_CANCEL','Cancelar',0)."' onClick=\"javascript:self.close();\"></td>";
		print "</tr>";


	} else
	if (isset($_POST['submit']) && $_POST['action']=='alter') {

		$sql = "UPDATE temas SET tm_nome='".$_POST['nomeTema']."' WHERE tm_id = ".$_POST['id']." ";
		$exec = mysql_query($sql) or die('ERRO NA TENTATIVA DE ATUALIZAR AS INFORMAÇÕES DO REGISTRO!<BR>'.$sql);
		print "<script>mensagem('Registro atualizado com sucesso!'); redirect('".$_SERVER['PHP_SELF']."?action=list');</script>";
		exit;

	}

	print "<table>";
	print "</form>";


	?>
<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idNomeTema','ALFAFULL','Nome do Tema',1);

		return ok;
	}
-->
</script>
	<?php 

	print "</body>";
	print "</html>";
?>