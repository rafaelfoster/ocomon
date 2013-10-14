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


	//session_register("s_logado");


	
	if ($_SESSION['s_logado']==0 ) 
	{
	        print "<script>window.open('../../index.php','_parent','')</script>";
	}
	else
	{
	        
		$conec = new conexao;
		$conec->conecta('MYSQL');
	
	}
?>
