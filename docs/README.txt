#
# OcoMon - versão 2.0 Alpha1
# Data: Maio de 2007
# Autor: Flávio Ribeiro (flavio@unilasalle.edu.br)
#
# Linceça: GPL
#

Requisitos:
    * Servidor Web (preferencialmente Apache);
    * Linguagem: PHP versão: >= 4.3x, HTML, CSS, Javascript;
    * Banco de dados: MySQL versão: >= 4.1x;

Notas importantes:

    * Para o sistema funcionar adequadamente é necessário que seu navegador permita que sistema rode funções
		javascript e aceite cookies do sistema.
    * Para a visualização dos gráficos é necessário que o PHP esteja compilado com suporte à biblioteca GD;
    * Para o upload de arquivos é necessário que essa propriedade esteja habilitada no arquivo de configurações do PHP (php.ini);
    * Para que o sistema envie e-mails será necessário ter um servidor SMTP interno para possibilitar essa funcionalidade;
    * Recomendo fortemente que o sistema seja utilizado com o navegador Firefox, onde o mesmo é largamente testado. Há vários problemas conhececidos relacionados ao uso do Ocomon no Internet Explorer.
    * Após instalar o sistema, é recomendado que você remova a pasta install.


Instalação (considerando que você ainda não tenha o Ocomon instalado)
==========

Copiar o diretório 'ocomon' para o seu web server (/usr/local/apache2/htdocs/ usualmente no FreeBSD ou var/www/htdocs, em sistemas Linux com Apache).
As permissões dos arquivos podem ser as default do seu servidor, apenas o diretório /includes/logs deve ter permissão de escrita
para todos os usuários, pois é o diretório onde são gravados os arquivos de log do sistema.


Criar um novo banco de dados no MySQL e nomeá-lo: 'ocomon'
Dentro do diretório do MYSQL no seu servidor digite:
mysql -u USERNAME -p create database ocomon

Para a criação das tabelas, você precisa apenas rodar um único arquivo SQL para popular a base do sistema:
o arquivo é: DB_OCOMON_2.0_FULL.sql (em ocomon/install/2.0/)

Você pode executar o script àcima através do próprio mysql (seguindo o mesmo procedimento citado abaixo) ou através de qualquer
gerenciador gráfico como o phpMyAdmin por exemplo.

Você também pode rodar o script citado da seguinte forma:
Dentro do diretório do MYSQL no seu servidor digite:
mysql -uUSERNAME -p DATABASENAME < DB_OCOMON_2.0_FULL.sql (considerando que o script está dentro do diretório do mysql)

Onde:
	USERNAME=nome do usuário "root" do MySQL
	DATABASENAME=nome do banco de dados criado para receber os dados do Ocomon (se você escolher um nome
		     diferente de "ocomon", não esqueça de alterar no arquivo includes/config.inc.php
	Você deverá digitar a senha de root para iniciar a execução dos scripts.


Atualização (considerando que você já tenha a versão 1.40 instalada em seu ambiente)
==============

A atualização da versão do Ocomon 1.40 para a versão 2.0 é bastante simples, bastando sobreescreever todos os scripts do sistema e atualizar a base de dados. Para isso, recomendo que seja feito da seguinte forma:

1 - Crie um backup do seu banco de dados do ocomon, essa é uma medida preventiva e sempre recomendada. Assim, em caso de problemas durante a atualização será possível retornar o banco no seu estágio anterior (funcionando!).

2 - Dentro do pacote 2.0 do Ocomon, há um script de atualização da base 1.40 para a base 2.0. Acesse esse arquivo em ocomon/install/2.0/UPDATE_FROM_1.40_TO_2.0.SQL (é recomendável que você importe esse arquivo utilizando o phpMyAdmin, sendo assim, nas opções de importação selecione "latin1" para o conjunto de caracteres do arquivo).

3 - Renomeie (não delete) a pasta ocomon (que está instalado no seu ambiente). Essa é uma medida de segurança caso você tenha realizado algum tipo de costumização no sistema, assim não perderá seus scripts.

4 - Descompacte o pacote do Ocomon no mesmo diretório raiz onde você tinha a versão 1.40 rodando (que você renomeou no passo anterior).

5 - Copie o arquivo config.inc.php da pasta antiga do Ocomon e cole no seu novo ocomon (ocomon/includes/).

Cuidados a serem tomados:
- Até a versão 1.40 a definição do endereço do seu site interno para acesso ao Ocomon era no arquivo config.inc.php. Na versão 2.0 essa definição deve ser feita no menu Admin->Configurações->Configurações gerais.


Configuração
============

As credenciais para conexão com o banco devem ser informadas no arquivo de configuração do Ocomon: config.inc.php
você não conseguirá utilizar o OCOMON até ter configurado esse arquivo. Para isso é necessário criar uma cópia do arquivo
config.inc.php-dist e renomeá-lo para config.inc.php. Quanto à sua configuração, o arquivo é auto-explicativo. :-)

Iniciando o uso do OCOMON (primeira instalação):

ACESSO
usuário: admin
senha: admin (Não esqueça de alterar esse senha tão logo tenha acesso ao sistema!!)

Novos usuários podem ser criados no menu ADMIN-USUÁRIOS


Infelizmente ainda não tive tempo de criar uma documentação do sistema, espero conseguir realizar essa tarefa com a ajuda da comunidade de usuários :-)

Você pode obter maiores informações através dos seguintes meios:
- Lista de discussão: http://svrmail.lasalle.tche.br/mailman/listinfo/ocomon-l
- Fórum do sistema: http://softwarelivre.unilasalle.edu.br/ocomon_forum

Espero que esse sistema lhe seja útil e lhe ajude no seu gerenciamento de suporte e equipamentos de informática
da mesma forma que nos ajuda aqui no Unilasalle.

Bom uso!! :)

Flávio Ribeiro
flavio@unilasalle.edu.br



=======================
Ocomon 2.0 Alpha1 - Know Issues
=======================

Essa versão do Ocomon é uma versão Alpha e ainda está em desenvolvimento, portanto ainda há uma série de detalhes que precisam ser ajustados até a liberação da versão 2.0 Final.

Descrevo a seguir as principais situações que precisam e deverão ser ajustadas até a versão 2.0 Final:

- Suporte a múltiplos idiomas: essa característica ainda não está 100% pois ainda existem alguns scripts que não estão com esse suporte.

- Compatibilidade com o Internet Explorer: ainda é necessário uma série de ajustes para possibilitar o correto funcionamento nesse navegador. Recomendo fortemente que seja utilizado o navegador Firefox.

- No cadastro de feriados, a opção "permanente" ainda não está sendo considerada para o cálculo de horas válidas.

- Há também uma série de detalhes menores que deverão ser ajustados até o fechamento da release 2.0.



