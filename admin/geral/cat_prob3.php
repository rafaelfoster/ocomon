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

	print "<html><head></head>";
	print "<body>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	$qry_config = "SELECT * FROM config ";
	$exec_config = mysql_query($qry_config) or die ("ERRO AO TENTAR ACESSAR A TABELA CONFIG! CERTIFIQUE-SE DE QUE A TABELA EXISTE!");;
	$row_config = mysql_fetch_array($exec_config);

        print "<BR><B>Categorização de Problemas - ".$row_config['conf_prob_tipo_3']."</B><BR>";

	$query = "SELECT * from prob_tipo_3 order by probt3_desc";
        $resultado = mysql_query($query);

	if ((!isset($_GET['action'])) && !isset($_POST['submit'])) {

        print "<TD align='right'><input type='button' class='minibutton' value='Novo Tipo' onclick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir')\"></TD><br><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("Não existem registros cadastrados no sistema!");
        }
        else
        {
                $cor=TD_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<td class='line'>";
                print "Existe(m) <b>".$linhas."</b> tipo(s) de classificação(ões) quanto a categoria \"".$row_config['conf_prob_tipo_3']."\".<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "<TR class='header'><td class='line'>DESCRIÇÃO</TD><td class='line'><b>Alterar</b></TD><td class='line'><b>Excluir</b></TD>";
                $j=2;
		while ($row=mysql_fetch_array($resultado))
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
			print "<td class='line'>".$row['probt3_desc']."</TD>";
			print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['probt3_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
			print "<td class='line'><a onClick=\"confirma('Tem Certeza que deseja excluir esse registro do sistema?','".$_SERVER['PHP_SELF']."?action=excluir&cod=".$row['probt3_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
			print "</TR>";
		}
		print "</TABLE>";
	}

	} else
	if ((isset($_GET['action']) && ($_GET['action'] == "incluir")) && !isset($_POST['submit']) ) {

		print "<B>Inclusão de registro:<br>";
		print "<form method='post' name='incluir' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td class='line'>Descrição</td><td class='line'><input type='text' class='text' name='descricao' id='idDesc'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='Incluir'></td>";

		print "<td class='line'><input type='reset'  class='button' name='reset' value='Cancelar' onClick=\"javascript:history.back();\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ( (isset($_GET['action']) && $_GET['action']=="alter") && !isset($_POST['submit'])) {
		$qry = "SELECT * from prob_tipo_3 where probt3_cod = ".$_GET['cod']."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);

		print "<B>Edição de registro:<br>";
		print "<form method='post' name='alter' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR."><b>Descrição</b></td><td class='line'><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter['probt3_desc']."'>";
		print " <input type='hidden' name='cod' value='".$_GET['cod']."'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='Alterar'></td>";
		print "<td class='line'><input type='reset'  class='button' name='reset' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if (isset($_GET['action']) && $_GET['action']=="excluir"){
		$qry_checa = "SELECT * FROM problemas WHERE prob_tipo_3 = ".$_GET['cod']." and prob_tipo_3 is not null";
		$exec_checa = mysql_query($qry_checa) or die ('ERRO NA TENTATIVA DE VERIFICAR A CONSISTÊNCIA DA EXCLUSÃO!<BR>'.$qry_checa);
		$total_prob = mysql_numrows($exec_checa);
		if ($total_prob != 0) {
			print "<script>mensagem('Não é possível excluir esse registro! O mesmo está vinculado à pelo menos 1 problema!'); ".
					"redirect('".$_SERVER['PHP_SELF']."');</script>";
			exit;
		}

		$qry = "DELETE FROM prob_tipo_3 where probt3_cod = ".$_GET['cod']."";
		$exec = mysql_query($qry) or die ('Erro na tentativa de deletar o registro!');

		print "<script>mensagem('Registro excluído com sucesso!'); redirect('".$_SERVER['PHP_SELF']."'); window.opener.location.reload(); </script>";

	} else

	if (isset($_POST['submit']) && $_POST['submit'] == "Incluir") {
		if ((!empty($_POST['descricao'])) ){
			$qry = "select * from prob_tipo_3 where probt3_desc = '".$_POST['descricao']."' ";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				print "<script>mensagem('Já existe um registro com a mesma descrição!'); redirect('".$_SERVER['PHP_SELF']."');</script>";
			} else {

				$qry = "INSERT INTO prob_tipo_3 (probt3_desc) values ('".noHtml($_POST['descricao'])."')";
				$exec = mysql_query($qry) or die ('Erro na inclusão do registro!'.$qry);
				print "<script>mensagem('Dados incluídos com sucesso!'); redirect('".$_SERVER['PHP_SELF']."'); window.opener.location.reload(); </script>";
			}
		} else {
			print "<script>mensagem('Dados incompletos!'); redirect('".$_SERVER['PHP_SELF']."'); </script>";
		}
	} else

	if (isset($_POST['submit']) && $_POST['submit'] = "Alterar") {
		if ((!empty($_POST['descricao']))){

			$qry = "UPDATE prob_tipo_3 set probt3_desc='".noHtml($_POST['descricao'])."' where probt3_cod=".$_POST['cod']."";
			$exec= mysql_query($qry) or die('Não foi possível alterar os dados do registro!'.$qry);

			print "<script>mensagem('Registro alterado com sucesso!'); redirect('".$_SERVER['PHP_SELF']."');</script>";

		} else {
			print "<script>mensagem('Dados incompletos!'); history.back(); window.opener.location.reload(); </script>";
		}
	}


print "<br><br><input type='button' class='minibutton' value='Fechar' onclick='self.close();'>";

print "</body>";
?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idDesc','','Descrição',1);

		return ok;
	}
-->
</script>
<?
print "</html>";

?>