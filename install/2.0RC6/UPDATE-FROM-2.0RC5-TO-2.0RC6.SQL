/*!40101 SET NAMES utf8 */;

ALTER TABLE `assentamentos` ADD `tipo_assentamento` INT( 1 ) NOT NULL DEFAULT '0' COMMENT 'Tipo do assentamento';
-- Tipo de asssentamentos:
-- 0: comentário
-- 1: descrição técnica
-- 2: solução
-- 3: justificativa em caso de estouro do SLA
ALTER TABLE `assentamentos` ADD INDEX ( `tipo_assentamento` ) ;


CREATE TABLE `sla_out` (
`out_numero` INT( 5 ) NOT NULL COMMENT 'ocorrencias',
`out_sla` INT( 1 ) NOT NULL DEFAULT '0' COMMENT 'se o sla estourou',
INDEX ( `out_numero` )
) ENGINE = MYISAM COMMENT = 'Tabela temporaria para controle do sla';

ALTER TABLE `config` ADD `conf_desc_sla_out` INT( 1 ) NOT NULL DEFAULT '0';



CREATE TABLE `areaXarea_abrechamado` (
  `area` int(4) unsigned NOT NULL COMMENT 'Área para a qual se quer abrir o chamado.',
  `area_abrechamado` int(4) unsigned NOT NULL COMMENT 'Área que pode abrir chamado.',
  PRIMARY KEY  (`area`,`area_abrechamado`),
  KEY `fk_area_abrechamado` (`area_abrechamado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


ALTER TABLE `problemas` ADD `prob_alimenta_banco_solucao` INT( 1 ) NOT NULL DEFAULT '1' COMMENT 'Flag para gravar a solucao ou nao';
ALTER TABLE `problemas` ADD `prob_descricao` TEXT NULL COMMENT 'Descricao do tipo de problema';




 CREATE TABLE `scripts` (
`scpt_id` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`scpt_nome` VARCHAR( 35 ) NOT NULL ,
`scpt_desc` VARCHAR( 100 ) NOT NULL ,
`scpt_script` TEXT NOT NULL ,
`scpt_enduser` TINYINT( 1 ) NOT NULL DEFAULT '1'
) ENGINE = MYISAM COMMENT = 'Tabela de scripts para solucoes';

 CREATE TABLE `prob_x_script` (
`prscpt_id` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`prscpt_prob_id` INT( 4 ) NOT NULL ,
`prscpt_scpt_id` INT( 4 ) NOT NULL ,
INDEX ( `prscpt_prob_id` , `prscpt_scpt_id` )
) ENGINE = MYISAM COMMENT = 'Scripts por problemas';


ALTER TABLE `config` ADD COLUMN `conf_qtd_max_anexos` INT(2)  NOT NULL DEFAULT 5 AFTER `conf_desc_sla_out`;

