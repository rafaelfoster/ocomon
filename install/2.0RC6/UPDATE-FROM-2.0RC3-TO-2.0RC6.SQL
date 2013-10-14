/*!40101 SET NAMES utf8 */;


ALTER TABLE `assentamentos` CHANGE `responsavelbkp` `asset_privated` TINYINT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `configusercall` ADD `conf_ownarea_2` VARCHAR( 200 ) NULL AFTER `conf_ownarea` ;

update configusercall set conf_ownarea_2 = conf_ownarea;

ALTER TABLE `configusercall` ADD `conf_name` VARCHAR( 50 ) NULL DEFAULT 'Default' AFTER `conf_cod` ;

ALTER TABLE `sistemas` ADD `sis_screen` INT( 2 ) NULL ;

ALTER TABLE `sistemas` ADD INDEX ( `sis_screen` ) ;


CREATE TABLE `uprefs` (
`upref_uid` INT( 4 ) NOT NULL ,
`upref_lang` VARCHAR( 50 ) NULL ,
PRIMARY KEY ( `upref_uid` ) ,
INDEX ( `upref_lang` )
) ENGINE = MYISAM COMMENT = 'Tabela de preferencias diversas dos usuarios';


CREATE TABLE `global_tickets` (
`gt_ticket` INT( 6 ) NOT NULL ,
`gt_id` VARCHAR( 200 ) NOT NULL ,
PRIMARY KEY ( `gt_ticket` ) ,
INDEX ( `gt_id` )
) ENGINE = MYISAM COMMENT = 'tabela para permitir acesso global as ocorrencias';


ALTER TABLE `utmp_usuarios` CHANGE `utmp_login` `utmp_login` VARCHAR( 100 ) NOT NULL;

ALTER TABLE `ocorrencias` ADD `date_first_queued` DATETIME NULL ;


INSERT INTO `temas` (`tm_nome`, `tm_color_destaca`, `tm_color_marca`, `tm_color_lin_par`, `tm_color_lin_impar`, `tm_color_body`, `tm_color_td`, `tm_borda_width`, `tm_borda_color`, `tm_tr_header`, `tm_color_topo`, `tm_color_topo_font`, `tm_color_barra`, `tm_color_menu`, `tm_color_barra_font`, `tm_color_barra_hover`, `tm_barra_fundo_destaque`, `tm_barra_fonte_destaque`, `tm_color_font_tr_header`, `tm_color_borda_header_centro`) VALUES
('black_edition', '#CCCCCC', '#FFFFCC', '#E3E1E1', '#F6F6F6', '#FFFFFF', '#999999', 2, '#FFFFFF', '#999999', '#000000', '#FFFFFF', '#000000', 'IMG_DEFAULT', '#FFFFFF', '#000000', '#FFFFFF', '#000000', '#000000', '#FF0000');




-- INÍCIO DA VERSÃO 2.0RC5 -------------------


 CREATE TABLE `prior_nivel` (
`prn_cod` INT( 2 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`prn_level` INT( 2 ) NOT NULL ,
`prn_used` TINYINT( 1 ) NOT NULL DEFAULT '0',
INDEX ( `prn_level` )
) ENGINE = MYISAM COMMENT = 'Niveis sequenciais para ordem de atendimento';

INSERT INTO `prior_nivel` (`prn_cod`, `prn_level`, `prn_used`) VALUES(1, 1, 0);
INSERT INTO `prior_nivel` (`prn_cod`, `prn_level`, `prn_used`) VALUES(2, 2, 0);
INSERT INTO `prior_nivel` (`prn_cod`, `prn_level`, `prn_used`) VALUES(3, 3, 0);
INSERT INTO `prior_nivel` (`prn_cod`, `prn_level`, `prn_used`) VALUES(4, 4, 0);
INSERT INTO `prior_nivel` (`prn_cod`, `prn_level`, `prn_used`) VALUES(5, 5, 0);
INSERT INTO `prior_nivel` (`prn_cod`, `prn_level`, `prn_used`) VALUES(6, 6, 0);
INSERT INTO `prior_nivel` (`prn_cod`, `prn_level`, `prn_used`) VALUES(7, 7, 0);
INSERT INTO `prior_nivel` (`prn_cod`, `prn_level`, `prn_used`) VALUES(8, 8, 0);
INSERT INTO `prior_nivel` (`prn_cod`, `prn_level`, `prn_used`) VALUES(9, 9, 0);
INSERT INTO `prior_nivel` (`prn_cod`, `prn_level`, `prn_used`) VALUES(10, 10, 0);


 CREATE TABLE `prior_atend` (
`pr_cod` INT( 2 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`pr_nivel` INT( 2 ) NOT NULL ,
`pr_default` TINYINT( 1 ) NOT NULL DEFAULT '0',
`pr_desc` VARCHAR( 50 ) NOT NULL DEFAULT '#CCCCCC',
`pr_color` VARCHAR( 7 ) NOT NULL ,
UNIQUE (
`pr_nivel`
)
) ENGINE = MYISAM COMMENT = 'Tabela de prioridades para atendimento dos chamados';

ALTER TABLE `ocorrencias` ADD `oco_prior` INT( 2 ) NULL;

ALTER TABLE `ocorrencias` ADD INDEX ( `oco_prior` ) ;


ALTER TABLE `configusercall` ADD `conf_scr_prior` TINYINT( 1 ) NOT NULL DEFAULT '1';


-- FIM DA VERSÃO 2.0RC5 ---------------------



-- INCIO DA VERSAO 2.0RC6 -----------------

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

-- FIM DA VERSAO 2.0RC6 ------------------