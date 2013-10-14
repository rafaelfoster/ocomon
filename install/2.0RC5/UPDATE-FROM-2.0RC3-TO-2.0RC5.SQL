
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

