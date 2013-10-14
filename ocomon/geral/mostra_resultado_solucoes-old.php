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
<BODY bgcolor=<?print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TD_COLOR?>>
                        <TR>
                        <?
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
<B>Consulta de Soluções</B>
<BR>

<?

		
		$problema = str_replace(" ","%",trim($problema));

		$query = "SELECT s.numero as numero, s.problema as problema, s.solucao as solucao, s.data as data, s.responsavel as responsavel, 
				a.assentamento as assentamento,	o.descricao as descricao, u.* 
				FROM solucoes s, assentamentos a, ocorrencias as o, usuarios as u 
				WHERE (CONVERT(lower( s.solucao ) using utf8) LIKE lower(  '%$problema%' ) OR  
					CONVERT (lower( a.assentamento )using utf8) LIKE lower(  '%$problema%' ) OR 
					CONVERT (lower( s.problema ) using utf8) LIKE lower(  '%$problema%' ) OR 
					CONVERT (lower(o.descricao) using utf8) LIKE lower('%$problema%'))  
					AND a.ocorrencia = s.numero AND o.numero = s.numero and o.operador = u.user_id 
					 and a.responsavel = u.user_id and s.responsavel = u.user_id ";

                if (!empty($data_inicial))
                {
                        $data_inicial = str_replace("-","/",$data_inicial);
						$data_inicial = datam($data_inicial);
                        $query.="and o.data_abertura >='$data_inicial' and o.data_fechamento >= '$data_inicial' ";
                }

                if (!empty($data_final))
                {
                        $data_final = str_replace("-","/",$data_final);
						$data_final = datam($data_final);
                        $query.="and o.data_abertura <='$data_final' and o.data_fechamento <= '$data_final' ";

		}


                if (!empty($operador) and $operador != -1)
                {
                        $query.="and s.responsavel=$operador ";
                }


                $query.=" group by numero ORDER BY numero";//


		$resultado = mysql_query($query);
		$linhas = mysql_numrows($resultado);
                        if ($linhas==0)
                        {
                                $aviso = "Nenhuma solução localizada.";
                                $origem = "consulta_solucoes.php";
                                session_register("aviso");
                                session_register("origem");
                                //echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
								?>
								<script>
								window.alert('<?print $aviso;?>');
								history.back();
								</script>
								
								<?
                        }

                $cor=TD_COLOR;
                $cor1=TD_COLOR;

                print "<td class='line'>";
                if ($linhas>1)
                        print "<TR><td class='line'><B>Foram encontradas $linhas soluções possíveis. </B></TD></TR>";
                else
                        print "<TR><td class='line'><B>Foi encontrada somente 1 solução possível.</B></TD></TR>";
                print "</TD>";

        while ($row = mysql_fetch_array($resultado))
        {
                ?>
                <TABLE border="1" style="{border-collapse:collapse;} align="center" width="100%">
                       <tr> 
						<TD align="left" bgcolor=<?print TD_COLOR?>>Número:</TD>
                        <TD align='left'><? print "<a onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><font color='blue'>".$row['numero']."</font></a>"?></TD>
                        <TD align="left">Data:</TD>
                        <TD align="left"><?print datab($row['data']);?></TD>
                        <TD align="left">Operador:</TD>
                        <TD align="left"><?print $row['nome'];?></TD>
                </TR>
                <TR>
                        <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Problema:</TD>
                        <TD colspan='5' width="80%" align="left"><?print nl2br($row['problema']);?></TD>
                </TR>

                <TR>
                        <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Solução:</TD>
                        <TD colspan='5' width="80%" align="left"><?print nl2br($row['solucao']);?></TD>
                </TR>

                <HR>

                </TABLE>
        <?
     
        }//while
	//print $query;

        ?>
<script type='text/javascript'>

	 function popup_alerta(pagina)	{ //Exibe uma janela popUP
      	x = window.open(pagina,'_blank','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
      	//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);	  	
		return false
     }

</script>

</body>
</html>
