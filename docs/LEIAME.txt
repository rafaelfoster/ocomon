#
# OcoMon - versão 2.0RC4
# Data: Fevereiro de 2008
# Autor: Flávio Ribeiro (flaviorib@gmail.com)
#
# Linceça: GPL
#


ATENÇÃO:
=========

Se você deseja instalar o OcoMon por conta própria, é necessário que saiba o que é um servidor WEB e conheça o processo genérico de instalação de sistemas WEB. Além disso, é necessário ter conhecimento mínimo em MySQL (processo de criação de banco e importação de tabelas bem como criação de usuários e permissões de acesso) e PHP.

Caso não tenha os requisitos citados, recomendo que encarregue a tarefa de instalação do OcoMon a outra pessoa que atenda os mesmos.


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
    		maneira adequada.

INSTALAÇÃO
==========

Primeira instalação:

Descompactar o arquivo do OcoMon para o seu web server (/usr/local/apache2/htdocs/ usualmente no FreeBSD ou var/www/html, em sistemas Linux com Apache).
As permissões dos arquivos podem ser as default do seu servidor, apenas o diretório /includes/logs deve ter permissão de escrita
para todos os usuários, pois é o diretório onde são gravados alguns arquivos de log do sistema.

Criar um novo banco de dados no MySQL e nomeá-lo: 'ocomon' (ou qualquer ou nome sugestivo). É recomendável a criação de um usuário
específico, no banco de dados, para manipulação da base do Ocomon.

Ex:
GRANT USAGE ON * . * TO 'ocomon_user'@'localhost' IDENTIFIED BY 'senha' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 ;
GRANT SELECT , INSERT , UPDATE , DELETE ON `base_ocomon` . * TO 'ocomon_user'@'localhost';

Dentro do diretório do MYSQL no seu servidor digite:
mysql -u USERNAME -p create database ocomon

Para a criação das tabelas, você precisa apenas rodar um único arquivo SQL para popular a base do sistema:
o arquivo é: DB_OCOMON_2.0RC4_FULL.SQL (em ocomon/install/2.0RC4/)

Você pode executar o script acima através do próprio mysql (seguindo o mesmo procedimento citado abaixo) ou através de algum
gerenciador gráfico como o phpMyAdmin por exemplo.

Você também pode rodar o script citado da seguinte forma:
Dentro do diretório do MYSQL no seu servidor digite:
mysql -uUSERNAME -p DATABASENAME < DB_OCOMON_2.0RC4_FULL.SQL (considerando que o script está dentro do diretório do mysql)

Onde:
	USERNAME=nome do usuário "root" do MySQL
	DATABASENAME=nome do banco de dados criado para receber os dados do Ocomon (se você escolher um nome
		     diferente de "ocomon", não esqueça de alterar no arquivo includes/config.inc.php
	Você deverá digitar a senha de root para iniciar a execução dos scripts.


Após a instalação, é recomendável a exclusão da pasta "install" dentro de ocomon/install;


ATUALIZAÇÃO:
============

Caso esteja atualizando apartir de uma versão anterior, basta sobrescrever os scripts da pasta do OcoMon pelos scripts da nova versão e importar para o MySQL o arquivo de atualização correspondente à sua versão atual. Os arquivos de atualização obedecem a seguinte nomenclatura: UPDATE-FROM{versão-anterior}-TO-{versao-final}.SQL


CONFIGURAÇÃO
============

Todas as configurações necessárias deverao ser feitas no arquivo config.inc.php e no menu Admin->Configurações.
você não conseguirá utilizar o OCOMON até ter configurado o arquivo config.inc.php. Para isso é necessário criar uma cópia do arquivo
config.inc.php-dist e renomeá-lo para config.inc.php. Quanto à sua configuração, o arquivo é auto-explicativo. :)

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

- Manual/wiki: http://ocomonphp.wiki.sourceforge.net/manual

- Fórum: http://softwarelivre.unilasalle.edu.br/ocomon_forum

- Lista de discussões: http://svrmail.lasalle.tche.br/mailman/listinfo/ocomon-l



Espero que esse sistema lhe seja útil e lhe ajude no seu gerenciamento de suporte e equipamentos de informática
da mesma forma que já ajuda uma série de empresas no Brasil.

Bom uso!! :)

Flávio Ribeiro
flaviorib@gmail.com

