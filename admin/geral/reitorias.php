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

	print "<BR><B>".TRANS('COL_MAJOR')."s:</B><BR>";

	$query = "SELECT * from reitorias order by reit_nome";
        $resultado = mysql_query($query);

	if ((!isset($_GET['action'])) and !isset($_POST['submit'])) {

        print "<TD align='right'><a href='".$_SERVER['PHP_SELF']."?action=incluir'>".TRANS('TXT_INCLUDE_MAJOR').".</a></TD><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem(TRANS('MSG_NOT_MAJOR_IN_SYSTEM'));
        }
        else
        {
                $cor=TD_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<td class='line'>";
                print "".TRANS('THERE_IS_ARE')." <b>".$linhas."</b> ".TRANS('TXT_MAJOR_IN_SYSTEM')."<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "<TR class='header'><td class='line'>".TRANS('COL_MAJOR')."</TD><td class='line'><b>".TRANS('COL_EDIT')."</b></TD><td class='line'><b>".TRANS('COL_DEL')."</b></TD>";
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

                        print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
			print "<td class='line'>".$row['reit_nome']."</TD>";
                        print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['reit_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></TD>";
                        print "<td class='line'><a onClick=\"confirma('".TRANS('MSG_DEL_MAJOR_SYSTEM')."','".$_SERVER['PHP_SELF']."?action=excluir&cod=".$row['reit_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
                        print "</TR>";
				}
                print "</TABLE>";
        }

	} else
	if ((isset($_GET['action'])  && $_GET['action']=="incluir") && (!isset($_POST['submit']))) {

		print "<B>".TRANS('TTL_CAD_MAJOR').":<br><a href='".$_SERVER['PHP_SELF']."'>".TRANS('TXT_LIST_GENERAL')."</a>  <br>";
		print "<form name='incluir' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td width='10%'bgcolor=".TD_COLOR.">".TRANS('COL_MAJOR')."</td><td class='line'><input type='text' class='text' name='descricao' id='idDesc'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='".TRANS('BT_INCLUDE')."'></td>";

		print "<td class='line'><input type='reset'  class='button' name='reset' value='".TRANS('BT_CANCEL')."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ((isset($_GET['action'])  && $_GET['action']=="alter") && (!isset($_POST['submit']))) {
		$qry = "SELECT * from reitorias where reit_cod = ".$_GET['cod']."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);

		print "<B>".TRANS('TTL_ALTER_NAME_MAJOR').":<br>";
		print "<form name='alter' method='post'  action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR."><b>".TRANS('COL_MAJOR')."</b></td><td class='line'><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter['reit_nome']."'>";

		print " <input type='hidden' name='cod' value='".$_GET['cod']."'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='".TRANS('BT_ALTER')."r'></td>";
		print "<td class='line'><input type='reset' name='reset'  class='button' value='".TRANS('BT_CANCEL')."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if (isset($_GET['action']) && $_GET['action']=="excluir"){

		$qry = "select * from localizacao where loc_reitoria = ".$_GET['cod']."";
		$exec = mysql_query($qry);
		$linhas = mysql_numrows($exec);
		if ($linhas!=0) {
			print "<script>mensagem('".TRANS('MSG_NOT_DEL_MAJOR_DEP_ASSOC')."');
					redirect('".$_SERVER['PHP_SELF']."')</script>";
		} else {



			$qry = "DELETE FROM reitorias where reit_cod = ".$_GET['cod']."";
			$exec = mysql_query($qry) or die (TRANS('MSG_ERR_DEL_REG'));

			print "<script>mensagem('".TRANS('OK_DEL')."'); window.opener.location.reload(); window.location.href='".$_SERVER['PHP_SELF']."';</script>";

		}
	} else

	if ($_POST['submit']== TRANS('BT_INCLUDE')){
		if (!empty($_POST['descricao'])){
			$qry = "select * from reitorias where reit_nome = '".$_POST['descricao']."'";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){

				print "<script>mensagem('".TRANS('MSG_MAJOR_EXIST_IN_SYSTEM')."'); history.go(-2)();</script>";

			} else {

				//$data = str_replace("-","/",$data);
				//$data = converte_dma_para_amd($data);

				$qry = "INSERT INTO reitorias (reit_nome) values ('".noHtml($_POST['descricao'])."')";
				$exec = mysql_query($qry) or die (TRANS('MSG_ERR_INCLUDE_REG'). $qry);
				print "<script>mensagem('".TRANS('MSG_DATA_INCLUDE_OK')."');window.opener.location.reload(); redirect('".$_SERVER['PHP_SELF']."');</script>";
				}
		} else {
				print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."!'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}

	} else

	if ($_POST['submit'] = TRANS('BT_ALTER')){
		if ((!empty($_POST['descricao']))){

				//$data = str_replace("-","/",$data);
				//$data = converte_dma_para_amd($data);
			$qry = "UPDATE reitorias set reit_nome='".noHtml($_POST['descricao'])."' where reit_cod=".$_POST['cod']."";
			$exec= mysql_query($qry) or die(TRANS('MSG_NOT_ALTER_REG'). $qry);

				print "<script>mensagem('".TRANS('MSG_DATA_INCLUDE_OK')."'); window.opener.location.reload(); history.go(-2)();</script>";

		} else {

			print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."s!'); history.go(-2)(); </script>";

		}
	}




print "</body>";
?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idDesc','','Descrição',1);
		//if (ok) var ok = validaForm('idData','DATA-','Data',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);

		return ok;
	}
-->
</script>

<?

print "</html>";

?>