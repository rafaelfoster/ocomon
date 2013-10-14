<?php 
    print "<html><head><title>Configuração de abertura de chamados</title>"; 
    
			print "<style type=\"text/css\"><!--";
			print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
            print "p{font-size:12px; text-align:justify; }";
            print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left; 
					border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
			print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";			
			print "--></STYLE>";			    
    print "</head><body class='corpo'>";
    
   

        print "<p><b>Configuração de abertura de chamados pelo usuário final:</b></p>";
		print "<p>Para a abertura de chamados funcionar adequadamente é necessário observar os seguintes pontos:</p>";
		print "<ul>";
		print "<li><p>Cadastre uma nova área de atendimento, e desmarque a opção \"Presta atendimento\". ".
				"Essa área será criada especificamente pára abertura de chamados. O e-mail dessa área não ".
				"precisa ser um e-mail válido pois não será utilizado pelo sistema.</p></li>";
		print "<li><p>Configure a área criada como \"Área de nível somente abertura\".</p></li>";
		print "<li><p>Para cadastrar usuários como somente abertura de chamados, utilize o auto-cadastro ".
				"na tela de login do sistema. Se for cadastrar manualmente cada usuário de abertura observe que o nível deve ser ".
				"definido como \"Somente abertura\" e a área deve ser a área criada para abertura de chamados sem definições de áreas secundárias.</p></li>";
		
		print "</ul>";

    print "</body></html>";

?>