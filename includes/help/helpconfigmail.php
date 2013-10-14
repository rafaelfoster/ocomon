<?
    print "<html><head><title>Configuração global para envio de e-mails</title>"; 
    
			print "<style type=\"text/css\"><!--";
			print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
            print "p{font-size:12px; text-align:justify; }";
            print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left; 
					border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
			print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";			
			print "--></STYLE>";			    
    print "</head><body class='corpo'>";
    
   

        print "<p><b>Configuração global para envio de e-mails pelo sistema</b></p>";
		print "<p>Nessa tela você poderá configurar as opções globais para envio de e-mail pelo sistema Ocomon, as opções são:</p>";
		print "<ul>";
		print "<li><p>Utiliza SMTP: essa opção vem marcada por default, isso significa que os e-mails enviados pelo ".
					"sistema utilizarão o endereço SMTP especificado por você. Caso você desabilite essa opção os e-mails serão ".
					"enviados utilizando a função \"mail\" do PHP e o arquivo php.ini deve estar configurado corretamente para ".
					"funcionar de maneira adequada.</p></li>";
		print "<li><p>Endereço SMTP: aqui você deve especificar o endereço SMTP que deverá ser utilizado para o envio das ".
					"mensagens do sistema se a opção \"Utiliza SMTP\" estiver habilitada.</p></li>";
		print "<li><p>Precisa de autenticação: se o seu servidor de e-mail requerer autenticação para envio de mensagens ".
					"você deve habilitar essa opção aqui.</p></li>";
		print "<li><p>Usuário: usuário válido para autenticação de envio de e-mail pelo SMTP definido por você. Também ".
					"é necessário digitar a senha para a autenticação.</p></li>";
		print "<li><p>Endereço de envio(FROM): endereço que aparecerá como remetente das mensagens enviadas pelo sistema.</p></li>";
		print "<li><p>Conteúdo HTML: se essa opção estiver habilitada, o sistema aceitará o envio de mensagens no formato HTML, ".
					"do contrário apenas mensagens texto serão enviadas.</p></li>";
		
		print "</ul>";

    print "</body></html>";

?>