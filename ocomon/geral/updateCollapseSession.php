<?php /*                        Copyright 2005 Flávio Ribeiro

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

	//print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

	$imgsPath = "../../includes/imgs/";

	$auth = new auth;
	$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);


		if (isset($_GET['CHAVE2'])) {

			if (!isset($_SESSION['ICON_CHAVE2'])) {
				$_SESSION['ICON_CHAVE2']="open.png";
			}

			if (!isset($_SESSION['CHAVE2'])) {
				$_SESSION['CHAVE2'] = "{display:none}";
			} else
			if (isset($_GET['CHAVE2'])) {
				if ($_GET['CHAVE2'] == "") {
					$_SESSION['CHAVE2'] = "{display:none}";
					$_SESSION['ICON_CHAVE2']="open.png";
				} else {
					$_SESSION['CHAVE2'] = "";
					$_SESSION['ICON_CHAVE2']="close.png";
				}
			}
		} else

		if (isset($_GET['CHAVE'])) {

			if (!isset($_SESSION['ICON_CHAVE'])) {
				$_SESSION['ICON_CHAVE']="close.png";
			}

			if (!isset($_SESSION['CHAVE'])) {
				$_SESSION['CHAVE'] = "";
			} else
			if (isset($_GET['CHAVE'])) {
				if ($_GET['CHAVE'] == "{display:none}") {
					$_SESSION['CHAVE'] = "";
					$_SESSION['ICON_CHAVE']="close.png";
				} else {
					$_SESSION['CHAVE'] = "{display:none}";
					$_SESSION['ICON_CHAVE']="open.png";
				}
			}
		}



?>