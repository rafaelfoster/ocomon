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
        include ("var_sessao.php");      // Tem que estar em primeiro por causa do header!
        include ("funcoes.inc");
        include ("config.inc.php");
        include ("logado.php");

        $hoje = date("Y-m-d H:i:s");

if ($s_nivel>1)
{
        echo "<META HTTP-EQUIV=REFRESH   CONTENT=\"0;
        URL=index.php\">";
}

	//	$query = "select nivel from usuarios where login = '$s_usuario'";
	//	$resultado = mysql_query($query);
	//	$nivel = mysql_result($resultado,0);
?>
<HTML>


<BODY bgcolor=<?php print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?php print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?php print TD_COLOR?>>
                        <TR>
                        <?php 
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap width=75%><p align left><b><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>InvMon - Controle de inventário para equipamentos de informática  -  Usuário: <font color=red><a title='Usuário logado no sistema'>$s_usuario</a></font></b></p></td><td bgcolor=$cor1 nowrap width=25%><p align=right><b><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif> Nível de acesso: <font color=red><a title='Nível de acesso ao sistema'>$s_nivel_desc</a></font></b></p></TD>";
						
                        if ($s_nivel==1)
                        {
								echo menu_usuario_admin(TD_COLOR);
                        } 
						else
						        echo menu_usuario();
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>	

<?php 
		
        $cor  = TD_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;

		
		$queryB = "SELECT count(*) from computadores";
		$resultadoB = mysql_query($queryB);
		$total = mysql_result($resultadoB,0);
				
		// Select para retornar a quantidade e percentual de equipamentos cadastrados no sistema
		$query = "SELECT count(*) as Quantidade, count(*)*100/$total as Percentual,
					 T.tipo_nome as Equipamento, T.tipo_cod as tipo
					FROM computadores as C, tipo_equip as T  
					WHERE C.comp_tipo_equip = T.tipo_cod 
					GROUP by C.comp_tipo_equip ORDER BY Equipamento";	
		
		$resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
		$row = mysql_fetch_array($resultado);  

		

#########################################################################
       print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='$cor3'>";
                
					print"<tr><td class='line'></TD></tr>";
					print"<tr><td class='line'></TD></tr>";
					print "<tr><td width=60% align=center><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><b>Controle de Garantias. Em implementação.</b></td></tr>";

  
        print "<td class='line'>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='$cor3'>";
       // print "<TR><TD bgcolor=$cor3><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><b>Equipamento</TD><TD bgcolor=$cor3><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><b>Quantidade</TD><TD bgcolor=$cor3><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><b>Percentual</TD></tr>";        
        $i=0;
        $j=2;
  
  
					
					print "</TABLE>";
				
######################################################################################
					
###############################################################################					
					
					
					print "<TABLE align=center>";
					print"<tr><td class='line'></TD></tr>";
					print"<tr><td class='line'></TD></tr>";
					print"<tr><td class='line'></TD></tr>";
					print"<tr><td class='line'></TD></tr>";

					print "<TABLE>";				


					print "<TABLE>";
					print"<tr><td class='line'></TD></tr>";
					print"<tr><td class='line'></TD></tr>";
					print"<tr><td class='line'></TD></tr>";
					print"<tr><td class='line'></TD></tr>";

					print "<tr><td width=60% align=center><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><b>Sistema em desenvolvimento pelo setor de <a href=http://www.intranet.lasalle.tche.br/cinfo/helpdesk TARGET=_blank title='Página do Helpdesk na Intranet La Salle'>Helpdesk Unilasalle</a>.</b></td></tr>";				
					print "<TABLE>";				
				

              
?>        


</BODY>
</HTML>
