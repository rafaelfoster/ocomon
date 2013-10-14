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
################################################################################

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

?>

<HTML>
<BODY bgcolor=<?php print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?php print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?php print TD_COLOR?>>
                        <TR>
                        <?php 
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - Módulo de Ocorrências</b></TD>";
                        echo menu_usuario();
                        if ($s_usuario=='admin')
                        {
                                echo menu_admin();
                        }
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>

<BR>
<B>Relatório para a Intranet do LaSalle</B>
<BR>

 <?php 
print"<B>Período de $data_inicial a $data_final</B><br>";


         if ($rodou == "sim")
        {
                $query = "SELECT * FROM ocorrencias WHERE (";

                //####################################################################

                if ($tipo_data=="abertura")
                {
                        if (!empty($data_inicial) and !empty($data_final))
                        {
                                if (strlen($query)>34)
                                        $query.="AND ";
                                $data_inicial = substr(datam($data_inicial),0,10);
                                $data_inicial.=" 00:00:01";
                                $data_final = substr(datam($data_final),0,10);
                                $data_final.=" 23:59:59";
                                $query.="data_abertura>='$data_inicial' AND data_abertura<='$data_final'";
                        }

                        if (!empty($data_inicial) and empty($data_final))
                        {
                                if (strlen($query)>34)
                                        $query.="AND ";
                                $data_inicial = substr(datam($data_inicial),0,10);
                                $data_inicial.=" 00:00:01";
                                $query.="data_abertura>='$data_inicial'";
                        }

                        if (empty($data_inicial) and !empty($data_final))
                        {
                                if (strlen($query)>34)
                                        $query.="AND ";
                                $data_final = substr(datam($data_final),0,10);
                                $data_final.=" 23:59:59";
                                $query.="data_abertura<='$data_final'";
                        }
                }
                else
                {
                        if (!empty($data_inicial) and !empty($data_final))
                        {
                                if (strlen($query)>34)
                                        $query.="AND ";
                                $data_inicial = substr(datam($data_inicial),0,10);
                                $data_inicial.=" 00:00:00";
                                $data_final = substr(datam($data_final),0,10);
                                $data_final.=" 23:59:59";
                                $query.="data_fechamento>='$data_inicial' AND data_fechamento<='$data_final'";
                        }

                        if (!empty($data_inicial) and empty($data_final))
                        {
                                if (strlen($query)>34)
                                        $query.="AND ";
                                $data_inicial = substr(datam($data_inicial),0,10);
                                $data_inicial.=" 00:00:00";
                                $query.="data_fechamento>='$data_inicial'";
                        }

                        if (empty($data_inicial) and !empty($data_final))
                        {
                                if (strlen($query)>34)
                                        $query.="AND ";
                                $data_final = substr(datam($data_final),0,10);
                                $data_final.=" 23:59:59";
                                $query.="data_fechamento<='$data_final'";
                        }

                }


                //###########################################################################

                if ($status == "Em aberto")
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $status = "Encerrada";
                        $query.="status !=4 ";
                }
                else
                {
                        if (strlen($query)>34)
                                $query.="AND ";
                        $query.="status=$status ";
                }
                 if ((!empty($area)) and ($area!="-1"))
                 {
                        $query.="and sistema=$area";
                 }
				if ($tipo_data == "abertura")
                        $query.=" ) ORDER BY data_abertura";
                else
                        $query.=" ) ORDER BY data_fechamento";
            //  print "Resultado do 1° select: <br>" ;
            // echo $query; print "<br>";

                if (strlen($query)>36)
                {
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                }

                $cor=TD_COLOR;
                $cor1=TD_COLOR;

                print "<td class='line'>";
                if ($linhas>1)
                        print "<TR><TD bgcolor=$cor1><B>Foram encontradas $linhas ocorrências. </B></TD></TR>";
                else
                        print "<TR><TD bgcolor=$cor1><B>Foi encontrada somente 1 ocorrência.</B></TD></TR>";
                print "</TD>";
                print "<td class='line'>";
                print "<TABLE border='1' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'";
                print "<TR><TD bgcolor=$cor1>Dia da semana</TD><TD bgcolor=$cor1 align=center>Data</TD>";
              //  print "<br>*******";
                $sql = "SELECT login from usuarios where nivel in (1,2)";
                if ((!empty($area)) and ($area!="-1")) {
                   $sql .= " and AREA=$area";
                }
                $sql .= " ORDER BY login";
               //  print "Resultado do 2° select: <br>" ;
               //  echo $sql;  print "<br>*******";
                $operador = mysql_query($sql);

                while ($resposta = mysql_fetch_array($operador)) {
                        ?>
                        <TD bgcolor=<?php print $cor1;?> align=right><?php print $resposta["login"];?></TD>
                        <?php 
                }
                print "<TD bgcolor=$cor1 align=right>Total diário</TD>";

                print "</TR>";

                $j = 2;

                $data_i = substr($data_inicial,0,10);
                $data_i.=" 00:00:00";
                $data_f = substr($data_inicial,0,10);
                $data_f.=" 23:59:59";

                $acumula_semana = 0;
                while ($data_i <= $data_final) {
                        if ($j % 2) {
                                $color =  BODY_COLOR;
                        } else {
                                $color = white;
                        }


                        $s = date("w",strtotime($data_i));

                        $semana = array(0=>"Domingo",1=>"Segunda",2=>"Terça",3=>"Quarta",4=>"Quinta",5=>"Sexta",6=>"Sábado");
                        $dia_semana = $semana[$s];

                        $sql2 = "SELECT login from usuarios where nivel in (1,2)";
                        if ((!empty($area)) and ($area!="-1")) {
                           $sql2 .= "and AREA=$area";
                        }
                        $sql2 .= " ORDER BY login";
                        
                        $resultado = mysql_query($sql2);
                        $num_operador = mysql_num_rows($resultado);

                        if ($s == 0) {
                                print "<TR>";

                                print "<TD bgcolor=$cor1 align=right>";
                                print "&nbsp;";
                                print "</TD>";

                                print "<TD bgcolor=$cor1>";
                                print "Total semanal";
                                print "</TD>";

                                $volta = 0;

                                while ($volta < ($num_operador))
                                {
                                        print "<TD bgcolor=$cor1 align=right>";
                                        print "&nbsp;";
                                        print "</TD>";
                                        $volta++;
                                }

                                print "<TD bgcolor=$cor1 align=right>";
                                print $acumula_semana;
                                print "</TD>";

                                print "</TR>";

                                $acumula_semana = 0;

                        }

                        print "<TR>";

                        print "<TD bgcolor=$color>";
                        print $dia_semana;
                        print "</TD>";

                        print "<TD bgcolor=$color align=center>";
                        print substr(datab($data_i),0,10);
                        print "</TD>";



                        $i = 0;

                        $acumula_dia = 0;

                        while ($i < $num_operador)
                        {
                                $operador = mysql_result($resultado,$i,0);

                                if ($tipo_data == "abertura") {
                                        $query = "SELECT * from ocorrencias WHERE operador='$operador' AND (data_abertura BETWEEN '$data_i' AND '$data_f')";
								} else { 
                                        $query = "SELECT * from ocorrencias WHERE operador='$operador' AND (data_fechamento BETWEEN '$data_i' AND '$data_f')";
								}
								
								if ((!empty($area)) and ($area!="-1")) {
                        			$query .= "and sistema=$area";
                 				}
								
                                $ocorrencias = mysql_query($query);

                                $linhas = mysql_num_rows($ocorrencias);

                                $acumula_dia = $acumula_dia + $linhas;

                                print "<TD bgcolor=$color align=right>";
                                //if ($linhas>0)
                                        print $linhas;
                                //else
                                //        print "&nbsp;";
                                print "</TD>";
                                $i++;

                        }

                        $acumula_semana = $acumula_semana + $acumula_dia;

                        print "<TD bgcolor=$color align=right>";

                        print $acumula_dia;

                        print "</TD>";

                        print "</TR>";

                        $j++;
						
						/*$parts = explode("-", substr($data_i,0,10));
						echo "<pre>";
						print_r($parts);
						echo "</pre>";
						$data_i = ($parts[0].$parts[1].$parts[2]) + 1;
						$data_i = substr($data_i,0,4)."-".substr($data_i,4,2)."-".substr($data_i,6,2);	
						$data_i .= " 00:00:00";
						*/
						$dataAux = substr($data_i,0,10);
						$SQL = "select date_add('".$dataAux."', INTERVAL '30' DAY)";
						
						$operador = mysql_query($sql);
		                while ($resposta = mysql_fetch_array($operador)) {
		                        ?>
		                        <TD bgcolor=<?php print $cor1;?> align=right><?php print $resposta["login"];?></TD>
		                        <?php 
		                }
						
						$data_i = strtotime($data_i);
						//if ( substr($dataAux,0,10) == "2004-02-14" ) {
						if ( substr($dataAux,0,10) >= "2004-02-14" ) {
							// este dia houve horário de verão. Por isto, tem que se somar 25horas
							$data_i = $data_i + 90000;
						} else {
                        	$data_i = $data_i + 86400;
							echo "joaoao - ";
						}
                        $data_i = date("Y-m-d H:i:s",$data_i);
						
						echo "Proximo: ".$data_i." - ".date("w",strtotime($data_i))."<BR><BR>";
							
						$dataAux = $data_f;
                        $data_f = strtotime($data_f);
						if ( substr($dataAux,0,10) == "2004-02-14" ) {
							// este dia houve horário de verão. Por isto, tem que se somar 25horas
							$data_f += 90000;
						} else {
                        	$data_f += 86400;
						}
                        $data_f = date("Y-m-d H:i:s",$data_f);

                }

                print "<TR>";

                print "<TD bgcolor=$cor1 align=right>";
                print "&nbsp;";
                print "</TD>";

                print "<TD bgcolor=$cor1>";
                print "Total semanal";
                print "</TD>";

                $volta = 0;

                while ($volta < ($num_operador))
                {
                        print "<TD bgcolor=$cor1 align=right>";
                        print "&nbsp;";
                        print "</TD>";
                        $volta++;
                }

                print "<TD bgcolor=$cor1 align=right>";
                print $acumula_semana;
                print "</TD>";

                print "</TR>";

                print "</TR>";
                print "</TABLE>";
        }
?>


</body>
</html>