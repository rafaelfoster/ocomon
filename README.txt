#
# OcoMon - versão 2.1RC1
# Data: Maio de 2013
# Autor: Flávio Ribeiro (flaviorib@gmail.com)
# Autor: Rafael Foster  (rafaelgfoster@gmail.com) /* Atual desenvolvedor da versao 2.1RC1 */
#
# Linceça: GPL
#


# Changelog
https://github.com/rafeta124/ocomon/wiki/Changelog

# Tasklist
https://github.com/rafeta124/ocomon/wiki/Tasklist

ATENÇÃO:
=========

Se você deseja instalar o OcoMon por conta própria, é necessário que saiba o que é um servidor WEB e conheça o processo genérico de instalação de sistemas WEB. Além disso, é necessário ter conhecimento mínimo em MySQL (processo de criação de banco e importação de tabelas bem como criação de usuários e permissões de acesso) e PHP.

Caso não tenha os requisitos citados, recomendo que encarregue a tarefa de instalação do OcoMon a outra pessoa que atenda os mesmos.


LEIA ESSE ARQUIVO ATÉ O FINAL!!!!!!!


REQUISITOS:
============

    * Sistema Operacional: Independente;
    * Servidor Web (preferencialmente Apache);
    * Linguagem: PHP versão:4.3x ou superior, HTML, CSS, Javascript;
    * Banco de dados: MySQL versão: 4.1x ou superior;
    * Navegador: Embora o sistema também funcione no Internet Explorer (com algumas limitações de layout), recomendo fortemente
    	a utilização do mesmo no Firefox. Os principais testes do Ocomon são realizados utilizando o Firefox pois é um navegador multi-plataforma
    	e bastante confiável. USE O OCOMON COM O FIREFOX!! :-)

Notas importantes:

    * Para o sistema funcionar adequadamente é necessário que seu navegador permita que sistema rode funções
		javascript e aceite cookies do sistema.
    * Para a visualização dos gráficos é necessário que o PHP esteja compilado com suporte à biblioteca GD;
    * Para o upload de imagens é necessário que essa propriedade esteja habilitada no arquivo de configurações do PHP (php.ini);
    * Para o envio de e-mails o Ocomon pode utilizar um SMTP especificado por você. Caso você desabilite a opção de SMTP os e-mails
    		serão enviados utilizando a função "mail" do PHP e o arquivo php.ini deve estar configurado corretamente para funcionar de
    		maneira adequada. Consulte a documentação do PHP para entender como esse processo funciona.

INSTALAÇÃO
==========

Primeira instalação:

Descompactar o pacote do OcoMon para o seu web server (o caminho do diretório público do web server varia de acordo com cada configuração, cheque essa informação junto ao administrador do seu web server).
As permissões dos arquivos podem ser as default do seu servidor, apenas o diretório /includes/logs deve ter permissão de escrita
para todos os usuários, pois é o diretório onde são gravados alguns arquivos de log do sistema.

Importar o script de criação do banco e tabelas do OcoMon:
Para a criação de toda a base do OcoMon, você precisa importar um único arquivo de instruções SQL:
o arquivo é: DB_OCOMON_2.0RC6_FULL.SQL (em ocomon/install/2.0RC6/).

Você pode executar o script à cima através do próprio mysql (seguindo o procedimento citado abaixo) ou através de algum
gerenciador gráfico como o phpMyAdmin por exemplo.

Para importar o Script diretamente pelo MySQL (linha de comando) faça da seguinte forma:
Dentro do diretório do MYSQL no seu servidor digite:
mysql -u root -p < /caminho/para/o/arquivo/DB_OCOMON_2.0RC6_FULL.SQL (o sistema irá solicitar a senha do usuário root do MySQL)

Ao importar o arquivo de intruções SQL será criado um banco com o nome "ocomon_rc6". Também será criado um usuário "ocomon" no banco de dados com a senha padrão "senha_ocomon_mysql". É recomendável alterar essa senha no MySQL após a instalação do sistema.

Caso queira que a base tenha outro nome (ao invés de "ocomon_rc6"), edite diretamente no arquivo "DB_OCOMON_2.0RC6_FULL.SQL" (3 entradas no início do arquivo) antes de realizar a importação do mesmo e não esqueça de alterar no arquivo includes/config.inc.php também (dentro do pacote do OcoMon).


Após a instalação, é recomendável a exclusão da pasta "install" dentro de ocomon/install;


ATUALIZAÇÃO:
============

