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

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

 	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	print "<BR><B>Relatórios:</B><BR>";

	print "<TR><td class='line'><B>Escolha um dos relatórios prontos, ou clique <a href='consultar.php'>AQUI</a> para um relatório geral.</B></TD></TR>";
	print "</TD>";
	print "<td class='line'>";
	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
	print "<TR class='header'><td class='line'>Relatórios por Periodo</TD><td class='line'>Relatórios por...</TD>";

	$color =  BODY_COLOR;

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='relatorio_problemas_areas.php'>".TRANS('REP_PROB_AREA')."</a></TD></TR>";
	print "</TR>";

	print "<TR class='lin_par'>";
	print "<td class='line' colspan='2'><a href='relatorio_setores_areas.php'>Locais mais atendidos</TD>";
	print "</TR>";

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='relatorio_geral.php'>Geral</TD>";
	print "</TR>";



	print "<tr class='lin_par'>";
		print "<td class='line' colspan='2'><a href='relatorio_slas_2.php'>SLA'S</a></TD>";
	print "</TR>";

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='chamados_x_etiqueta.php'>Chamados por equipamento</TD>";
	print "</TR>";


	print "<TR class='lin_par'>";
	print "<td class='line' colspan='2'><a onClick =\"checa_permissao('relatorio_gerencial.php')\">Gerência do Helpdesk</a></TD>";
	print "</TR>";

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='relatorio_operadores_areas.php'>Atendimentos por operador</a></TD>";
	print "</TR>";

	print "<TR class='lin_par'>";
	print "<td class='line' colspan='2'><a href='relatorio_usuarios_areas.php'>Atendimentos por usuário</a></TD>";
	print "</TR>";

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='relatorio_chamados_area.php'>Quantidade de chamados: Área x período</a></TD>";
	print "</tr>";

	print "<TR class='lin_par'>";
	print "<td class='line' colspan='2'><a href='relatorio_usuario_final.php'>Chamados abertos pelo usuário-final</a></TD>";
	print "</TR>";

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='relatorio_chamados_categorias'>Quantidade de chamados x categoria de problema</a></TD>";
	print "</tr>";


print "</BODY>";
?>
<script type='text/javascript'>

	function redirect(url){
		window.location.href=url;
	}


	function checa_permissao(URL){
		var admin = '<?print $_SESSION['s_nivel'];?>';
		var area_admin = '<?print $_SESSION['s_area_admin']?>';
		if( (admin!=1) && (area_admin!=1) ) {
			window.alert('Acesso Restrito!');
		} else
			redirect(URL);

		return false;
	}

</script>
<?
print "</HTML>";
?>
