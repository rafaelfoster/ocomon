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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$cab = new headers;
	$cab->set_title(TRANS("html_title"));

	print "<BR><B>Relatórios:</B><BR>";

        print "<br><table><TR><td class='line'><B>Escolha um dos relatórios prontos, ou clique <a href='consulta_comp.php'>AQUI</a> para um relatório personalizado.</B></TD></TR></table>";
        //print "<br><a href=relatorio_geral.php>Relatório geral.</a></br>";

        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";
        print "<TR><td class='line'><b>Estatísticas</b></TD></TR>";

	print "<TR class='lin_par'>".
		"<td class='line'><a href='estat_geral.php'>Estatística geral de equipamentos cadastrados</a></TD>".
		"<td class='line'><a href='hw_alteracoes.php'>Alterações de HW por período</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_topten_modelo.php'>10 modelos mais cadastrados</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_equippordia.php'>Quantidade de equipamentos cadastrados por dia</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_equipporlocal.php'>Quantidade de equipamento por Setor</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_compporlocal.php'>Computadores x Setor</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_comppormemoria.php'>Computadores x memória</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_modelo_memoria.php'>Modelos x memória</a></TD>".
	"</TR>";


	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_compporprocessador.php'>Computadores x processador</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_compporhd.php'>Computadores x HD</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_situacao_geral.php'>Situação geral dos equipamentos</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_equipporsituacao.php'>Equipamentos x situação</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_instituicao.php'>Distribuição geral de equipamentos por Unidade</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_equipporreitoria_agrup.php'>Equipamentos x reitoria</a></TD>".
	"</TR>";

	print "<TR class='lin_impar'>".
		"<td class='line' colspan='2'><a href='estat_equippordominio.php'>Equipamentos x Domínio</a></TD>".
	"</TR>";

	print "<TR class='lin_par'>".
		"<td class='line' colspan='2'><a href='estat_vencimentos.php'>Vencimentos das garantias</a></TD>".
	"</TR>";

print "</table>";
print "</BODY>";
print "</HTML>";
?>