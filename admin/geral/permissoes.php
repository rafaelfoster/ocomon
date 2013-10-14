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

	$query = "SELECT p.*, s.*, m.* FROM permissoes p, sistemas s, modulos m WHERE p.perm_area = s.sis_id and
					p.perm_modulo = m.modu_cod order by s.sistema";
        $resultado = mysql_query($query);

	if ((!isset($_GET['action'])) and (!isset($_POST['submit']))) {

		print "<TD align='right'><a href='permissoes.php?action=incluir'>Incluir permissão.</a></TD><BR>";
		if (mysql_numrows($resultado) == 0)
		{
			echo mensagem("Não existem permissões cadastradas no sistema.");
		}
        else
        {
                $linhas = mysql_numrows($resultado);
                print "<td class='line'>";
                print "Existe(m) <b>".$linhas."</b> permissão(oes) cadastrada(s) no sistema.<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "<TR class='header'><td class='line'><b>Área</b></TD><td class='line'><b>Módulo</b></TD><td class='line'><b>Excluir</b></TD>";
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
                        print "<td class='line'>".$row['sistema']."</TD>";
                        print "<td class='line'>".strtoupper($row['modu_nome'])."</TD>";
                        print "<td class='line'><a onClick=\"confirma('Tem Certeza que deseja excluir essa permissão?','permissoes.php?action=excluir&cod=".$row['perm_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
                        print "</TR>";
				}
                print "</TABLE>";
        }

	} else
	if ((isset($_GET['action'])  && $_GET['action']=="incluir") && (!isset($_POST['submit']))) {
		print "<B>Cadastro de permissões:<br>";
		print "<form name='incluir' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr><td class='line'>Área:</td><td class='line'><select class='select' name='area' id='idArea'>";
		print "<option value=-1>Área</option>";
			$qry = "select * from sistemas order by sistema";
			$exec = mysql_query($qry);
			while ($row_area = mysql_fetch_array($exec)){
				print "<option value=".$row_area['sis_id'].">".$row_area['sistema']."</option>";
			}
		print "</select>";
		print "</td></tr>";
		print "<tr><td class='line'>Módulo:</td><td class='line'><select class='select' name='modulo' id='idModulo'>";
		print "<option value=-1>Módulo</option>";
			$qry = "select * from modulos order by modu_nome";
			$exec = mysql_query($qry);
			while ($row_modulo = mysql_fetch_array($exec)){
				print "<option value=".$row_modulo['modu_cod'].">".$row_modulo['modu_nome']."</option>";
			}
		print "</select>";
		print "</td></tr>";
		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='Incluir'></td>";
		print "<td class='line'><input type='reset'  class='button' name='reset' value='Cancelar' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else
	if (isset($_GET['action']) && $_GET['action']=="excluir"){
		$qry = "DELETE FROM permissoes where perm_cod = ".$_GET['cod']."";
		$exec = mysql_query($qry) or die ('Erro na tentativa de deletar o registro!');
		?>
		<script language="javascript">
		<!--
			mensagem('Registro excluído com sucesso!');
			window.location.href='permissoes.php';
		//-->
		</script>
		<?
	} else
	if ($_POST['submit']=="Incluir") {
		if (($_POST['area']!=-1)&& ($_POST['modulo']!=-1)){
			$qry = "select * from permissoes where perm_area=".$_POST['area']." and perm_modulo=".$_POST['modulo']."";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				?>
				<script language="javascript">
				<!--
					mensagem('Essas permissões já existem!');
					history.go(-2)();
				//-->
				</script>
				<?
			} else {

				$qry = "INSERT INTO permissoes (perm_area,perm_modulo,perm_flag) values (".$_POST['area'].",".$_POST['modulo'].",1)";
				$exec = mysql_query($qry) or die ('Erro na inclusão da permissão!'.$qry);
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

	}



print "</body>";
?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idArea','COMBO','Área',1);
		if (ok) var ok = validaForm('idModulo','COMBO','Módulo',1);


		return ok;
	}
-->
</script>
<?
print "</html>";

?>