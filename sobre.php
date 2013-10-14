<?php
    print "<html><head><title>Sobre o Ocomon</title>"; 
    
			print "<style type=\"text/css\"><!--";
			print "body.corpo {background-color:#F6F6F6;}";
            print "p{font-size:12px; margin-left:10%; margin-right: 10%; text-indent: 1cm; text-align:justify;}";
			print "ul{font-size:12px; margin-left:10%; margin-right: 10%; text-indent: 1cm; text-align:justify;}";
            print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left; 
					border: 0px; border-spacing:1 ;background-color:#cde5ff; padding-top:10px; }";
			print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";			
			print "--></STYLE>";			    
    print "</head><body class='corpo'>";
    
	print "<center><b>O ocomon</b></center>";
	print "<p>O Ocomon surgiu em Março de 2002 como projeto pessoal do programador Franque Custódio, tendo como características".
			" iniciais o cadastro, acompanhamento, controle e consulta de ocorrências de suporte e tendo como primeiro usuário".
			" o Centro Universitário La Salle (UNILASALLE). A apartir de então, o sistema foi assumido pelo Analista de Suporte ".
			"Flávio Ribeiro que adotou a ferramenta e desde então a tem aperfeiçoado e implementado diversas características ".
			"buscando atender a questões de ordem prática, operacional e gerencial de áreas de suporte técnico como Helpdesks e Service Desks. ".
			"Em Maio de 2003 surgiu a primeira versão do módulo de inventário (Invmon), e a partir daí e todas as informações de atendimentos ".
			"começaram as estar vinculadas ao respectivo ".
			"equipamento, acrescentando grande praticidade e valor ao sistema de atendimento.".
			" Com a percepção da necessidade crescente de informações mais relacionadas com à questão de qualidade no suporte,".
			" no início de 2004 foram adicionadas características de gerenciamento de SLAs, mudando de ".
			"forma sensível a maneira como o gerenciamento de chamados vinha acontecendo e obtendo crescente melhoria da qualidade".
			" final de acordo com os indicadores fixados para os serviços realizados.</p>";
			
	print "<p>Hoje é possível responder questões como:</p>";
	print "<p><ul><li>volume de chamados por período;</li>".
			"<li>tempo médio de resposta e solução para os chamados;</li>".
			"<li>percentual de chamados atendidos e resolvidos dentro do SLA;</li>".
			"<li>tempo dos chamados decomposto em cada status de atendimento;</li>".
			"<li>usuários mais ativos;</li>".
			"<li>principais problemas;</li>".
			"<li>reincidência de chamados por equipamento;</li>".
			"<li>estado real do parque de equipamentos;</li>".
			"<li>como e onde estão distribuídos os equipamentos;</li>".
			"<li>vencimento das garantias dos equipamentos;</li>".
			"além de uma série outras questões pertinentes à gerência pró-ativa do setor de suporte.</ul></p>";
	print "<p>No início de 2005, os dois sistemas: Ocomon e Invmon foram finalmente 100% integrados ganhando um novo ".
			"layout e permancendo com o nome único de OCOMON. Tendo então sua utilização baseada em dois ".
			"módulos principais: ".
			"<ul><li>Módulo de Ocorrências;</li>".
			"<li>Módulo de Inventário;</li></ul>".
			"</p>";

	print "<p>Principais funções do módulo de <b>ocorrências:</b></p>";
	print "<ul><li>abertura de chamados de suporte por área de competência;</li>".
			"<li>vínculo do chamado com a etiqueta de patrimônio do equipamento;</li>".
			"<li>busca rápida de informações referentes ao equipamento ".
			"(configuração, localização, histórico de chamados, garantia..) no momento da abertura do chamado;</li>".
			"<li>envio automático de e-mail para as áreas de competência;</li>".
			"<li>acompanhamento do andamento do processo de atendimento das ocorrências;</li>".
			"<li>encerramento das ocorrências;</li>".
			"<li>controle de horas válidas;</li>".
			"<li>definições de níveis de prioridades para os setores da empresa;</li>".
			"<li>gerenciamento de tempo de resposta baseado nas definições de prioridades dos setores;</li>".
			"<li>gerenciamento de tempo de solução baseado nas definições de categorias de problemas;</li>".
			"<li>controle de dependências para o andamento do chamado;</li>".
			"<li>base de conhecimento;</li>".
			"<li>consultas personalizadas;</li>".
			"<li>relatórios gerenciais;</li>".
			"<li>controle de SLAs;</li></ul>";
	
	print "<p>Principais funções do <b>módulo de inventário:</b></p>";
	print "<ul><li>cadastro detalhado das informações (configuração) de hardware do equipamento;</li>".
			"<li>cadastro de informações contábeis do equipamento (valor, centro de custo,localização, reitoria, fornecedor..);</li>".
			"<li>cadastro de modelos de configuração para carga rápida de informações de novos equipamentos;</li>".
			"<li>cadastro de documentações relacionadas aos equipamentos (manuais, termos de garantia, mídias..);</li>".
			"<li>controle de garantias dos equipamentos;</li>".
			"<li>histórico de mudanças (de localidades) dos equipamentos;</li>".
			"<li>controle de licenças de softwares;</li>".
			"<li>busca rápida das informações de chamados de suporte para o equipamento;</li>".
			"<li>busca rápida de informações dos equipamentos;</li>".
			"<li>buscas por histórico de mudanças (localização);</li>".
			"<li>consultas personalizadas;</li>".
			"<li>estatísticas técnicas e gerenciais do parque de equipamentos;</li>".
			"<li>relatórios gerenciais; </li></ul>";

	print "<p><b>Questões técnicas:</b></p>";
	print "<p>O Ocomon foi concebido sob a visão de software opensource sob o modelo GPL de licenciamento, ".
			"utilizando tecnologias e ferramentas livres para o seu desenvolvimento e manutenção.</p>";
	print "<p>Abaixo listo as principais questões técnicas do sistema:</p>";
	print "<ul><li>Linguagem: PHP versão: a partir da 4.3x até a 5x , HTML, CSS, Javascript;</li>".
			"<li>Banco de dados: MySQL versão: A partir da 4.1x;</li>".
			"<li>Autenticação de usuários: a autenticação de usuários pode ser feita tanto ".
			"na própria base do sistema quanto através de uma base LDAP em algum ponto da rede.</li></ul>";
			
	print "<p>Novas funcionalidades têm sido acrescentadas ao sistema ao longo do tempo e o objetivo é torná-lo cada ".
		"vez mais aderente às boas práticas relacionadas tanto à operacionalização quanto à gestão de áreas de atendimento técnico.".
			"";
	print "<p>Atenciosamente</p><p><a href='mailto:flaviorib@gmail.com'>Flávio Ribeiro</a></p>";		
			
    print "</body></html>";

?>