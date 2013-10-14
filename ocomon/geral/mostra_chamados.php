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

	$hoje = date("Y-m-d H:i:s");
	$destaque=false;

	$cor=TD_COLOR;
	$cor1=TD_COLOR;
	$percLimit = 20; //Tolerância em percentual
	$imgSlaR = 'sla1.png';
	$imgSlaS = 'checked.png';

	$dtS = new dateOpers; //objeto para Solução
	$dtR = new dateOpers; //objeto para Resposta

?>
<script type="text/javascript">
	function popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
		return false
	}

	function popup_alerta(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
	}
</script>
<?
	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$query = "";
	$query = "SELECT o.numero, o.data_abertura, o.data_atendimento, o.data_fechamento, o.sistema AS cod_area, o.contato, ".
				"o.telefone, o.descricao, o.status as status, u.login as operador, st.status AS stat, s.sistema, p.problema AS problema, ".
				"sl.slas_desc AS sla,  sl.slas_tempo AS tempo, l. * , pr. * , res.slas_tempo AS resposta ".
			"FROM localizacao AS l ".
				"LEFT JOIN prioridades AS pr ON pr.prior_cod = l.loc_prior ".
				"LEFT JOIN sla_solucao AS res ON res.slas_cod = pr.prior_sla, problemas AS p ".
				"LEFT JOIN sla_solucao AS sl ON p.prob_sla = sl.slas_cod, ocorrencias AS o, sistemas AS s, status AS st, ".
				"usuarios as u ".
			"WHERE s.sis_id = o.sistema AND p.prob_id = o.problema AND o.local = l.loc_id AND o.status = st.stat_id ".
				"and o.operador=u.user_id ";

	if (isset($_GET['numero']) )
		$query.=" and o.numero in (".$_GET['numero'].") ";

	$query.=" ORDER BY numero";

	//dump($query);
	$resultado = mysql_query($query);
	$linhas = mysql_numrows($resultado);
	if ($linhas==0)
	{
		$aviso = "Nenhuma ocorrência localizada!";
		print "<script>mensagem('".$aviso."'); redirect('consultar.php');</script>";
		exit;
	}

		print "<br><B>Listando ".$linhas." ocorrências.<BR>";

		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
		$valign = " valign=top ";
			print "<TR class='header'>";
				print "<TD class='line'>Número</TD>";
				print "<td class='line'>Problema</TD>";
				print "<td class='line'>Contato<BR>Operador</TD>";
				print "<td class='line'>Local</TD>";
				print "<td class='line'>Data";
					if (isset($_POST['tipo_data'])) print " de ".$_POST['tipo_data']."";
				print "</TD>";
				print "<td class='line'>Status</TD>";
				print "<td class='line'>RESP.</TD>";
				print "<td class='line'>SOLUC.</TD>";
			print "</TR>";

		$j=2;
		while ($row = mysql_fetch_array($resultado))
		{
			if ($j % 2)
			{
				$trClass = "lin_par";
			}
			else
			{
				$trClass = "lin_impar";
			}
			if ($row['status']==4 and $destaque) { $color =  "#F1FD4A";}
			if (($row['status'] == 1)) { $calcula = true;} else $calcula = false;
			$j++;

				$texto = trim( $row['descricao']);
				$limite = 250;
				if (strlen($texto)>$limite){
					$texto = substr($texto,0,($limite-3))."...";
				};

			if ($calcula) {

				$areaChamado = "";
				$areaChamado=testaArea($areaChamado,$row['cod_area'],$H_horarios);
				$dtR->setData1($row['data_abertura']);
				if ($row['data_atendimento'] =="") {
					$dtR->setData2($hoje) ;
				} else {
					$dtR->setData2($row['data_atendimento']) ;
				}
				$dtR->tempo_valido($dtR->data1,$dtR->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
				$diffR = $dtR->tValido;
				$diff2R = $dtR->diff["hValido"];
				$segundosR = $dtR->diff["sValido"]; //segundos válidos

				$diff = date_diff($row['data_abertura'],$hoje);
				$sep = explode ("dias",$diff);
				if ($sep[0]>20) { //Se o chamado estiver aberto a mais de 20 dias o tempo é mostrado em dias para não ficar muito pesado.
					$diff = $sep[0]." dias";
					$segundosS = ($sep[0]*86400);
				}  else {
					$dtS->setData1($row['data_abertura']);
						if ($row['data_fechamento'] =="") {
							$dtS->setData2($hoje) ;
						} else {
							$dtS->setData2($row['data_fechamento']) ;
					}
					$dtS->tempo_valido($dtS->data1,$dtS->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
					$diffS = $dtS->tValido;
					$diff2S = $dtS->diff["hValido"];
					$segundosS = $dtS->diff["sValido"]; //segundos válidos
				}

				if ($row['data_atendimento'] ==""){//Controle das bolinhas de SLA de Resposta
					if ($segundosR<=($row['resposta']*60)){
						$imgSlaR = 'sla1.png';
					} else
					if ($segundosR  <=(($row['resposta']*60) + (($row['resposta']*60) *$percLimit/100)) ){
						$imgSlaR = 'sla2.png';
					} else {
						$imgSlaR = 'sla3.png';
					}
				} else
					$imgSlaR = 'checked.png';

				$sla_tempo = $row['tempo'];
				if (($sla_tempo !="") && ($row['data_fechamento']=="")) { //Controle das bolinhas de SLA de solução
					if ($segundosS <= ($row['tempo']*60)){
						$imgSlaS = 'sla1.png';
					} else
					if ($segundosS  <=(($row['tempo']*60) + (($row['tempo']*60) *$percLimit/100)) ){
						$imgSlaS = 'sla2.png';
					} else
						$imgSlaS = 'sla3.png';
				} else
					$imgSlaS = 'checked.png';
			} else {
				$imgSlaR = 'checked.png';
				$imgSlaS = 'checked.png';
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

			//print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";
			print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
			print "<TD ".$valign."><a onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><font color='blue'>".$row['numero']."</font></a>".$imgSub."</TD>";
			print "<TD ".$valign.">".$row['problema']."</TD>";
			print "<TD ".$valign."><b>".$row['contato']."</b><br>".$row['operador']."</TD>";
			print "<TD ".$valign."><b>".$row['local']."</b><br>".$texto."</TD>";
			print "<TD ".$valign.">".$row['data_abertura']."</TD>";
			print "<TD ".$valign."><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\"><font color='blue'>".$row['stat']."</font></a></TD>";
			print "<TD ".$valign." align='center'><a onClick=\"javascript:popup('imgs/sla_popup.php?sla=r')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaR."'></a></TD>";
			print "<TD ".$valign." align='center'><a onClick=\"javascript:popup('imgs/sla_popup.php?sla=s')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaS."'></a></TD>";
			print "</TR>";
		} //while
		print "</TABLE>";

print "</body>";
print "</html>";
?>