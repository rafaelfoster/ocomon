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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	if (!isset($_POST['ok'])) { //&& $_POST['ok'] != 'Pesquisar')
		print "<html>";
		print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";
		print "	<BR><BR>";
		print "	<B><center>::: ".TRANS('TTL_ALTER_HW_EQUIP')." :::</center></B><BR><BR>";
		print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1' onSubmit=\"return valida();\">";
		print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >";

		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_BEGIN').":</td>";
		//print "					<td ><INPUT type='text' name='d_ini' class='data' id='idD_ini'><a href=\"javascript:cal1.popup();\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
		print "					<td><INPUT type='text' name='d_ini' class='data' id='idD_ini' value='01-".date("m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_ini,'dd-mm-yyyy',this)\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('HNT_SEL_DATE')."'></a></td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_FINISH').":</td>";
		print "					<td><INPUT type='text' name='d_fim' class='data' id='idD_fim' value='".date("d-m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_fim,'dd-mm-yyyy',this)\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('HNT_SEL_DATE')."'></a></td>";
		print "				</tr>";
		
						print "<tr><td colspan='2'><input type='checkbox' name='checkprint'>".TRANS('OPT_NEW_WINDOW')."</td></tr>";
		print "		</TABLE><br>";
		print "		<TABLE align='center'>";
		print "			<tr>";
		print "	            <td class='line'>";
		print "					<input type='submit' class='button' value='".TRANS('BT_SEARCH')."' name='ok' >";//onclick='ok=sim'
		print "	            </TD>";
		print "	            <td class='line'>";
		print "					<INPUT type='reset'  class='button' value='".TRANS('BT_CLEAR')."' name='cancelar'>";
		print "				</TD>";
		print "			</tr>";
		print "	    </TABLE>";
		print " </form>";
		?>
				<script language="JavaScript">

					function valida(){
						var ok = validaForm('idD_ini','DATA-','<?php print TRANS('OCO_FIELD_DATE_BEGIN');?>',0);
						if (ok) var ok = validaForm('idD_fim','DATA-','<?php print TRANS('OCO_FIELD_DATE_FINISH');?>',0);
						
						if (ok) newTarget();
						
						return ok;
					}
				
				
					function newTarget()
					{
						if (document.form1.checkprint.checked) {
							document.form1.target = "_blank";
							document.form1.submit();
						} else {
							document.form1.target = "";
							document.form1.submit();
						}
					}
				
				</script>
		<?php 
	//if $ok!=Pesquisar
	} else { //if $ok==Pesquisar

		$hora_inicio = ' 00:00:00';
		$hora_fim = ' 23:59:59';

		$query = "SELECT count(*) as total, h.*, t.*, m.*, i.*, u.*
					FROM hw_alter as h, itens as t, modelos_itens as m, instituicao as i, usuarios as u
					WHERE h.hwa_inst = i.inst_cod and hwa_item = m.mdit_cod and hwa_user = u.user_id and t.item_cod = m.mdit_tipo ";


		if ((!isset($_POST['d_ini'])) || ((!isset($_POST['d_fim'])))) {

			print "<script>window.alert('".TRANS('MSG_ALERT_PERIOD')."'); history.back();</script>";
		} else {
			$d_ini = str_replace("-","/",$_POST['d_ini']);
			$d_fim = str_replace("-","/",$_POST['d_fim']);
			$d_ini_nova = converte_dma_para_amd($d_ini);
			$d_fim_nova = converte_dma_para_amd($d_fim);

			$d_ini_completa = $d_ini_nova.$hora_inicio;
			$d_fim_completa = $d_fim_nova.$hora_fim;


			if($d_ini_completa <= $d_fim_completa) {

				print "<table class='centro' cellspacing='0' border='0' align='center' >";
					print "<tr><td colspan='2'><b>".TRANS('TTL_PERIOD_FROM')." ".$d_ini." a ".$d_fim."</b></td></tr>";
				print "</table>";

				$query.= " and hwa_data between '".$d_ini_completa."' and '".$d_fim_completa."' ";

				$query.="GROUP BY h.hwa_inst, h.hwa_inv
						ORDER BY h.hwa_data ";

				//print $query; exit;
				$resultado = mysql_query($query) or die(TRANS('MSG_ERR_RESCUE_DATA'));
				$linhas = mysql_num_rows($resultado);

				if ($linhas==0) {
					$aviso = TRANS('MSG_NO_DATA_INFORM_PERIOD');
					echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
				} else { //if($linhas==0)
					echo "<br><br>";
					$background = '#CDE5FF';

					print "<table class='centro' cellspacing='0' border='1' align='center'>";

						print "<tr><td bgcolor='".$background."'><B>".TRANS('COL_AMOUNT')."</td>
								<td bgcolor='".$background."' ><B>".TRANS('COL_EQUIP')."</td>
							</tr>";
					$total = 0;
					while ($row = mysql_fetch_array($resultado)) {

						print "<tr>";
						print "<td class='line'><a class='botao' onClick= \"javascript: popup_alerta('hw_historico.php?inv=".$row['hwa_inv']."&inst=".$row['hwa_inst']."')\"><font color='#5E515B'>".$row['total']."</font></td>";
						print "<td class='line'><a onClick=\"javascript: popup_alerta('mostra_consulta_inv.php?comp_inv=".$row['hwa_inv']."&comp_inst=".$row['hwa_inst']."')\"><font color='#5E515B'>".$row['inst_nome']."&nbsp;".$row['hwa_inv']."</font></a></td>";

						print "</tr>";
						$total+=$row['total'];
					}

					print "<tr><td class='line'><b>".TRANS('TOTAL')."</b></td><td class='line'><b>".$total."</b></td></tr>";

				} //if($linhas==0)
				//if  $d_ini_completa <= $d_fim_completa
			} else {

				$aviso = TRANS('MSG_DATE_FINISH_>_DATE_BEGIN');
				print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
			}
		}//if ((empty($d_ini)) and (empty($d_fim)))
		?>
			<script type='text/javascript'>
			<!--
				function popup(pagina)	{ //Exibe uma janela popUP
					x = window.open(pagina,'popup','width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
					//x.moveTo(100,100);
					x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
					return false
				}

				function popup_alerta(pagina)	{ //Exibe uma janela popUP
					x = window.open(pagina,'_blank','width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
					//x.moveTo(100,100);
					x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
					return false
				}


			-->
			</script>
		<?php 



	}//if $ok==Pesquisar
	print "</BODY>";
	print "</html>";
?>