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
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;

                $query2 = "select * from equipamentos where comp_marca='".$_GET['marc_cod']."'";
                $resultado2 = mysql_query($query2);
                $linhas2 = mysql_numrows($resultado2);

                if ($linhas2!=0)
                {
                        echo mensagem("Existe(m) $linhas2 computador(es) cadastrado(s) com este modelo.","Não pode ser excluido até que esse(s) computador(es) seja(m) excluído(s).");
                }
                else
                {
                                $query = "DELETE FROM marcas_comp WHERE marc_cod='".$_GET['marc_cod']."'";
                                $resultado = mysql_query($query) or die('NÃO FOI POSSÍVEL EXCLUIR O REGISTRO!');

                                        $aviso = "OK. Modelo excluido com sucesso.";
                                
                                print "<script>mensagem('".$aviso."'); redirect('marcas_comp');</script>";

                   }
        ?>