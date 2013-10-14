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
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	print "<BR><B>Administração de itens emprestados</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

		$query = "SELECT e.* , u.* , l.local as local_nome FROM emprestimos AS e, usuarios AS u, localizacao AS l ".
				"WHERE e.responsavel = u.user_id AND e.local = l.loc_id ";

		if (isset($_GET['cod'])) {
			$query.= " AND e.empr_id= ".$_GET['cod']." ";
		}
		$query .=" ORDER  BY data_devol";
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÃO DA QUERY DE CONSULTA!<br>'.$query);
		$registros = mysql_num_rows($resultado);

	if ((!isset($_GET['action'])) && !isset($_POST['submit'])) {

		print "<TR><TD bgcolor='".BODY_COLOR."'>".
				//"<a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Incluir empréstimo</a>".
				"<input type='button' class='button' id='idBtIncluir' value='Novo Empréstimo' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\">".
				"</TD></TR>";
		if (mysql_num_rows($resultado) == 0) {
			//$msg = "<p class='msg'>Não há nenhum empréstimo cadastrado!</p>";
			echo "<tr><td align='center'>".mensagem("Não há nenhum empréstimo cadastrado!")."</td></tr>";
		} else {
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td class='line'>";
			print "Existe(m) <b>".$registros."</b> empréstimos cadastrados.</td>";
			print "</tr>";
			print "<TR class='header'><td class='line'>Material</TD><td class='line'>Responsável</TD><td class='line'>Data do empréstimo</TD>";
			print "<td class='line'>Data de devolução</TD><td class='line'>Quem</TD><td class='line'>Local</TD><td class='line'>Ramal</TD>
				<td class='line'>Alterar</TD><td class='line'>Excluir</TD></TR>";

			$j=2;
			while ($row = mysql_fetch_array($resultado)) {
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

				print "<td class='line'>".$row['material']."</td>";
				print "<td class='line'>".$row['nome']."</td>";
				print "<td class='line'>".datab($row['data_empr'])."</td>";
				print "<td class='line'>".datab($row['data_devol'])."</td>";
				print "<td class='line'>".$row['quem']."</td>";
				print "<td class='line'>".$row['local_nome']."</td>";
				print "<td class='line'>".$row['ramal']."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['empr_id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('Tem Certeza que deseja excluir esse registro do sistema?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['empr_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";

				print "</TR>";
			}
                //print "</TABLE>";
         	}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& !isset($_POST['submit'])) {

		print "<BR><B>Cadastro de empréstimo de material</B><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Material:</TD>";
                print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'><TEXTAREA class='textarea' name='material' id='idMaterial'></textarea></TD>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Para Quem:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='quem' id='idQuem' ></td>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Ramal *:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='ramal' id='idRamal'></TD>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Local:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select' name='local' id='idLocal'>";
			print "<option value=-1>Selecione o local</option>";

				$sql="select * from localizacao order by local";
				$commit = mysql_query($sql);
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['loc_id'].">".$row["local"]."</option>";
				} // while
		print "</select>";
		print "</td>";

                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data de saída:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text'  class='text' name='saida' id='idDataSaida' value='".date("d/m/Y")."'></TD>";

		print "</tr>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data de devolução:</TD>";
                print "<TD width='30%' colspan='3' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text'  class='text' name='volta' id='idDataDevolucao' value='".date("d/m/Y")."'></TD>";
        	print "</TR>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<TR>";
		print "<TD colspan='2' align='center' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='Cadastrar' name='submit'>";
		print "</TD>";
		print "<TD colspan='2' align='center' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='Cancelar' name='cancelar' onClick=\"redirect('".$_SERVER['PHP_SELF']."')\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>Edição de empréstimos</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Material:</TD>";
                print "<TD width='80%' colspan='3' align='left' bgcolor='".BODY_COLOR."'><textarea class='textarea' name='material' id='idMaterial'>".$row['material']."</textarea></td>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Para quem:</TD>".
			"<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='quem' id='idQuem' value='".$row['quem']."'>";

                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Ramal *:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='ramal' id='idRamal' value='".$row['ramal']."'></TD>";

		print "</tr>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data de saída:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='saida' id='idDataSaida' value='".datab($row['data_empr'])."'></TD>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data de devolução:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='volta' class='text' id='idDataDevolucao' value='".datab($row['data_devol'])."'></TD>";
		print "</tr>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>Operador:</TD>".
			"<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='responsavel' id='idResponsavel'>";

			$sql = "select * from usuarios where user_id=".$row['responsavel']."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >Selecione o responsável</option>";
					$sql="select * from usuarios order by nome";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["user_id"]."";
                        			if ($rowB['user_id'] == $row['user_id'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["nome"]."</option>";
					} // while

		print "</select>";
		print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Local:</TD>";
		print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select' name='local' id='idLocal'>";
			print "<option value='-1'>Selecione o local</option>";

				$sql="select * from localizacao order by local";
				$commit = mysql_query($sql);
				while($rowL = mysql_fetch_array($commit)){
					print "<option value=".$rowL['loc_id']."";
					if ($rowL['loc_id'] == $row['local'])
						print " selected";
					print ">".$rowL["local"]."</option>";
				} // while
		print "</select>";
		print "</td>";

        	print "</TR>";


		print "<tr><td colspan='4'>&nbsp;</td></tr>";
		print "<TR>";
		print "<TD align='center' width='20%' colspan='2' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='Alterar' name='submit'>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='center' colspan='2' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='Cancelar' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

		print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$query2 = "DELETE FROM emprestimos WHERE empr_id='".$_GET['cod']."'";
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

	if ($_POST['submit'] == "Cadastrar"){

		$erro=false;

		if (!$erro)
		{

			$query = "INSERT INTO emprestimos (material, responsavel, data_empr, data_devol, quem, local, ramal) values".
				" ('".noHtml($_POST['material'])."', '".$_SESSION['s_uid']."','".datam($_POST['saida'])."','".datam($_POST['volta'])."',".
				"'".$_POST['quem']."', '".$_POST['local']."', '".$_POST['ramal']."')";
			$resultado = mysql_query($query) or die ('ERRO NA TENTATIVA DE INCLUIR O REGISTRO!<br>'.$query);

			if ($resultado == 0)
			{
				$aviso = "ERRO NA TENTATIVA DE INCLUIR O REGISTRO!<br>".$query;
			}
			else
			{
				$aviso = "OK. REGISTRO INCLUÍDO COM SUCESSO!.";
			}
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if ($_POST['submit'] == "Alterar"){

                $query2 = "UPDATE emprestimos SET material='".noHtml($_POST['material'])."', responsavel='".noHtml($_POST['responsavel'])."', ".
                	"ramal = '".$_POST['ramal']."', local = ".$_POST['local'].", data_empr='".datam($_POST['saida'])."', data_devol='".datam($_POST['volta'])."', ".
                	"quem='".$_POST['quem']."' WHERE empr_id='".$_POST['cod']."'";
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
	print "</form>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idMaterial','','Material',1);
		if (ok) var ok = validaForm('idQuem','','Para quem',1);

		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);

		if (ok) var ok = validaForm('idRamal','FONE','Ramal',1);
		if (ok) var ok = validaForm('idDataSaida','DATAFULL','Data Saída',1);
		if (ok) var ok = validaForm('idDataDevolucao','DATAFULL','Data Devolução',1);

		return ok;

	}

-->
</script>


<?
print "</body>";
print "</html>";
