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

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	if (isset($_GET['popup'])) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);


	print "<BR><B>".TRANS('TTL_QUICK_SCH','Busca de Ocorrências').":</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()' >";
	print "<TABLE border='0'  align='left' width='40%' bgcolor='".BODY_COLOR."'>";
        print "<TR>";
                print "<TD  width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_NUMBER','Número')."".TRANS('NUMBER_PLURAL','(s)').":</TD>";
                print "<TD  width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='numero' id='idEtiqueta'></TD>";
	print "</TR>";
	print "<TR>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
                print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'>".
                		"<input type='submit'  class='button' value='".TRANS('BT_OK','',0)."' name='submit'>";
		print "</TD>";
                print "<TD align='center' width='80%' bgcolor='".BODY_COLOR."'>".
                		"<INPUT type='button'  class='button' value='".TRANS('BT_CANCEL','',0)."' name='cancela' onClick=\"redirect('abertura.php');\">".
                	"</TD>";
	print "</TR>";

	print "</table><br><br><br><br><br><br>";


	if (isset($_POST['submit']))
	{

		print "<div id='idLoad' class='loading' style='{display:none}'><img src='../../includes/imgs/loading.gif'></div>";

		//TESTA A EXPRESSÃO: IMPORTANTE CASO O JAVASCRIPT NÃO ESTEJA HABILITADO!
		if (!valida(TRANS('OCO_FIELD_TAG'), $_POST['numero'], 'ETIQUETA', 1, $ERRO)) {
			print "<script>mensagem('".$ERRO."'); history.back();</script>";
			exit;
		} else {
			$query = $QRY["ocorrencias_full_ini"]." where numero in (".$_POST['numero'].") order by numero";
			$resultado = mysql_query($query);
			$linhas = mysql_numrows($resultado);
			if ($linhas==0)
			{
				print "<script>window.alert('".TRANS('MSG_NOT_FOUND','Nenhum registro encontrado',0)."!'); redirect('alterar.php');</script>";
			}
			else
			{
				print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%' >";//bgcolor='".BODY_COLOR."'
				print "<TR class='header'><td class='line'>".TRANS('OCO_FIELD_NUMBER')."</TD>".
						"<td class='line'>".TRANS('OCO_FIELD_PROB')."</TD><td class='line'>".TRANS('OCO_FIELD_CONTACT')."</TD>".
						"<td class='line'>".TRANS('OCO_FIELD_LOCAL')."</TD><td class='line'>".TRANS('OCO_FIELD_OPEN','Abertura')."</TD>".
						"<td class='line'>".TRANS('OCO_FIELD_STATUS')."</TD></TR>";
				$j = 2;
				while ($row = mysql_fetch_array($resultado))
				{
					if ($j % 2)
					{
						$trClass= "lin_par";
					}
					else
					{
						$trClass = "lin_impar";
					}

					$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." or dep_filho=".$row['numero']."";
					$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
					$regSub = mysql_num_rows($execSubCall);
					if ($regSub > 0) {
						#É CHAMADO PAI?
						$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
						$execSubCall = mysql_query($sqlSubCall) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DOS SUBCHAMADOS!<br>'.$sqlSubCall);
						$regSub = mysql_num_rows($execSubCall);
						$comDeps = false;
						while ($rowSubPai = mysql_fetch_array($execSubCall)){
							$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
							$execStatus = mysql_query($sqlStatus) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE STATUS DOS CHAMADOS FILHOS<br>'.$sqlStatus);
							$regStatus = mysql_num_rows($execStatus);
							if ($regStatus > 0) {
								$comDeps = true;
							}
						}
						if ($comDeps) {
							$imgSub = "<img src='".ICONS_PATH."view_tree_red.png' width='16' height='16' title='Chamado com vínculos pendentes'>";
						} else
							$imgSub =  "<img src='".ICONS_PATH."view_tree_green.png' width='16' height='16' title='Chamado com vínculos mas sem pendências'>";
					} else
						$imgSub = "";

					//print "<TR class='".$trClass."'>";
					print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
						//$j++;
						print "<td class='line'><a onClick=\"exibeEscondeImg('idTr".$j."'); exibeEscondeImg('idDivDetails".$j."'); ajaxFunction('idDivDetails".$j."', 'mostra_consulta.php', 'idLoad', 'numero=idNumero".$j."','INDIV=idINDIV');\">".$row['numero']."</a>".$imgSub."</TD>";
						print "<input type='hidden' name='numeroAjax".$j."' id='idNumero".$j."' value='".$row['numero']."'>";
						print "<input type='hidden' name='INDIV' id='idINDIV' value='INDIV'>";

						//print "<td class='line'><a href='mostra_consulta.php?numero=".$row['numero']."'>".$row['numero']."</a>".$imgSub."</TD>";
						print "<td class='line'>".$row['problema']."</TD>";
						print "<td class='line'>".$row['contato']."</TD>";
						print "<td class='line'>".$row['setor']."</TD>";
						print "<td class='line'>".datab($row['data_abertura'])."</TD>";
						print "<td class='line'>".$row['chamado_status']."</TD>";
					print "</tr>";
					print "<tr><td colspan='6'  id='idTr".$j."' style='{display:none;}'><div id='idDivDetails".$j."' style='{display:none;}'></div></td></tr>";
					$j++;
				}

				//print "<tr><td colspan='6'><div id='idDivDetails".$j."' style='{display:none;}'></div></td></tr>";

			}
			print "</TABLE>";
			print "</FORM>";
		}
	}

	?>
	<script type="text/javascript">
	<!--
		function valida(){

			var ok = validaForm('idEtiqueta','ETIQUETA','Ocorrências',1);
			//var ok = validaForm('idEtiqueta','FONE','Ocorrências',1);

			return ok;
		}
	-->
	</script>
	<?php 
	print "</body>";
	print "</html>";
	?>