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