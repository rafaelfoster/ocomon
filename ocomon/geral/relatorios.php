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

	print "<BR><B>".TRANS('TLT_REPORTS').":</B><BR>";

	print "<TR><td class='line'><B>".TRANS('TLT_REPORTS_SOON')." <a href='consultar.php'>".TRANS('TLT_HERE')."</a> ".TRANS('TLT_REPORTS_SOON_2').".</B></TD></TR>";
	print "</TD>";
	print "<td class='line'>";
	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
	print "<TR class='header'><td class='line'>".TRANS('COL_REPORT_FOR_PERIOD')."</TD><td class='line'>".TRANS('COL_REPORT_FOR')."...</TD>";

	$color =  BODY_COLOR;

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='relatorio_problemas_areas.php'>".TRANS('REP_PROB_AREA')."</a></TD></TR>";
	print "</TR>";

	print "<TR class='lin_par'>";
	print "<td class='line' colspan='2'><a href='relatorio_setores_areas.php'>".TRANS('TLT_REPORT_LOCAL_MORE_ATTEN')."</TD>";
	print "</TR>";

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='relatorio_geral.php'>".TRANS('TLT_REPORT_GENERAL')."</TD>";
	print "</TR>";



	print "<tr class='lin_par'>";
		print "<td class='line' colspan='2'><a href='relatorio_slas_2.php'>".TRANS('TLT_REPORT_SLAS')."</a></TD>";
	print "</TR>";

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='chamados_x_etiqueta.php'>".TRANS('TLT_REPORT_CALL_FOR_EQUIP')."</TD>";
	print "</TR>";


	print "<TR class='lin_par'>";
	print "<td class='line' colspan='2'><a onClick =\"checa_permissao('relatorio_gerencial.php')\">".TRANS('TLT_REP_MANEGER_HELPDESK')."</a></TD>";
	print "</TR>";

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='relatorio_operadores_areas.php'>".TRANS('TTL_REP_ATTEND_FOR_OPERATOR')."</a></TD>";
	print "</TR>";

	print "<TR class='lin_par'>";
	print "<td class='line' colspan='2'><a href='relatorio_usuarios_areas.php'>".TRANS('TTL_REP_ATTEND_FOR_USER')."</a></TD>";
	print "</TR>";

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='relatorio_chamados_area.php'>".TRANS('TTL_REP_QTD_CALL_AREA_PERIOD')."</a></TD>";
	print "</tr>";

	print "<TR class='lin_par'>";
	print "<td class='line' colspan='2'><a href='relatorio_usuario_final.php'>".TRANS('TTL_REP_CALL_OPEN_USER_FINISH')."</a></TD>";
	print "</TR>";

	print "<TR class='lin_impar'>";
	print "<td class='line' colspan='2'><a href='relatorio_chamados_categorias.php'>".TRANS('TTL_REP_QTD_CALL_CAT_PROB')."</a></TD>";
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
