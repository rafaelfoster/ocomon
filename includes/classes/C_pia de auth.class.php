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


class auth {
	var $saida;
	var $texto;
	var $time;
	

	
	function testa_user($s_usuario, $s_nivel, $s_nivel_desc, $permissao){
	include ("../../includes/languages/".LANGUAGE."");		
		if ($s_nivel>$permissao) //se o nível do usuário for maior do que a permissão necessária para o script..
		{
		        $this->saida= "<script>window.open('../../index.php','_parent','')</script>";
		} else {
			if (is_file( "./.invmon_dir" )) $this->texto = $TRANS["menu_title"]; 
			else $this->texto = $TRANS["menu_title_ocomon"]; 
			$this->saida =  "<TABLE class='header'>".
		        		"<tr>".
						"<td class='line'>".
		                "<TABLE class=menu>".
		                        "<TR>".
		                        //"<td class='line'><b>".$TRANS["menu_title"]."".  
								"<td class='line'><b>".$this->texto."".  
								"</b></td><td width='25%' nowrap>".
								"<p class='parag'><b>".transvars(date ("l d/m/Y H:i"),$TRANS_WEEK)."</b></p></TD>";
								
		                        if ($s_nivel==1)
		                        
								{
										$this->saida.= menu_usuario_admin();
										
		                        } 
								else
								        $this->saida.= menu_usuario();
		                        
		                        $this->saida.= "</TR>
		                	</TABLE>
		        		</TD>
					</tr>
					</TABLE>";	
	
		}
		print $this->saida;

	}

	function testa_user_hidden($s_usuario, $s_nivel, $s_nivel_desc, $permissao){
	
	include ("../../includes/languages/".LANGUAGE."");		
		if ($s_nivel>$permissao)
		{
		        $this->saida= "<script>window.open('../../index.php','_parent','')</script>";
		} else {
			if (is_file( "./.invmon_dir" )) $this->texto = $TRANS["menu_title"]; 
			else $this->texto = $TRANS["menu_title_ocomon"]; 
			$this->saida =  "<TABLE class=header>
		        		<tr class=topo>
						<td class='line'>
		                <TABLE class=menu>
		                        <TR class=topo>
		                        <td class='line'><b>".$this->texto."  -  ".$TRANS["usuario"].": 
								<font color='red'><a title='".$TRANS["hint_usuario"]."'>$s_usuario</a></font></b></td><td width=25%>
								<b>".$TRANS["nivel"].": <font color='red'><a title='".$TRANS["hint_nivel"]."'>".$s_nivel_desc."</a></font></b></TD>";
								
		                        
		                        $this->saida.= "</TR>
		                	</TABLE>
		        		</TD>
					</tr>
					</TABLE>";	
	
		}
		print $this->saida;
	}

}
?>