Caso esteja atualizando apartir de uma versão anterior, basta sobrescrever os scripts da pasta do OcoMon pelos scripts da nova versão e importar para o MySQL o arquivo de atualização correspondente à sua versão atual. Os arquivos de atualização obedecem a seguinte nomenclatura: UPDATE-FROM{versão-anterior}-TO-{versao-final}.SQL

	Dependendo da sua versão atual, após a atualização pode ser necessário realizar alguns ajustes de configuração em função da incorporação de novas funcionalidades:

	DEFINIÇÃO DE PRIORIDADES DE ATENDIMENTO
	========================================
	1 - Ir ao menu Admin-> Ocorrências-> Prioridades de atendimento e cadastrar os tipos de prioridades de seu interesse. Ex: Urgente, Alto, Normal, Baixo.. etc.
	2 - Definir um dos tipos de prioridade criados como sendo padrão;
	3 - Na tela de cadastro de tipos de prioridades, clicar no link para atualizar os chamados antigos para o tipo de prioridade padrão do sistema;
	4 - Caso não deseje que o campo "Prioridade" apareça na tela de abertura de chamados do usuário-final, será necessário configurar esse comportamento no menu Admin -> Perfis de tela de abertura;


	DEFINIÇÃO DE QUE ÁREAS DE ATENDIMENTO PODEM ABRIR CHAMADOS ENTRE SI
	===================================================================
	Menu Admin->Ocorrências->Áreas - Configuração: Para garantir o funcionamento do sistema da forma como ocorria antes da atualização clique no botão "Marca todos" e aplique a alteração. Entenda melhor a configuração dessa funcionalidade acessando o manual do sistema.


CONFIGURAÇÃO GERAL DO SISTEMA
==============================

Todas as configurações necessárias deverão ser feitas no arquivo config.inc.php e no menu Admin->Configurações.
você não conseguirá utilizar o OCOMON até ter configurado o arquivo config.inc.php. Para isso é necessário criar uma cópia do arquivo
config.inc.php-dist e renomeá-lo para config.inc.php. Esse arquivo é responsável pela conexão com o banco de dados e sua configuração é auto-explicativa. :)


Iniciando o uso do OCOMON:

Passo a passo:

ACESSO
usuário: admin
senha: admin (Não esqueça de alterar esse senha tão logo tenha acesso ao sistema!!)

Novos usuários podem ser criados no menu ADMIN-USUÁRIOS



IMPORTANTE!!
==============

CONFIGURAÇÃO DE ABERTURA DE CHAMADOS PELO USUÁRIO FINAL:

Para a abertura de chamados funcionar adequadamente é necessário observar os seguintes pontos:

	1 - Cadastre uma nova área de atendimento, e desmarque a opção "Presta atendimento". Essa área será criada
		especificamente pára abertura de chamados. O e-mail dessa área não precisa ser um e-mail válido pois
		não será utilizado pelo sistema.

	2 - Configure a área criada como "Área de nível somente abertura".

	3 - Para cadastrar usuários como somente abertura de chamados, utilize o auto-cadastro na tela de login do sistema.
		Se for cadastrar manualmente cada usuário de abertura observe que o nível deve ser definido como "Somente abertura"
		e a área deve ser a área criada para abertura de chamados sem definições de áreas secundárias.


	AGENDAMENTO DE CHAMADOS:

	Para o controle de SLAs funcionar adequadamente, é necessário a criação de mais dois STATUS
	(menu Admin->Ocorrências->Status) específicos, um para ser utilizado automaticamente no agendamento de chamados na
	abertura dos mesmos e outro para ser utilizado automaticamente no agendamento de chamados já abertos(na edição).

	- O status a ser criado para agendamento na abertura deverá, OBRIGATORIAMENTE, ter dependência igual a "SERVIÇO DE TERCEIROS" ou
	"A ÁREA TÉCNICA".

	- O status a ser criado para agendamento na edição deverá, OBRIGATORIAMENTE, ter dependência igual a "INDEPENDENTE" ou
	"AO USUÁRIO".

	Os status criados deverão ser utilizados no menu Admin->Configurações->Agendamento de chamados



DOCUMENTAÇÃO:
=============

Toda a documentação do OcoMon está disponível no site do projeto:

- Site do projeto: http://ocomonphp.sourceforge.net/

- Manual/wiki: http://sourceforge.net/apps/mediawiki/ocomonphp/index.php?title=Main_Page

- Fórum: http://sourceforge.net/p/ocomonphp/feature-requests/

- GitHub: https://github.com/rafeta124/ocomon


Espero que esse sistema lhe seja útil e lhe ajude no seu gerenciamento de suporte e equipamentos de informática
da mesma forma que já ajuda uma série de empresas no Brasil.

Bom uso!! :)

