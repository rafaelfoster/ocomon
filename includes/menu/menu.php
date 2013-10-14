<?php


	
	
	
function menu_usuario()
{
	include ("../../includes/languages/".LANGUAGE."");	
	return "";
}






function menu_usuario_admin() //admin
{
	include ("../../includes/languages/".LANGUAGE."");
	return "";


	//return $saída;
}

	function montaMenu () {
			$myDirPath = '../includes/menu/phplm320/';
			$myWwwPath = '../includes/menu/phplm320/';
			?>
			
			<link rel="stylesheet" href="<?php print $myWwwPath; ?>invmon-layersmenu-demo.css" type="text/css"></link>
			<link rel="stylesheet" href="<?php print $myWwwPath; ?>invmon-layersmenu-gtk2.css" type="text/css"></link>
			<link rel="shortcut icon" href="<?php print $myWwwPath; ?>LOGOS/shortcut_icon_phplm.png"></link>
			<script language="JavaScript" type="text/javascript">
			<!--
			<?php require_once $myDirPath . 'libjs/layersmenu-browser_detection.js'; ?>
			// -->
			</script>
			<script language="JavaScript" type="text/javascript" src="<?php print $myWwwPath; ?>libjs/layersmenu-library.js"></script>
			<script language="JavaScript" type="text/javascript" src="<?php print $myWwwPath; ?>libjs/layersmenu.js"></script>
			
			<?php
			require_once $myDirPath . 'lib/PHPLIB.php';
			require_once $myDirPath . 'lib/layersmenu-common.inc.php';
			require_once $myDirPath . 'lib/layersmenu.inc.php';
			
			$mid = new LayersMenu(6, 7, 2, 1);	// Gtk2-like
			
			$menuInvmon =
			".|Iniciar|abertura.php|Tela inicial do Sistema\n".
			".|Cadastrar\n".
			"..|Equipamento\n".
			"..|Documento\n".
			"..|Item de estoque\n".
			"..|Local\n".
			"..|Usuário\n".
			".|Visualizar\n".
			"..|Equipamentos\n".
			"..|Documentos\n".
			"..|Estoque\n".
			".|Consultar\n".
			"..|Consulta rápida\n".
			"..|Consulta Especial\n".
			"..|Histórico\n".
			"...|Por Etiqueta\n".
			"...|Localização anterior\n".
			".|Estatíticas e Relatórios\n".
			".|Senha\n".
			".|Admin\n".
			"..|Componentes\n".
			"...|CD-Rom\n".
			"...|DVD\n".
			"...|Gravador\n".
			"...|HD\n".
			"...|Placa mãe\n".
			"...|Memória\n".
			"...|Placa de modem\n".
			"...|Processador\n".
			"...|Placa de rede\n".
			"...|Placa de som\n".
			"...|Vídeo\n".
			"..|Fabricantes\n".
			"..|Fornecedores\n".
			"..|Softwares\n".
			"..|Configurações\n".
			"..|Estoque\n".
			".|Exemplo\n".
			"..|Submenu\n".
			"...|submenu\n".
			"....|Item|link|Title ou Hint|invmon-favicon.ico|Target\n".
			"....|---\n".
			"....|Item|link|Title ou Hint|invmon-favicon.ico|Target\n";
			
			/* TO USE ABSOLUTE PATHS: */
			$mid->setDirroot($myDirPath);
			$mid->setLibjsdir($myDirPath . 'libjs/');
			$mid->setImgdir($myDirPath . 'menuimages/');
			$mid->setImgwww($myWwwPath . 'menuimages/');
			$mid->setIcondir($myDirPath . 'menuicons/');
			$mid->setIconwww($myWwwPath . 'menuicons/');
			$mid->setTpldir($myDirPath . 'templates/');
			$mid->setHorizontalMenuTpl('layersmenu-horizontal_menu.ihtml');
			$mid->setSubMenuTpl('layersmenu-sub_menu.ihtml');
			
			$mid->setDownArrowImg('down-arrow-blue.png');
			$mid->setForwardArrowImg('forward-arrow-blue.png');
			$mid->setMenuStructureString($menuInvmon);
			
			$mid->setIconsize(16, 16);
			$mid->parseStructureForMenu('menuHorizontal1');
			$mid->newHorizontalMenu('menuHorizontal1');
			
			$mid->printHeader();
			$mid->printMenu('menuHorizontal1');
			$mid->printFooter();
		}

 //montaMenu();
?>