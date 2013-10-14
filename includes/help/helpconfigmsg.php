<?php 
    print "<html><head><title>Configuração de abertura de chamados</title>"; 
    
			print "<style type=\"text/css\"><!--";
			print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
            print "p{font-size:12px; text-align:left; }";
            print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:justify; 
					border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
			print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";			
			print "--></STYLE>";			    
    print "</head><body class='corpo'>";
    
   

        print "<p><b>Configuração de mensagens para envio de e-mail pelo Ocomon:</b></p>";
		print "<p>Você pode customizar as mensagens de e-mail enviadas pelo Ocomon em qualquer um dos eventos adequados do sistema:</p>";
		print "<p>Os eventos possíveis são:</p>";
		print "<ul>";
		print "<li><p><b>abertura-para-usuario:</b> E-mail enviado para o usuário-final no momento em que um chamado é aberto no sistema;</p></li>";
		print "<li><p><b>abertura-para-area:</b> E-mail enviado para a área de atendimento no momento em que um chamado é aberto no sistema;</p></li>";
		print "<li><p><b>encerra-para-area:</b> E-mail enviado para a área de atendimento no momento em que o um chamado é encerrado no sistema;</p></li>";
		print "<li><p><b>encerra-para-usuario:</b> E-mail enviado para o usuário-final no momento em que o um chamado é encerrado no sistema;</p></li>";
		print "<li><p><b>edita-para-area:</b> E-mail enviado para a área de atendimento no momento em que o um chamado é editado no sistema;</p></li>";
		print "<li><p><b>edita-para-usuario:</b> E-mail enviado para o usuário-final no momento em que o um chamado é editado no sistema;</p></li>";
		print "<li><p><b>edita-para-operador:</b> E-mail enviado para o operador técnico no momento em que o um chamado é editado no sistema;</p></li>";
		print "<li><p><b>cadastro-usuario:</b> E-mail enviado para o usuário-final para confirmação de cadastro para abertura de chamados no sistema.</p></li>";
		print "<li><p><b>cadastro-usuario-from-admin:</b> E-mail enviado para o usuário-final para confirmação de cadastro quando o cadastro for confirmado diretamente através da interface administrativa do sistema.</p></li>";
		print "</ul>";
		print "<br>";
		print "<p>As opções de configuração são:</p>";
		print "<ul>";
		print "<li><p><b>FROM:</b> Será o \"name\" do endereço de e-mail que aparecerá como remetente da mensagem;</p></li>";
		print "<li><p><b>Responder para:</b> endereço de resposta do e-mail;</p></li>";
		print "<li><p><b>Assunto:</b> será o campo \"assunto\" da mensagem enviada;</p></li>";
		print "<li><p><b>Mensagem HTML:</b> texto que será enviado nas mensagens de e-mail se a opção de conteúdo HTML estiver habilitada; </p></li>";
		print "<li><p><b>Mensagem alternativa:</b> texto que será enviado nas mensagens de e-mail se a opção de conteúdo HTML estiver desabilitada.</p></li>";
		print "</ul>";
		print "<br>";

		print "<p>Você pode utilizar variáveis de ambiente para customizar as mensagens de e-mail:</p>";
		print "<p>As variáveis possíveis são:</p>";
		print "<ul>";
		print "<li><p><b>%area%</b>: área técnica para atendimento do chamado;</p></li>";
		print "<li><p><b>%assentamento%</b>: assentamento definido durante uma edição do chamado;</p></li>";
		print "<li><p><b>%contato%</b>: campo contato;</p></li>";
		print "<li><p><b>%descricao%</b>: campo descrição do chamado;</p></li>";
		print "<li><p><b>%editor%</b>: usuário logado que está editando um chamado;</p></li>";
		print "<li><p><b>%linkconfirma%</b>: link para confirmação de cadastro de usuário somente abertura;</p></li>";
		print "<li><p><b>%login%</b>: só tem valor se for utilizado na mensagem de confirmação de cadastro;</p></li>";
		print "<li><p><b>%numero%</b>: número do chamado;</p></li>";
		print "<li><p><b>%operador%</b>: operador técnico do chamado;</p></li>";
		print "<li><p><b>%problema%</b>: problema classificado para o chamado;</p></li>";
		print "<li><p><b>%ramal%</b>: telefone de contato do usuário que solicitou a abertura do chamado;</p></li>";
		print "<li><p><b>%setor%</b>: local/departamento do usuário que solicitou a abertura do chamado;</p></li>";
		print "<li><p><b>%site%</b>: endereço do sistema Ocomon na sua empresa (definido no arquivo config.inc.php);</p></li>";
		print "<li><p><b>%solucao%</b>: solução adotada para o chamado;</p></li>";
		print "<li><p><b>%usuario%</b>: dependendo do evento será o próprio usuário-final que abriu o chamado;</p></li>";
		print "<li><p><b>%versao%</b>: versão do Ocomon.</p></li>";
		print "</ul>";
		
		
    print "</body></html>";

?>