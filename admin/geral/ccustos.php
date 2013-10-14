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


	print "<BR><B>Centros de Custo:</B><BR>";

	$query = "SELECT * from `".DB_CCUSTO."`.".TB_CCUSTO." order by ".CCUSTO_DESC."";
        //print $query; exit;
	$resultado = mysql_query($query);

	if ((!isset($_GET['action'])) and (!isset($_POST['submit']))) {
        print "<TD align='right'><a href='ccustos.php?action=incluir'>Incluir Centro de Custo.</a></TD><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem("Não existem Centros de Custo cadastrados no sistema!");
        }
        else
        {
                $cor=TD_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<td class='line'>";
                print "Existe(m) <b>".$linhas."</b> Centro(s) de Custo cadastrado(s) no sistema.<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "<TR class='header'><td class='line'>Descricão</TD><td class='line'>Código</TD><td class='line'><b>Alterar</b></TD><td class='line'><b>Excluir</b></TD>";
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

                        //print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";
                        print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
                        print "<td class='line'>".$row[CCUSTO_DESC]."</TD>";
						print "<td class='line'>".$row['codccusto']."</TD>";
                        print "<td class='line'><a onClick=\"redirect('ccustos.php?action=alter&cod=".$row['codigo']."')\"><img height='16' width='16'  src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
                        print "<td class='line'><a onClick=\"confirma('Tem Certeza que deseja excluir esse Centro de Custo?','ccustos.php?action=excluir&cod=".$row['codigo']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
                        print "</TR>";
				}
                print "</TABLE>";

        }

	} else
	if ((isset($_GET['action'])  && $_GET['action']=="incluir") && (!isset($_POST['submit']))) {

		print "<B>Cadastro de Centros de Custo:<br>";
		print "<form name='incluir' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td class='line'>Descrição</td><td class='line'><input type='text' class='text' name='descricao' id='idDesc'></td>";
		print "</tr>";

		print "<tr>";
		print "<td class='line'>Código</td><td class='line'><input type='text' class='text' name='codigo' id='idCodigo'></td>";
		print "</tr>";
		print "<tr><td class='line'><input type='submit' class='button' name='submit' value='Incluir'></td>";
		print "<td class='line'><input type='reset' name='reset' class='button' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";

	} else

	if ((isset($_GET['action'])  && $_GET['action']=="alter") && (!isset($_POST['submit']))) {

		$qry = "SELECT * from `".DB_CCUSTO."`.".TB_CCUSTO." where codigo = ".$_GET['cod']."";

		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);

		print "<B>Alteração do Centro de Custo:<br>";
		print "<form name='alter' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor='".TD_COLOR."'><b>Descrição</b></td><td class='line'><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter[CCUSTO_DESC]."'></td>";
		print "</tr>";
		print "<tr>";
		print "<td bgcolor='".TD_COLOR."'><b>Código</b></td><td class='line'><input type='text' class='text' name='codigo' id='idCodigo' value='".$rowAlter['codccusto']."'>";

		print " <input type='hidden' name='cod' value='".$_GET['cod']."'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit' class='button' name='submit' value='Alterar'></td>";
		print "<td class='line'><input type='reset' name='reset' class='button' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";

	} else

	if (isset($_GET['action']) && $_GET['action']=="excluir"){

		$qryBusca = "SELECT C.*, E.* from equipamentos E, `".DB_CCUSTO."`.".TB_CCUSTO." C where E.comp_ccusto = C.codigo and C.codigo = ".$_GET['cod']."";
		$execBusca = mysql_query($qryBusca) or die ('ERRO NA BUSCA DE REGISTRO PARA ESSE CENTRO DE CUSTO! '.$qryBusca);
		$achou = mysql_numrows($execBusca);
		if ($achou) {
			?>
			<script language="javascript">
			<!--
				mensagem('Esse registro não pode ser excluído por existirem <?print $achou;?> etiquetas associadas!');
				window.location.href='ccustos.php';
			//-->
			</script>
			<?
			exit;
		} else {

			$qry = "DELETE FROM `".DB_CCUSTO."`.".TB_CCUSTO." where codigo = ".$_GET['cod']."";
			$exec = mysql_query($qry) or die ('Erro na tentativa de deletar o registro!');
			?>
			<script language="javascript">
			<!--
				mensagem('Registro excluído com sucesso!');
				window.location.href='ccustos.php';
			//-->
			</script>
			<?
		}


	} else

	if ($_POST['submit']=="Incluir"){
		if ((isset($_POST['descricao'])) && (isset($_POST['codigo']))){
			$qry = "select * from `".DB_CCUSTO."`.".TB_CCUSTO." where ".CCUSTO_DESC."='".$_POST['descricao']."' and codccusto = ".$_POST['codigo']."";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				?>
				<script language="javascript">
				<!--
					mensagem('Esse Centro de Custo já está cadastrado no sistema!');
					history.go(-2)();
				//-->
				</script>
				<?
			} else {

				$qry = "INSERT INTO `".DB_CCUSTO."`.".TB_CCUSTO." (".CCUSTO_DESC.",codccusto) values ('".noHtml($_POST['descricao'])."','".noHtml($_POST['codigo'])."')";
				$exec = mysql_query($qry) or die ('Erro na inclusão do Centro de Custo!'.$qry);
				?>
				<script language="javascript">
				<!--
					mensagem('Dados incluídos com sucesso!');
					history.go(-2)();
				//-->
				</script>
				<?
			}
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



	} else

	if ($_POST['submit'] = "Alterar"){
		if ((isset($_POST['descricao'])) && (isset($_POST['codigo']))){
			$qry = "UPDATE `".DB_CCUSTO."`.".TB_CCUSTO." set ".CCUSTO_DESC."='".noHtml($_POST['descricao'])."', codccusto='".noHtml($_POST['codigo'])."' where codigo=".$_POST['cod']."";
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
		if (ok) var ok = validaForm('idCodigo','','Código',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);

		return ok;
	}
-->
</script>
<?
print "</html>";

?>