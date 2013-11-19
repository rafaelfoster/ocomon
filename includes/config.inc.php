<?php

// arquivo: config.inc.php

// configurar de acordo com as suas necessidades

// Configurações vitais

//Usuario do banco
define ( "SQL_USER", "root"); //NOME DO USUÁRIO PARA ACESSAR A BASE DO OCOMON - ESSE USUÁRIO É CRIADO AUTOMATICAMENTE NA INSTALACAO DO SISTEMA!!

//Senha do banco
define ( "SQL_PASSWD", "12345678");//ESSA SENHA É A SENHA PADRAO CRIADA NA INSTALACAO DO SISTEMA. É RECOMENDÁVEL A ALTERACAO DA MESMA NO MYSQL.
//Servidor do banco
define ( "SQL_SERVER", "localhost"); //SE O BANCO DE DADOS ESTIVER EM UM SERVIDOR DIFERENTE DO SERVIDOR WEB DEVE-SE ALTERAR O VALOR "localhost"
//Nome do banco
define ( "SQL_DB", "ocomon_rc6");//NOME DO BANCO DE DADOS. O PADRÃO DESSA VERSÃO É: ocomon_rc6


define ( "DB_CCUSTO","ocomon_rc6"); //Base de dados onde são buscados os Centros de Custos, o padrão dessa versão é: ocomon_rc6.
define ( "TB_CCUSTO","CCUSTO"); //Tabela de CEntro de custos dentro da base de dados - padrão: CCUSTO
define ( "CCUSTO_ID","codigo");//Chave primária da tabela de centros de custo
define ( "CCUSTO_DESC","descricao"); //Campo referente à descrição do Centro de Custo
define ( "CCUSTO_COD","codccusto"); //Campo referente ao código de Centro de Custo


define ("INST_TERCEIRA", "-1"); //Define que UNIDADES  não devem aparecer na estatística geral da tela de abertura - o código deve ser extraido da tabela: INSTITUICOES. 
									//Por padrão todas as unidades aparecem na estatística inicial.

define ( "LOG_PATH", "../../logs/logs.txt"); //Esse diretório deve ter permissão de escrita pra gravar os logs.
define ( "LOGO_PATH", "../../includes/logos");
define ( "ICONS_PATH", "../../includes/icons/");

define ( "HELP_ICON", "".ICONS_PATH."solucoes2.png");
define ( "HELP_PATH", "../../includes/help/");

define ( "LANGUAGE", "pt_BR.php");
//define ( "LANGUAGE", "en.php");


//Define o tipo de autenticação do sistema , por padrão a altenticação é feita na tabela de usuários do próprio OCOMON, porém isso pode ser feito através de um serviço LDAP.
//INICIALMENTE É NECESSÁRIO DEIXAR A AUTENTICAÇÃO LOCAL PARA QUE SEJA POSSÍVEL ACESSAR O SISTEMA PELA PRIMEIRA VEZ E CRIAR OS USUÁRIOS.
define ( "AUTH_TYPE" , "SYSTEM"); //DEFAULT
//define ( "AUTH_TYPE", "LDAP"); // ALTERNATIVE

/* Variáveis para conexão LDAP   DEVEM SER CONFIGURADAS EM CASO DE AUTH_TYPE==LDAP   DE ACORDO COM  AS CONFIGURAÇÕES DO SEU SERVIDOR LDAP*/
define ( "LDAP_HOST", "localhost"); //IP do servidor LDAP
define ( "LDAP_DOMAIN", "ou=People,dc=yourdomain,dc=edu,dc=br");
define ( "LDAP_DOMAIN_SEC", "ou=People,dc=yourdomain1,dc=yourdomain2,dc=edu,dc=br"); //Segundo domínio LDAP
define ( "LDAP_DN", "cn=admin,dc=yourdomain,dc=edu,dc=br");
define ( "LDAP_PASSWORD", "");


		//CARGA HORÁRIA DE CADA AREA DE ATENDIMENTO - OS PARÂMETROS SÃO: HORA DE INICIO, HORA DE FIM...
		//... FINAL DO INTERVALO, E CARGA DE TRABALHO NO SÁBADO!!
        $H_default = array (8,22,13,4);  //AQUI A JORNADA É: DAS 8:00 ÀS 22:00 , HORA FINAL DE INTERVALO: 13:00 E SÁBADO SÃO TRABALHADAS 4 HORAS

		$H_horarios = array (1=>$H_default);//PARA CADA ÁREA DE ATENDIMENTO QUE TIVER HORÁRIO DE ATENDIMENTO DIVERENTE DO DEFAULT...
						//...DEVE SER CRIADA UMA ENTRADA NO ARRAY H_horarios
						/*EX:
							$H_novaArea = array (10,18,13,0); AQUI O HORÁRIO DE INÍCIO É 10:00 E O FINAL DE EXPEDIENTE É 18:00 
							SEM TRABALHO NO SÁBADO (vida boa..)  :)
							$H_horarios = array (1=>$H_default, 2=>$H_novaArea);
						*/



?>
