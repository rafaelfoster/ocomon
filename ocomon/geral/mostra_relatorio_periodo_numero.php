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


include ("var_sessao.php");
include ("funcoes.inc");
include ("config.inc.php");
include ("logado.php");

?>

<HTML>
<BODY>

<?

        $query = "SELECT * FROM ocorrencias WHERE (";

        if (!empty($data_inicial) and !empty($data_final))
        {
                $data_inicial = datam($data_inicial);
                $data_final = datam($data_final);
                $query.="data_abertura>='$data_inicial' AND data_abertura<='$data_final'";
        }

        if (!empty($data_inicial) and empty($data_final))
        {
                if (strlen($query)>34)
                        $query.="AND ";
                $data_inicial = datam($data_inicial);
                $query.="data_abertura>='$data_inicial'";
        }

        if (empty($data_inicial) and !empty($data_final))
        {
                if (strlen($query)>34)
                        $query.="AND ";
                $data_final = datam($data_final);
                $query.="data_abertura<='$data_final'";
        }


        if (empty($data_inicial) and empty($data_final))
        {
                $data_inicial = datam("01/01/1990");
                $query.="data_abertura>='$data_inicial'";
        }


        $query_total = $query." ) ORDER BY numero";
        $query_abertas = $query." AND status!='4') ORDER BY numero";
        $query_encerradas = $query." AND status='4') ORDER BY numero";
        $query_aguardando = $query." AND status='1') ORDER BY numero";
        $query_atendimento = $query." AND status='2') ORDER BY numero";
        $query_estudo = $query." AND status='3') ORDER BY numero";

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
                        $origem = "relatorio_periodo_numero.php";
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php?aviso=$aviso&origem=$origem\">";
                }
        }

        print "<BR><B>OcoMon - Relatório de ocorrências por período.</B> - <a href=relatorio_periodo_numero.php>Voltar</a><BR>";
        print "<HR>";
?>
<TABLE border="0"  align="center" width="100%">

        <TR>
        <TABLE border="0"  align="center" width="100%">
                <TD width="20%" align="left">Período de:</TD>
                <TD width="20%" align="left"><?print datab($data_inicial);?> a <?print datab($data_final);?></TD>
                <TD width="40%" align="left">Número total de ocorrências no período:</TD>
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


