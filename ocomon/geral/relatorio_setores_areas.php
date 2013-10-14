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
	print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></link>";

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	if (!isset($_POST['ok'])) { //&& $_POST['ok'] != 'Pesquisar')
		print "<html>";
		print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";
		print "	<BR><BR>";
		print "	<B><center>:::CHAMADOS FECHADOS - SETORES X ÁREAS DE ATENDIMENTO:::</center></B><BR><BR>";
		print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1' onSubmit=\"return valida()\" >"; //onSubmit=\"return valida()\"
		print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">Área Responsável:</td>";


		print "					<td class='line'><Select name='area' class='select' size='1'>";
		print "							<OPTION value=-1 selected>-->Todos<--</OPTION>";
										$query="select * from sistemas where sis_status not in (0) order by sistema";
										$resultado=mysql_query($query);
										$linhas = mysql_num_rows($resultado);
										while($row=mysql_fetch_array($resultado))
										{
											print "<option value=".$row['sis_id']."";
											if ($row['sis_id']==$_SESSION['s_area']) print " selected";
											print ">".$row['sistema']."</option>";
										} // while
		print "		 				</Select>";
		print "					 </td>";
		print "				</tr>";



		print "					<td bgcolor=".TD_COLOR.">Data Inicial:</td>";
		print "					<td><INPUT type='text' name='d_ini' class='data' id='idD_ini' value='01-".date("m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_ini,'dd-mm-yyyy',this)\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">Data Final:</td>";
		print "					<td><INPUT type='text' name='d_fim' class='data' id='idD_fim' value='".date("d-m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_fim,'dd-mm-yyyy',this)\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
		print "				</tr>";

		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">Tipo de relatório:</td>";
		print "					<td class='line'><select name='saida' class='data'>";
		print "							<option value=-1 selected>Normal</option>";
		//	print "							<option value=1>Relatório 1 linha</option>";
		print "						</select>";
		print "					</td>";
		print "				</tr>";
		print "		</TABLE><br>";
		print "		<TABLE align='center'>";
		print "			<tr>";
		print "	            <td class='line'>";
		print "					<input type='submit' class='button' value='Pesquisar' name='ok' >";//onclick='ok=sim'
		print "	            </TD>";
		print "	            <td class='line'>";
		print "					<INPUT type='reset' class='button' value='Limpar campos' name='cancelar'>";
		print "				</TD>";
		print "			</tr>";
		print "	    </TABLE>";
		print " </form>";
		print "</BODY>";
    		print "</html>";
	}  else {//if $ok!=Pesquisar


		$hora_inicio = ' 00:00:00';
		$hora_fim = ' 23:59:59';

    		$query = "SELECT count(*)  AS quantidade, l.local AS setor, s.sistema AS area FROM ocorrencias AS o, localizacao AS l, sistemas AS s
						WHERE o.sistema = s.sis_id AND o.local = l.loc_id";

		if (isset($_POST['area']) && !empty($_POST['area']) && ($_POST['area'] != -1)) // variavel do select name
		{
		$query .= " and o.sistema = '".$_POST['area']."'";
		}

		if ((empty($_POST['d_ini'])) || (empty($_POST['d_fim'])))
		{
			print "<script>window.alert('O período deve ser informado!'); history.back();</script>";
		} else {
			$d_ini_nova = converte_dma_para_amd($_POST['d_ini']);
			$d_fim_nova = converte_dma_para_amd($_POST['d_fim']);

			$d_ini_completa = $d_ini_nova.$hora_inicio;
			$d_fim_completa = $d_fim_nova.$hora_fim;


			if($d_ini_completa <= $d_fim_completa) {
				print "<table class='centro' cellspacing='0' border='0' align='center'>";
				print "<tr><td colspan='2'><b>PERÍODO DE ".$_POST['d_ini']." a ".$_POST['d_fim']."</b></td></tr>";
				print "</table>";

		   		$query .= " and o.data_fechamento >= '".$d_ini_completa."' and o.data_fechamento <= '".$d_fim_completa."' and
					    o.data_atendimento is not null
					GROUP  BY l.local, s.sistema order by area, quantidade desc, setor";

				$resultado = mysql_query($query);
				$linhas = mysql_num_rows($resultado);


				if (($linhas==0) ) {
					print "<script>window.alert('Não há dados no período informado!'); history.back();</script>";
				} else {
			   		$campos=array();
					switch($_POST['saida'])
					{
						case -1:

                            			echo "<br><br>";
						$background = '#CDE5FF';
						print "<p class='titulo'>CHAMADOS FECHADOS - SETORES X ÁREAS DE ATENDIMENTO</p>";
                            			print "<table class='centro' cellspacing='0' border='1' align='center' >";

						print "<tr><td bgcolor=$background><B>QUANTIDADE</td>
							   <td bgcolor=$background ><B>SETOR</td>
		                                       <td bgcolor=$background ><B>ÁREA DE ATENDIMENTO</td>
						  </tr>";
		                             	$total = 0;
						while ($row = mysql_fetch_array($resultado)) {
						print "<tr>";
						print "<td class='line'>".$row['quantidade']."</td><td class='line'>".$row['setor']."</td><td class='line'>".$row['area']."</td>";
						print "</tr>";
						$total+=$row['quantidade'];
						}
						print "<tr><td colspan='2'><b>TOTAL</b></td><td class='line'><b>".$total."</b></td></tr>";

						break;

						case 1:
						$campos=array();
						$campos[]="numero";
						$campos[]="data_abertura";
						$campos[]="data_atendimento";
						$campos[]="data_fechamento";
						$campos[]="t_res_hora";
						$campos[]="t_sol_hora";
						$campos[]="t_res_valida_hor";
						$campos[]="t_sol_valida_hor";

						$cabs=array();
						$cabs[]="Número";
						$cabs[]="Abertura";
						$cabs[]="1ª Resposta";
						$cabs[]="Fechamento";
						$cabs[]="T Resposta Total";
						$cabs[]="T Solução Total";
						$cabs[]="T Resposta Válido";
						$cabs[]="T Solução Válido";

						$logo="logo_unilasalle.gif";
						$msg1="Centro de Informática";
						$msg2=date('d/m/Y H:m');
						$msg3= "Relatório de SLA's";

						gera_relatorio(1,$query,$campos,$cabs,$logo,$msg1, $msg2, $msg3);
						break;
					} // switch
				} //if($linhas==0)
			} else {  //if  $d_ini_completa <= $d_fim_completa
				print "<script>window.alert('A data final não pode ser menor do que a data inicial!'); history.back();</script>";
			}
		}//if ((empty($d_ini)) and (empty($d_fim)))
	?>
        <script type='text/javascript'>

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
        </script>
    <?



}//if $ok==Pesquisar
?>
