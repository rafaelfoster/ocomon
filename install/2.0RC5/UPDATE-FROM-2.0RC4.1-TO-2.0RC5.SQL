

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


