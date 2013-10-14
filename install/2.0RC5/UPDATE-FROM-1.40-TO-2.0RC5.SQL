-- VERSION 2.0 --

ALTER TABLE `config` ADD `conf_formatBar` VARCHAR( 40 ) NULL DEFAULT '%%mural%';

ALTER TABLE `config` ADD INDEX ( `conf_formatBar` ) ;

ALTER TABLE `config` ADD `conf_page_size` INT( 3 ) NOT NULL DEFAULT '50';

ALTER TABLE `problemas` ADD `prob_tipo_1` INT( 4 ) NULL ,
ADD `prob_tipo_2` INT( 4 ) NULL ;

ALTER TABLE `problemas` ADD INDEX ( `prob_tipo_1` , `prob_tipo_2` ) ;

ALTER TABLE `problemas` ADD `prob_tipo_3` INT( 4 ) NULL ;

ALTER TABLE `problemas` ADD INDEX ( `prob_tipo_3` ) ;

CREATE TABLE `prob_tipo_1` (
`probt1_cod` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`probt1_desc` VARCHAR( 30 ) NOT NULL
) TYPE = MYISAM ;

CREATE TABLE `prob_tipo_2` (
`probt2_cod` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`probt2_desc` VARCHAR( 30 ) NOT NULL
) TYPE = MYISAM ;

CREATE TABLE `prob_tipo_3` (
`probt3_cod` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`probt3_desc` VARCHAR( 30 ) NOT NULL
) TYPE = MYISAM ;


ALTER TABLE `config` ADD `conf_prob_tipo_1` VARCHAR( 30 ) NOT NULL DEFAULT 'Categoria 1',
ADD `conf_prob_tipo_2` VARCHAR( 30 ) NOT NULL DEFAULT 'Categoria 2',
ADD `conf_prob_tipo_3` VARCHAR( 30 ) NOT NULL DEFAULT 'Categoria 3';

ALTER TABLE `config` ADD INDEX ( `conf_prob_tipo_1` , `conf_prob_tipo_2` , `conf_prob_tipo_3` ) ;


