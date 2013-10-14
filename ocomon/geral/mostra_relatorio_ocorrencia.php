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
  */


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

?>

<HTML>
<BODY>

<?

        $query = "SELECT * FROM ocorrencias WHERE (";

        if (!empty($numero_inicial) and !empty($numero_final))
                $query.="(numero>='$numero_inicial' AND numero<='$numero_final')";

        if (!empty($numero_inicial) and empty($numero_final))
                $query.="(numero>='$numero_inicial')";

        if (empty($numero_inicial) and !empty($numero_final))
                $query.="(numero<='$numero_final')";

        if (empty($numero_inicial) and empty($numero_final))
                $query.="(numero>0)";


        $query_total = $query." ) ORDER BY numero";
        $query_abertas = $query." AND status!='Encerrada') ORDER BY numero";
        $query_encerradas = $query." AND status='Encerrada') ORDER BY numero";
        $query_aguardando = $query." AND status='Aguardando atendimento') ORDER BY numero";
        $query_atendimento = $query." AND status='Em atendimento') ORDER BY numero";
        $query_estudo = $query." AND status='Em estudo') ORDER BY numero";

        if (strlen($query)>36)
        {
                $resultado_total = mysql_query($query_total);
                $linhas_total = mysql_numrows($resultado_total);

                $resultado_abertas = mysql_query($query_abertas);
                $linhas_abertas = mysql_numrows($resultado_abertas);

                $resultado_encerradas = mysql_query($query_encerradas);
                $linhas_encerradas = mysql_numrows($resultado_encerradas);

                $resultado_aguardando = mysql_query($query_aguardando);
                $linhas_aguardando = mysql_numrows($resultado_aguardando);

                $resultado_atendimento = mysql_query($query_atendimento);
                $linhas_atendimento = mysql_numrows($resultado_atendimento);

                $resultado_estudo = mysql_query($query_estudo);
                $linhas_estudo = mysql_numrows($resultado_estudo);


                if ($linhas_total == 0)
                {
                        $aviso = "Nenhuma_ocorrencia_localizada.";
                        $origem = "relatorio_ocorrencias.php";
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php?aviso=$aviso&origem=$origem\">";
                }
        }

        print "<BR><B>OcoMon - Relatório de ocorrências por número.</B> - <a href=relatorio_ocorrencias.php>Voltar</a><BR>";
        print "<HR>";
?>
<TABLE border="0"  align="center" width="100%">

        <TR>
        <TABLE border="0"  align="center" width="100%">
                <TD width="20%" align="left">Ocorrências de:</TD>
                <TD width="20%" align="left"><?print $numero_inicial;?> a <?print $numero_final;?></TD>
                <TD width="40%" align="left">Número total de ocorrências:</TD>
                <TD width="20%" align="left"><?print $linhas_total;?></TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="0"  align="center" width="100%">
                <TD width="20%" align="left">Ocorrências abertas:</TD>
                <TD width="30%" align="left"><?print $linhas_abertas;?></TD>
                <TD width="20%" align="left">Percentual:</TD>
                <TD width="30%" align="left"><?print round(($linhas_abertas*100)/$linhas_total);?>%</TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="0"  align="center" width="100%">
                <TD width="20%" align="left">Ocorrências encerradas:</TD>
                <TD width="30%" align="left"><?print $linhas_encerradas;?></TD>
                <TD width="20%" align="left">Percentual:</TD>
                <TD width="30%" align="left"><?print round(($linhas_encerradas*100)/$linhas_total);?>%</TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="0"  align="center" width="100%">
                <TD width="20%" align="left">Ocorrências aguardando atendimento:</TD>
                <TD width="30%" align="left"><?print $linhas_aguardando;?></TD>
                <TD width="20%" align="left">Percentual:</TD>
                <TD width="30%" align="left"><?print round(($linhas_aguardando*100)/$linhas_total);?>%</TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="0"  align="center" width="100%">
                <TD width="20%" align="left">Ocorrências em atendimento:</TD>
                <TD width="30%" align="left"><?print $linhas_atendimento;?></TD>
                <TD width="20%" align="left">Percentual:</TD>
                <TD width="30%" align="left"><?print round(($linhas_atendimento*100)/$linhas_total);?>%</TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="0"  align="center" width="100%">
                <TD width="20%" align="left">Ocorrências em estudo:</TD>
                <TD width="30%" align="left"><?print $linhas_estudo;?></TD>
                <TD width="20%" align="left">Percentual:</TD>
                <TD width="30%" align="left"><?print round(($linhas_estudo*100)/$linhas_total);?>%</TD>
        </TABLE>
        </TR>

</TABLE>
<HR>

</BODY>
</HTML>


