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
        $resultado_total = mysql_query($query_total);
        $linhas_total = mysql_numrows($resultado_total);

        $query_status = "SELECT * FROM status ORDER BY status"; // Antes estava 	//ordenado por estado.
        $resultado_status = mysql_query($query_status);
        $linhas_status = mysql_numrows($resultado_status);

        if ($linhas_total == 0)
        {
                $aviso = "Nenhuma_ocorrencia_localizada.";
                $origem = "relatorio_periodo_status.php";
                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php?aviso=$aviso&origem=$origem\">";
        }

        print "<BR><B>OcoMon - Relatório de ocorrências por status.</B> - <a href=relatorio_periodo_status.php>Voltar</a><BR>";
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

        <?
        $i = 0;
        while ($i < $linhas_status)
        {
                $stat = mysql_result($resultado_status,$i,0);
                $query_stat = $query." AND status='$stat') ORDER BY numero";
                $resultado_stat = mysql_query($query_stat);
                $linhas_stat = mysql_numrows($resultado_stat);
                ?>
                <TR>
                <TABLE border="0"  align="center" width="100%">
                        <TD width="20%" align="left"><?print mysql_result($resultado_status,$i,1);?>:</TD> //Foi alterado a posição de inicio 
	//no vetor
                        <TD width="30%" align="left"><?print $linhas_stat;?></TD>
                        <TD width="20%" align="left">Percentual:</TD>
                        <TD width="30%" align="left"><?print round(($linhas_stat*100)/$linhas_total);?>%</TD>
                </TABLE>
                </TR>
                <?
                $i++;
         }
         ?>



</TABLE>
<HR>

</BODY>
</HTML>



