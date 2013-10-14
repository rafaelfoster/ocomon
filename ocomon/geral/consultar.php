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
	print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></LINK>";
	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];


	print "<HTML>";
	print "<head><script language='JavaScript' src=\"../../includes/javascript/calendar.js\"></script></head>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);

	print "<br><B>Consulta de Ocorrências:</B><BR>";

	print "<FORM method='POST'  name='form1' action='mostra_resultado_consulta.php' onSubmit='return valida()'>";
	print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Número inicial:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='numero_inicial' id='idNumeroInicial'></TD>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Número final:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='numero_final' id='idNumeroFinal'></TD>";
        print "</TR>";
        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Problema:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
               		print "<SELECT class='select' name='problema' size=1>";
                		print "<option value=-1 selected>-  Selecione um problema -</option>";
                		$query = "SELECT * from problemas order by problema";
                		$resultado = mysql_query($query);
				while ($row = mysql_fetch_array($resultado))
				{
					print "<option value='".$row['prob_id']."'>".$row['problema']."</option>";
				}
                	print "</select>";
                print "</td>";


                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Área responsável:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";

                	print "<SELECT class='select' name='sistema' size=1>";
				print "<option value=-1 selected>-  ÁREA RESPONSÁVEL -</option>";
				$query = "SELECT * from sistemas order by sistema";
				$resultado = mysql_query($query);
				while ($row = mysql_fetch_array($resultado))
				{
					print "<option value='".$row['sis_id']."'>".$row['sistema']."</option>";
				}
                	print "</select>";
                print "</td>";
        print "</TR>";

        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Descrição:</TD>";
                print "<TD width='30%' colspan='3' align='left' bgcolor='".BODY_COLOR."'>";
                	print "<TEXTAREA class='textarea' name='descricao' id='idDescricao'></textarea>";
                print "</TD>";
        print "</TR>";

        print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Unidade:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='instituicao' size='1'>";
				print "<option value=-1 selected>Selecione a Unidade</option>";
				$query2 = "SELECT * from instituicao order by inst_cod";
				$resultado2 = mysql_query($query2);
				while ($row = mysql_fetch_array($resultado2))
				{
					print "<option value='".$row['inst_cod']."'>".$row['inst_nome']."</option>";
				}
                	print "</select>";
                print "</td>";

                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><b>Etiqueta</b> do equipamento:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
                	print "<INPUT type='text' class='text' name='equipamento' id='idEtiqueta'>";
                print "</TD>";
        print "</TR>";
        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Contato:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
                	print "<INPUT type='text' class='text' name='contato' id='idContato'></TD>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Ramal:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
                	print "<INPUT type='text' class='text' name='telefone' id='idRamal'></TD>";
        print "</TR>";
        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Local:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";

                	print "<SELECT class='select' name='local' size='1'>";
                		print "<option value=-1 selected>-  Selecione um local -</option>";
                		$query = "SELECT * from localizacao order by local";
				$resultado = mysql_query($query);
				while ($row = mysql_fetch_array($resultado))
				{
					print "<option value='".$row['loc_id']."'>".$row['local']."</option>";
				}
                	print "</select>";
                print "</td>";

                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Operador:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";

                print "<SELECT class='select' name='operador' size='1'>";
                	print "<option value=-1 selected>-  Selecione um operador -</option>";
			$query = "SELECT * from usuarios order by nome";
			$resultado = mysql_query($query);
			while ($rowU = mysql_fetch_array($resultado))
			{
				print "<option value='".$rowU['user_id']."'>".$rowU['nome']."</option>";
			}
                print "</select><input type='checkbox' name='opAbertura' title='Marque essa opção para buscar sobre o operador que abriu o chamado'>Origem";
	print "</td>";

        print "</TR>";
        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data inicial:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
                	print "<INPUT type=text class='data' name='data_inicial' id='idDataInicial'  ><a onclick=\"displayCalendar(document.forms[0].data_inicial,'dd-mm-yyyy',this)\">".
                		"<img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></TD>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data final:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
                	print "<INPUT type=text class='data' name='data_final' id='idDataFinal'  ><a onclick=\"displayCalendar(document.forms[0].data_final,'dd-mm-yyyy',this)\">".
                		"<img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></TD>";

	print "</tr>";
	print "<tr>";
		print "<TD width='10%' align='left' bgcolor='".TD_COLOR."'>Data de:</TD>";
                print "<TD width='20%' align='left' bgcolor='".BODY_COLOR."'>";
                	print "<SELECT class='select' name='tipo_data' size='1'>";
                		print "<option value='abertura' selected>Abertura</option>";
                		print "<option value='fechamento'>Fechamento</option>";
                	print "</SELECT>";
                print "</TD>";

		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Ordenar por:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<SELECT class='select' name='ordem' size='1'>";
				print "<option value='numero' selected>Número</option>";
				print "<option value='problema'>Problema</option>";
				print "<option value='area'>Área</option>";
				print "<option value='etiqueta'>Equipamento</option>";
				print "<option value='contato'>Contato</option>";
				print "<option value='setor'>Local</option>";
				print "<option value='nome'>Operador</option>";
				print "<option value='data'>Data</option>";
			print "</SELECT>";

                print "</TD>";

	print "</TR>";

	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Status:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
	                print "<SELECT class='select' name='status'>";
        	        print "<option value='Em aberto'>Em aberto</option>";
			$query = "SELECT * from status order by status";
			$resultado = mysql_query($query);
				while ($row = mysql_fetch_array($resultado))
				{
					print "<option value='".$row['stat_id']."'";
					if ($row['stat_id'] == 15) print " selected";
					print ">".$row['status']."</option>";
				}
                	print "</select>";
                print "</td>";

                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Saída:</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
	                print "<SELECT class='select' name='saida'>";
        	        print "<option value='1' selected>Padrão</option>";
			print "<option value='2'>Detalhada</option>";
                	print "</select>";
		print "</td>";
	print "</TR>";
	print "<tr><td colspan='4'><input type='checkbox' name='novaJanela' title='Selecione para que a saída seja em uma nova janela.'>Nova Janela (para impressão)<td><tr>";
	print "<TR>";
		print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
				"<input type='submit' class='button' value='Ok' name='submit' onClick='javascript:submitForm()'>";
		print "</TD>";
		print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
				"<INPUT type='button'  class='button' value='Cancelar' name='cancela' onclick=\"redirect('abertura.php');\">";
		print "</td>";
	print "</TR>";

print "</TABLE>";
print "</FORM>";

?>
	<script language="JavaScript">

	function valida(){
		var ok = validaForm('idNumeroInicial','INTEIRO','Número inicial',0);
		if (ok) var ok = validaForm('idNumeroFinal','INTEIRO','Número final',0);
		if (ok) var ok = validaForm('idEtiqueta','ETIQUETA','Etiqueta',0);
		if (ok) var ok = validaForm('idRamal','INTEIRO','Ramal',0);
		if (ok) var ok = validaForm('idDataInicial','DATA-','Data inicial',0);
		if (ok) var ok = validaForm('idDataFinal','DATA-','Data final',0);

		return ok;
	}

	function checar() {
		var checado = false;
		if (document.form1.novaJanela.checked){
			checado = true;
		} else {
			checado = false;
		}
		return checado;
	}

	function submitForm()
	{
		if (checar() == true) {
			document.form1.target = "_blank";
			document.form1.submit();
		} else {
			document.form1.target = "";
			document.form1.submit();
		}
	}


	//-->
	</script>
<?
print "</BODY>";
print "</HTML>";
?>