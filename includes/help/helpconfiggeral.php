<?
print "<html><head><title>Configuração do sistema</title>";

	print "<style type=\"text/css\"><!--";
	print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
	print "p{font-size:12px; text-align:justify; }";
	print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left;
		border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
	print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";
	print "--></STYLE>";
	print "</head><body class='corpo'>";

	print "AJUDA DO OCOMON - CONFIGURAÇÕES GERAIS";

	print "<p><b>Site para acesso ao Ocomon:</b></p>";
		print "<ul>";
		print "<li><p>Configure com o endereço para acesso local ao Ocomon. Esse endereço será utilizado pelas ".
				"variáveis de ambiente do sistema. Exemplo: \"http://sua_intranet/ocomon\". Não coloque o sinal \"/\" no ".
				"final do endereço!</p></li>";
		print "</ul>";

	print "<p><b>Registros por página:</b></p>";
		print "<ul>";
		print "<li><p>Quantidade de registros a serem exibidos nas telas que possuem botões de navegação. ".
				"Por padrão, 50 registros são exibidos.</p></li>";
		print "</ul>";

	print "<p><b>Configuração de Upload de imagens nos chamados:</b></p>";
		print "<ul>";
		print "<li><p>TAMANHO MÁXIMO: É o tamanho máximo (em bytes) do arquivo de imagem a ser feito o upload;</p></li>";
		print "<li><p>LARGURA MÁXIMA: É a largura máxima (em pixels) permitida para a imagem a ser feito o upload;</p></li>";
		print "<li><p>ALTURA MÁXIMA: É a altura máxima (em pixels) permitida para a imagem a ser feito o upload;</p></li>";
		print "</ul>";

	print "<p><b>Barra de formatação de texto:</b></p>";
		print "<ul>";
		print "<li><p>Permite a utilização de uma barra de formação para a edição de textos no mural de avisos e/ou nas telas ".
				"de edição de ocorrências:</p> <img src='./img/toolbar.png'></li>";
		print "</ul>";

	print "<p><b>Categorias de problemas:</b></p>";
		print "<ul>";
		print "<li><p>É possível criar até 3 tipos de categorias para os tipos de problemas existentes no Ocomon. ".
				"Esse tipo de classificação facilita o agrupamento dos chamados por até 3 critérios distintos. ".
				"Exemplo: Posso definir a categoria 1 quanto ao tipo de manutenção: PREVENTIVA OU CORRETIVA.".
				" Posso definir a categoria 2 quanto ao objeto de atendimento: HARDWARE OU SOFTWARE. Etc...<br>".
				"Nessa tela você apenas irá denominar cada uma das categorias a serem utilizadas. ".
				"Para criar os tipos dentro de cada categoria acesse: menu Admin->Ocorrências->Problemas->Novo->Gerenciar</p></li>";
		print "</ul>";

	print "<p><b>Aparência:</b></p>";
		print "<ul>";
		print "<li><p>COR DA SELEÇÃO DE LINHAS: É a cor que destaca cada linha de registro quando o cursor do mouse está sobre ela. ".
					"Você pode selecionar uma cor clicando no ícone de lápis ou digitar diretamente o código da cor.".
			"</li>";
		print "<li><p>COR DA MARCAÇÃO DAS LINHAS: É a cor que destaca cada linha de registro quando é clicado em qualquer".
				" área da linha do registro. ".
				"Você pode selecionar uma cor clicando no ícone de lápis ou digitar diretamente o código da cor.</li>";
		print "</ul>";


print "</body></html>";

?>