CREATE TABLE `temas` (
`tm_id` INT( 2 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`tm_destaca` VARCHAR( 10 ) NOT NULL DEFAULT '#CCCCCC',
`tm_marca` VARCHAR( 10 ) NOT NULL DEFAULT '#FFFFCC',
`tm_lin_par` VARCHAR( 10 ) NOT NULL DEFAULT '#E3E1E1',
`tm_lin_impar` VARCHAR( 10 ) NOT NULL DEFAULT '#F6F6F6',
`tm_color_body` VARCHAR( 10 ) NOT NULL DEFAULT '#F6F6F6',
`tm_color_td` VARCHAR( 10 ) NOT NULL DEFAULT '#DBDBDB',
`tm_borda_width` INT( 2 ) NOT NULL DEFAULT '2'
) TYPE = MYISAM ;

ALTER TABLE `temas` ADD `tm_nome` VARCHAR( 15 ) NOT NULL DEFAULT 'DEFAULT' AFTER `tm_id` ;

ALTER TABLE `temas` ADD `tm_borda_color` VARCHAR( 10 ) NOT NULL DEFAULT '#F6F6F6',
ADD `tm_tr_header` VARCHAR( 11 ) NOT NULL DEFAULT 'IMG_DEFAULT';

ALTER TABLE `temas` ADD `tm_color_topo` VARCHAR( 11 ) NOT NULL DEFAULT 'IMG_DEFAULT',
ADD `tm_color_barra` VARCHAR( 11 ) NOT NULL DEFAULT 'IMG_DEFAULT';

ALTER TABLE `temas` ADD `tm_color_menu` VARCHAR( 11 ) NOT NULL DEFAULT 'IMG_DEFAULT';

ALTER TABLE `temas` ADD `tm_color_barra_font` VARCHAR( 7 ) NOT NULL DEFAULT '#675E66',
ADD `tm_color_barra_hover` VARCHAR( 7 ) NOT NULL DEFAULT '#FFFFFF';

ALTER TABLE `temas` ADD `tm_barra_fundo_destaque` VARCHAR( 7 ) NOT NULL DEFAULT '#666666',
ADD `tm_barra_fonte_destaque` VARCHAR( 7 ) NOT NULL DEFAULT '#FFFFFF';


ALTER TABLE `temas` CHANGE `tm_id` `tm_id` INT( 2 ) NOT NULL AUTO_INCREMENT ,
CHANGE `tm_nome` `tm_nome` VARCHAR( 15 )  NOT NULL DEFAULT 'DEFAULT',
CHANGE `tm_destaca` `tm_color_destaca` VARCHAR( 10 )  NOT NULL DEFAULT '#CCCCCC',
CHANGE `tm_marca` `tm_color_marca` VARCHAR( 10 )  NOT NULL DEFAULT '#FFFFCC',
CHANGE `tm_lin_par` `tm_color_lin_par` VARCHAR( 10 )  NOT NULL DEFAULT '#E3E1E1',
CHANGE `tm_lin_impar` `tm_color_lin_impar` VARCHAR( 10 )  NOT NULL DEFAULT '#F6F6F6',
CHANGE `tm_color_body` `tm_color_body` VARCHAR( 10 )  NOT NULL DEFAULT '#F6F6F6',
CHANGE `tm_color_td` `tm_color_td` VARCHAR( 10 )  NOT NULL DEFAULT '#DBDBDB',
CHANGE `tm_borda_width` `tm_borda_width` INT( 2 ) NOT NULL DEFAULT '2',
CHANGE `tm_borda_color` `tm_borda_color` VARCHAR( 10 )  NOT NULL DEFAULT '#F6F6F6',
CHANGE `tm_tr_header` `tm_tr_header` VARCHAR( 11 )  NOT NULL DEFAULT 'IMG_DEFAULT',
CHANGE `tm_color_topo` `tm_color_topo` VARCHAR( 11 )  NOT NULL DEFAULT 'IMG_DEFAULT',
CHANGE `tm_color_barra` `tm_color_barra` VARCHAR( 11 )  NOT NULL DEFAULT 'IMG_DEFAULT',
CHANGE `tm_color_menu` `tm_color_menu` VARCHAR( 11 )  NOT NULL DEFAULT 'IMG_DEFAULT',
CHANGE `tm_color_barra_font` `tm_color_barra_font` VARCHAR( 7 )  NOT NULL DEFAULT '#675E66',
CHANGE `tm_color_barra_hover` `tm_color_barra_hover` VARCHAR( 7 )  NOT NULL DEFAULT '#FFFFFF',
CHANGE `tm_barra_fundo_destaque` `tm_barra_fundo_destaque` VARCHAR( 7 )  NOT NULL DEFAULT '#666666',
CHANGE `tm_barra_fonte_destaque` `tm_barra_fonte_destaque` VARCHAR( 7 )  NOT NULL DEFAULT '#FFFFFF';






CREATE TABLE `styles` (
`tm_id` INT( 2 ) NOT NULL AUTO_INCREMENT PRIMARY KEY
) TYPE = MYISAM ;

ALTER TABLE `styles` ADD `tm_color_destaca` VARCHAR( 15 ) NOT NULL DEFAULT '#CCCCCC';

ALTER TABLE `styles` ADD `tm_color_marca` VARCHAR( 15 ) NOT NULL DEFAULT '#FFFFCC';

ALTER TABLE `styles` ADD `tm_color_lin_par` VARCHAR( 15 ) NOT NULL DEFAULT '#E3E1E1';

ALTER TABLE `styles` ADD `tm_color_lin_impar` VARCHAR( 15 ) NOT NULL DEFAULT '#F6F6F6';

ALTER TABLE `styles` ADD `tm_color_body` VARCHAR( 15 ) NOT NULL DEFAULT '#F6F6F6',
ADD `tm_color_td` VARCHAR( 15 ) NOT NULL DEFAULT '#DBDBDB';

ALTER TABLE `styles` ADD `tm_borda_width` INT( 2 ) NOT NULL DEFAULT '2';

ALTER TABLE `styles` ADD `tm_borda_color` VARCHAR( 10 ) NOT NULL DEFAULT '#F6F6F6',
ADD `tm_tr_header` VARCHAR( 11 ) NOT NULL DEFAULT 'IMG_DEFAULT';

ALTER TABLE `styles` ADD `tm_color_topo` VARCHAR( 11 ) NOT NULL DEFAULT 'IMG_DEFAULT',
ADD `tm_color_barra` VARCHAR( 11 ) NOT NULL DEFAULT 'IMG_DEFAULT';

ALTER TABLE `styles` ADD `tm_color_menu` VARCHAR( 11 ) NOT NULL DEFAULT 'IMG_DEFAULT';

ALTER TABLE `styles` ADD `tm_color_barra_font` VARCHAR( 7 ) NOT NULL DEFAULT '#675E66',
ADD `tm_color_barra_hover` VARCHAR( 7 ) NOT NULL DEFAULT '#FFFFFF';

ALTER TABLE `styles` ADD `tm_barra_fundo_destaque` VARCHAR( 7 ) NOT NULL DEFAULT '#666666',
ADD `tm_barra_fonte_destaque` VARCHAR( 7 ) NOT NULL DEFAULT '#FFFFFF';

INSERT INTO `styles` ( `tm_id` , `tm_color_destaca` , `tm_color_marca` , `tm_color_lin_par` , `tm_color_lin_impar` , `tm_color_body` , `tm_color_td` , `tm_borda_width` , `tm_borda_color` , `tm_tr_header` , `tm_color_topo` , `tm_color_barra` , `tm_color_menu` , `tm_color_barra_font` , `tm_color_barra_hover` , `tm_barra_fundo_destaque` , `tm_barra_fonte_destaque` )
VALUES (
NULL , '#CCCCCC', '#FFFFCC', '#E3E1E1', '#F6F6F6', '#F6F6F6', '#DBDBDB', '2', '#F6F6F6', 'IMG_DEFAULT', 'IMG_DEFAULT', 'IMG_DEFAULT', 'IMG_DEFAULT', '#675E66', '#FFFFFF', '#666666', '#FFFFFF'
);


CREATE TABLE `uthemes` (
`uth_id` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`uth_uid` INT( 4 ) NOT NULL ,
`uth_thid` INT( 2 ) NOT NULL ,
INDEX ( `uth_uid` , `uth_thid` )
) TYPE = MYISAM COMMENT = 'Tabela de Temas por usuario';


ALTER TABLE `config` ADD `conf_allow_change_theme` INT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `styles` ADD `tm_color_font_tr_header` VARCHAR( 7 ) NOT NULL DEFAULT '#000000';

ALTER TABLE `temas` ADD `tm_color_font_tr_header` VARCHAR( 7 ) NOT NULL DEFAULT '#000000';

ALTER TABLE `styles` ADD `tm_color_borda_header_centro` VARCHAR( 7 ) NOT NULL DEFAULT '#999999';
ALTER TABLE `temas` ADD `tm_color_borda_header_centro` VARCHAR( 7 ) NOT NULL DEFAULT '#999999';


ALTER TABLE `ocorrencias` CHANGE `telefone` `telefone` VARCHAR( 40 ) NULL DEFAULT NULL;

ALTER TABLE `temas` ADD `tm_color_topo_font` VARCHAR( 7 ) NOT NULL DEFAULT '#FFFFFF' AFTER `tm_color_topo` ;

ALTER TABLE `styles` ADD `tm_color_topo_font` VARCHAR( 7 ) NOT NULL DEFAULT '#FFFFFF' AFTER `tm_color_topo` ;

ALTER TABLE `config` ADD `conf_upld_file_types` VARCHAR( 30 ) NOT NULL DEFAULT '%%IMG%';

ALTER TABLE `imagens` ADD `img_size` BIGINT( 15 ) NOT NULL ;

ALTER TABLE `imagens` CHANGE `img_nome` `img_nome` VARCHAR( 40 ) NOT NULL;

ALTER TABLE `emprestimos` CHANGE `ramal` `ramal` VARCHAR( 20 ) NULL DEFAULT NULL;

ALTER TABLE `feriados` ADD `fixo_feriado` INT( 1 ) NOT NULL DEFAULT '0';


CREATE TABLE `contatos` (
`contact_id` INT( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`contact_login` VARCHAR( 15 ) NOT NULL ,
`contact_name` VARCHAR( 40 ) NOT NULL ,
`contact_email` VARCHAR( 40 ) NOT NULL ,
UNIQUE (
`contact_login` ,
`contact_email`
)
) TYPE = MYISAM COMMENT = 'Tabela de Contatos';


CREATE TABLE `lock_oco` (
`lck_id` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`lck_uid` INT( 4 ) NOT NULL ,
`lck_oco` INT( 5 ) NOT NULL ,
INDEX ( `lck_uid` ) ,
UNIQUE (
`lck_oco`
)
) TYPE = MYISAM COMMENT = 'Tabela de Lock para chamados em edição';

ALTER TABLE `materiais` CHANGE `mat_caixa` `mat_caixa` VARCHAR( 30 ) NULL DEFAULT '';

ALTER TABLE `situacao` DROP INDEX `situac_cod`;

ALTER TABLE `situacao` ADD `situac_destaque` INT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `config` ADD `conf_date_format` VARCHAR( 20 ) NOT NULL DEFAULT '%d/%m/%Y %H:%M:%S';

INSERT INTO `temas` VALUES (1, 'GREEN', '#D0DBCE', '#D0DBCE', '#FFFFFF', '#FFFFFF', '#EEEFE9', '#D0DBCE', 1, '#427041', '#427041', '#3B6B39', '#FFFFFF', '#E3E3E3', '#EEEFE9', '#000000', '#FFFFFF', '#427041', '#FFFFFF', '#FFFFFF', '#427041');
INSERT INTO `temas` VALUES (2, 'OLD_TIMES', '#99CCFF', '#99CCFF', '#CDE5FF', '#FFFFFF', '#CDE5FF', '#92AECC', 0, '#FFFFFF', '#92AECC', '#92AECC', '#FFFFFF', '#CDE5FF', '#CDE5FF', '#0000EE', '#8F6C7F', '#CDE5FF', '#8F6C7F', '#000000', '#92AECC');
INSERT INTO `temas` VALUES (3, 'GMAIL', '#FFFFCC', '#E8EEF7', '#FFFFFF', '#FFFFFF', '#FFFFFF', '#E0ECFF', 1, '#BBBBBB', '#C3D9FF', '#DFECF5', '#0000CC', '#C3D9FF', '#FFFFFF', '#0000CC', '#000000', '#FFFFFF', '#000000', '#000000', '#C3D9FF');
INSERT INTO `temas` VALUES (4, 'CLASSICO', '#D5D5D5', '#FFCC99', '#EAE6D0', '#F8F8F1', '#F6F6F6', '#ECECDB', 0, '#F6F6F6', '#DDDCC5', '#5e515b', '#FFFFFF', '#999999', 'IMG_DEFAULT', '#FFFFFF', '#FFFFFF', '#666666', '#FFFFFF', '#000000', '#DDDCC5');
INSERT INTO `temas` VALUES (5, 'DEFAULT', '#CCCCCC', '#FFFFCC', '#E3E1E1', '#F6F6F6', '#F6F6F6', '#DBDBDB', 2, '#F6F6F6', 'IMG_DEFAULT', 'IMG_DEFAULT', '#FFFFFF', 'IMG_DEFAULT', 'IMG_DEFAULT', '#675E66', '#FFFFFF', '#666666', '#FFFFFF', '#000000', '#999999');

ALTER TABLE `config` CHANGE `conf_ocomon_site` `conf_ocomon_site` VARCHAR( 100 ) NOT NULL DEFAULT 'http://localhost/ocomon/';


ALTER TABLE `estoque` ADD `estoq_tag_inv` INT( 6 ) NULL ,
ADD `estoq_tag_inst` INT( 6 ) NULL ,
ADD `estoq_nf` INT( 15 ) NULL ,
ADD `estoq_warranty` INT( 3 ) NULL ,
ADD `estoq_value` FLOAT( 15 ) NULL ,
ADD `estoq_situac` INT( 2 ) NULL ,
ADD `estoq_data_compra` DATETIME NULL ,
ADD `estoq_ccusto` INT( 6 ) NULL ,
ADD `estoq_vendor` INT( 6 ) NULL ;

ALTER TABLE `estoque` ADD INDEX ( `estoq_tag_inv` , `estoq_tag_inst` ) ;

ALTER TABLE `estoque` ADD `estoq_partnumber` VARCHAR( 15 ) NULL ;

ALTER TABLE `estoque` ADD INDEX ( `estoq_partnumber` ) ;


CREATE TABLE `equipXpieces` (
`eqp_id` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`eqp_equip_inv` INT( 6 ) NOT NULL ,
`eqp_equip_inst` INT( 4 ) NOT NULL ,
`eqp_piece_id` INT( 6 ) NOT NULL ,
`eqp_piece_modelo_id` INT( 6 ) NOT NULL ,
INDEX ( `eqp_equip_inv` , `eqp_equip_inst` , `eqp_piece_id` )
) TYPE = MYISAM COMMENT = 'Tabela de associacao de equipamentos com componentes';


 CREATE TABLE `hist_pieces` (
`hp_id` INT( 6 ) NOT NULL AUTO_INCREMENT ,
`hp_piece_id` INT( 6 ) NOT NULL ,
`hp_piece_local` INT( 4 ) NULL ,
`hp_comp_inv` INT( 6 ) NULL ,
`hp_comp_inst` INT( 4 ) NULL ,
`hp_uid` INT( 6 ) NOT NULL ,
`hp_date` DATETIME NOT NULL ,
PRIMARY KEY ( `hp_id` ) ,
INDEX ( `hp_piece_id` , `hp_piece_local` , `hp_comp_inv` , `hp_comp_inst` )
) ENGINE = MYISAM CHARACTER SET latin1 COLLATE latin1_swedish_ci COMMENT = 'Tabela de histórico de movimentacões de peças avulsas';


 CREATE TABLE `email_warranty` (
`ew_id` INT( 6 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ew_piece_type` INT( 1 ) NOT NULL DEFAULT '0',
`ew_piece_id` INT( 6 ) NOT NULL ,
`ew_sent_first_alert` BOOL NOT NULL DEFAULT '0',
`ew_sent_last_alert` BOOL NOT NULL DEFAULT '0',
INDEX ( `ew_piece_id` )
) TYPE = MYISAM COMMENT = 'Tabela de controle para envio de email sobre prazo de garantias';


ALTER TABLE `config` ADD `conf_days_bf` INT( 3 ) NOT NULL DEFAULT '30',
ADD `conf_wrty_area` INT( 4 ) NOT NULL DEFAULT '1';


INSERT INTO `msgconfig` (`msg_cod`, `msg_event`, `msg_fromname`, `msg_replyto`, `msg_subject`, `msg_body`, `msg_altbody`)
VALUES (NULL , 'mail-about-warranty', 'SISTEMA OCOMON', 'ocomon@yourdomain.com', 'OCOMON - VENCIMENTO DE GARANTIA', 'Aten&ccedil;&atilde;o: <br />Existem equipamentos com o prazo de garantia prestes a expirar.<br /><br />Tipo de equipamento: %tipo%<br />N&uacute;mero de s&eacute;rie: %serial%<br />Partnumber: %partnumber%<br />Modelo: %modelo%<br />Departamento: %local%<br />Fornecedor: %fornecedor%<br />Nota fiscal: %notafiscal%<br />Vencimento: %vencimento%', 'Atenção:\r\nExistem equipamentos com o prazo de garantia prestes a expirar.\r\n\r\nTipo de equipamento: %tipo%\r\nNúmero de série: %serial%\r\nPartnumber: %partnumber%\r\nModelo: %modelo%\r\nDepartamento: %local%\r\nFornecedor: %fornecedor%\r\nNota fiscal: %notafiscal%\r\nVencimento: %vencimento%');


ALTER TABLE `estoque` CHANGE `estoq_data_compra` `estoq_data_compra` DATETIME NULL DEFAULT '0000-00-00 00:00:00';


ALTER TABLE `config` ADD `conf_allow_reopen` TINYINT( 1 ) NOT NULL DEFAULT '1';

ALTER TABLE `config` ADD `conf_allow_date_edit` TINYINT( 1 ) NOT NULL DEFAULT '0';


ALTER TABLE `ocorrencias` DROP `operadorbkp` , DROP `abertoporbkp` ;

ALTER TABLE `ocorrencias` ADD `oco_scheduled` TINYINT( 1 ) NOT NULL DEFAULT '0',
ADD `oco_real_open_date` DATETIME NULL ;

ALTER TABLE `ocorrencias` ADD INDEX ( `oco_scheduled` ) ;

ALTER TABLE `configusercall` ADD `conf_scr_schedule` TINYINT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `config` ADD `conf_schedule_status` INT( 4 ) NOT NULL DEFAULT '1';

ALTER TABLE `config` ADD `conf_schedule_status_2` INT( 4 ) NOT NULL DEFAULT '1';

ALTER TABLE `config` ADD `conf_foward_when_open` INT( 4 ) NOT NULL DEFAULT '1';

ALTER TABLE `configusercall` ADD `conf_scr_foward` TINYINT( 1 ) NOT NULL DEFAULT '0';

ALTER TABLE `hist_pieces` ADD `hp_technician` INT( 4 ) NULL ;

ALTER TABLE `hist_pieces` ADD INDEX ( `hp_technician` ) ;


INSERT INTO `msgconfig` (`msg_cod`, `msg_event`, `msg_fromname`, `msg_replyto`, `msg_subject`, `msg_body`, `msg_altbody`) VALUES (NULL, 'abertura-para-operador', 'SISTEMA OCOMON', 'ocomon@yourdomain.com', 'CHAMADO ABERTO PARA VOCÊ', '<span style="font-weight: bold;">SISTEMA OCOMON %versao%</span><br />Caro %operador%,<br />O chamado <span style="font-weight: bold;">%numero%</span> foi aberto e direcionado a voc&ecirc;.<br /><span style="font-weight: bold;">Descri&ccedil;&atilde;o: </span>%descricao%<br /><span style="font-weight: bold;">Contato: </span>%contato%<br /><span style="font-weight: bold;">Ramal:</span> %ramal%<br />Ocorr&ecirc;ncia aberta pelo operador: %aberto_por%<br />%site%', 'SISTEMA OCOMON %versao%\r\nCaro %operador%,\r\nO chamado %numero% foi aberto e direcionado a você.\r\nDescrição: %descricao%\r\nContato: %contato%\r\nRamal: %ramal%\r\nOcorrência aberta pelo operador: %aberto_por%\r\n%site%');

CREATE TABLE `script_solution` (
`script_cod` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`script_desc` TEXT NOT NULL
) TYPE = MYISAM COMMENT = 'Tabela de scripts de solucoes';

ALTER TABLE `ocorrencias` ADD `oco_script_sol` INT( 4 ) NULL ;

ALTER TABLE `ocorrencias` ADD INDEX ( `oco_script_sol` ) ;


CREATE TABLE `mail_templates` (
`tpl_cod` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`tpl_sigla` VARCHAR( 10 ) NOT NULL ,
`tpl_desc` TEXT NOT NULL ,
`tpl_msg_html` TEXT NOT NULL ,
`tpl_msg_text` TEXT NOT NULL
) TYPE = MYISAM COMMENT = 'Tabela de templates de e-mails';

ALTER TABLE `mail_templates` CHANGE `tpl_desc` `tpl_subject` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

CREATE TABLE `mail_list` (
`ml_cod` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ml_sigla` VARCHAR( 15 ) NOT NULL ,
`ml_desc` TEXT NOT NULL ,
`ml_address` TEXT NOT NULL
) TYPE = MYISAM COMMENT = 'Tabela de listas de distribuicao';

ALTER TABLE `mail_list` CHANGE `ml_address` `ml_addr_to` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `mail_list` ADD `ml_addr_cc` TEXT NULL ;


CREATE TABLE `mail_hist` (
`mhist_cod` INT( 6 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`mhist_listname` VARCHAR( 150 ) NOT NULL ,
`mhist_address` TEXT NOT NULL ,
`mhist_body` TEXT NOT NULL ,
`mhist_date` DATETIME NOT NULL ,
`mhist_technician` INT( 4 ) NOT NULL ,
INDEX ( `mhist_technician` )
) TYPE = MYISAM COMMENT = 'Tabela de histórico de emails enviados';

ALTER TABLE `mail_hist` ADD `mhist_subject` VARCHAR( 40 ) NOT NULL AFTER `mhist_address` ;
ALTER TABLE `mail_hist` ADD `mhist_address_cc` TEXT NULL AFTER `mhist_address` ;

ALTER TABLE `mail_hist` ADD `mhist_oco` INT( 6 ) NOT NULL AFTER `mhist_cod` ;

ALTER TABLE `mail_hist` ADD INDEX ( `mhist_oco` ) ;

ALTER TABLE `mailconfig` ADD `mail_from_name` VARCHAR( 30 ) NOT NULL DEFAULT 'SISTEMA_OCOMON';



-- FIM DA VERSÃO 2.0RC3--------

-- INÍCIO VERSÃO 2.0RC4.1 -----

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


-- FIM DA VERSÃO 2.0RC4.1 -------------------


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



