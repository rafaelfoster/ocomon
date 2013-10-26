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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$cab = new headers;
	$cab->set_title(TRANS('TTL_INVMON'));

	print "<BR><B>".TRANS('MNL_RELATORIOS').":</B><BR>";

        print "<br><table><TR><td class='line'><B>".TRANS('TXT_REPORTS_OPTIONS_1')." <a class='ui-accordion-header ui-helper-reset ui-state-default ui-corner-all' href='consulta_comp.php'>".TRANS('TLT_HERE')."</a> ".TRANS('TXT_REPORTS_OPTIONS_2')."</B></TD></TR></table>";
        //print "<br><a href=relatorio_geral.php>Relatório geral.</a></br>";

        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
        print "<TR><td class='line'><b>".TRANS('TXT_ESTATISTIC')."</b></TD></TR>";

	print "<TR class='lin_par'>".
		"<td class='line'  colspan='2'><a href='estat_geral.php'>".TRANS('TTL_REP_EST_GENERAL_EQUIP')."</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_topten_modelo.php'>".TRANS('TTL_TOP_TEN')."</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_equippordia.php'>".TRANS('TTL_QTD_EQUIP_CAD_FOR_DAY')."</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_equipporlocal.php'>".TRANS('TTL_QTD_EQUIP_CAD_FOR_LOCAL')."</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_compporlocal.php'>".TRANS('TTL_COMP_X_SECTOR')."</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_comppormemoria.php'>".TRANS('TTL_COMP_X_MEMORY')."</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_modelo_memoria.php'>".TRANS('TTL_MODEL_X_MEMORY')."</a></TD>".
	"</TR>";


	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_compporprocessador.php'>".TRANS('TTL_COMP_X_PROCESSOR')."</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_compporhd.php'>".TRANS('TTL_COMP_X_HD')."</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_situacao_geral.php'>".TRANS('TTL_SIT_GENERAL_EQUIP')."</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_equipporsituacao.php'>".TRANS('TTL_EQUIP_X_SITUAC')."</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_instituicao.php'>".TRANS('TTL_DIST_GENERAL_EQUIP_FOR_UNIT')."</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_equipporreitoria_agrup.php'>".TRANS('TTL_EQUIP_X_MAJOR')."</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_equippordominio.php'>".TRANS('TTL_EQUIP_X_DOMAIN')."</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_vencimentos.php'>".TRANS('TTL_EXPIRAT_GUARANTEE')."</a></TD>".
	"</TR>";
//
	print "<TR class='lin_par'>".
		"<td class='line'  colspan='2'><a href='hw_alteracoes.php'>".TRANS('SUBTTL_ALTER_HW_PERIOD')."</a></TD>".
	"</TR>";
//
	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='pieces_x_technician.php'>".TRANS('PIECES_BY_TECHNICIAN')."</a></TD>".
	"</TR>";

print "</table>";
print "</BODY>";
print "</HTML>";
?>
