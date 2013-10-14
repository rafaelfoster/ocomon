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

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";


        	$query = "SELECT m.marc_cod as codigo, m.marc_nome as modelo, t.tipo_nome as tipo, t.tipo_cod as tipo_cod ".
        					"FROM marcas_comp as m, tipo_equip as t ".
        					"WHERE m.marc_tipo = t.tipo_cod ";
		if (isset($_GET['cod'])) {
			$query.= " AND m.marc_cod = ".$_GET['cod']." ";
		}
		$query .=" ORDER BY m.marc_nome, t.tipo_nome";
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÃO DA QUERY DE CONSULTA!');
		$registros = mysql_num_rows($resultado);

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Cadastrar modelo</a></TD></TR>";
		if (mysql_num_rows($resultado) == 0)
		{
			echo mensagem("Não há nenhum registro cadastrado!");
		}
		else
		{
			print "<tr><td class='line'>";
			print "Existe(m) <b>".$registros."</b> cadastrados.</td>";
			print "</tr>";
			print "<TR class='header'><td class='line'>Modelo</TD><td class='line'>Tipo</TD>".
				"<td class='line'>Alterar</TD><td class='line'>Excluir</TD></tr>";

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
				print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";

				$qryImg = "select * from imagens where img_model = ".$row['codigo']."";
				$execImg = mysql_query($qryImg) or die ("ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE IMAGENS!");
				$rowTela = mysql_fetch_array($execImg);
				$regImg = mysql_num_rows($execImg);
				if ($regImg!=0) {
					$linkImg = "<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$row['codigo']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='../../includes/icons/attach2.png'></a>";
				} else $linkImg = "";

				print "<td class='line'>".$linkImg."&nbsp;".$row['modelo']."</td>";
				print "<td class='line'>".$row['tipo']."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['codigo']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('Tem Certeza que deseja excluir esse registro do sistema?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['codigo']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";

				print "</TR>";
			}
			print "<tr><td colspan='4'><input type='button' class='minibutton' value='Fechar' onclick='self.close();'></td></tr>";
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>Cadastro de Modelos de equipamentos</B><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Nome:</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='modelo' class='text' id='idModelo'></td>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Tipo de equipamento:</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='tipo' id='idTipo'>";
				print "<option value=-1 selected>Selecione o tipo</option>";
				$select = "select * from tipo_equip order by tipo_nome";
				$exec = mysql_query($select);
				while($row = mysql_fetch_array($exec)){
					print "<option value=".$row['tipo_cod'].">".$row['tipo_nome']."</option>";
				} // while
			print "</select>";

		print "</td>";
		print "</tr>";

                print "<td width='20%' bgcolor='".TD_COLOR."'><b>Anexar imagem</b></td>";
		print "<TD width='80%' align='left' bgcolor=".BODY_COLOR."><INPUT type='file' class='text' name='img' id='idImg'></TD>";

		print "<TR>";

		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='Cadastrar' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='Cancelar' name='cancelar' onClick=\"javascript:fecha();\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>Edição do registro</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Nome do modelo:</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='modelo' id='idModelo' value='".$row['modelo']."'></td>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Tipo:</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='tipo' id='idTipo'>";

			$sql = "select * from tipo_equip where tipo_cod=".$row["tipo_cod"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >Selecione o tipo</option>";
					$sql="select * from tipo_equip order by tipo_nome";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["tipo_cod"]."";
                        			if ($rowB['tipo_cod'] == $row['tipo_cod'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["tipo_nome"]."</option>";
					} // while

		print "</select>";
		print "</TD>";
        	print "</TR>";

		print "<tr>";
                print "<td width='20%' bgcolor='".TD_COLOR."'><b>Anexar imagem</b></td>";
		print "<TD width='80%' align='left'><INPUT type='file' class='text' name='img' id='idImg'></TD>";
		print "</tr>";

		$qryTela3 = "select  i.* from imagens i  WHERE i.img_model = '".$_GET['cod']."'  order by i.img_inv ";
		$execTela3 = mysql_query($qryTela3) or die ("NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DA TABELA DE IMAGENS!");
		//$rowTela = mysql_fetch_array($execTela);
		$isTela3 = mysql_num_rows($execTela3);
		$cont = 0;
		while ($rowTela3 = mysql_fetch_array($execTela3)) {
		//if ($isTela !=0) {
			$cont++;
			print "<tr>";

			print "<TD  width='20%' bgcolor='".TD_COLOR."' >Imagem ".$cont." do modelo:</td>";
			print "<td colspan='3'><a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$rowTela3['img_cod']."&cod=".$rowTela3['img_cod']."',".$rowTela3['img_largura'].",".$rowTela3['img_altura'].")\"><img src='../../includes/icons/attach2.png'>".$rowTela3['img_nome']."</a>";
			print "<input type='checkbox' name='delImg[".$cont."]' value='".$rowTela3['img_cod']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";

			print "</tr>";
		}

		print "<TR>";
		print "<BR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='Alterar' name='submit'>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
		print "<input type='hidden' name='cont' value='".$cont."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='Cancelar' name='cancelar' onClick=\"javascript:fecha();\"></TD>";

		print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$total = 0; $texto = "";
		$sql_1 = "SELECT * from equipamentos where comp_marca='".$_GET['cod']."'";
		$exec_1 = mysql_query($sql_1);
		$total+=mysql_numrows($exec_1);
		if (mysql_numrows($exec_1)!=0) $texto.="EQUIPAMENTOS, ";

		if ($total!=0)
		{
			print "<script>mensagem('Este registro não pode ser excluído, existem pendências nas tabelas: ".$texto." associados a ele!');
				redirect('".$_SERVER['PHP_SELF'].".php');</script>";
		}
		else
		{
			$query2 = "DELETE FROM marcas_comp WHERE marc_cod='".$_GET['cod']."'";
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

		}


	} else

	if ($_POST['submit'] == "Cadastrar"){

		$erro=false;

		$qryl = "SELECT * FROM marcas_comp WHERE marc_nome='".$_POST['modelo']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = "Já existe um registro com essa descrição cadastro no sistema!!";
				$erro = true;
		}

		if (!$erro)
		{
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


			$query = "INSERT INTO marcas_comp (marc_nome, marc_tipo) values ('".noHtml($_POST['modelo'])."', ".$_POST['tipo'].")";
			$resultado = mysql_query($query) or die('ERRO NA TENTATIVA DE CADASTRAR O REGISTRO!');
			$modelCod = mysql_insert_id();

			$anexado = false;
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
					$SQL = "Insert Into imagens (img_nome, img_model, img_tipo, img_bin, img_largura, img_altura) values ".
							"('".$_FILES['img']['name']."', '".$modelCod."', '".$_FILES['img']['type']."', '".$image."', ".$tamanho[0].", ".$tamanho[1].")";
					// now we can delete the temp file
					unlink($fileinput);
					$anexado = true;
				} /*else {
					echo "NENHUMA IMAGEM FOI SELECIONADA!";
					exit;
				}*/
				$exec = mysql_query($SQL); //or die ("NÃO FOI POSSÍVEL GRAVAR O ARQUIVO NO BANCO DE DADOS! ");
				if ($exec == 0) $aviso.= "NÃO FOI POSSÍVEL ANEXAR A IMAGEM!<br>";

			}
			$aviso = "OK. REGISTRO INCLUÍDO COM SUCESSO!.";
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if ($_POST['submit'] == "Alterar"){

		$query2 = "UPDATE marcas_comp SET marc_nome='".noHtml($_POST['modelo'])."', marc_tipo=".$_POST['tipo']." WHERE marc_cod='".$_POST['cod']."'";
		$resultado2 = mysql_query($query2) or die('ERRO NA TENTATIVA DE ALTERAR AS INFORMAÇÕES DO REGISTRO!');

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

		for ($j=1; $j<=$_POST['cont']; $j++) {
			if (isset($_POST['delImg'][$j])){
				$qryDel = "DELETE FROM imagens WHERE img_cod = ".$_POST['delImg'][$j]."";
				$execDel = mysql_query($qryDel) or die ("NÃO FOI POSSÍVEL EXCLUIR A IMAGEM!");
			}
		}


		$anexado = false;
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
				$SQL = "Insert Into imagens (img_nome, img_model, img_tipo, img_bin, img_largura, img_altura) values ".
						"('".$_FILES['img']['name']."', '".$_POST['cod']."', '".$_FILES['img']['type']."', '".$image."', ".$tamanho[0].", ".$tamanho[1].")";
				// now we can delete the temp file
				unlink($fileinput);

				$anexado = true;
			} /*else {
				echo "NENHUMA IMAGEM FOI SELECIONADA!";
				exit;
			}*/
			$exec = mysql_query($SQL); //or die ("NÃO FOI POSSÍVEL GRAVAR O ARQUIVO NO BANCO DE DADOS! ");
			if ($exec == 0) $aviso.= "NÃO FOI POSSÍVEL ANEXAR A IMAGEM!<br>";
		}


		$aviso =  "REGISTRO ALTERADO COM SUCESSO!";

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}

	print "</table>";
	print "<form>";


?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idModelo','','Nome do modelo',1);
		if (ok) var ok = validaForm('idTipo','COMBO','Tipo de equipamento',1);

		return ok;
	}

-->
</script>


<?
print "</body>";
print "</html>";
?>
