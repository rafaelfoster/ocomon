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
                if (strlen($query)>34)
                        $query.="AND ";
                $data_inicial = datam($data_inicial);
                $data_final = datam($data_final);
                $query.="data_fechamento>='$data_inicial' AND data_fechamento<='$data_final'";
        }

        if (!empty($data_inicial) and empty($data_final))
        {
                if (strlen($query)>34)
                        $query.="AND ";
                $data_inicial = datam($data_inicial);
                $query.="data_fechamento>='$data_inicial'";
        }

        if (empty($data_inicial) and !empty($data_final))
        {
                if (strlen($query)>34)
                        $query.="AND ";
                $data_final = datam($data_final);
                $query.="data_fechamento<='$data_final'";
        }

        if (empty($data_inicial) and empty($data_final))
        {
                if (strlen($query)>34)
                        $query.="AND ";
                $data_inicial = datam("01/01/1990");
                $query.="data_fechamento>='$data_inicial'";
        }


        $query = $query." ) ORDER BY numero";
        $resultado = mysql_query($query);
        $linhas = mysql_numrows($resultado);

        $query_total = "SELECT * FROM ocorrencias";
        $resultado_total = mysql_query($query_total);
        $linhas_total = mysql_numrows($resultado_total);

        if ($linhas == 0)
        {
                $aviso = "Nenhuma_ocorrencia_localizada.";
                $origem = "relatorio_data_encerramento.php";
                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php?aviso=$aviso&origem=$origem\">";
        }

        print "<BR><B>OcoMon - Relatório de ocorrências por data de encerramento.</B> - <a href=relatorio_data_encerramento.php>Voltar</a><BR>";
        print "<HR>";
?>
<TABLE border="0"  align="center" width="100%">

        <TR>
        <TABLE border="0"  align="center" width="100%">
                <TD width="20%" align="left">Período de:</TD>
                <TD width="30%" align="left"><?print datab($data_inicial);?> a <?print datab($data_final);?></TD>
                <TD width="40%" align="left">Número total de ocorrências no banco de dados:</TD>
                <TD width="10%" align="left"><?print $linhas_total;?></TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="0"  align="center" width="100%">
                <TD width="60%" align="left">Número de ocorrências encerradas no período de <?print datab($data_inicial);?> a <?print datab($data_final);?>:</TD>
                <TD width="15%" align="left"><? print $linhas;?></TD>
                <TD width="15%" align="left">Porcentagem</TD>
                <TD width="10%" align="left"><?print round(($linhas*100)/$linhas_total)?>%</TD>
        </TABLE>
        </TR>

</TABLE>
<HR>

</BODY>
</HTML>


