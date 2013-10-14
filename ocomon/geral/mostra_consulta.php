<?session_start();
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
*/

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");


	print "<HTML><BODY bgcolor='".BODY_COLOR."'>";
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);


	if (isset($_POST['numero'])) {
		$COD = $_POST['numero'];
	} else
	if (isset($_GET['numero'])){
		$COD = $_GET['numero'];
	} else {
		print "ERRO: ESSE SCRIPT NÃO PODE SER EXECUTADO DE FORMA INDEPENDENTE!";
		exit;
	}


	$query = $QRY["ocorrencias_full_ini"]." where numero in (".$COD.") order by numero";
	$resultado = mysql_query($query);
	$row = mysql_fetch_array($resultado);

        $query2 = "select a.*, u.* from assentamentos a, usuarios u where a.responsavel=u.user_id and a.ocorrencia=".$COD."";
        $resultado2 = mysql_query($query2);
        $linhas=mysql_numrows($resultado2);

	if ($_SESSION['s_nivel'] == 1) $linkEdita = "<td align='right' width='10%' ><a href='altera_dados_ocorrencia.php?numero=".$COD."'>Editar como admin</a>&nbsp;|&nbsp;</td>"; else //&nbsp;|&nbsp;
		$linkEdita = "";

	$sqlPai = "select * from ocodeps where dep_filho = ".$COD." ";
	$execpai = mysql_query($sqlPai) or die ('NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DE DEPENDÊNCIAS DO CHAMADO!');
	$rowPai = mysql_fetch_array($execpai);
	if ($rowPai['dep_pai']!=""){
		$msgPai = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='Chamado com vínculos'><u><a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowPai['dep_pai']."')\">Esta ocorrência é um sub-chamado da ocorrência ".$rowPai['dep_pai']."</a></u>";
	} else
		$msgPai = "";

	?>
	<script type='text/javascript'>

		function popup_alerta(pagina)	{ //Exibe uma janela popUP
			x = window.open(pagina,'_blank','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
			x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
			return false
		}

		function popup_alerta_mini(pagina)	{ //Exibe uma janela popUP
			x=window.open(pagina,'_blank','dependent=yes,width=400,height=250,scrollbars=yes,statusbar=no,resizable=yes');
			x.moveTo(100,100);
			x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
			return false
		}

		function popup(pagina)	{ //Exibe uma janela popUP
			x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
			x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
			return false
		}

	</script>
	<?


	print "<BR><B>Consulta de Ocorrências</B><BR>".$msgPai."</br>";

	if (isset($_GET['justOpened']) && $_GET['justOpened']==true) {
		$msg = "Ocorrência incluída com sucesso! ";
		//$msg.="<br><a align='center' onClick=\"exibeEscondeImg('idAlerta');\"><img src='".ICONS_PATH."/stop.png' width='16px' height='16px'>&nbsp;Fechar</a>";
		print "</table>";//#EFEFE7
		print "<div class='alerta' id='idAlerta'><table class='divAlerta'><tr><td colspan='2'><a align='center' onClick=\"exibeEscondeImg('idAlerta');\" title='Ocultar'><img src='".ICONS_PATH."/ok.png' width='16px' height='16px'><b>".$msg."</b></a></td></tr></table></div>";
		//exit;
	}


	if ($row['status_cod']!=4 && $_SESSION['s_nivel'] < 3) {
		print "<TD align='right' width='10%' ><a href='encerramento.php?numero=".$row['numero']."'>Encerrar ocorrência</a>&nbsp;|&nbsp;</TD>"; //
	}

	print "<TD align='right' width='10%' ><a href='mostra_relatorio_individual.php?numero=".$row['numero']."' target='_blank'>Imprimir ocorrência</a>&nbsp;|&nbsp;</TD>"; //&nbsp;|&nbsp;
	if ($_SESSION['s_nivel'] < 3)
		print "<TD align='right' width='10%' ><a href='encaminhar.php?numero=".$row['numero']."'>Editar ocorrência</a>&nbsp;|&nbsp;</TD>".$linkEdita.""; //&nbsp;|&nbsp;

	if (($row['status_cod']!=2) && ($row['status_cod']!=4) && ($_SESSION['s_nivel'] < 3)) {
		print "<TD align='right' width='10%' ><a href='atender.php?numero=".$COD."'>Atender</a>&nbsp;|&nbsp;</TD>"; //&nbsp;|&nbsp;
	}

	print "<TD align='right' width='10%' ><a onClick=\"javascript:popup('mostra_sla_definido.php?popup=true&numero=".$row['numero']."')\">SLA</a>&nbsp;|&nbsp;</TD>";//&nbsp;|&nbsp;

	if ($row['status_cod']!=4 && $_SESSION['s_nivel'] < 3) {
		print "<TD align='right' width='10%' bgcolor='".BODY_COLOR."' ><a onClick=\"javascript:popup_alerta('incluir.php?popup=true".
				"&pai=".$row['numero']."&invTag=".$row['etiqueta']."&invInst=".$row['unidade_cod']."&invLoc=".$row['setor_cod']."".
				"&telefone=".$row['telefone']."')\">Abrir sub-chamado</a>&nbsp;|&nbsp;</TD>";//&nbsp;|&nbsp;
	}

	print "<TD align='right' width='10%' bgcolor='".BODY_COLOR."'  ><a onClick=\"javascript:popup('tempo_doc.php?popup=true".
			"&cod=".$row['numero']."')\">Tempo de documentação</a>&nbsp;|&nbsp;</TD>";//&nbsp;|&nbsp;

	//print "</table>";
	//print "</tr>";

	print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
        	print "<TR>";
                	print "<TD width='20%' align='left' bgcolor='". TD_COLOR."'>Número:</TD>";
                	print "<TD width='30%' align='left'><input class='disable' value='".$row['numero']."' disabled></TD>";
                	print "<TD width='20%' align='left' bgcolor='". TD_COLOR."'>Área responsável:</TD>";
                	print "<TD colspan='3' width='30%' align='left'  ><input class='disable' value='".$row['area']."' disabled></TD>";
		print "</TR>";
        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Problema:</TD>";
                print "<TD width='30%' align='left' ><input class='disable' value='".$row['problema']."' disabled></TD>";
		print "<TD width='20%' align='left' bgcolor='". TD_COLOR."'>Aberto Por:</TD>";
                print "<TD colspan='3' width='30%' align='left' ><input class='disable' value='".$row['aberto_por']."' disabled></TD>";
        print "</TR>";
        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Descrição:</TD>";

		if (isset($_GET['destaca'])){
			print "<TD  colspan='2' valign='top' align='left'  class='wide'>".destaca($_GET['destaca'], nl2br($row['descricao']))."</TD>";
		} else
                	//print "<TD  colspan='2' valign='top' align='left'  class='textareaDisable'>".nl2br($row['descricao'])."</TD>";//textareaDisable
                	print "<TD  colspan='2' valign='top' align='left' class='wide'>".nl2br($row['descricao'])."</TD>";//textareaDisable
                print "<TD colspan='3' width='40%' align='left' >&nbsp;</TD>";
        print "</TR>";


	print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Unidade:</TD>";
                print "<TD width='30%' align='left'><input class='disable' value='".$row['unidade']."' disabled></TD>";

                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Etiqueta do equipamento:</TD>";
		print "<TD  width='30%' align='left'>".
				"<a onClick=\"popup_alerta('../../invmon/geral/mostra_consulta_inv.php?".
				"comp_inst=".$row['unidade_cod']."&comp_inv=".$row['etiqueta']."&popup=true')\">".
				"<font color='blue'><u>".$row['etiqueta']."</u></font></a>".
			"</TD>";
		print "<TD colspan='2' align='left'>&nbsp;</td>";
		print "</TR>";
		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Contato:</TD>";
                print "<TD width='30%' align='left' ><input class='disable' value='".$row['contato']."' disabled></TD>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Ramal:</TD>";
                print "<TD colspan='3' width='30%' align='left' ><input class='disable' value='".$row['telefone']."' disabled></TD>";
	print "</TR>";
        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Local:</TD>";
                print "<TD width='30%' align='left'><input class='disable' value='".$row['setor']."' disabled></TD>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Último operador:</TD>";
                print "<TD colspan='3' width='30%' align='left' ><input class='disable' value='".$row['nome']."' disabled></TD>";
	print "</TR>";

        if ($row['status_cod'] == 4)
	{
		print "<TR>";
			print "<TD  align='left' bgcolor='".TD_COLOR."'>Data de abertura:</TD>";
			print "<TD  align='left' ><input class='disable' value='".formatDate($row['data_abertura'])."' disabled></TD>";
			print "<TD  align='left' bgcolor='".TD_COLOR."'>Data de encerramento:</TD>";
			print "<TD  width='30%' colspan='3' align='left' ><input class='disable' value='".formatDate($row['data_fechamento'])."' disabled></TD>";
		print "</tr>";
		print "<tr>";
			print "<TD  align='left' bgcolor='".TD_COLOR."'>Status:</TD>";
			print "<TD colspan='5' align='left'  >".
					"<font color='blue'><u><a onClick=\"popup_alerta_mini('mostra_hist_status.php?numero=".$COD."&popup=true')\">".
					"".$row['chamado_status']."</u></a></font>".
				"</TD>";
				//print "<TD colspan='2' align='left'>".

		print "</TR>";
	}
        else
	{
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data de abertura:</TD>";
			print "<TD width='30%' align='left' ><input class='disable' value='".formatDate($row['data_abertura'])."' disabled></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Status:</TD>";
			print "<TD width='30%' align='left'  >".
					"<b><font color='blue'><u><a onClick=\"popup_alerta_mini('mostra_hist_status.php?numero=".$COD."&popup=true')\">".
					"".$row['chamado_status']."</u></a></font></b>".
				"</TD>";
			print "<TD colspan='2' align='left'>&nbsp;</td>";
		print "</TR>";
	}

	if ($linhas != 0) { //ASSENTAMENTOS DO CHAMADO
		print "<tr><td colspan='6'><IMG ID='imgAssentamento2' SRC='../../includes/icons/close.png' width='9' height='9' ".
				"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('Assentamento2')\">&nbsp;<b>Existe(m) <font color='red'>".$linhas."</font>".
				" assentamento(s) para essa ocorrência.</b></td></tr>";

		//style='{padding-left:5px;}'
		print "<tr><td colspan='6' ><div id='Assentamento2' >"; //style='{display:none}'
		print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
		$i = 0;
		while ($rowAssentamento2 = mysql_fetch_array($resultado2)){
			$printCont = $i+1;
			print "<TR>";
			print "<TD width='20%' ' bgcolor='".TD_COLOR."' valign='top'>".
					"Assentamento ".$printCont." de ".$linhas." por ".$rowAssentamento2['nome']." em ".
					//"".datab($rowAssentamento2['data'])."".
					"".formatDate($rowAssentamento2['data'])."".
				"</TD>";

			if (isset($_GET['destaca'])){
				print "<TD colspan='4' align='left'  class='textareaDisable' valign='top'>".destaca($_GET['destaca'], nl2br($rowAssentamento2['assentamento']))."</TD>";
			} else
				print "<TD colspan='4' align='left'  class='textareaDisable' valign='top'>".nl2br($rowAssentamento2['assentamento'])."</TD>";
			print "<TD width='20%'  valign='top'>&nbsp;</td>";
			print "</TR>";
			$i++;
		}
		print "</table></div></td></tr>";
	}


	$qryTela = "select * from imagens where img_oco = ".$row['numero']."";
	$execTela = mysql_query($qryTela) or die ("NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DA TABELA DE IMAGENS!");
	//$rowTela = mysql_fetch_array($execTela);
	$isTela = mysql_num_rows($execTela);
	$cont = 0;
	while ($rowTela = mysql_fetch_array($execTela)) {
	//if ($isTela !=0) {
		$cont++;
		print "<tr>";
		$size = round($rowTela['img_size']/1024,1);
		print "<TD  bgcolor='".TD_COLOR."' >Anexo ".$cont."&nbsp;[".$rowTela['img_tipo']."]<br>(".$size."k):</td>";

		if(isImage($rowTela["img_tipo"])) {
			$viewImage = "&nbsp;<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?".
				"file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\" ".
				"title='View the file'><img src='../../includes/icons/kghostview.png' width='16px' height='16px' border='0'></a>";
		} else {
			$viewImage = "";
		}
		print "<td colspan='5' ><a onClick=\"redirect('../../includes/functions/download.php?".
				"file=".$row['numero']."&cod=".$rowTela['img_cod']."')\" title='Download the file'>".
				"<img src='../../includes/icons/attach2.png' width='16px' height='16px' border='0'>".
				"".$rowTela['img_nome']."</a>".$viewImage."</TD>";
		print "</tr>";
	}


        $qrySubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
        $execSubCall = mysql_query($qrySubCall) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE SUB-CHAMADOS!<br>'.$qrySubCall);
	$existeSub = mysql_num_rows($execSubCall);
	if ($existeSub>0) {
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
			$imgSub = ICONS_PATH."view_tree_red.png";
		} else {
			$imgSub = ICONS_PATH."view_tree_green.png";
		}

		print "<tr><td  colspan='6'><IMG ID='imgSubCalls' SRC='../../includes/icons/open.png' width='9' height='9' ".
				"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('SubCalls')\">&nbsp;<b><img src='".$imgSub."' width='16' height='16' title='Chamado com vínculos'>Sub-Chamados / Dependências:</b></td></tr>";//<span style=\"background:yellow\">

		print "<tr><td colspan='6'></td></tr>";
		print "<tr><td colspan='6'><div id='SubCalls' style='{display:none}'>"; //style='{display:none}'	//style='{padding-left:5px;}'

		print "<TABLE border='0' style='{padding-left:10px;}' cellpadding='5' cellspacing='0' align='left' width='100%'>";
		print "<tr class='header'><td class='line'>Número<br>Área</td><td class='line'>Problema</td><td class='line'>Contato<br>ramal</td><td class='line'>Local<br>Descricão</td><td class='line'>Último operador<br>Status</td></tr>";
		$j=2;
		$execSubCall = mysql_query($qrySubCall);
		while ($rowSub = mysql_fetch_array($execSubCall)) {
			if ($j % 2) {
					$trClass = "lin_par";
			}
			else {
					$trClass = "lin_impar";
			}
			$j++;

			$qryDetail = $QRY["ocorrencias_full_ini"]." WHERE  o.numero = ".$rowSub['dep_filho']." ";
			$execDetail = mysql_query($qryDetail) or die ('ERRO NA TENTATIVA DE RECUPERAR OS DADOS DAS OCORRÊNCIAS! '.$qryDetail);
			$rowDetail = mysql_fetch_array($execDetail);

			//print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";
			print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";

			print "<td class='line'><a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowDetail['numero']."')\"><b>".$rowDetail['numero']."</b></a><br>".$rowDetail['area']."</TD>";
			print "<td class='line'>".$rowDetail['problema']."</TD>";
			print "<td class='line'><b>".$rowDetail['contato']."</b><br>".$rowDetail['telefone']."</TD>";
			$texto = trim($rowDetail['descricao']);
			if (strlen($texto)>200){
				$texto = substr($texto,0,195)." ..... ";
			};
			print "<td class='line'><b>".$rowDetail['setor']."</b><br>".$texto."</TD>";
			print "<td class='line'><b>".$rowDetail['nome']."</b><br>".$rowDetail['chamado_status']."</TD>";
			print "</tr>";
		}
		print "</tr>";
		print "</table>";
		print "</div>";
	}

print "</TABLE>";


?>
<script type="text/javascript">
	desabilitaLinks(<?print $_SESSION['s_ocomon'];?>);

	function invertView(id) {
		var element = document.getElementById(id);
		var elementImg = document.getElementById('img'+id);
		var address = '../../includes/icons/';

		if (element.style.display=='none'){
			element.style.display='';
			elementImg.src = address+'close.png';
		} else {
			element.style.display='none';
			elementImg.src = address+'open.png';
		}
	}
</script>
<?
print "</body>";
print "</html>";
?>