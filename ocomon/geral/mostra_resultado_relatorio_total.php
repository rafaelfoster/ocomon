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
  */


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

?>

<HTML>
<BODY>

<?php 

         if ($rodou == "sim")
         {
                $query = "SELECT * FROM ocorrencias WHERE (";

                if (!empty($numero_inicial) and !empty($numero_final))
                        $query.="(numero>='$numero_inicial' AND numero<='$numero_final')";

                if (!empty($numero_inicial) and empty($numero_final))
                        $query.="numero>=$numero_inicial";

                if (empty($numero_inicial) and !empty($numero_final))
                {
                        $numero_inicial = 1;
                        $query.="(numero>=$numero_inicial AND numero<=$numero_final";
                }


                if ($problema != -1)
                {
                        if (!empty($problema) and $problema != -1)
                        {
                                if (strlen($query)>34)
                                        $query.="AND ";
                                $query.="problema=$problema ";
                        }
                }

                if (!empty($descricao))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="descricao LIKE '%$descricao%' ";
                }

                if (!empty($equipamento))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="equipamento LIKE '%$equipamento%' ";
                }


                if (!empty($sistema) and $sistema != -1)
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="sistema=$sistema ";
                }


                if (!empty($contato))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="contato LIKE '%$contato%' ";
                }


                if (!empty($local) and $local != -1)
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="local=$local ";
                }


                if (!empty($operador) and $operador != -1)
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="operador='$operador' ";
                }


                if (!empty($data_inicial) and !empty($data_final))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $data_inicial = datam($data_inicial);
                        $data_final = datam($data_final);
                        $query.="data_abertura>='$data_inicial' AND data_abertura<='$data_final'";
                }
                elseif (!empty($data_inicial) and empty($data_final))
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $data_inicial = datam($data_inicial);
                        $query.="data_abertura>='$data_inicial' AND data_abertura<='$data_inicial'";
                }


                if ($status == "Em aberto")
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                       // $status = "Encerrada";
                          $status = "4"; 
			$query.="status !='$status' ";
                }
                else
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="status='$status' ";
                }

                if ($ordem == "oco")
                        $query.=" ) ORDER BY numero";

                if ($ordem == "data_decres")
                        $query.=" ) ORDER BY $ordem DESC";
                else
                        $query.=" ) ORDER BY $ordem";


                if (strlen($query)>36)
                {
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        $query2 = "select * from assentamentos where ocorrencia='$numero'";
                        $resultado2 = mysql_query($query2);
                        $linhas2=mysql_numrows($resultado2);

                        if ($linhas==0)
                        {
                                $aviso = "Nenhuma_ocorrencia_localizada.".$query;
                                $origem = "relatorios.php";
                                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php?aviso=$aviso&origem=$origem\">";
                                //echo mensagem("Nenhuma ocorrência localizada.");
                                //exit;
                        }
                }

                $j=0;
                while ($j < $linhas)
                {
                $numero = mysql_result($resultado,$j,0);

                $query2 = "select * from assentamentos where ocorrencia='$numero'";
                $resultado2 = mysql_query($query2);
                $linhas2=mysql_numrows($resultado2);


				print $query;
				exit;

                if ($relatorio == "impressao")
                {
                        if ($j == 0)
                                print "<BR><B>OcoMon - Relatório -=- Foi(ram) encontrada(s) $linhas ocorrência(s).</B> - <a href=relatorio_total.php>Voltar</a><BR>";
                                print "<HR>";
                        ?>
                        <TABLE border="0"  align="center" width="100%">
                        <TR>
                                <TABLE border="0"  align="center" width="100%">
                                        <TD width="20%" align="left">Número:</TD>
                                        <TD width="80%" align="left"><?php print mysql_result($resultado,$j,0);?></TD>
                                </TABLE>
                        </TR>
                        <TR>
                        <TABLE border="0"  align="center" width="100%">
                        <TD width="20%" align="left">Problema:</TD>
                        <?php 
                                $problemas = mysql_result($resultado,$j,1);
                                $query = "SELECT * FROM problemas WHERE prob_id=$problemas";
                                $resultado3 = mysql_query($query);
                        ?>
                        <TD width="30%" align="left"><?php print mysql_result($resultado3,0,1);?></TD>
                        <TD width="20%" align="left">Sistema:</TD>
                        <?php 
                                $sistemas = mysql_result($resultado,$j,4);
                                $query = "SELECT * FROM sistemas WHERE sis_id=$sistemas";
                                $resultado3 = mysql_query($query);
                        ?>
                        <TD width="30%" align="left"><?php print mysql_result($resultado3,0,1);?></TD>
                        </TABLE>
                        </TR>
                        <TR>
                        <TABLE border="0"  align="center" width="100%">
                                <TD width="20%" align="left" valign="top">Descrição:</TD>
                                <TD width="80%" align="left"><?php print nl2br(mysql_result($resultado,$j,2));?></TD>
                        </TABLE>
                        </TR>
                        <?php 
                                if ($linhas2!=0)
                                {
                                        $i=0;
                                        while ($i < $linhas2)
                                        {
                                                ?>
                                                <TR>
                                                <TABLE border="0"  align="center" width="100%">
                                                        <TD width="20%" align="left" valign="top">Assentamento <?php print $i+1;?> de <?php print $linhas2;?>:</TD>
                                                        <TD width="40%" align="left" valign="top"><?php print nl2br(mysql_result($resultado2,$i,2));?></TD>
                                                        <TD width="5%" align="left" valign="top">Data:</TD>
                                                        <TD width="15%" align="left" valign="top"><?php print datab(mysql_result($resultado2,$i,3));?></TD>
                                                        <TD width="10%" align="left" valign="top">Responsável:</TD>
                                                        <TD width="10%" align="left" valign="top"><?php print mysql_result($resultado2,$i,4);?></TD>
                                                </TABLE>
                                                </TR>
                                                <?php 
                                                $i++;
                                        }
                                }
                        ?>
                        <TR>
                        <TABLE border="0"  align="center" width="100%">
                                <TD width="20%" align="left">Equipamento:</TD>
                                <TD width="80%" align="left"><?php print mysql_result($resultado,$j,3);?></TD>
                        </TABLE>
                        </TR>
                        <TR>
                        <TABLE border="0"  align="center" width="100%">
                                <TD width="20%" align="left">Contato:</TD>
                                <TD width="30%" align="left"><?php print mysql_result($resultado,$j,5);?></TD>
                                <TD width="20%" align="left">Ramal:</TD>
                                <TD width="30%" align="left"><?php print mysql_result($resultado,$j,6);?></TD>
                        </TABLE>
                        </TR>
                        <TR>
                        <TABLE border="0"  align="center" width="100%">
                                <TD width="20%" align="left">Local:</TD>
                                <?php 
                                        $local = mysql_result($resultado,$j,7);
                                        $query = "SELECT * FROM localizacao WHERE loc_id='$local'";
                                        $resultado3 = mysql_query($query);
                                ?>
                                <TD width="30%" align="left"><?php print mysql_result($resultado3,0,1);?></TD>
                                <TD width="20%" align="left">Operador:</TD>
                                <TD width="30%" align="left"><?php print mysql_result($resultado,$j,8);?></TD>
                        </TABLE>
                        </TR>
                        <TR>
                        <TABLE border="0"  align="center" width="100%">
                                <TD width="20%" align="left">Data de abertura:</TD>
                                <TD width="30%" align="left"><?php print datab(mysql_result($resultado,$j,9));?></TD>
                                <TD width="20%" align="left">Status:</TD>
                                <TD width="30%" align="left"><?php print mysql_result($resultado,$j,11);?></TD>
                        </TABLE>
                        </TR>
                        </TABLE>
                        <HR>
                        <?php 
                }
                $j++;
                //###############################################################################
                if ($relatorio == "intranet")
                {
                        $aviso = "Ainda_em_desenvolvimento.";
                        $origem = "relatorio_total.php";
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php?aviso=$aviso&origem=$origem\">";
                        /*

                        if (empty($data_inicial) and empty($data_final))
                        {
                                $data_inicial = "01/".date(m)."/".date(Y);
                                $mes = date(m);
                                $data_final = date(t,$mes)."/".date(m)."/".date(Y);
                        }
                        if (!empty($data_inicial) and empty($data_final))
                        {
                                $data_final = date(t,$mes)."/".date(m)."/".date(Y);
                        }
                        if (empty($data_inicial) and !empty($data_final))
                        {
                                $data_inicial = "01/".date(m)."/".date(Y);
                        }

                        $data_inicial = datam($data_inicial);
                        $data_final = datam($data_final);
                        $query = "SELECT * FROM ocorrencias WHERE (data_abertura>='$data_inicial' AND data_abertura<='$data_final') ORDER BY data_abertura";

                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        $i = 0;

                        $sem1 = 0;
                        $sem2 = 0;
                        $sem3 = 0;
                        $sem4 = 0;
                        $sem5 = 0;

                        $semana1 = "2002-03-07";
                        $semana2 = "2002-03-14";
                        $semana3 = "2002-03-21";
                        $semana4 = "2002-03-28";
                        $semana5 = "2002-03-31";

                        while ($i < $linhas)
                        {
                                if (mysql_result($resultado,$i,9) <= $semana1)
                                        $sem1++;

                                if ((mysql_result($resultado,$i,9) <= $semana2) and (mysql_result($resultado,$i,9) > $semana1))
                                        $sem2++;

                                if ((mysql_result($resultado,$i,9) <= $semana3)  and (mysql_result($resultado,$i,9) > $semana2))
                                        $sem3++;

                                if ((mysql_result($resultado,$i,9) <= $semana4) and (mysql_result($resultado,$i,9) > $semana3))
                                        $sem4++;

                                if ((mysql_result($resultado,$i,9) <= $semana5) and (mysql_result($resultado,$i,9) > $semana4))
                                        $sem5++;

                                $i++;
                        }

                        include ("templates/helpdesk.tpl");
                        */
                }
                //###############################################################################

                }
        }
                ?>

</BODY>
</HTML>


