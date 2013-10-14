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
	print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></LINK>";

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";

 	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	print "<BR><B>".TRANS('TLT_CONS_SOLUT_PROB').":</B><BR>";

	print "<FORM method='POST' name='form1' action='mostra_resultado_solucoes.php' onSubmit='return valida()'>";
	print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'";

        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_DATE_BEGIN').":</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>".
                		"<INPUT type='text' class='data' name='data_inicial' id='idDataInicial'><a onclick=\"displayCalendar(document.forms[0].data_inicial,'dd-mm-yyyy',this)\">".
                		"<img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('HNT_SEL_DATE')."'></a></TD>";

                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_DATE_FINISH').":</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>".
                		"<INPUT type='text' class='data' name='data_final' id='idDataFinal'><a onclick=\"displayCalendar(document.forms[0].data_final,'dd-mm-yyyy',this)\">".
                		"<img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('HNT_SEL_DATE')."'></a></TD>";
        print "</TR>";

        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_PROB').":</TD>";
                print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'>".
                		"<TEXTAREA class='textarea' name='problema' id='idDescricao'></textarea>".
                	"</TD>";
	print "</TR>";

        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('MNS_OPERADOR').":</TD>";
                print "<TD width='30%' colspan='3' align='left' bgcolor='".BODY_COLOR."'>";
                	print "<SELECT class='select' name='operador' size=1>";
                		print "<option value=-1 selected>".TRANS('OCO_SEL_OPERATOR')."</option>";
				$query = "SELECT * from usuarios order by nome";
				$resultado = mysql_query($query);
				while ($row = mysql_fetch_array($resultado))
				{
					print "<option value='".$row['user_id']."'>".$row['nome']."</option>";
				}
				print "</SELECT>";

                print "</TD>";
        print "</TR>";

        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_RETURN').":</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>".
                		"<input type='checkbox' name='anyword'>".TRANS('OPT_ONE_WORD')."".
                	"</TD>";

                print "<TD width='20%' colspan='2' align='left' bgcolor='".BODY_COLOR."'>".
                		"<input type='checkbox' name='onlyImgs'>".TRANS('OPT_ONLY_CALL_ATTACH')."".
                	"</TD>";


        print "</TR>";


        print "<TR>";
                print "<BR>";
                print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
                		"<input type='submit'  class='button' value='".TRANS('BT_OK')."' name='submit'>";
		print "</TD>";
                print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
                		"<INPUT type='button'  class='button' value='".TRANS('BT_CANCEL')."' name='desloca' onClick=\"redirect('abertura.php');\">".
                	"</TD>";
        print "</TR>";

print "</TABLE>";
print "</FORM>";

	?>
	<script language="JavaScript">

		function valida(){
			var ok = validaForm('idDataInicial','DATA-','Data inicial',0);
			if (ok) var ok = validaForm('idDataFinal','DATA-','Data final',0);
			if (ok) var ok = validaForm('idDescricao','','Problema',1);

			return ok;
		}
	//-->
	</script>
	<?php 

print "</BODY>";
print "</HTML>";
?>
