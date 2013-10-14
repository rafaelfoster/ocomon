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
  */session_start();
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

print "<HTML>";
print "<BODY bgcolor='".BODY_COLOR."'>";


print "<BR>";
	//'#C7C8C6'
	print "<TABLE bgcolor='#EFEFE7' STYLE='".
			"{border-bottom:  solid #999999; border-top:  thin solid #999999; border-left:thin  solid #999999; border-right: thin solid #999999;}' ".
			"cellspacing='1' border='0' cellpadding='1' align='center' width='320'>".//#5E515B
			"<TR>".
			"<TD align='center'><b>".$_SESSION['aviso']."</b></td>".
                	"</TR>".
			"<tr><TD align='center'><a href='".$_SESSION['origem']."'><b>Voltar</b></a></td></tr>";
	print "</TABLE>";


print "</BODY>";
print "</HTML>";
?>
