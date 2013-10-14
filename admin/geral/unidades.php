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
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	print "<BR><B>Domínios:</B><BR>";

	$query = "SELECT * from instituicao order by inst_nome";
        $resultado = mysql_query($query);

	if ((!isset($_GET['action'])) and !isset($_POST['submit'])){

		print "<TD align='right'><a href='unidades.php?action=incluir'>Incluir unidade.</a></TD><BR>";
		if (mysql_numrows($resultado) == 0)
		{
			echo mensagem("Não existem unidades cadastradas no sistema!");
		}
        else
        {
                $linhas = mysql_numrows($resultado);
                print "<td class='line'>";
                print "Existe(m) <b>".$linhas."</b> unidade(s) cadastrado(s) no sistema.<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "<TR class='header'><td class='line'>Unidade</TD><td class='line'>Status</TD><td class='line'><b>Alterar</b></TD><td class='line'><b>Excluir</b></TD>";
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
                        if ($row['inst_status'] == 0) $status ='INATIVO'; else $status = 'ATIVO';
                        //print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";
                        print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
                        print "<td class='line'>".$row['inst_nome']."</TD>";
						print "<td class='line'>".$status."</TD>";
                        print "<td class='line'><a onClick=\"redirect('unidades.php?action=alter&cod=".$row['inst_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
                        print "<td class='line'><a onClick=\"confirma('Tem Certeza que deseja excluir esse unidade do sistema?','unidades.php?action=excluir&cod=".$row['inst_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
                        print "</TR>";
				}
                print "</TABLE>";
        }

	} else
	if ((isset($_GET['action'])  && $_GET['action']=="incluir") && (!isset($_POST['submit']))) {

		print "<B>Cadastro de Unidades:<br>";
		print "<form name='incluir' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td class='line'>Descrição</td><td class='line'><input type='text' class='text' name='descricao' id='idDesc'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='Incluir'></td>";

		print "<td class='line'><input type='reset' class='button'  name='reset' value='Cancelar' onClick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";

	} else

	if ((isset($_GET['action'])  && $_GET['action']=="alter") && (!isset($_POST['submit']))) {

		$qry = "SELECT * from instituicao where inst_cod = ".$_GET['cod']."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);

		print "<B>Alteração da descrição da unidade:<br>";
		print "<form name='alter' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor='".TD_COLOR."'><b>Descrição</b></td><td class='line'><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter['inst_nome']."'></td>";
		print "</tr>";
		print "<tr>";
		print "<td bgcolor='".TD_COLOR."'><b>Status</b></td><td class='line'><select name='status' class='select'>";

		//<input type='text' class='text' name='data' value='".$rowAlter['data_feriado']."'>";
			print"<option value=1";
			if ($rowAlter['inst_status']==1) print " selected";
			print ">ATIVO</option>";
			print"<option value=0";
			if ($rowAlter['inst_status']==0) print " selected";
			print">INATIVO</option>";

		print "</select>";
		print " <input type='hidden' name='cod' value='".$_GET['cod']."'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='Alterar'></td>";
		print "<td class='line'><input type='reset' name='reset'  class='button' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if (isset($_GET['action']) && $_GET['action']=="excluir"){
			$qryAcha = "select * from equipamentos where comp_inst = ".$_GET['cod']."";
			$execAcha = mysql_query($qryAcha);
			$achou = mysql_numrows($execAcha);

			if ($achou){
				print "<script>mensagem('Esse registro não pode ser excluído por existirem equipamentos associados!');".
						" redirect('unidades.php');</script>";
				exit;
			} else {

				$qry = "DELETE FROM instituicao where inst_cod = ".$_GET['cod']."";
				$exec = mysql_query($qry) or die ('Erro na tentativa de deletar o registro!');
				print "<script>mensagem('Registro excluído com sucesso!');".
						" redirect('unidades.php');</script>";
			}
	} else

	if ($_POST['submit']=="Incluir"){
		if (!empty($_POST['descricao'])){
			$qry = "select * from instituicao where inst_nome = '".$_POST['descricao']."'";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				?>
				<script language="javascript">
				<!--
					mensagem('Esse unidade já está cadastrado no sistema!');
					redirect('unidades.php');
				//-->
				</script>
				<?
			} else {


				$qry = "INSERT INTO instituicao (inst_nome) values ('".noHtml($_POST['descricao'])."')";
				$exec = mysql_query($qry) or die ('Erro na inclusão do unidade!'.$qry);
				print "<script>mensagem('Dados incluídos com sucesso!'); redirect('unidades.php');</script>";
				}
		} else {
				print "<script>mensagem('Dados incompletos!'); redirect('unidades.php');</script>";
		}

	} else

	if ($_POST['submit'] = "Alterar"){
		if (!empty($_POST['descricao'])){

			$qry = "UPDATE instituicao set inst_nome='".noHtml($_POST['descricao'])."', inst_status='".$_POST['status']."' where inst_cod=".$_POST['cod']."";
			$exec= mysql_query($qry) or die('Não foi possível alterar os dados do registro!'.$qry);
				?>
				<script language="javascript">
				<!--
					mensagem('Dados alterados com sucesso!');
					history.go(-2)();
				//-->
				</script>
				<?
		} else {
			?>
			<script language="javascript">
			<!--
				mensagem('Dados incompletos!');
				history.go(-2)();
			//-->
			</script>
			<?
		}
	}




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