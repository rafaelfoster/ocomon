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

		//echo "data_inicial $data_inicial <BR>";
		//echo "data_final $data_final <BR>";

		$dti = explode("/",$data_inicial);
		$dtf = explode("/",$data_final);

		if ((checkdate($dti[1],$dti[0],$dti[2])) and (checkdate($dtf[1],$dtf[0],$dtf[2]))) {

		    $dis = $dti[0]."/".$dti[1]."/".$dti[2]." 00:00:00";
		    $dfs = $dtf[0]."/".$dtf[1]."/".$dtf[2]." 23:59:59";

		    $data_inicial = $dti[2]."-".$dti[1]."-".$dti[0]." 00:00:00";
		    $data_final   = $dtf[2]."-".$dtf[1]."-".$dtf[0]." 23:59:59";
		}else{
			header("Location: http://www.intranet.unilasalle.edu.br/sistemas/ocomon/relatorio_periodo_sistema.php?msg=dtinvalida&di=$data_inicial&df=$data_final");
			exit;
		}
		
		/*
		echo "<PRE>";
		var_dump($dti);
		var_dump($dtf);
		echo "</PRE>";
		*/

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

        $query_sistemas = "SELECT * FROM sistemas where sis_id not in (3,4,5)ORDER BY sis_id";
        $resultado_sistemas = mysql_query($query_sistemas);
        $linhas_sistemas = mysql_numrows($resultado_sistemas);

        if ($linhas_total == 0)
        {
                $aviso = "Nenhuma_ocorrencia_localizada.";
                $origem = "relatorio_periodo_sistema.php";
                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php?aviso=$aviso&origem=$origem\">";
        }

        print "<BR><B>OcoMon - Relatório de ocorrências por Área responsável.</B> - <a href=relatorio_periodo_sistema.php>Voltar</a><BR>";
        print "<HR>";
?>
<TABLE border="0"  align="center" width="100%">

        <TR>
        <TABLE border="0"  align="center" width="100%">
                <TD width="20%" align="left">Período de:</TD>
                <TD width="20%" align="left"><?print "<B>".$dis."</B>"; //datab($data_inicial);?> a <?print "<BR><B>".$dfs."</B>"; //datab($data_final);?></TD>
                <TD width="40%" align="left">Número total de ocorrências no período:</TD>
                <TD width="20%" align="left"><?print $linhas_total;?></TD>
        </TABLE>
        </TR>

        <?
        $i = 0;
        while ($i < $linhas_sistemas)
        {
                $sis = mysql_result($resultado_sistemas,$i,0);
                $query_sis = $query." AND sistema=$sis) ORDER BY numero";
                $resultado_sis = mysql_query($query_sis);
                $linhas_sis = mysql_numrows($resultado_sis);
                ?>
                <TR>
                <TABLE border="0"  align="center" width="100%">
                        <TD width="20%" align="left"><?print mysql_result($resultado_sistemas,$i,1);?>:</TD>
                        <TD width="30%" align="left"><?print $linhas_sis;?></TD>
                        <TD width="20%" align="left">Percentual:</TD>
                        <TD width="30%" align="left"><?print round(($linhas_sis*100)/$linhas_total);?>%</TD>
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


