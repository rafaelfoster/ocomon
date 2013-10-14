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

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);


	print "<BR><B>".TRANS('TTL_CENTER_CUST').":</B><BR>";

	$query = "SELECT * from `".DB_CCUSTO."`.".TB_CCUSTO." order by ".CCUSTO_DESC."";
        //print $query; exit;
	$resultado = mysql_query($query);

	if ((!isset($_GET['action'])) and (!isset($_POST['submit']))) {
        print "<TD align='right'><a href='".$_SERVER['PHP_SELF']."?action=incluir'>".TRANS('TXT_INC_CENTER_CUST')."</a></TD><BR>";
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem(TRANS('MSG_NOT_CENTER_CUST_IN_SYSTEM'));
        }
        else
        {
                $cor=TD_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<td class='line'>";
                print "".TRANS('THERE_IS_ARE')." <b>".$linhas."</b> ".TRANS('TXT_CENTER_CUST_CAD_SYSTEM')."<br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "<TR class='header'><td class='line'>".TRANS('OCO_DESC')."</TD><td class='line'>".TRANS('COL_CODE')."</TD><td class='line'><b>".TRANS('OCO_FIELD_ALTER')."</b></TD><td class='line'><b>".TRANS('OCO_FIELD_EXCLUDE')."</b></TD>";
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
                        print "<td class='line'>".$row[CCUSTO_DESC]."</TD>";
						print "<td class='line'>".$row['codccusto']."</TD>";
                        print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['codigo']."')\"><img height='16' width='16'  src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></TD>";
                        print "<td class='line'><a onClick=\"confirma('".TRANS('MSG_DEL_CENTER_CUST')."','".$_SERVER['PHP_SELF']."?action=excluir&cod=".$row['codigo']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'</a></TD>";
                        print "</TR>";
				}
                print "</TABLE>";

        }

	} else
	if ((isset($_GET['action'])  && $_GET['action']=="incluir") && (!isset($_POST['submit']))) {

		print "<B>".TRANS('SUBTTL_CAD_CENTER_CUST').":<br>";
		print "<form name='incluir' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td class='line'>".TRANS('OCO_DESC')."</td><td class='line'><input type='text' class='text' name='descricao' id='idDesc'></td>";
		print "</tr>";

		print "<tr>";
		print "<td class='line'>".TRANS('COL_CODE')."</td><td class='line'><input type='text' class='text' name='codigo' id='idCodigo'></td>";
		print "</tr>";
		print "<tr><td class='line'><input type='submit' class='button' name='submit' value='".TRANS('BT_INCLUDE')."'></td>";
		print "<td class='line'><input type='reset' name='reset' class='button' value='".TRANS('BT_CANCEL')."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";

	} else

	if ((isset($_GET['action'])  && $_GET['action']=="alter") && (!isset($_POST['submit']))) {

		$qry = "SELECT * from `".DB_CCUSTO."`.".TB_CCUSTO." where codigo = ".$_GET['cod']."";

		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);

		print "<B>".TRANS('SUBTTL_ALTER_CENTER_CUST').":<br>";
		print "<form name='alter' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor='".TD_COLOR."'><b>".TRANS('OCO_DESC')."</b></td><td class='line'><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter[CCUSTO_DESC]."'></td>";
		print "</tr>";
		print "<tr>";
		print "<td bgcolor='".TD_COLOR."'><b>".TRANS('COL_CODE')."</b></td><td class='line'><input type='text' class='text' name='codigo' id='idCodigo' value='".$rowAlter['codccusto']."'>";

		print " <input type='hidden' name='cod' value='".$_GET['cod']."'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit' class='button' name='submit' value='".TRANS('BT_ALTER')."'></td>";
		print "<td class='line'><input type='reset' name='reset' class='button' value='".TRANS('BT_CANCEL')."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";

	} else

	if (isset($_GET['action']) && $_GET['action']=="excluir"){

		$qryBusca = "SELECT C.*, E.* from equipamentos E, `".DB_CCUSTO."`.".TB_CCUSTO." C where E.comp_ccusto = C.codigo and C.codigo = ".$_GET['cod']."";
		$execBusca = mysql_query($qryBusca) or die ( TRANS('MSG_ERR_CENTER_CUST') .$qryBusca);
		$achou = mysql_numrows($execBusca);
		if ($achou) {

			print "<script>mensagem('".TRANS('MSG_THIS_REG_DONT_DEL_EXIST')."". $achou. "".TRANS('MSG_TAG_ASSOC')."');
					window.location.href=".$_SERVER['PHP_SELF'].";
				</script>";

			exit;
		} else {

			$qry = "DELETE FROM `".DB_CCUSTO."`.".TB_CCUSTO." where codigo = ".$_GET['cod']."";
			$exec = mysql_query($qry) or die (TRANS('MSG_ERR_DEL_REG'));

			print "<script>mensagem('".TRANS('OK_DEL')."'); window.location.href='".$_SERVER['PHP_SELF']."'; </script>";

		}
	} else

	if ($_POST['submit']== TRANS('BT_INCLUDE')){
		if ((isset($_POST['descricao'])) && (isset($_POST['codigo']))){
			$qry = "select * from `".DB_CCUSTO."`.".TB_CCUSTO." where ".CCUSTO_DESC."='".$_POST['descricao']."' and codccusto = ".$_POST['codigo']."";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){

				print "<script>mensagem('".TRANS('MSG_CENTER_CUST_EXIST_SYSTEM')."'); history.go(-2)(); </script>";

			} else {
				$qry = "INSERT INTO `".DB_CCUSTO."`.".TB_CCUSTO." (".CCUSTO_DESC.",codccusto) values ('".noHtml($_POST['descricao'])."','".noHtml($_POST['codigo'])."')";
				$exec = mysql_query($qry) or die ( TRANS('MSG_ERR_INC_CENTER_CUST') .$qry);

				print "<script>mensagem('".TRANS('MSG_DATA_INCLUDE_OK')."'); history.go(-2)(); </script>";

			}
		} else {
			print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); history.go(-2)(); </script>";
		}



	} else

	if ($_POST['submit'] = TRANS('BT_ALTER')){
		if ((isset($_POST['descricao'])) && (isset($_POST['codigo']))){
			$qry = "UPDATE `".DB_CCUSTO."`.".TB_CCUSTO." set ".CCUSTO_DESC."='".noHtml($_POST['descricao'])."', codccusto='".noHtml($_POST['codigo'])."' where codigo=".$_POST['cod']."";
			$exec= mysql_query($qry) or die('Não foi possível alterar os dados do registro!'.$qry);

			print "<script>mensagem('".TRANS('MSG_DATA_ALTER_OK')."'); history.go(-2)(); </script>";

		} else {

			print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); history.go(-2)(); </script>";

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
<?php 
print "</html>";

?>