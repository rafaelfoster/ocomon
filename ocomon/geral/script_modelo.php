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

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}



	print "<BR><B>Administração de XXXXX</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";


        	$query = "SELECT XXXXX FROM XXXXX  ".
        			"WHERE  XXXXX ";
		if (isset($_GET['cod'])) {
			$query.= " AND XXXXX = ".$_GET['cod']." ";
		}
		$query .=" ORDER BYXXXXX";
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÃO DA QUERY DE CONSULTA!');
		$registros = mysql_num_rows($resultado);

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Cadastrar XXXXX</a></TD></TR>";
		if (mysql_num_rows($resultado) == 0)
		{
			echo mensagem("Não há nenhum registro cadastrado!");
		}
		else
		{
			print "<tr><td class='line'>";
			print "Existe(m) <b>".$registros."</b> XXXXX cadastrados.</td>";
			print "</tr>";
			print "<TR class='header'><td class='line'>XXXXX</TD><td class='line'>XXXXX</TD>".
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
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";

				print "<td class='line'>".$row['fab_nome']."</td>";
				print "<td class='line'>".$row['tipo_it_desc']."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['XXXXX']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('Tem Certeza que deseja excluir esse registro do sistema?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['XXXXX']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";

				print "</TR>";
			}
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>Cadastro de XXXXX</B><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>XXXXX:</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='XXXXX' class='text' id='XXXXX'></td>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>XXXXX:</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='XXXXX' id='XXXXX'>";
				print "<option value=-1 selected>Selecione o tipo</option>";
				$select = "select * from XXXXX order by XXXXX";
				$exec = mysql_query($select);
				while($row = mysql_fetch_array($exec)){
					print "<option value=".$row['XXXXX'].">".$row['XXXXX']."</option>";
				} // while
			print "</select>";

		print "</td>";
		print "</tr>";

		print "<TR>";

		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='Cadastrar' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' value='Cancelar' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>Edição de XXXXX</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>XXXXX:</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='XXXXX' id='XXXXX' value='".$row['XXXXX']."'></td>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>XXXXX:</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='XXXXX' id='XXXXX'>";

			$sql = "select * from XXXXX where XXXXX=".$row["XXXXX"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >Selecione o tipo</option>";
					$sql="select * from XXXXX order by XXXXX";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["XXXXX"]."";
                        			if ($rowB['XXXXX'] == $row['XXXXX'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["XXXXX"]."</option>";
					} // while

		print "</select>";
		print "</TD>";
        	print "</TR>";

		print "<TR>";
		print "<BR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' value='Alterar' name='submit'>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' value='Cancelar' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$total = 0; $texto = "";
		$sql_1 = "SELECT * from XXXXX where XXXXX='".$_GET['cod']."'";
		$exec_1 = mysql_query($sql_1);
		$total+=mysql_numrows($exec_1);
		if (mysql_numrows($exec_1)!=0) $texto.="XXXXX, ";

		$sql_2 = "SELECT * FROM XXXXX where XXXXX ='".$_GET['cod']."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="XXXXX, ";


		if ($total!=0)
		{
			print "<script>mensagem('Este registro não pode ser excluído, existem pendências nas tabelas: ".$texto." associados a ele!');
				redirect('".$_SERVER['PHP_SELF'].".php');</script>";
		}
		else
		{
			$query2 = "DELETE FROM XXXXX WHERE XXXXX='".$_GET['cod']."'";
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

		$qryl = "SELECT * FROM XXXXX WHERE XXXXX='".$_POST['fab_nome']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = "Já existe um registro com essa descrição cadastro no sistema!!";
				$erro = true;;
		}

		if (!$erro)
		{

			$query = "INSERT INTO XXXXX (XXXXX, XXXXX) values ('".noHtml($_POST['XXXXX'])."', ".$_POST['XXXXX'].")";
			$resultado = mysql_query($query);
			if ($resultado == 0)
			{
				$aviso = "ERRO NA TENTATIVA DE INCLUIR O REGISTRO!";
			}
			else
			{
				$aviso = "OK. REGISTRO INCLUÍDO COM SUCESSO!.";
			}
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if ($_POST['submit'] == "Alterar"){

		$query2 = "UPDATE XXXXX SET XXXXX='".noHtml($_POST['XXXXX'])."', XXXXX=".noHtml($_POST['XXXXX'])." WHERE XXXXX='".$_POST['XXXXX']."'";
		$resultado2 = mysql_query($query2);

		if ($resultado2 == 0)
		{
			$aviso =  "ERRO NA TENTATIVA DE ALTERAR O REGISTRO!";
		}
		else
		{
			$aviso =  "REGISTRO ALTERADO COM SUCESSO!";
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}

	print "</table>";
	print "<form>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('XXXXX','','XXXXX',1);
		if (ok) var ok = validaForm('XXXXX','XXXXX','XXXXX',1);

		return ok;
	}

-->
</script>


<?
print "</body>";
print "</html>";
?>
