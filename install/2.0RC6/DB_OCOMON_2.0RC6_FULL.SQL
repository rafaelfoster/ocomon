/*!40101 SET NAMES utf8 */;

-- CREATE DATABASE /*!32312 IF NOT EXISTS*/`ocomon_rc6` /*!40100 DEFAULT CHARACTER SET utf8 */;

-- CREATE USER 'ocomon'@'localhost' IDENTIFIED BY 'senha_ocomon_mysql';
-- GRANT SELECT , INSERT , UPDATE , DELETE ON `ocomon_rc6` . * TO 'ocomon'@'localhost';

-- USE `ocomon_rc6`;

-- 
-- Estrutura da tabela `CCUSTO`
-- 

CREATE TABLE `CCUSTO` (
  `codigo` int(4) NOT NULL auto_increment,
  `codccusto` varchar(6) NOT NULL default '',
  `descricao` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`codigo`),
  KEY `codccusto` (`codccusto`)
)   COMMENT='Tabela de Centros de Custo';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `assentamentos`
-- 

CREATE TABLE `assentamentos` (
  `numero` int(11) NOT NULL auto_increment,
  `ocorrencia` int(11) NOT NULL default '0',
  `assentamento` text NOT NULL,
  `data` datetime default NULL,
  `responsavel` int(4) NOT NULL default '0',
  `responsavelbkp` varchar(20) default NULL,
  PRIMARY KEY  (`numero`),
  KEY `ocorrencia` (`ocorrencia`)
)   ;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `assistencia`
-- 

CREATE TABLE `assistencia` (
  `assist_cod` int(4) NOT NULL auto_increment,
  `assist_desc` varchar(30) default NULL,
  PRIMARY KEY  (`assist_cod`)
)   COMMENT='Tabela de tipos de assistencia para manutencao';

-- --------------------------------------------------------


-- 
-- Estrutura da tabela `avisos`
-- 

CREATE TABLE `avisos` (
  `aviso_id` int(11) NOT NULL auto_increment,
  `avisos` text,
  `data` datetime default NULL,
  `origem` int(4) NOT NULL default '0',
  `status` varchar(100) default NULL,
  `area` int(11) NOT NULL default '0',
  `origembkp` varchar(20) default NULL,
  PRIMARY KEY  (`aviso_id`)
)   ;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `cat_problema_sistemas`
-- 

CREATE TABLE `cat_problema_sistemas` (
  `ctps_id` int(10) NOT NULL default '0',
  `ctps_descricao` varchar(100) NOT NULL default '',
  `ctps_peso` decimal(10,2) NOT NULL default '1.00',
  PRIMARY KEY  (`ctps_id`)
)   ;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `categoriaXproblema_sistemas`
-- 

CREATE TABLE `categoriaXproblema_sistemas` (
  `prob_id` int(11) NOT NULL default '0',
  `ctps_id` int(11) NOT NULL default '0',
  `ctps_id_old` int(11) NOT NULL default '0',
  PRIMARY KEY  (`prob_id`),
  KEY `ctps_id` (`ctps_id`,`prob_id`)
)   ;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `categorias`
-- 

CREATE TABLE `categorias` (
  `cat_cod` int(4) NOT NULL auto_increment,
  `cat_desc` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`cat_cod`)
)   COMMENT='Tabela de categoria de softwares';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `dominios`
-- 

CREATE TABLE `dominios` (
  `dom_cod` int(4) NOT NULL auto_increment,
  `dom_desc` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`dom_cod`)
)   COMMENT='Tabela de Domínios de Rede';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `emprestimos`
-- 

CREATE TABLE `emprestimos` (
  `empr_id` int(11) NOT NULL auto_increment,
  `material` text NOT NULL,
  `responsavel` int(4) NOT NULL default '0',
  `data_empr` datetime default NULL,
  `data_devol` datetime default NULL,
  `quem` varchar(100) default NULL,
  `local` varchar(100) default NULL,
  `ramal` int(11) default NULL,
  `responsavelbkp` varchar(20) default NULL,
  PRIMARY KEY  (`empr_id`)
)   ;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `equipamentos`
-- 

CREATE TABLE `equipamentos` (
  `comp_cod` int(4) unsigned NOT NULL auto_increment,
  `comp_inv` int(6) NOT NULL default '0',
  `comp_sn` varchar(30) default NULL,
  `comp_marca` int(4) unsigned NOT NULL default '0',
  `comp_mb` int(4) default NULL,
  `comp_proc` int(4) unsigned default NULL,
  `comp_memo` int(4) unsigned default NULL,
  `comp_video` int(4) unsigned default NULL,
  `comp_som` int(4) unsigned default NULL,
  `comp_rede` int(4) unsigned default NULL,
  `comp_modelohd` int(4) unsigned default NULL,
  `comp_modem` int(4) unsigned default NULL,
  `comp_cdrom` int(4) unsigned default NULL,
  `comp_dvd` int(4) unsigned default NULL,
  `comp_grav` int(4) unsigned default NULL,
  `comp_nome` varchar(15) default NULL,
  `comp_local` int(4) unsigned NOT NULL default '0',
  `comp_fornecedor` int(4) default NULL,
  `comp_nf` varchar(30) default NULL,
  `comp_coment` text,
  `comp_data` datetime default NULL,
  `comp_valor` float default NULL,
  `comp_data_compra` datetime NOT NULL default '0000-00-00 00:00:00',
  `comp_inst` int(4) NOT NULL default '0',
  `comp_ccusto` int(6) default NULL,
  `comp_tipo_equip` int(4) NOT NULL default '0',
  `comp_tipo_imp` int(4) default NULL,
  `comp_resolucao` int(4) default NULL,
  `comp_polegada` int(4) default NULL,
  `comp_fab` int(4) NOT NULL default '0',
  `comp_situac` int(4) default NULL,
  `comp_reitoria` int(4) default NULL,
  `comp_tipo_garant` int(4) default NULL,
  `comp_garant_meses` int(4) default NULL,
  `comp_assist` int(4) default NULL,
  PRIMARY KEY  (`comp_inv`,`comp_inst`),
  KEY `comp_cod` (`comp_cod`),
  KEY `comp_inv` (`comp_inv`),
  KEY `comp_assist` (`comp_assist`)
)   COMMENT='Tabela principal modulo de inventario de computadores';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `estoque`
-- 

CREATE TABLE `estoque` (
  `estoq_cod` int(4) NOT NULL auto_increment,
  `estoq_tipo` int(4) NOT NULL default '0',
  `estoq_desc` int(4) NOT NULL default '0',
  `estoq_sn` varchar(30) default NULL,
  `estoq_local` int(4) NOT NULL default '0',
  `estoq_comentario` varchar(250) default NULL,
  PRIMARY KEY  (`estoq_cod`),
  KEY `estoq_tipo` (`estoq_tipo`,`estoq_desc`),
  KEY `estoq_local` (`estoq_local`)
)   COMMENT='Tabela de estoque de itens.';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fabricantes`
-- 

CREATE TABLE `fabricantes` (
  `fab_cod` int(4) NOT NULL auto_increment,
  `fab_nome` varchar(30) NOT NULL default '',
  `fab_tipo` int(4) default NULL,
  PRIMARY KEY  (`fab_cod`),
  KEY `fab_cod` (`fab_cod`),
  KEY `fab_tipo` (`fab_tipo`)
)   COMMENT='Tabela de fabricantes de equipamentos do Invmon';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `feriados`
-- 

CREATE TABLE `feriados` (
  `cod_feriado` int(4) NOT NULL auto_increment,
  `data_feriado` datetime NOT NULL default '0000-00-00 00:00:00',
  `desc_feriado` varchar(40) default NULL,
  PRIMARY KEY  (`cod_feriado`),
  KEY `data_feriado` (`data_feriado`)
)   COMMENT='Tabela de feriados';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `fornecedores`
-- 

CREATE TABLE `fornecedores` (
  `forn_cod` int(4) NOT NULL auto_increment,
  `forn_nome` varchar(30) NOT NULL default '',
  `forn_fone` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`forn_cod`),
  KEY `forn_cod` (`forn_cod`)
)   COMMENT='Tabela de fornecedores de equipamentos';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `historico`
-- 

CREATE TABLE `historico` (
  `hist_cod` int(4) NOT NULL auto_increment,
  `hist_inv` int(6) NOT NULL default '0',
  `hist_inst` int(4) NOT NULL default '0',
  `hist_local` int(4) NOT NULL default '0',
  `hist_data` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`hist_cod`),
  KEY `hist_inv` (`hist_inv`),
  KEY `hist_inst` (`hist_inst`)
)   COMMENT='Tabela de controle de histórico de locais por onde o equipam';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `hw_sw`
-- 

CREATE TABLE `hw_sw` (
  `hws_cod` int(4) NOT NULL auto_increment,
  `hws_sw_cod` int(4) NOT NULL default '0',
  `hws_hw_cod` int(4) NOT NULL default '0',
  `hws_hw_inst` int(4) NOT NULL default '0',
  PRIMARY KEY  (`hws_cod`),
  KEY `hws_sw_cod` (`hws_sw_cod`,`hws_hw_cod`),
  KEY `hws_hw_inst` (`hws_hw_inst`)
)   COMMENT='Tabela de relacionamentos entre equipamentos e softwares';

-- --------------------------------------------------------


-- 
-- Estrutura da tabela `instituicao`
-- 

CREATE TABLE `instituicao` (
  `inst_cod` int(4) NOT NULL auto_increment,
  `inst_nome` varchar(30) NOT NULL default '',
  `inst_status` int(11) NOT NULL default '1',
  PRIMARY KEY  (`inst_cod`),
  KEY `inst_cod` (`inst_cod`),
  KEY `inst_status` (`inst_status`)
)   COMMENT='Tabela de Instituições Lasalistas';

-- --------------------------------------------------------



-- 
-- Estrutura da tabela `itens`
-- 

CREATE TABLE `itens` (
  `item_cod` int(4) NOT NULL auto_increment,
  `item_nome` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`item_cod`),
  KEY `item_nome` (`item_nome`)
)   COMMENT='Tabela de componentes individuais';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `licencas`
-- 

CREATE TABLE `licencas` (
  `lic_cod` int(4) NOT NULL auto_increment,
  `lic_desc` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`lic_cod`)
)   COMMENT='Tabela de tipos de licenças de softwares';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `localizacao`
-- 

CREATE TABLE `localizacao` (
  `loc_id` int(11) NOT NULL auto_increment,
  `local` char(200) default NULL,
  `loc_reitoria` int(4) default '0',
  `loc_prior` int(4) default NULL,
  `loc_dominio` int(4) default NULL,
  `loc_predio` int(4) default NULL,
  `loc_status` int(4) NOT NULL default '1',
  UNIQUE KEY `loc_id` (`loc_id`),
  KEY `loc_sla` (`loc_prior`),
  KEY `loc_dominio` (`loc_dominio`),
  KEY `loc_predio` (`loc_predio`),
  KEY `loc_status` (`loc_status`),
  KEY `loc_prior` (`loc_prior`)
)   ;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `marcas_comp`
-- 

CREATE TABLE `marcas_comp` (
  `marc_cod` int(4) unsigned NOT NULL auto_increment,
  `marc_nome` varchar(30) NOT NULL default '0',
  `marc_tipo` int(4) NOT NULL default '0',
  PRIMARY KEY  (`marc_cod`),
  KEY `marc_cod` (`marc_cod`),
  KEY `marc_tipo` (`marc_tipo`)
)   COMMENT='Tabela das marcas de computadores';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `materiais`
-- 

CREATE TABLE `materiais` (
  `mat_cod` int(4) NOT NULL auto_increment,
  `mat_nome` varchar(100) NOT NULL default '',
  `mat_qtd` int(11) NOT NULL default '0',
  `mat_caixa` int(4) NOT NULL default '0',
  `mat_data` datetime NOT NULL default '0000-00-00 00:00:00',
  `mat_obs` varchar(200) NOT NULL default '',
  `mat_modelo_equip` int(4) default NULL,
  PRIMARY KEY  (`mat_cod`),
  KEY `mat_cod_2` (`mat_cod`),
  KEY `mat_modelo_equip` (`mat_modelo_equip`)
)   COMMENT='Tabela de materiais do Helpdesk';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `modelos_itens`
-- 

CREATE TABLE `modelos_itens` (
  `mdit_cod` int(4) NOT NULL auto_increment,
  `mdit_fabricante` varchar(30) NOT NULL default '',
  `mdit_desc` varchar(40) default NULL,
  `mdit_desc_capacidade` float default NULL,
  `mdit_tipo` int(4) NOT NULL default '0',
  `mdit_cod_old` int(4) default NULL,
  `mdit_sufixo` varchar(5) default NULL,
  PRIMARY KEY  (`mdit_cod`),
  KEY `mdit_desc` (`mdit_desc`),
  KEY `mdit_tipo` (`mdit_tipo`),
  KEY `cod_old` (`mdit_cod_old`)
)   COMMENT='Tabela de modelos de componentes';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `modulos`
-- 

CREATE TABLE `modulos` (
  `modu_cod` int(4) NOT NULL auto_increment,
  `modu_nome` varchar(15)  NOT NULL default '',
  PRIMARY KEY  (`modu_cod`),
  KEY `modu_nome` (`modu_nome`)
)  COMMENT='Tabela de mÃ³dulos do sistema';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `moldes`
-- 

CREATE TABLE `moldes` (
  `mold_cod` int(4) NOT NULL auto_increment,
  `mold_inv` int(6) default NULL,
  `mold_sn` varchar(30) default NULL,
  `mold_marca` int(4) NOT NULL default '0',
  `mold_mb` int(4) default NULL,
  `mold_proc` int(4) default NULL,
  `mold_memo` int(4) default NULL,
  `mold_video` int(4) default NULL,
  `mold_som` int(4) default NULL,
  `mold_rede` int(4) default NULL,
  `mold_modelohd` int(4) default NULL,
  `mold_modem` int(4) default NULL,
  `mold_cdrom` int(4) default NULL,
  `mold_dvd` int(4) default NULL,
  `mold_grav` int(4) default NULL,
  `mold_nome` varchar(10) default NULL,
  `mold_local` int(4) default NULL,
  `mold_fornecedor` int(4) default NULL,
  `mold_nf` varchar(30) default NULL,
  `mold_coment` varchar(200) default NULL,
  `mold_data` datetime default NULL,
  `mold_valor` float default NULL,
  `mold_data_compra` datetime NOT NULL default '0000-00-00 00:00:00',
  `mold_inst` int(4) default NULL,
  `mold_ccusto` int(4) default NULL,
  `mold_tipo_equip` int(4) NOT NULL default '0',
  `mold_tipo_imp` int(4) default NULL,
  `mold_resolucao` int(4) default NULL,
  `mold_polegada` int(4) default NULL,
  `mold_fab` int(4) default NULL,
  PRIMARY KEY  (`mold_marca`),
  KEY `mold_cod` (`mold_cod`)
)   COMMENT='Tabela de padrões de configurações';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `nivel`
-- 

CREATE TABLE `nivel` (
  `nivel_cod` int(4) NOT NULL auto_increment,
  `nivel_nome` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`nivel_cod`)
)   COMMENT='Tabela de níveis de acesso ao invmon';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ocorrencias`
-- 

CREATE TABLE `ocorrencias` (
  `numero` int(11) NOT NULL auto_increment,
  `problema` int(11) NOT NULL default '0',
  `descricao` text NOT NULL,
  `equipamento` int(6) default NULL,
  `sistema` int(11) NOT NULL default '0',
  `contato` varchar(100) NOT NULL default '',
  `telefone` varchar(10) default NULL,
  `local` int(11) NOT NULL default '0',
  `operador` int(4) NOT NULL default '0',
  `data_abertura` datetime default NULL,
  `data_fechamento` datetime default NULL,
  `status` int(11) default NULL,
  `data_atendimento` datetime default NULL,
  `instituicao` int(4) default NULL,
  `aberto_por` int(4) NOT NULL default '0',
  `operadorbkp` varchar(20) default NULL,
  `abertoporbkp` varchar(20) default NULL,
  PRIMARY KEY  (`numero`),
  KEY `data_abertura` (`data_abertura`),
  KEY `data_fechamento` (`data_fechamento`),
  KEY `local` (`local`),
  KEY `aberto_por` (`aberto_por`)
)   ;

-- --------------------------------------------------------




-- 
-- Estrutura da tabela `permissoes`
-- 

CREATE TABLE `permissoes` (
  `perm_cod` int(4) NOT NULL auto_increment,
  `perm_area` int(4) NOT NULL default '0',
  `perm_modulo` int(4) NOT NULL default '0',
  `perm_flag` int(4) NOT NULL default '0',
  PRIMARY KEY  (`perm_cod`),
  KEY `perm_area` (`perm_area`,`perm_modulo`,`perm_flag`)
)   COMMENT='Tabela para permissoes das áreas';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `polegada`
-- 

CREATE TABLE `polegada` (
  `pole_cod` int(4) NOT NULL auto_increment,
  `pole_nome` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`pole_cod`),
  KEY `pole_cod` (`pole_cod`)
)   COMMENT='Tabela de polegadas de monitores de vídeo';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `predios`
-- 

CREATE TABLE `predios` (
  `pred_cod` int(4) NOT NULL auto_increment,
  `pred_desc` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`pred_cod`)
)   COMMENT='Tabela de predios - vinculada a tabela de localizaÃ§Ãµes';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `prioridades`
-- 

CREATE TABLE `prioridades` (
  `prior_cod` int(4) NOT NULL auto_increment,
  `prior_nivel` varchar(15) NOT NULL default '',
  `prior_sla` int(4) NOT NULL default '0',
  PRIMARY KEY  (`prior_cod`),
  KEY `prior_nivel` (`prior_nivel`,`prior_sla`),
  KEY `prior_sla` (`prior_sla`)
)   COMMENT='Tabela de prioridades para resposta de chamados';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `problemas`
-- 

CREATE TABLE `problemas` (
  `prob_id` int(11) NOT NULL auto_increment,
  `problema` varchar(100) NOT NULL default '',
  `prob_area` int(4) default NULL,
  `prob_sla` int(4) default NULL,
  PRIMARY KEY  (`prob_id`),
  KEY `prob_id` (`prob_id`),
  KEY `prob_area` (`prob_area`),
  KEY `prob_sla` (`prob_sla`)
)   ;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `reitorias`
-- 

CREATE TABLE `reitorias` (
  `reit_cod` int(4) NOT NULL auto_increment,
  `reit_nome` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`reit_cod`),
  KEY `reit_nome` (`reit_nome`)
)   COMMENT='Tabela de reitorias do UniLasalle';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `resolucao`
-- 

CREATE TABLE `resolucao` (
  `resol_cod` int(4) NOT NULL auto_increment,
  `resol_nome` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`resol_cod`),
  KEY `resol_cod` (`resol_cod`)
)   COMMENT='Tabela de resoluções para scanners';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `sistemas`
-- 

CREATE TABLE `sistemas` (
  `sis_id` int(11) NOT NULL auto_increment,
  `sistema` varchar(100) default NULL,
  `sis_status` int(4) NOT NULL default '1',
  `sis_email` varchar(35) default NULL,
  PRIMARY KEY  (`sis_id`),
  KEY `sis_status` (`sis_status`)
)   ;


ALTER TABLE `sistemas` ADD `sis_atende` INT( 1 ) DEFAULT '1' NOT NULL ;
-- --------------------------------------------------------

-- 
-- Estrutura da tabela `situacao`
-- 

CREATE TABLE `situacao` (
  `situac_cod` int(4) NOT NULL auto_increment,
  `situac_nome` varchar(20) NOT NULL default '',
  `situac_desc` varchar(200) default NULL,
  PRIMARY KEY  (`situac_cod`),
  KEY `situac_cod` (`situac_cod`)
)   COMMENT='Tabela de situação de computadores quanto ao seu funcionamen';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `sla_solucao`
-- 

CREATE TABLE `sla_solucao` (
  `slas_cod` int(4) NOT NULL auto_increment,
  `slas_tempo` int(6) NOT NULL default '0',
  `slas_desc` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`slas_cod`),
  KEY `slas_tempo` (`slas_tempo`),
  KEY `slas_tempo_2` (`slas_tempo`)
)   COMMENT='Tabela de SLAs de tempo de solução';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `softwares`
-- 

CREATE TABLE `softwares` (
  `soft_cod` int(4) NOT NULL auto_increment,
  `soft_fab` int(4) NOT NULL default '0',
  `soft_desc` varchar(30) NOT NULL default '',
  `soft_versao` varchar(10) NOT NULL default '',
  `soft_cat` int(4) NOT NULL default '0',
  `soft_tipo_lic` int(4) NOT NULL default '0',
  `soft_qtd_lic` int(4) default NULL,
  `soft_forn` int(4) default NULL,
  `soft_nf` varchar(20) default NULL,
  PRIMARY KEY  (`soft_cod`),
  KEY `soft_fab` (`soft_fab`,`soft_cat`,`soft_tipo_lic`),
  KEY `soft_versao` (`soft_versao`),
  KEY `soft_nf` (`soft_nf`),
  KEY `soft_forn` (`soft_forn`)
)   COMMENT='Tabela Softwares do sistema';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `solucoes`
-- 

CREATE TABLE `solucoes` (
  `numero` int(11) NOT NULL default '0',
  `problema` text NOT NULL,
  `solucao` text NOT NULL,
  `data` datetime default NULL,
  `responsavel` int(4) NOT NULL default '0',
  `responsavelbkp` varchar(20) default NULL,
  PRIMARY KEY  (`numero`),
  KEY `numero` (`numero`)
)   ;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `status`
-- 

CREATE TABLE `status` (
  `stat_id` int(11) NOT NULL auto_increment,
  `status` varchar(100) NOT NULL default '',
  `stat_cat` int(4) default NULL,
  `stat_painel` int(2) default NULL,
  PRIMARY KEY  (`stat_id`),
  KEY `stat_cat` (`stat_cat`),
  KEY `stat_painel` (`stat_painel`)
)   ;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `status_categ`
-- 

CREATE TABLE `status_categ` (
  `stc_cod` int(4) NOT NULL auto_increment,
  `stc_desc` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`stc_cod`)
)   COMMENT='Tabela de Categorias de Status para Chamados';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `sw_padrao`
-- 

CREATE TABLE `sw_padrao` (
  `swp_cod` int(4) NOT NULL auto_increment,
  `swp_sw_cod` int(4) NOT NULL default '0',
  PRIMARY KEY  (`swp_cod`),
  KEY `swp_sw_cod` (`swp_sw_cod`)
)   COMMENT='Tabela de softwares padrao para cada equipamento';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `tempo_garantia`
-- 

CREATE TABLE `tempo_garantia` (
  `tempo_cod` int(4) NOT NULL auto_increment,
  `tempo_meses` int(4) NOT NULL default '0',
  PRIMARY KEY  (`tempo_cod`),
  KEY `tempo_meses` (`tempo_meses`)
)   COMMENT='Tabela de tempos de duração das garantias';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `tempo_status`
-- 

CREATE TABLE `tempo_status` (
  `ts_cod` int(6) NOT NULL auto_increment,
  `ts_ocorrencia` int(5) NOT NULL default '0',
  `ts_status` int(4) NOT NULL default '0',
  `ts_tempo` int(10) NOT NULL default '0',
  `ts_data` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ts_cod`),
  KEY `ts_ocorrencia` (`ts_ocorrencia`,`ts_status`)
)   COMMENT='Tabela para armazenar o tempo dos chamados em cada status';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `tipo_equip`
-- 

CREATE TABLE `tipo_equip` (
  `tipo_cod` int(11) NOT NULL auto_increment,
  `tipo_nome` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`tipo_cod`),
  KEY `tipo_cod` (`tipo_cod`)
)   COMMENT='Tabela de Tipos de Equipamentos de informática';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `tipo_garantia`
-- 

CREATE TABLE `tipo_garantia` (
  `tipo_garant_cod` int(4) NOT NULL auto_increment,
  `tipo_garant_nome` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`tipo_garant_cod`)
)   COMMENT='Tabela de tipos de garantias de equipamentos';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `tipo_imp`
-- 

CREATE TABLE `tipo_imp` (
  `tipo_imp_cod` int(11) NOT NULL auto_increment,
  `tipo_imp_nome` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`tipo_imp_cod`),
  KEY `tipo_imp_cod` (`tipo_imp_cod`)
)   COMMENT='Tabela de tipos de impressoras';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `tipo_item`
-- 

CREATE TABLE `tipo_item` (
  `tipo_it_cod` int(4) NOT NULL auto_increment,
  `tipo_it_desc` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`tipo_it_cod`)
)   COMMENT='Tipos de itens - hw ou sw';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `usuarios`
-- 

CREATE TABLE `usuarios` (
  `user_id` int(4) NOT NULL auto_increment,
  `login` varchar(100) NOT NULL default '',
  `nome` varchar(200) NOT NULL default '',
  `password` varchar(200) NOT NULL default '',
  `data_inc` date default NULL,
  `data_admis` date default NULL,
  `email` varchar(100) default NULL,
  `fone` varchar(10) default NULL,
  `nivel` char(2) default NULL,
  `AREA` char(3) default 'ALL',
  PRIMARY KEY  (`user_id`),
  KEY `login` (`login`)
)   COMMENT='Tabela de operadores do sistema';

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `usuarios_areas`
-- 

CREATE TABLE `usuarios_areas` (
  `uarea_cod` int(4) NOT NULL auto_increment,
  `uarea_uid` int(4) NOT NULL default '0',
  `uarea_sid` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`uarea_cod`),
  KEY `uarea_uid` (`uarea_uid`,`uarea_sid`)
)   COMMENT='Tabela de areas que o usuario pertence';








-- Insere o usuario ADMIN

INSERT INTO `usuarios` ( `user_id` , `login` , `nome` , `password` , `data_inc` , `data_admis` , `email` , `fone` , `nivel` , `AREA` )
VALUES ('', 'admin', 'Administrador do Sistema', '21232f297a57a5a743894a0e4a801fc3', now(), now() , 'admin@yourdomain.com' , '123456' , 1 , '1');

-- 
-- Extraindo dados da tabela `assistencia`
-- 

INSERT INTO `assistencia` (`assist_cod`, `assist_desc`) VALUES (1, 'Contrato de Manutenção');
INSERT INTO `assistencia` (`assist_cod`, `assist_desc`) VALUES (2, 'Garantia do Fabricante');
INSERT INTO `assistencia` (`assist_cod`, `assist_desc`) VALUES (3, 'Sem Cobertura');


-- 
-- Extraindo dados da tabela `modulos`
-- 

INSERT INTO `modulos` VALUES (2, 'inventário');
INSERT INTO `modulos` VALUES (1, 'ocorrências');


-- 
-- Extraindo dados da tabela `sistemas`
-- 

INSERT INTO `sistemas` VALUES (1, 'DEFAULT', 1, 'default@yourdomain.com', 1);
INSERT INTO `sistemas` VALUES (2, 'USUÁRIOS', 1, 'default@yourdomain.com', 0);

-- 
-- Extraindo dados da tabela `categorias`
-- 

INSERT INTO `categorias` VALUES (1, 'Escritório');
INSERT INTO `categorias` VALUES (2, 'Browser');
INSERT INTO `categorias` VALUES (3, 'Editor');
INSERT INTO `categorias` VALUES (4, 'Visualizador');
INSERT INTO `categorias` VALUES (5, 'Jogos');
INSERT INTO `categorias` VALUES (6, 'Sistema Operacional');
INSERT INTO `categorias` VALUES (7, 'Antivírus');
INSERT INTO `categorias` VALUES (8, 'E-mail');
INSERT INTO `categorias` VALUES (9, 'Desenvolvimento');
INSERT INTO `categorias` VALUES (10, 'Utilitários');
INSERT INTO `categorias` VALUES (11, 'Compactador');


-- 
-- Extraindo dados da tabela `itens`
-- 

INSERT INTO `itens` VALUES (1, 'HD');
INSERT INTO `itens` VALUES (2, 'Placa de vídeo');
INSERT INTO `itens` VALUES (3, 'Placa de rede');
INSERT INTO `itens` VALUES (4, 'Placa de som');
INSERT INTO `itens` VALUES (5, 'CD-ROM');
INSERT INTO `itens` VALUES (6, 'Modem');
INSERT INTO `itens` VALUES (7, 'Memória');
INSERT INTO `itens` VALUES (8, 'DVD');
INSERT INTO `itens` VALUES (9, 'Gravador');
INSERT INTO `itens` VALUES (10, 'Placa mãe');
INSERT INTO `itens` VALUES (11, 'Processador');


-- Extraindo dados da tabela `dominios`
-- 

INSERT INTO `dominios` VALUES (1, 'ARQUIVOS');


-- 
-- Extraindo dados da tabela `fabricantes`
-- 

INSERT INTO `fabricantes` VALUES (1, 'Samsung', 1);
INSERT INTO `fabricantes` VALUES (2, 'LG', 1);
INSERT INTO `fabricantes` VALUES (3, 'Philips', 1);
INSERT INTO `fabricantes` VALUES (4, 'Toshiba', 1);
INSERT INTO `fabricantes` VALUES (5, 'Compaq', 1);
INSERT INTO `fabricantes` VALUES (6, 'IBM', 1);
INSERT INTO `fabricantes` VALUES (7, 'Dell', 1);
INSERT INTO `fabricantes` VALUES (8, 'Epson', 1);
INSERT INTO `fabricantes` VALUES (9, 'HP', 1);
INSERT INTO `fabricantes` VALUES (10, 'Lexmark', 1);
INSERT INTO `fabricantes` VALUES (11, 'Ricoh', 1);
INSERT INTO `fabricantes` VALUES (12, 'Creative', 1);
INSERT INTO `fabricantes` VALUES (13, 'Alfa Digital', 1);
INSERT INTO `fabricantes` VALUES (14, 'Itautec', 1);
INSERT INTO `fabricantes` VALUES (15, 'Metron', 1);
INSERT INTO `fabricantes` VALUES (16, 'Netrix', 1);
INSERT INTO `fabricantes` VALUES (17, 'Waytech', 1);
INSERT INTO `fabricantes` VALUES (18, 'Canon', 1);
INSERT INTO `fabricantes` VALUES (19, 'Montada', 1);
INSERT INTO `fabricantes` VALUES (20, '3 Com', 1);
INSERT INTO `fabricantes` VALUES (21, 'SMS', 1);
INSERT INTO `fabricantes` VALUES (22, 'AOC', 1);
INSERT INTO `fabricantes` VALUES (23, 'Brother', 1);
INSERT INTO `fabricantes` VALUES (24, 'Iomega', 1);
INSERT INTO `fabricantes` VALUES (25, 'Bematech', 1);
INSERT INTO `fabricantes` VALUES (26, 'Mark Vision', 1);
INSERT INTO `fabricantes` VALUES (27, 'NK', 1);
INSERT INTO `fabricantes` VALUES (28, 'Icone Sul', 1);
INSERT INTO `fabricantes` VALUES (29, 'TCI', 1);
INSERT INTO `fabricantes` VALUES (30, 'Infoway P75', 1);
INSERT INTO `fabricantes` VALUES (31, 'AdRS', 1);
INSERT INTO `fabricantes` VALUES (32, 'Compudesk', 1);
INSERT INTO `fabricantes` VALUES (33, 'Perto', 1);
INSERT INTO `fabricantes` VALUES (34, 'Okipage', 1);
INSERT INTO `fabricantes` VALUES (35, 'NCS', 1);
INSERT INTO `fabricantes` VALUES (36, 'SACT', 1);
INSERT INTO `fabricantes` VALUES (37, 'GTI', 1);
INSERT INTO `fabricantes` VALUES (38, 'Troni', 1);
INSERT INTO `fabricantes` VALUES (39, 'SID', 1);
INSERT INTO `fabricantes` VALUES (40, 'Yamaha', 1);
INSERT INTO `fabricantes` VALUES (41, 'CP Eletronica', 1);
INSERT INTO `fabricantes` VALUES (42, 'Kingston', 1);
INSERT INTO `fabricantes` VALUES (43, 'Encore', 1);
INSERT INTO `fabricantes` VALUES (44, 'Gênius', 1);
INSERT INTO `fabricantes` VALUES (45, 'Planet', 1);
INSERT INTO `fabricantes` VALUES (46, 'Inovar', 1);
INSERT INTO `fabricantes` VALUES (47, 'InFocus', 1);
INSERT INTO `fabricantes` VALUES (48, 'TrendNet', 1);
INSERT INTO `fabricantes` VALUES (49, 'Elebra', 1);
INSERT INTO `fabricantes` VALUES (51, 'EMC', 1);
INSERT INTO `fabricantes` VALUES (52, 'ABC BULL', 1);
INSERT INTO `fabricantes` VALUES (53, 'Facit', 1);
INSERT INTO `fabricantes` VALUES (54, 'VideoComp', 1);
INSERT INTO `fabricantes` VALUES (55, 'Techmedia', 1);
INSERT INTO `fabricantes` VALUES (56, 'Advanced', 1);
INSERT INTO `fabricantes` VALUES (57, 'TDA', 1);
INSERT INTO `fabricantes` VALUES (58, 'Byte On', 1);
INSERT INTO `fabricantes` VALUES (59, 'Acer', 1);
INSERT INTO `fabricantes` VALUES (60, 'Visioneer', 1);
INSERT INTO `fabricantes` VALUES (61, 'Extreme', 1);
INSERT INTO `fabricantes` VALUES (62, 'SUN', 3);
INSERT INTO `fabricantes` VALUES (63, 'D-link', 1);
INSERT INTO `fabricantes` VALUES (64, 'Liesegang', 1);
INSERT INTO `fabricantes` VALUES (65, 'N/A', 1);
INSERT INTO `fabricantes` VALUES (66, 'Sony', 1);
INSERT INTO `fabricantes` VALUES (67, 'Lightware', 1);
INSERT INTO `fabricantes` VALUES (68, 'PowerWare', 1);
INSERT INTO `fabricantes` VALUES (69, 'Ericsson', 1);
INSERT INTO `fabricantes` VALUES (70, 'Cisco', 1);
INSERT INTO `fabricantes` VALUES (71, 'Metrologic', 1);
INSERT INTO `fabricantes` VALUES (72, 'Gertec', 1);
INSERT INTO `fabricantes` VALUES (73, 'Aligent', 1);
INSERT INTO `fabricantes` VALUES (74, 'DIGI', 1);
INSERT INTO `fabricantes` VALUES (75, 'Adobe', 2);
INSERT INTO `fabricantes` VALUES (76, 'Microsoft', 2);
INSERT INTO `fabricantes` VALUES (77, 'EA Games', 2);
INSERT INTO `fabricantes` VALUES (80, 'OpenOffice.org', 2);
INSERT INTO `fabricantes` VALUES (81, 'Trend', 3);
INSERT INTO `fabricantes` VALUES (82, 'Qualcom', 2);
INSERT INTO `fabricantes` VALUES (83, 'Mozilla.org', 2);
INSERT INTO `fabricantes` VALUES (84, 'Adaptec', 2);
INSERT INTO `fabricantes` VALUES (85, 'Macromedia', 2);
INSERT INTO `fabricantes` VALUES (86, 'Ahead', 2);
INSERT INTO `fabricantes` VALUES (90, 'Izsoft', 2);
INSERT INTO `fabricantes` VALUES (91, 'Projeto Livre', 2);
INSERT INTO `fabricantes` VALUES (92, 'Projeto Pessoal', 2);
INSERT INTO `fabricantes` VALUES (93, 'CyberLink', 2);
INSERT INTO `fabricantes` VALUES (94, 'Oracle', 2);
INSERT INTO `fabricantes` VALUES (95, 'SPSS Inc.', 2);
INSERT INTO `fabricantes` VALUES (96, 'Globalink', 2);
INSERT INTO `fabricantes` VALUES (97, 'SulSoft', 2);
INSERT INTO `fabricantes` VALUES (98, 'Corel', 2);
INSERT INTO `fabricantes` VALUES (99, 'Host & Haicol', 2);
INSERT INTO `fabricantes` VALUES (100, 'Borland', 2);
INSERT INTO `fabricantes` VALUES (101, 'Logic Works', 2);
INSERT INTO `fabricantes` VALUES (103, 'Safer-Networking', 2);
INSERT INTO `fabricantes` VALUES (104, 'CM Data', 2);
INSERT INTO `fabricantes` VALUES (105, 'MACECRAFT SOFTWARE', 2);
INSERT INTO `fabricantes` VALUES (106, 'LeaderShip', 1);
INSERT INTO `fabricantes` VALUES (107, 'Justsoft', 2);
INSERT INTO `fabricantes` VALUES (108, 'Xerox', 3);
INSERT INTO `fabricantes` VALUES (109, 'Sharp', 1);
INSERT INTO `fabricantes` VALUES (110, 'Minolta', 1);
INSERT INTO `fabricantes` VALUES (111, 'Micronet', 1);
INSERT INTO `fabricantes` VALUES (112, 'Kodak', 3);
INSERT INTO `fabricantes` VALUES (115, 'USRobotics', 1);
INSERT INTO `fabricantes` VALUES (116, 'EliteGroup Computer Systens', 1);
INSERT INTO `fabricantes` VALUES (117, 'Dr. Hank', 1);
INSERT INTO `fabricantes` VALUES (119, 'MicroPower', 2);


-- 
-- Extraindo dados da tabela `fornecedores`
-- 

INSERT INTO `fornecedores` VALUES (1, 'Teletex', '0800-55-64-05');
INSERT INTO `fornecedores` VALUES (2, 'DELL', '0800-90-33-55');
INSERT INTO `fornecedores` VALUES (3, 'Processor', '0800-13-09-99');
INSERT INTO `fornecedores` VALUES (4, 'Ingram Micro', '(11) 3677-5800');


-- 
-- Extraindo dados da tabela `licencas`
-- 

INSERT INTO `licencas` VALUES (1, 'Open Source / livre');
INSERT INTO `licencas` VALUES (2, 'Freeware');
INSERT INTO `licencas` VALUES (3, 'Shareware');
INSERT INTO `licencas` VALUES (4, 'Adware');
INSERT INTO `licencas` VALUES (5, 'Contrato');
INSERT INTO `licencas` VALUES (6, 'Comercial');
INSERT INTO `licencas` VALUES (7, 'OEM');

-- 
-- Extraindo dados da tabela `localizacao`
-- 

INSERT INTO `localizacao` VALUES (1, 'DEFAULT', NULL, 5, NULL, NULL, 1);


-- 
-- Extraindo dados da tabela `modelos_itens`
-- 

INSERT INTO `modelos_itens` VALUES (1, 'Seagate', 'IDE 5400rpm', 10.2, 1, 2, 'GB');
INSERT INTO `modelos_itens` VALUES (2, 'Fujitsu', 'IDE', 10, 1, 3, 'GB');
INSERT INTO `modelos_itens` VALUES (3, 'Toshiba', 'IDE', 6, 1, 4, 'GB');
INSERT INTO `modelos_itens` VALUES (4, 'Seagate', 'IDE', 10, 1, 5, 'GB');
INSERT INTO `modelos_itens` VALUES (5, 'Seagate', 'IDE 5400rpm', 40, 1, 17, 'GB');
INSERT INTO `modelos_itens` VALUES (6, 'Quantum', 'IDE', 2, 1, 7, 'GB');
INSERT INTO `modelos_itens` VALUES (7, 'Maxtor', 'IDE 5400rpm', 40, 1, 8, 'GB');
INSERT INTO `modelos_itens` VALUES (8, 'Samsung', 'IDE 5400rpm', 10.2, 1, 9, 'GB');
INSERT INTO `modelos_itens` VALUES (9, 'Quantum', 'IDE', 1.2, 1, 10, 'GB');
INSERT INTO `modelos_itens` VALUES (10, 'Seagate', 'IDE', 1.2, 1, 11, 'GB');
INSERT INTO `modelos_itens` VALUES (11, 'Quantum', 'SCSI', 3, 1, 12, 'GB');
INSERT INTO `modelos_itens` VALUES (12, 'Quantum', 'IDE', 0.6, 1, 13, 'GB');
INSERT INTO `modelos_itens` VALUES (13, 'Western Digital', 'IDE', 1, 1, 14, 'GB');
INSERT INTO `modelos_itens` VALUES (14, 'Western Digital', 'IDE 5400rpm', 20, 1, 15, 'GB');
INSERT INTO `modelos_itens` VALUES (15, 'Fujitsu', 'IDE', 20, 1, 16, 'GB');
INSERT INTO `modelos_itens` VALUES (16, 'Maxtor', 'IDE 5400rpm', 20, 1, 18, 'GB');
INSERT INTO `modelos_itens` VALUES (17, 'Samsung', 'IDE 5400rpm', 20, 1, 19, 'GB');
INSERT INTO `modelos_itens` VALUES (18, 'Western Digital', 'IDE 5400rpm', 40, 1, 20, 'GB');
INSERT INTO `modelos_itens` VALUES (19, 'Seagate', 'IDE 5400rpm', 20, 1, 21, 'GB');
INSERT INTO `modelos_itens` VALUES (20, 'Toshiba', 'IDE 5400rpm', 12, 1, 22, 'GB');
INSERT INTO `modelos_itens` VALUES (21, 'Toshiba', 'IDE 5400rpm', 20, 1, 23, 'GB');
INSERT INTO `modelos_itens` VALUES (22, 'Hitashi', 'IDE 4200rpm', 20, 1, 24, 'GB');
INSERT INTO `modelos_itens` VALUES (23, 'Genérico', 'IDE 4200rpm', 40, 1, 25, 'GB');
INSERT INTO `modelos_itens` VALUES (24, 'Quantum', 'IDE', 4, 1, 26, 'GB');
INSERT INTO `modelos_itens` VALUES (25, 'Seagate', 'IDE', 4, 1, 27, 'GB');
INSERT INTO `modelos_itens` VALUES (26, 'Maxtor', 'IDE 5400rpm', 4, 1, 28, 'GB');
INSERT INTO `modelos_itens` VALUES (27, 'Western Digital', 'IDE', 1.2, 1, 29, 'GB');
INSERT INTO `modelos_itens` VALUES (28, 'Genérico', 'M1614TA', 1, 1, 30, 'GB');
INSERT INTO `modelos_itens` VALUES (29, 'Samsung', 'IDE', 2.1, 1, 31, 'GB');
INSERT INTO `modelos_itens` VALUES (30, 'Quantum', 'IDE', 2.1, 1, 32, 'GB');
INSERT INTO `modelos_itens` VALUES (31, 'Quantum', 'IDE', 3.2, 1, 33, 'GB');
INSERT INTO `modelos_itens` VALUES (32, 'Maxtor', 'IDE', 6.5, 1, 34, 'GB');
INSERT INTO `modelos_itens` VALUES (33, 'Seagate', 'IDE', 2.5, 1, 35, 'GB');
INSERT INTO `modelos_itens` VALUES (34, 'Genérico', 'IDE', 4, 1, 36, 'GB');
INSERT INTO `modelos_itens` VALUES (35, 'Western Digital', 'IDE', 6, 1, 37, 'GB');
INSERT INTO `modelos_itens` VALUES (36, 'Fujitsu', 'IDE', 6, 1, 38, 'GB');
INSERT INTO `modelos_itens` VALUES (37, 'Genérico', 'IDE', 20, 1, 39, 'GB');
INSERT INTO `modelos_itens` VALUES (38, 'Genérico', 'IDE', 6, 1, 40, 'GB');
INSERT INTO `modelos_itens` VALUES (39, 'Fujitsu', 'IDE', 1, 1, 41, 'GB');
INSERT INTO `modelos_itens` VALUES (40, 'Genérico', 'IDE', 3, 1, 42, 'GB');
INSERT INTO `modelos_itens` VALUES (41, 'Samsung', 'Ultra DMA', 40, 1, 43, 'GB');
INSERT INTO `modelos_itens` VALUES (42, 'Genérico', 'IDE', 2, 1, 44, 'GB');
INSERT INTO `modelos_itens` VALUES (43, 'Quantum', 'IDE', 1, 1, 45, 'GB');
INSERT INTO `modelos_itens` VALUES (44, 'Quantum', 'IDE 5400rpm', 10, 1, 46, 'GB');
INSERT INTO `modelos_itens` VALUES (45, 'Samsung', 'IDE 5400rpm', 5, 1, 47, 'GB');
INSERT INTO `modelos_itens` VALUES (46, 'Paladium', 'IDE 5400rpm', 1.2, 1, 48, 'GB');
INSERT INTO `modelos_itens` VALUES (47, 'Genérico', 'IDE', 10, 1, 49, 'GB');
INSERT INTO `modelos_itens` VALUES (48, 'Quantum', 'IDE 5400rpm', 3, 1, 50, 'GB');
INSERT INTO `modelos_itens` VALUES (49, 'Genérico', 'IDE 5400rpm', 30, 1, 51, 'GB');
INSERT INTO `modelos_itens` VALUES (50, 'IBM', 'SCSI', 4.3, 1, 52, 'GB');
INSERT INTO `modelos_itens` VALUES (51, 'Samsung', 'IDE 5400 rpm', 1.6, 1, 53, 'GB');
INSERT INTO `modelos_itens` VALUES (52, 'Samsung', 'IDE 5400 rpm', 9, 1, 54, 'GB');
INSERT INTO `modelos_itens` VALUES (53, 'Seagate', 'IDE 5400 rpm', 8, 1, 55, 'GB');
INSERT INTO `modelos_itens` VALUES (54, 'Genérico', 'IDE', 15, 1, 56, 'GB');
INSERT INTO `modelos_itens` VALUES (55, 'Genérico', 'IDE', 1, 1, 57, 'GB');
INSERT INTO `modelos_itens` VALUES (56, 'Genérico', 'IDE', 0.8, 1, 58, 'GB');
INSERT INTO `modelos_itens` VALUES (57, 'Genérico', 'IDE 5400 rpm', 0, 1, 59, 'GB');
INSERT INTO `modelos_itens` VALUES (58, 'Genérico', 'IDE', 0.4, 1, 60, 'GB');
INSERT INTO `modelos_itens` VALUES (59, 'SIS', '6326', NULL, 2, 2, NULL);
INSERT INTO `modelos_itens` VALUES (60, 'Trident', 'Blade 3D on Board/AGP 4MB', NULL, 2, 3, NULL);
INSERT INTO `modelos_itens` VALUES (61, 'Trident', '9440', NULL, 2, 4, NULL);
INSERT INTO `modelos_itens` VALUES (62, 'Trident', '9750 AGP', NULL, 2, 5, NULL);
INSERT INTO `modelos_itens` VALUES (63, 'Trident', '9680', NULL, 2, 6, NULL);
INSERT INTO `modelos_itens` VALUES (64, 'Cirrus Logic', '9521', NULL, 2, 7, NULL);
INSERT INTO `modelos_itens` VALUES (65, 'Cirrus Logic', '9421', NULL, 2, 8, NULL);
INSERT INTO `modelos_itens` VALUES (66, 'SIS', '530 on board', NULL, 2, 9, NULL);
INSERT INTO `modelos_itens` VALUES (67, 'Intel', '82815 (Dell/HP)', NULL, 2, 10, NULL);
INSERT INTO `modelos_itens` VALUES (68, 'Trident', '9000i', NULL, 2, 11, NULL);
INSERT INTO `modelos_itens` VALUES (69, 'Trident', '8900 CL/D', NULL, 2, 12, NULL);
INSERT INTO `modelos_itens` VALUES (70, 'Cirrus Logic', '5480', NULL, 2, 13, NULL);
INSERT INTO `modelos_itens` VALUES (71, 'Nvidia', 'Vanta 16 MB', NULL, 2, 14, NULL);
INSERT INTO `modelos_itens` VALUES (72, 'Nvidia', 'Riva TNT2 32MB', NULL, 2, 15, NULL);
INSERT INTO `modelos_itens` VALUES (73, 'Via Tech.', 'VT8361/VT8601', NULL, 2, 16, NULL);
INSERT INTO `modelos_itens` VALUES (74, 'Intel', '82845G/GL/GE/PE/GV', NULL, 2, 17, NULL);
INSERT INTO `modelos_itens` VALUES (75, 'S3', 'Savage /IX W/MV(8MB)', NULL, 2, 18, NULL);
INSERT INTO `modelos_itens` VALUES (76, 'Intel', '82810E Integrated', NULL, 2, 19, NULL);
INSERT INTO `modelos_itens` VALUES (77, 'ATI', 'Rage Mobility AGP', NULL, 2, 20, NULL);
INSERT INTO `modelos_itens` VALUES (78, 'Radeon', 'ATI IGP 340M (Radeon Mobile)', NULL, 2, 21, NULL);
INSERT INTO `modelos_itens` VALUES (79, 'SIS', '300/305', NULL, 2, 22, NULL);
INSERT INTO `modelos_itens` VALUES (80, 'Cirrus Logic', 'CL-GD5434-HC-C', NULL, 2, 23, NULL);
INSERT INTO `modelos_itens` VALUES (81, 'Cirrus Logic', 'CL-GD5422-75A', NULL, 2, 24, NULL);
INSERT INTO `modelos_itens` VALUES (82, 'MarkVision', 'MVVEXP01 16Mb', NULL, 2, 25, NULL);
INSERT INTO `modelos_itens` VALUES (83, 'SIS', '86C306', NULL, 2, 26, NULL);
INSERT INTO `modelos_itens` VALUES (84, 'SIS', '86C201', NULL, 2, 27, NULL);
INSERT INTO `modelos_itens` VALUES (85, 'ATI', 'Rage LT PRO PCI', NULL, 2, 28, NULL);
INSERT INTO `modelos_itens` VALUES (86, 'Trident', '9660', NULL, 2, 29, NULL);
INSERT INTO `modelos_itens` VALUES (87, 'S3', 'Virge PCI 4MB', NULL, 2, 30, NULL);
INSERT INTO `modelos_itens` VALUES (88, 'Riva', 'TNT2 32MB AGP', NULL, 2, 31, NULL);
INSERT INTO `modelos_itens` VALUES (89, 'S3', 'Virge 86C325', NULL, 2, 32, NULL);
INSERT INTO `modelos_itens` VALUES (90, 'Cirrus Logic', '5430', NULL, 2, 33, NULL);
INSERT INTO `modelos_itens` VALUES (91, 'Via', 'Savage 4 16Mb', NULL, 2, 34, NULL);
INSERT INTO `modelos_itens` VALUES (92, 'ATI', '3D Rage Pro', NULL, 2, 35, NULL);
INSERT INTO `modelos_itens` VALUES (93, 'S3', 'ProSavage (16MB)', NULL, 2, 36, NULL);
INSERT INTO `modelos_itens` VALUES (94, 'S3', 'ProSavage (8MB)', NULL, 2, 37, NULL);
INSERT INTO `modelos_itens` VALUES (95, 'S3', 'ProSavage DDR (8MB)', NULL, 2, 38, NULL);
INSERT INTO `modelos_itens` VALUES (96, 'S3', 'Trio64v2-DX/GX (3MB)', NULL, 2, 39, NULL);
INSERT INTO `modelos_itens` VALUES (97, 'S3', 'Virge DX/GX (2MB)', NULL, 2, 40, NULL);
INSERT INTO `modelos_itens` VALUES (98, 'SIS', '630/730', NULL, 2, 41, NULL);
INSERT INTO `modelos_itens` VALUES (99, 'Cirrus Logic', '5428 on board', NULL, 2, 42, NULL);
INSERT INTO `modelos_itens` VALUES (100, 'SIS', '5597/5598', NULL, 2, 43, NULL);
INSERT INTO `modelos_itens` VALUES (101, 'Cirrus Logic', '5434 PCI', NULL, 2, 44, NULL);
INSERT INTO `modelos_itens` VALUES (102, 'Trident', '8400 PCI/AGP', NULL, 2, 45, NULL);
INSERT INTO `modelos_itens` VALUES (103, 'IGA', '1682 PCI', NULL, 2, 46, NULL);
INSERT INTO `modelos_itens` VALUES (104, 'Intel', '810 Chipset Graphics Driver', NULL, 2, 47, NULL);
INSERT INTO `modelos_itens` VALUES (105, 'SIS', 'Integrated Video', NULL, 2, 48, NULL);
INSERT INTO `modelos_itens` VALUES (106, 'SIS', '540', NULL, 2, 49, NULL);
INSERT INTO `modelos_itens` VALUES (107, '3 Com', '3C 905B', NULL, 3, 2, NULL);
INSERT INTO `modelos_itens` VALUES (108, '3 Com', '3c 900 TPO', NULL, 3, 3, NULL);
INSERT INTO `modelos_itens` VALUES (109, '3 Com', '3c 590', NULL, 3, 4, NULL);
INSERT INTO `modelos_itens` VALUES (110, '3 Com', '3C 905B-TX', NULL, 3, 5, NULL);
INSERT INTO `modelos_itens` VALUES (111, '3 Com', '3C 9050TX', NULL, 3, 6, NULL);
INSERT INTO `modelos_itens` VALUES (112, 'Intel', 'Pro/100+', NULL, 3, 7, NULL);
INSERT INTO `modelos_itens` VALUES (113, '3 Com', '3c 905C-TX', NULL, 3, 8, NULL);
INSERT INTO `modelos_itens` VALUES (114, 'Realtek', 'RTL8139 10/100', NULL, 3, 9, NULL);
INSERT INTO `modelos_itens` VALUES (115, 'Intel', '82557', NULL, 3, 10, NULL);
INSERT INTO `modelos_itens` VALUES (116, 'Intel', 'Pro 100 VM (compaq)', NULL, 3, 11, NULL);
INSERT INTO `modelos_itens` VALUES (117, 'Realtek', 'RTL8029', NULL, 3, 12, NULL);
INSERT INTO `modelos_itens` VALUES (118, '3 Com', '3c 920 Integrated (Dell)', NULL, 3, 13, NULL);
INSERT INTO `modelos_itens` VALUES (119, 'Intel', 'Pro PCI Adapter', NULL, 3, 14, NULL);
INSERT INTO `modelos_itens` VALUES (120, 'Intel', 'Pro /1000 (Dell)', NULL, 3, 15, NULL);
INSERT INTO `modelos_itens` VALUES (121, 'Intel', 'Pro 100 VE (HP/Compaq)', NULL, 3, 16, NULL);
INSERT INTO `modelos_itens` VALUES (122, 'Toshiba', 'PCMCIA ToPIC95-B(3com)', NULL, 3, 17, NULL);
INSERT INTO `modelos_itens` VALUES (123, 'Encore', 'PCMCIA 10/100 Base-TX', NULL, 3, 18, NULL);
INSERT INTO `modelos_itens` VALUES (124, 'Intel', 'Pro /100 M (Dell)', NULL, 3, 19, NULL);
INSERT INTO `modelos_itens` VALUES (125, 'Digitron', 'DEC Chip 21041-PB', NULL, 3, 20, NULL);
INSERT INTO `modelos_itens` VALUES (126, '3 Com', '3C 509B', NULL, 3, 21, NULL);
INSERT INTO `modelos_itens` VALUES (127, 'AMD', 'AM79C970 (PC NET Family)', NULL, 3, 22, NULL);
INSERT INTO `modelos_itens` VALUES (128, 'Winbond', 'W89C940F', NULL, 3, 23, NULL);
INSERT INTO `modelos_itens` VALUES (129, 'Digital', 'DC1017BA', NULL, 3, 24, NULL);
INSERT INTO `modelos_itens` VALUES (130, 'SIS', 'SIS 900', NULL, 3, 25, NULL);
INSERT INTO `modelos_itens` VALUES (131, 'Compex', '100TX', NULL, 3, 26, NULL);
INSERT INTO `modelos_itens` VALUES (132, 'Intel', '21140 10/100', NULL, 3, 27, NULL);
INSERT INTO `modelos_itens` VALUES (133, 'DEC', 'DC 21041 Ehernet', NULL, 3, 28, NULL);
INSERT INTO `modelos_itens` VALUES (134, 'Davicom', 'PCI', NULL, 3, 29, NULL);
INSERT INTO `modelos_itens` VALUES (135, 'Genérico', 'NE2000', NULL, 3, 30, NULL);
INSERT INTO `modelos_itens` VALUES (136, 'Via', 'VT6105 RHINE III', NULL, 3, 31, NULL);
INSERT INTO `modelos_itens` VALUES (137, 'NE 2000', 'CompatÃ­vel', NULL, 3, 32, NULL);
INSERT INTO `modelos_itens` VALUES (138, 'SIS', '900 10/100', NULL, 3, 33, NULL);
INSERT INTO `modelos_itens` VALUES (139, 'VIA', 'Rhine II Fast Ethernet', NULL, 3, 34, NULL);
INSERT INTO `modelos_itens` VALUES (140, 'Yes', 'Ne2000 Chipset', NULL, 3, 35, NULL);
INSERT INTO `modelos_itens` VALUES (141, 'UMC', 'UM 900 SAF', NULL, 3, 36, NULL);
INSERT INTO `modelos_itens` VALUES (142, '3 Com', 'Generic', NULL, 3, 37, NULL);
INSERT INTO `modelos_itens` VALUES (143, 'AMD', 'AM 2100', NULL, 3, 38, NULL);
INSERT INTO `modelos_itens` VALUES (144, '3Com', 'IIIBusMaster Etherlink', NULL, 3, 39, NULL);
INSERT INTO `modelos_itens` VALUES (145, 'Creative', 'SB AWE 64', NULL, 4, 2, NULL);
INSERT INTO `modelos_itens` VALUES (146, 'Xwave', 'PCI', NULL, 4, 3, NULL);
INSERT INTO `modelos_itens` VALUES (147, 'Yamaha', 'OPL 3 on board', NULL, 4, 4, NULL);
INSERT INTO `modelos_itens` VALUES (148, 'Iwill', 'On board', NULL, 4, 5, NULL);
INSERT INTO `modelos_itens` VALUES (149, 'C MÃ©dia', '8738 audio driver on board', NULL, 4, 6, NULL);
INSERT INTO `modelos_itens` VALUES (150, 'Forte Media', 'FM 801', NULL, 4, 8, NULL);
INSERT INTO `modelos_itens` VALUES (151, 'Yamaha', 'Native DSXG-PCI', NULL, 4, 11, NULL);
INSERT INTO `modelos_itens` VALUES (152, 'C MÃ©dia', 'CMI8738 integrated', NULL, 4, 12, NULL);
INSERT INTO `modelos_itens` VALUES (153, 'ESS', 'Maestro technology-2E', NULL, 4, 13, NULL);
INSERT INTO `modelos_itens` VALUES (154, 'Acer Labs', 'M5451 AC-link', NULL, 4, 14, NULL);
INSERT INTO `modelos_itens` VALUES (155, 'C MÃ©dia', 'CMI 8330', NULL, 4, 15, NULL);
INSERT INTO `modelos_itens` VALUES (156, 'C MÃ©dia', 'CMI 8338', NULL, 4, 16, NULL);
INSERT INTO `modelos_itens` VALUES (157, 'Creative', 'SB 16', NULL, 4, 17, NULL);
INSERT INTO `modelos_itens` VALUES (158, 'Creative', 'SB 32', NULL, 4, 18, NULL);
INSERT INTO `modelos_itens` VALUES (159, 'Creative', 'VIBRA 16C', NULL, 4, 19, NULL);
INSERT INTO `modelos_itens` VALUES (160, 'OPTi', '86C931', NULL, 4, 20, NULL);
INSERT INTO `modelos_itens` VALUES (161, 'SIS', '7018', NULL, 4, 21, NULL);
INSERT INTO `modelos_itens` VALUES (162, 'Sound Blaster', 'Pro', NULL, 4, 22, NULL);
INSERT INTO `modelos_itens` VALUES (163, 'Opti', '82c931', NULL, 4, 23, NULL);
INSERT INTO `modelos_itens` VALUES (164, 'Crystal', 'CS4231A-KL', NULL, 4, 24, NULL);
INSERT INTO `modelos_itens` VALUES (165, 'Creative', '32x', NULL, 5, 2, NULL);
INSERT INTO `modelos_itens` VALUES (166, 'Creative', '36x', NULL, 5, 3, NULL);
INSERT INTO `modelos_itens` VALUES (167, 'Max', '42x', NULL, 5, 4, NULL);
INSERT INTO `modelos_itens` VALUES (168, 'Max', '50x', NULL, 5, 5, NULL);
INSERT INTO `modelos_itens` VALUES (169, 'Creative', '50x', NULL, 5, 6, NULL);
INSERT INTO `modelos_itens` VALUES (170, 'Troni', 'CSI-56x', NULL, 5, 7, NULL);
INSERT INTO `modelos_itens` VALUES (171, 'Sony', '52x', NULL, 5, 8, NULL);
INSERT INTO `modelos_itens` VALUES (172, 'Samsung', '48x', NULL, 5, 9, NULL);
INSERT INTO `modelos_itens` VALUES (173, 'LG', '52x', NULL, 5, 10, NULL);
INSERT INTO `modelos_itens` VALUES (174, 'Genérico', '56x', NULL, 5, 11, NULL);
INSERT INTO `modelos_itens` VALUES (175, 'Liteon', '48x', NULL, 5, 12, NULL);
INSERT INTO `modelos_itens` VALUES (176, 'LG', '48x', NULL, 5, 13, NULL);
INSERT INTO `modelos_itens` VALUES (177, 'Max', '48x', NULL, 5, 14, NULL);
INSERT INTO `modelos_itens` VALUES (178, 'Max', '44x', NULL, 5, 15, NULL);
INSERT INTO `modelos_itens` VALUES (179, 'Creative', '24x', NULL, 5, 16, NULL);
INSERT INTO `modelos_itens` VALUES (180, 'Panasonic', '4x', NULL, 5, 17, NULL);
INSERT INTO `modelos_itens` VALUES (181, 'Mitsushita', '54x', NULL, 5, 18, NULL);
INSERT INTO `modelos_itens` VALUES (182, 'Max', '60', NULL, 5, 19, NULL);
INSERT INTO `modelos_itens` VALUES (183, 'ATAPI', '52x', NULL, 5, 20, NULL);
INSERT INTO `modelos_itens` VALUES (184, 'Samsung', '8x', NULL, 5, 21, NULL);
INSERT INTO `modelos_itens` VALUES (185, 'Max', '56x', NULL, 5, 22, NULL);
INSERT INTO `modelos_itens` VALUES (186, 'Mitsumi FX', '54 x', NULL, 5, 23, NULL);
INSERT INTO `modelos_itens` VALUES (187, 'Max', '24x', NULL, 5, 24, NULL);
INSERT INTO `modelos_itens` VALUES (188, 'PCtel', 'HSP on board', NULL, 6, 2, NULL);
INSERT INTO `modelos_itens` VALUES (189, 'Lucent', 'Agere V.92', NULL, 6, 3, NULL);
INSERT INTO `modelos_itens` VALUES (190, 'Agere', 'PCI 56k V.92 Soft Moden', NULL, 6, 4, NULL);
INSERT INTO `modelos_itens` VALUES (191, 'PCtel', 'HSP56 PCI', NULL, 6, 5, NULL);
INSERT INTO `modelos_itens` VALUES (192, 'US Robotics', '56k Fax ext', NULL, 6, 6, NULL);
INSERT INTO `modelos_itens` VALUES (193, 'Toshiba', 'internal V.90 56k (built in Lu', NULL, 6, 7, NULL);
INSERT INTO `modelos_itens` VALUES (194, 'HSF', 'HSFi v.92 56k', NULL, 6, 8, NULL);
INSERT INTO `modelos_itens` VALUES (195, 'ESS', 'ES56STH-PI', NULL, 6, 9, NULL);
INSERT INTO `modelos_itens` VALUES (196, 'Motorola', 'SM56 PCI', NULL, 6, 10, NULL);
INSERT INTO `modelos_itens` VALUES (197, 'US Robotics', '33.6 voice', NULL, 6, 11, NULL);
INSERT INTO `modelos_itens` VALUES (198, 'Toshiba', '8x24x', NULL, 8, 1, NULL);
INSERT INTO `modelos_itens` VALUES (199, 'Genérico(notes)', '24x', NULL, 8, 2, NULL);
INSERT INTO `modelos_itens` VALUES (200, 'LG', '50 x Combo', NULL, 8, 3, NULL);
INSERT INTO `modelos_itens` VALUES (201, 'Sony', '12x40x', NULL, 8, 4, NULL);
INSERT INTO `modelos_itens` VALUES (202, 'LG', '12x8x32x', NULL, 9, 2, NULL);
INSERT INTO `modelos_itens` VALUES (203, 'Samsung', '32x10x40x', NULL, 9, 3, NULL);
INSERT INTO `modelos_itens` VALUES (204, 'HP', '12x8x32', NULL, 9, 4, NULL);
INSERT INTO `modelos_itens` VALUES (205, 'NEC', '48x', NULL, 9, 5, NULL);
INSERT INTO `modelos_itens` VALUES (206, 'Genérico(notes)', '24x', NULL, 9, 6, NULL);
INSERT INTO `modelos_itens` VALUES (207, 'TEAC', '4x4x24', NULL, 9, 7, NULL);
INSERT INTO `modelos_itens` VALUES (208, 'LG', '24x10x40', NULL, 9, 8, NULL);
INSERT INTO `modelos_itens` VALUES (209, 'Mitsumi', '54x', NULL, 9, 9, NULL);
INSERT INTO `modelos_itens` VALUES (210, 'Yamaha', '4x4x16', NULL, 9, 10, NULL);
INSERT INTO `modelos_itens` VALUES (211, 'Genérico', 'GCE-8523B', NULL, 9, 11, NULL);
INSERT INTO `modelos_itens` VALUES (212, 'LG', '52X24X52X', NULL, 9, 12, NULL);
INSERT INTO `modelos_itens` VALUES (213, 'Iwill', 'XA 100 Plus ATX', NULL, 10, 1, NULL);
INSERT INTO `modelos_itens` VALUES (214, 'Digitron', 'BB745sV AT', NULL, 10, 3, NULL);
INSERT INTO `modelos_itens` VALUES (215, 'ECS-', 'P6IWP-fe', NULL, 10, 4, NULL);
INSERT INTO `modelos_itens` VALUES (216, 'Compaq', 'EVO D-300', NULL, 10, 5, NULL);
INSERT INTO `modelos_itens` VALUES (217, 'Dell', 'Optiplex GX-150', NULL, 10, 6, NULL);
INSERT INTO `modelos_itens` VALUES (218, 'Soyo', 'P4IS2/P4ISR (soyo)', NULL, 10, 7, NULL);
INSERT INTO `modelos_itens` VALUES (219, 'Chaintech', '7AIVL (MO07063BCHAE)', NULL, 10, 8, NULL);
INSERT INTO `modelos_itens` VALUES (220, 'Dell', 'Computer Corp 03x290', NULL, 10, 9, NULL);
INSERT INTO `modelos_itens` VALUES (221, 'HP', 'System Board', NULL, 10, 10, NULL);
INSERT INTO `modelos_itens` VALUES (222, 'Toshiba', 'Portable PC', NULL, 10, 11, NULL);
INSERT INTO `modelos_itens` VALUES (223, 'Dell', 'Computer Corp C8RP.07W079', NULL, 10, 12, NULL);
INSERT INTO `modelos_itens` VALUES (224, 'Compaq', 'EVO D-310', NULL, 10, 13, NULL);
INSERT INTO `modelos_itens` VALUES (225, 'Compaq', 'N1020v', NULL, 10, 14, NULL);
INSERT INTO `modelos_itens` VALUES (226, 'Amptron', 'PM8400C/8400D/8600B/8600C', NULL, 10, 15, NULL);
INSERT INTO `modelos_itens` VALUES (227, 'Amptron', 'PM598', NULL, 10, 16, NULL);
INSERT INTO `modelos_itens` VALUES (228, 'Kaimei', 'KM-T5-V2', NULL, 10, 17, NULL);
INSERT INTO `modelos_itens` VALUES (229, 'Shuttle', 'HOT-541', NULL, 10, 18, NULL);
INSERT INTO `modelos_itens` VALUES (230, 'Genérico', 'Chipset Intel 82430 FX', NULL, 10, 19, NULL);
INSERT INTO `modelos_itens` VALUES (231, 'Hsin Tech', '519/529', NULL, 10, 20, NULL);
INSERT INTO `modelos_itens` VALUES (232, 'Gem light', 'GMB-P56IPS', NULL, 10, 21, NULL);
INSERT INTO `modelos_itens` VALUES (233, 'Via', 'VT82C42M', NULL, 10, 22, NULL);
INSERT INTO `modelos_itens` VALUES (234, 'OPTI Viper', '82C557M', NULL, 10, 23, NULL);
INSERT INTO `modelos_itens` VALUES (235, 'Genérico', 'Chipset SB82371FB', NULL, 10, 24, NULL);
INSERT INTO `modelos_itens` VALUES (236, 'Genérico', 'Chipset SIS 5591', NULL, 10, 25, NULL);
INSERT INTO `modelos_itens` VALUES (237, 'PC Chips', 'M598', NULL, 10, 26, NULL);
INSERT INTO `modelos_itens` VALUES (238, 'PC Chips', 'M748 LMRT', NULL, 10, 27, NULL);
INSERT INTO `modelos_itens` VALUES (239, 'Genérico', 'Chipset ALi M1531 Aladdin', NULL, 10, 28, NULL);
INSERT INTO `modelos_itens` VALUES (240, 'Genérico', 'Chipset SIS 5597', NULL, 10, 29, NULL);
INSERT INTO `modelos_itens` VALUES (241, 'Amptron', 'PM9900', NULL, 10, 30, NULL);
INSERT INTO `modelos_itens` VALUES (242, 'Amptron', 'PM9200', NULL, 10, 31, NULL);
INSERT INTO `modelos_itens` VALUES (243, 'Amptron', 'PM8800', NULL, 10, 32, NULL);
INSERT INTO `modelos_itens` VALUES (244, 'DTK', 'PAM - 0057I - E1', NULL, 10, 33, NULL);
INSERT INTO `modelos_itens` VALUES (245, 'Amptron', 'PM 7900/8800', NULL, 10, 34, NULL);
INSERT INTO `modelos_itens` VALUES (246, 'Genérico', 'Chipset Intel Triton 82430VX', NULL, 10, 35, NULL);
INSERT INTO `modelos_itens` VALUES (247, 'Fugutech', 'M507', NULL, 10, 36, NULL);
INSERT INTO `modelos_itens` VALUES (248, 'PC Chips', 'M715', NULL, 10, 37, NULL);
INSERT INTO `modelos_itens` VALUES (249, 'Amptron', 'PM8600A', NULL, 10, 38, NULL);
INSERT INTO `modelos_itens` VALUES (250, 'Genérico', 'Chipset SIS 540', NULL, 10, 39, NULL);
INSERT INTO `modelos_itens` VALUES (251, 'Genérico', 'Chipset Utron VXPRO II', NULL, 10, 40, NULL);
INSERT INTO `modelos_itens` VALUES (252, 'GenÃ©rica', 'Chipset sis530', NULL, 10, 41, NULL);
INSERT INTO `modelos_itens` VALUES (253, 'Soyo', '5EH', NULL, 10, 42, NULL);
INSERT INTO `modelos_itens` VALUES (254, 'Via', 'VT8364', NULL, 10, 43, NULL);
INSERT INTO `modelos_itens` VALUES (255, 'A-Trend', 'ATC-6130', NULL, 10, 44, NULL);
INSERT INTO `modelos_itens` VALUES (256, 'Chaintech', '6xxx', NULL, 10, 45, NULL);
INSERT INTO `modelos_itens` VALUES (257, 'Chaintech', '7AIV', NULL, 10, 46, NULL);
INSERT INTO `modelos_itens` VALUES (258, 'Chaintech', '7AIV5(E)', NULL, 10, 47, NULL);
INSERT INTO `modelos_itens` VALUES (259, 'CTX-508', 'Chipset Intel Triton 82430VX', NULL, 10, 48, NULL);
INSERT INTO `modelos_itens` VALUES (260, 'ECS', 'K7VMM+', NULL, 10, 49, NULL);
INSERT INTO `modelos_itens` VALUES (261, 'GigaByte', 'GA-7VEML', NULL, 10, 50, NULL);
INSERT INTO `modelos_itens` VALUES (262, 'MSI', 'MS-6378', NULL, 10, 51, NULL);
INSERT INTO `modelos_itens` VALUES (263, 'PcChips', 'M810LR', NULL, 10, 52, NULL);
INSERT INTO `modelos_itens` VALUES (264, 'Shuttle', 'HOT-569', NULL, 10, 53, NULL);
INSERT INTO `modelos_itens` VALUES (265, 'Soyo', '4SAW', NULL, 10, 54, NULL);
INSERT INTO `modelos_itens` VALUES (266, 'Genérico', 'Chipset Intel FW82371AB', NULL, 10, 55, NULL);
INSERT INTO `modelos_itens` VALUES (267, 'PcChips', 'LMR 598', NULL, 10, 56, NULL);
INSERT INTO `modelos_itens` VALUES (268, 'Soyo', 'Chipset SIS85c496', NULL, 10, 57, NULL);
INSERT INTO `modelos_itens` VALUES (269, 'ALI', 'M1429GA1', NULL, 10, 58, NULL);
INSERT INTO `modelos_itens` VALUES (270, 'Soyo', 'Chipset Intel pci 7sB82371FB', NULL, 10, 59, NULL);
INSERT INTO `modelos_itens` VALUES (271, 'Elpina', 'PM 9100/Pine PT-7602', NULL, 10, 60, NULL);
INSERT INTO `modelos_itens` VALUES (272, 'PcChips', '585 LMR', NULL, 10, 61, NULL);
INSERT INTO `modelos_itens` VALUES (273, 'Holco Enterprise', 'Generic', NULL, 10, 62, NULL);
INSERT INTO `modelos_itens` VALUES (274, 'Dell', 'Optiplex GX-100', NULL, 10, 63, NULL);
INSERT INTO `modelos_itens` VALUES (275, 'Soyo', '7VBA133', NULL, 10, 64, NULL);
INSERT INTO `modelos_itens` VALUES (276, 'Amptron', 'PM900', NULL, 10, 65, NULL);
INSERT INTO `modelos_itens` VALUES (277, 'N/A', '', 64, 7, 3, 'MB');
INSERT INTO `modelos_itens` VALUES (278, 'N/A', '', 128, 7, 4, 'MB');
INSERT INTO `modelos_itens` VALUES (279, 'N/A', '', 16, 7, 5, 'MB');
INSERT INTO `modelos_itens` VALUES (280, 'N/A', '', 32, 7, 6, 'MB');
INSERT INTO `modelos_itens` VALUES (281, 'N/A', '', 256, 7, 7, 'MB');
INSERT INTO `modelos_itens` VALUES (282, 'N/A', '', 512, 7, 8, 'MB');
INSERT INTO `modelos_itens` VALUES (283, 'N/A', '', 384, 7, 9, 'MB');
INSERT INTO `modelos_itens` VALUES (284, 'N/A', '', 320, 7, 10, 'MB');
INSERT INTO `modelos_itens` VALUES (285, 'N/A', '', 48, 7, 11, 'MB');
INSERT INTO `modelos_itens` VALUES (286, 'N/A', '', 24, 7, 12, 'MB');
INSERT INTO `modelos_itens` VALUES (287, 'N/A', '', 56, 7, 13, 'MB');
INSERT INTO `modelos_itens` VALUES (288, 'N/A', '', 96, 7, 14, 'MB');
INSERT INTO `modelos_itens` VALUES (289, 'N/A', '', 40, 7, 15, 'MB');
INSERT INTO `modelos_itens` VALUES (290, 'Intel', 'Pentium', 166, 11, 2, 'MHZ');
INSERT INTO `modelos_itens` VALUES (291, 'AMD', 'K6-2', 550, 11, 3, 'MHZ');
INSERT INTO `modelos_itens` VALUES (292, 'Intel', 'Pentium III', 1000, 11, 4, 'MHZ');
INSERT INTO `modelos_itens` VALUES (293, 'Intel', 'Pentium IV', 1700, 11, 5, 'MHZ');
INSERT INTO `modelos_itens` VALUES (294, 'AMD', 'K6-2', 300, 11, 6, 'MHZ');
INSERT INTO `modelos_itens` VALUES (295, 'Intel', 'Pentium', 75, 11, 7, 'MHZ');
INSERT INTO `modelos_itens` VALUES (296, 'Intel', 'Pentium', 200, 11, 8, 'MHZ');
INSERT INTO `modelos_itens` VALUES (297, 'Intel', 'Celeron', 600, 11, 9, 'MHZ');
INSERT INTO `modelos_itens` VALUES (298, 'AMD', 'K6-2', 450, 11, 10, 'MHZ');
INSERT INTO `modelos_itens` VALUES (299, 'AMD', 'K6-2', 500, 11, 11, 'MHZ');
INSERT INTO `modelos_itens` VALUES (300, 'Intel', 'Pentium', 133, 11, 12, 'MHZ');
INSERT INTO `modelos_itens` VALUES (301, 'Intel', 'Pentium III', 500, 11, 13, 'MHZ');
INSERT INTO `modelos_itens` VALUES (302, 'Intel', 'Pentium III', 450, 11, 14, 'MHZ');
INSERT INTO `modelos_itens` VALUES (303, 'AMD', 'Athlon', 1300, 11, 15, 'MHZ');
INSERT INTO `modelos_itens` VALUES (304, 'AMD', 'Athlon', 1500, 11, 16, 'MHZ');
INSERT INTO `modelos_itens` VALUES (305, 'AMD', 'Duron', 1100, 11, 17, 'MHZ');
INSERT INTO `modelos_itens` VALUES (306, 'AMD', 'K6-2', 266, 11, 18, 'MHZ');
INSERT INTO `modelos_itens` VALUES (307, 'Intel', 'Celeron', 700, 11, 19, 'MHZ');
INSERT INTO `modelos_itens` VALUES (308, 'Intel', 'Pentium II', 300, 11, 20, 'MHZ');
INSERT INTO `modelos_itens` VALUES (309, 'Intel', 'Pentium III', 900, 11, 21, 'MHZ');
INSERT INTO `modelos_itens` VALUES (310, 'Intel', 'Pentium IV', 1600, 11, 22, 'MHZ');
INSERT INTO `modelos_itens` VALUES (311, 'Intel', 'Pentium IV', 2260, 11, 23, 'MHZ');
INSERT INTO `modelos_itens` VALUES (312, 'Intel', 'Celeron', 1100, 11, 24, 'MHZ');
INSERT INTO `modelos_itens` VALUES (313, 'Intel', 'Pentium III', 700, 11, 25, 'MHZ');
INSERT INTO `modelos_itens` VALUES (314, 'Intel', 'Celeron', 1800, 11, 26, 'MHZ');
INSERT INTO `modelos_itens` VALUES (315, 'Intel', 'Pentium IV', 2000, 11, 27, 'MHZ');
INSERT INTO `modelos_itens` VALUES (316, 'Intel', 'Pentium III', 850, 11, 28, 'MHZ');
INSERT INTO `modelos_itens` VALUES (317, 'Intel', 'Pentium IV', 2600, 11, 29, 'MHZ');
INSERT INTO `modelos_itens` VALUES (318, 'Intel', 'Celereron IV', 1700, 11, 30, 'MHZ');
INSERT INTO `modelos_itens` VALUES (319, 'AMD', 'Athlon', 1400, 11, 31, 'MHZ');
INSERT INTO `modelos_itens` VALUES (320, 'AMD', 'K6-2', 350, 11, 32, 'MHZ');
INSERT INTO `modelos_itens` VALUES (321, 'Intel', 'Pentium MMX', 166, 11, 33, 'MHZ');
INSERT INTO `modelos_itens` VALUES (322, 'Intel', 'Pentium', 100, 11, 34, 'MHZ');
INSERT INTO `modelos_itens` VALUES (323, 'AMD', 'K6', 300, 11, 35, 'MHZ');
INSERT INTO `modelos_itens` VALUES (324, 'Intel', 'Pentium MMX', 233, 11, 36, 'MHZ');
INSERT INTO `modelos_itens` VALUES (325, 'Intel', 'Pentium', 150, 11, 37, 'MHZ');
INSERT INTO `modelos_itens` VALUES (326, 'Intel', 'Pentium MMX', 200, 11, 38, 'MHZ');
INSERT INTO `modelos_itens` VALUES (327, 'AMD', 'K6-2', 380, 11, 39, 'MHZ');
INSERT INTO `modelos_itens` VALUES (328, 'Intel', 'Pentium MMX', 150, 11, 40, 'MHZ');
INSERT INTO `modelos_itens` VALUES (329, 'AMD', 'Athlon', 1333, 11, 41, 'MHZ');
INSERT INTO `modelos_itens` VALUES (330, 'AMD', 'K6-2', 150, 11, 42, 'MHZ');
INSERT INTO `modelos_itens` VALUES (331, 'Intel', 'Celeron', 266, 11, 43, 'MHZ');
INSERT INTO `modelos_itens` VALUES (332, 'AMD', 'K6', 166, 11, 44, 'MHZ');
INSERT INTO `modelos_itens` VALUES (333, 'AMD', 'Duron', 750, 11, 45, 'MHZ');
INSERT INTO `modelos_itens` VALUES (334, 'AMD', 'Duron XP', 1300, 11, 46, 'MHZ');
INSERT INTO `modelos_itens` VALUES (335, 'AMD', 'Athlon XP', 1466, 11, 47, 'MHZ');
INSERT INTO `modelos_itens` VALUES (336, 'AMD', 'Athlon XP', 1100, 11, 48, 'MHZ');
INSERT INTO `modelos_itens` VALUES (337, 'Cyrix', 'DX', 4100, 11, 49, 'MHZ');
INSERT INTO `modelos_itens` VALUES (338, 'IBM', '586', 100, 11, 50, 'MHZ');
INSERT INTO `modelos_itens` VALUES (339, 'Intel', '486', 100, 11, 51, 'MHZ');
INSERT INTO `modelos_itens` VALUES (340, 'AMD', 'K6-2', 400, 11, 52, 'MHZ');
INSERT INTO `modelos_itens` VALUES (341, 'Intel', 'Pentium', 120, 11, 53, 'MHZ');
INSERT INTO `modelos_itens` VALUES (342, 'Intel', 'AC''97 82801BA(m) (Compaq/Dell/', NULL, 4, 7, NULL);
INSERT INTO `modelos_itens` VALUES (346, 'Intel Pentium', 'Xeon', 1200, 11, NULL, 'Mhz');
INSERT INTO `modelos_itens` VALUES (345, 'Intel Pentium', 'Xeon', 2200, 11, NULL, 'Mhz');
INSERT INTO `modelos_itens` VALUES (347, 'N/A', '', 2000, 7, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (348, 'Genérico', 'Ultra 3 SCSI 15.000 Rpm', 36, 1, NULL, 'Gb');
INSERT INTO `modelos_itens` VALUES (349, 'N/A', 'Tigon3 10/100/1000', NULL, 3, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (350, 'N/A', 'N/A', NULL, 2, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (351, 'Genérico', 'SCSI 10.000 Rpm', 36, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (352, 'N/A', 'Marvel 10/100/1000', NULL, 3, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (353, 'ATI', 'Mach64-GR Graphics Accelerator', NULL, 2, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (354, 'N/A', 'N/A', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (355, 'Intel', 'Pentium Xeon', 2400, 11, NULL, 'Mhz');
INSERT INTO `modelos_itens` VALUES (356, 'ATI', 'Rage XL', 8, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (361, 'ATI', 'LT PRO AGP', 8, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (362, 'Via Tech', 'AC\\''97 Audio Controller', NULL, 4, 9, NULL);
INSERT INTO `modelos_itens` VALUES (363, 'N/A', 'Cabo Paralelo padrÃ£o', NULL, 12, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (364, 'Leadership', 'XPC Stereo', 160, 13, NULL, 'Watts');
INSERT INTO `modelos_itens` VALUES (365, 'Sunshine', 'Lince - toshiba', NULL, 13, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (366, 'N/A', 'GenÃ©rica', NULL, 13, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (367, 'Multi-media', 'MS-560', 100, 13, NULL, 'Watts');
INSERT INTO `modelos_itens` VALUES (368, 'NVIDIA', 'GeForce4 MX Integrated', 32, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (369, 'SoundMAX', 'Digital Audio', NULL, 4, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (370, '3Com', '3C920B-EMB Integrated (HP)', NULL, -1, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (371, '3Com', '3C920B-EMB Integrated (HP)', NULL, 3, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (372, 'Seagate', 'IDE 7200rpm', 40, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (373, 'AMD', 'Atlhon XP', 1900, 11, NULL, 'Mhz');
INSERT INTO `modelos_itens` VALUES (374, 'HP', '0830h', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (375, 'Genérico', 'IDE', 0.325, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (376, 'Intel', '486 DX2', 50, 11, NULL, 'Mhz');
INSERT INTO `modelos_itens` VALUES (377, 'N/A', 'Chips & Tech Accelerator', NULL, 2, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (378, 'N/A', '', 6, 7, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (379, 'N/A', '', 192, 7, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (380, 'LG', '16x COMBO 48x24x48x', NULL, 8, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (386, 'HP', '16X', NULL, 5, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (382, '', 'Dimm', NULL, 14, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (383, '', 'Mini-Dimm', NULL, 14, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (384, '', 'Serial', NULL, 15, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (385, '', 'PS/2', NULL, 15, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (387, 'Intel', 'Pentium Pro', 200, 11, NULL, 'MHZ');
INSERT INTO `modelos_itens` VALUES (388, 'Genérico', 'SCSI', 4, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (389, 'Genérico', '8x', NULL, 5, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (390, 'Intel', '82443BX', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (391, 'Intel', '82443GX', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (392, 'Genérico', 'SCSI', 36, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (393, 'Genérico', 'SCSI', 8, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (394, 'Genérico', 'SCSI', 18, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (395, '', 'Genérico', 45, 5, NULL, 'X');
INSERT INTO `modelos_itens` VALUES (396, 'Max', '40x', NULL, 5, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (397, 'Intel', 'Pentium III', 350, 11, NULL, 'MHZ');
INSERT INTO `modelos_itens` VALUES (398, 'Intel', 'Pentium II', 350, 11, NULL, 'MHZ');
INSERT INTO `modelos_itens` VALUES (399, 'Genérico', 'SCSI', 9, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (400, 'Intel', '82555', NULL, 3, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (401, 'N/A', '', 196, 7, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (402, '3Com', '3c 905', NULL, 3, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (403, 'Genérico', '45x', NULL, 5, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (404, 'Netgear', 'FA310TX', NULL, 3, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (405, 'Genérico', 'Drive de disquete', NULL, 16, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (406, 'Trident', '3D Blade (PCI)', 8, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (407, 'Mitsumi', 'FX48++M', NULL, 5, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (408, 'ATI', 'Radeon IGP 345M', 64, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (409, 'Legacy Audio Drivers', 'ALI M5451 AC-LINK', NULL, 4, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (410, 'Broadcom', '54G Max Performance', NULL, 3, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (411, 'Conexant', '56K Aclink Modem', NULL, 6, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (412, 'Thoshiba', 'MK4025GAS', 40, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (413, 'Toshiba', 'SD-R2512 COMBO', NULL, 8, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (414, 'Intel', 'Pentium IV A', 2100, 11, NULL, 'MHZ');
INSERT INTO `modelos_itens` VALUES (415, 'n/a', 'Chipset ATI M. Radeon 7000 igp', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (416, 'ATI', 'Rage LT Pro TVOUT', 8, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (417, 'Intel', 'Pentium IV HT', 2800, 11, NULL, 'Mhz');
INSERT INTO `modelos_itens` VALUES (418, 'Dell', 'Optiplex GX-270', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (419, 'Intel', '82865G Graphics Controller', 64, 2, NULL, 'Mb');
INSERT INTO `modelos_itens` VALUES (420, 'Intel', 'Pro / 1000 MT (Dell)', NULL, 3, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (421, 'Intel', 'ACÂ´97 - 82801EB (Dell GX-270)', NULL, 4, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (422, 'Trident', '9685', 2, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (423, 'Nvidia', 'GeForce4 MX 440 AGP', 64, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (424, 'Samsung', 'DVD Combo (CDRW) SM-352F', NULL, 8, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (425, 'NVIDIA', 'RIVA TNT', 8, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (426, 'Samsung', 'IDE 7200 RPM', 40, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (427, 'LG', '52x32x52x', NULL, 9, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (428, 'Intel', 'Pentium IV A', 2800, 11, NULL, 'MHz');
INSERT INTO `modelos_itens` VALUES (429, 'Intel', '82845 G Graphics Controller', 64, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (430, 'nVidia', 'GeForce4 MX 4000', 64, -1, NULL, 'Mb');
INSERT INTO `modelos_itens` VALUES (431, 'NVidia', 'GeForce4 MX 4000', 64, 2, NULL, 'Mb');
INSERT INTO `modelos_itens` VALUES (432, 'Genérico', 'Intel Brookdale-G I845G', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (433, 'SIS', '7012', NULL, 4, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (434, 'Samsung', '7200 RPM', 80, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (435, 'Samsung', 'IDE 7200rpm', 80, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (436, 'LG', 'LM-I56N', NULL, 6, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (437, 'LG', '16x COMBO 52x32x52x', NULL, 8, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (438, 'Asus', 'P4S800', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (439, 'N/A', '', 8, 7, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (441, 'Pine', '3D Phanton XP-PCI2800', 32, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (442, 'Intel', 'Celeron', 1300, 11, NULL, 'MHZ');
INSERT INTO `modelos_itens` VALUES (443, 'Intel', 'i810-W83627', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (444, 'Intel', 'Pentium IV', 3000, 11, NULL, 'MHZ');
INSERT INTO `modelos_itens` VALUES (445, 'Dell', 'Optiplex GX 270 chipset i865G', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (446, 'Intel', 'Pro /1000 MT', NULL, 3, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (447, 'Western Digital', 'IDE 7200 rpm', 40, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (448, 'EliteGroup Computer System', 'ESC 651-M', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (449, 'Maxtor', 'IDE 5400 rpm', 30, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (450, 'FUJITSU', 'IDE 5400', 1.6, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (451, 'AMD', 'Semprom', 2200, 11, NULL, 'MHZ');
INSERT INTO `modelos_itens` VALUES (452, 'Asus', 'P4S800-MX', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (453, 'SIS', '661FX', 32, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (454, 'Broadcom', 'NetXtreme 57xx', 1000, 3, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (455, 'Intel', '82915G/GV/910GL Express', 128, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (456, 'Dell', 'Optiplex GX-280', NULL, 10, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (457, 'Western Digital', 'SATA 7200 rpm', 40, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (458, 'Intel', 'Xeon FSB 800MHZ', 2800, 11, NULL, 'MHZ');
INSERT INTO `modelos_itens` VALUES (459, 'Dell', 'Pesc 1425', 800, 10, NULL, 'MHZ');
INSERT INTO `modelos_itens` VALUES (460, 'ATI', 'RADEON 7000-M', 16, 2, NULL, 'MB');
INSERT INTO `modelos_itens` VALUES (461, 'Seagate', 'SCSI', 36, 1, NULL, 'GB');
INSERT INTO `modelos_itens` VALUES (462, 'LG', '8x COMBO 24x24x24x', NULL, 8, NULL, NULL);
INSERT INTO `modelos_itens` VALUES (463, 'NEC', 'ND-3530A 16XDVDR 4XDVDRW 48XCD', NULL, 8, NULL, NULL);



-- 
-- Extraindo dados da tabela `nivel`
-- 

INSERT INTO `nivel` VALUES (1, 'Administrador');
INSERT INTO `nivel` VALUES (2, 'Operador');
INSERT INTO `nivel` VALUES (3, 'Apenas Consulta');
INSERT INTO `nivel` VALUES (4, 'Contabilidade');
INSERT INTO `nivel` VALUES (5, 'Desabilitado');


-- 
-- Extraindo dados da tabela `polegada`
-- 

INSERT INTO `polegada` VALUES (1, '14 polegadas');
INSERT INTO `polegada` VALUES (2, '15 polegadas');
INSERT INTO `polegada` VALUES (3, '17 polegadas');



-- 
-- Extraindo dados da tabela `prioridades`
-- 

INSERT INTO `prioridades` VALUES (2, 'NíVEL 1', 18);
INSERT INTO `prioridades` VALUES (3, 'NíVEL 2', 19);
INSERT INTO `prioridades` VALUES (4, 'NíVEL 3', 20);
INSERT INTO `prioridades` VALUES (5, 'NíVEL 4', 2);


-- 
-- Extraindo dados da tabela `reitorias`
-- 

INSERT INTO `reitorias` VALUES (1, 'DEFAULT');


-- 
-- Extraindo dados da tabela `resolucao`
-- 

INSERT INTO `resolucao` VALUES (1, '9600 DPI');
INSERT INTO `resolucao` VALUES (2, '2400 DPI');
INSERT INTO `resolucao` VALUES (3, '1200 DPI');


-- 
-- Extraindo dados da tabela `situacao`
-- 

INSERT INTO `situacao` VALUES (1, 'Operacional', 'Equipamento sem problemas de funcionamento');
INSERT INTO `situacao` VALUES (2, 'Não Operacional', 'Equipamento utilizado apenas para testes de hardware e não funcionando');
INSERT INTO `situacao` VALUES (3, 'Em manutenção', 'Equipamento aguardando peça para manutenção');
INSERT INTO `situacao` VALUES (4, 'Furtado', 'Equipamentos furtados da empresa.');
INSERT INTO `situacao` VALUES (5, 'Trocado', 'Equipamento trocado por outro em função da sua garantia.');
INSERT INTO `situacao` VALUES (6, 'Aguardando orçamento', 'Aguardando orçamento para conserto');
INSERT INTO `situacao` VALUES (7, 'Sucateado', 'Equipamento não possui condições para conserto');



-- 
-- Extraindo dados da tabela `sla_solucao`
-- 

INSERT INTO `sla_solucao` VALUES (1, 15, '15 minutos');
INSERT INTO `sla_solucao` VALUES (2, 30, '30 minutos');
INSERT INTO `sla_solucao` VALUES (3, 45, '45 minutos');
INSERT INTO `sla_solucao` VALUES (4, 60, '1 hora');
INSERT INTO `sla_solucao` VALUES (5, 120, '2 horas');
INSERT INTO `sla_solucao` VALUES (6, 180, '3 horas');
INSERT INTO `sla_solucao` VALUES (7, 240, '4 horas');
INSERT INTO `sla_solucao` VALUES (8, 480, '8 horas');
INSERT INTO `sla_solucao` VALUES (9, 720, '12 horas');
INSERT INTO `sla_solucao` VALUES (10, 1440, '24 horas');
INSERT INTO `sla_solucao` VALUES (11, 2880, '2 dias');
INSERT INTO `sla_solucao` VALUES (12, 4320, '3 dias');
INSERT INTO `sla_solucao` VALUES (13, 5760, '4 dias');
INSERT INTO `sla_solucao` VALUES (14, 10080, '1 semana');
INSERT INTO `sla_solucao` VALUES (15, 20160, '2 semanas');
INSERT INTO `sla_solucao` VALUES (16, 30240, '3 semanas');
INSERT INTO `sla_solucao` VALUES (17, 43200, '1 mês');
INSERT INTO `sla_solucao` VALUES (18, 5, '5 minutos');
INSERT INTO `sla_solucao` VALUES (19, 10, '10 minutos');
INSERT INTO `sla_solucao` VALUES (20, 20, '20 minutos');
INSERT INTO `sla_solucao` VALUES (21, 25, '25 minutos');


-- 
-- Extraindo dados da tabela `status_categ`
-- 

INSERT INTO `status_categ` VALUES (1, 'AO USUÁRIO');
INSERT INTO `status_categ` VALUES (2, 'À ÀREA TÉCNICA');
INSERT INTO `status_categ` VALUES (3, 'À SERVIÇOS DE TERCEIROS');
INSERT INTO `status_categ` VALUES (4, 'INDEPENDENTE');


-- 
-- Extraindo dados da tabela `status`
-- 

INSERT INTO `status` VALUES (1, 'Aguardando atendimento', 2, 2);
INSERT INTO `status` VALUES (2, 'Em atendimento', 2, 1);
INSERT INTO `status` VALUES (3, 'Em estudo', 2, 1);
INSERT INTO `status` VALUES (4, 'Encerrada', 4, 3);
INSERT INTO `status` VALUES (7, 'Agendado com usuÁrio', 1, 2);
INSERT INTO `status` VALUES (12, 'Cancelado', 4, 3);
INSERT INTO `status` VALUES (15, 'Todos', 4, 2);
INSERT INTO `status` VALUES (16, 'Aguardando feedback do usuÁrio', 1, 2);
INSERT INTO `status` VALUES (19, 'IndisponÍvel para atendimento', 1, 2);
INSERT INTO `status` VALUES (21, 'Encaminhado para operador', 2, 1);
INSERT INTO `status` VALUES (22, 'Interrompido para atender outro chamado', 2, 1);
INSERT INTO `status` VALUES (25, 'Aguardando retorno do fornecedor', 3, 1);
INSERT INTO `status` VALUES (26, 'Com Backup', 4, 2);
INSERT INTO `status` VALUES (27, 'Reservado para Operador', 2, 1);


-- 
-- Extraindo dados da tabela `tempo_garantia`
-- 

INSERT INTO `tempo_garantia` VALUES (1, 12);
INSERT INTO `tempo_garantia` VALUES (2, 24);
INSERT INTO `tempo_garantia` VALUES (3, 36);
INSERT INTO `tempo_garantia` VALUES (4, 6);
INSERT INTO `tempo_garantia` VALUES (5, 18);



-- 
-- Extraindo dados da tabela `tipo_equip`
-- 

INSERT INTO `tipo_equip` VALUES (1, 'Computador PC');
INSERT INTO `tipo_equip` VALUES (2, 'Notebook');
INSERT INTO `tipo_equip` VALUES (3, 'Impressora');
INSERT INTO `tipo_equip` VALUES (4, 'Scanner');
INSERT INTO `tipo_equip` VALUES (5, 'Monitor');
INSERT INTO `tipo_equip` VALUES (6, 'Zip Drive');
INSERT INTO `tipo_equip` VALUES (7, 'Switch');
INSERT INTO `tipo_equip` VALUES (8, 'HUB');
INSERT INTO `tipo_equip` VALUES (9, 'Gravador externo de CD');
INSERT INTO `tipo_equip` VALUES (10, 'Placa externa de captura');
INSERT INTO `tipo_equip` VALUES (11, 'No Break');
INSERT INTO `tipo_equip` VALUES (12, 'Servidor SCSI');


-- 
-- Extraindo dados da tabela `tipo_garantia`
-- 

INSERT INTO `tipo_garantia` VALUES (1, 'Balcão');
INSERT INTO `tipo_garantia` VALUES (2, 'On site');


-- 
-- Extraindo dados da tabela `tipo_imp`
-- 

INSERT INTO `tipo_imp` VALUES (1, 'Matricial');
INSERT INTO `tipo_imp` VALUES (2, 'Jato de tinta');
INSERT INTO `tipo_imp` VALUES (3, 'Laser');
INSERT INTO `tipo_imp` VALUES (4, 'Multifuncional');
INSERT INTO `tipo_imp` VALUES (5, 'Copiadora');
INSERT INTO `tipo_imp` VALUES (6, 'Matricial cupom não fiscal');



-- 
-- Extraindo dados da tabela `tipo_item`
-- 

INSERT INTO `tipo_item` VALUES (1, 'HARDWARE');
INSERT INTO `tipo_item` VALUES (2, 'SOFTWARE');
INSERT INTO `tipo_item` VALUES (3, 'HARDWARE E SOFTWARE');


-- 
-- Extraindo dados da tabela `instituicao`
-- 

INSERT INTO `instituicao` VALUES (1, '01-DEFAULT', 1);


-- 
-- Extraindo dados da tabela `usuarios_areas`
-- 

INSERT INTO `usuarios_areas` VALUES (1, 1, '1');

INSERT INTO `permissoes` VALUES (1, 1, 1, 1);
INSERT INTO `permissoes` VALUES (2, 1, 2, 1);



UPDATE `nivel` SET `nivel_nome` = 'Somente Abertura' WHERE `nivel_cod` = 3;


CREATE TABLE `configusercall` (
`conf_cod` INT( 4 ) NOT NULL AUTO_INCREMENT ,
`conf_nivel` VARCHAR( 200 ) NOT NULL ,
`conf_scr_area` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_prob` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_desc` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_unit` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_tag` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_chktag` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_chkhist` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_contact` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_fone` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_local` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_btloadlocal` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_searchbylocal` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_operator` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_date` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_status` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_replicate` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_mail` INT( 1 ) DEFAULT '1' NOT NULL ,
`conf_scr_msg` TEXT NOT NULL ,
PRIMARY KEY ( `conf_cod` )
) COMMENT = 'tabela de configuração para usuários de somente abertura de chamados';


ALTER TABLE `configusercall` ADD `conf_opentoarea` INT( 4 ) DEFAULT '1' NOT NULL AFTER `conf_nivel` ;

ALTER TABLE `configusercall` ADD INDEX ( `conf_opentoarea` ) ;

ALTER TABLE `configusercall` ADD INDEX ( `conf_nivel` ) ;

ALTER TABLE `configusercall` ADD `conf_ownarea` INT( 4 ) DEFAULT '1' NOT NULL AFTER `conf_nivel` ;

ALTER TABLE `configusercall` ADD INDEX ( `conf_ownarea` ) ;

ALTER TABLE `configusercall` ADD `conf_user_opencall` INT( 1 ) DEFAULT '0' NOT NULL AFTER `conf_cod` ;

ALTER TABLE `configusercall` CHANGE `conf_nivel` `conf_custom_areas` VARCHAR( 200 ) NOT NULL;


INSERT INTO `configusercall` VALUES (1, 0, '2', 2, 1, 0, 0, 1, 1, 1, 0, 0, 1, 1, 1, 1, 0, 1, 1, 1, 0, 0, 'Seu chamado foi aberto com sucesso no sistema de ocorrências! O número é %numero%. Aguarde o atendimento pela equipe de suporte.');


ALTER TABLE `usuarios` ADD `user_admin` INT( 1 ) DEFAULT '0' NOT NULL ;



CREATE TABLE `utmp_usuarios` (
  `utmp_cod` int(4) NOT NULL auto_increment,
  `utmp_login` varchar(15) NOT NULL default '',
  `utmp_nome` varchar(40) NOT NULL default '',
  `utmp_email` varchar(40) NOT NULL default '',
  `utmp_passwd` varchar(40) NOT NULL default '',
  `utmp_rand` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`utmp_cod`),
  UNIQUE KEY `utmp_login` (`utmp_login`,`utmp_email`),
  KEY `utmp_rand` (`utmp_rand`)
)  COMMENT='Tabela de transição para cadastro de usuários';


CREATE TABLE `mailconfig` (
`mail_cod` INT( 4 ) NOT NULL AUTO_INCREMENT ,
`mail_issmtp` INT( 1 ) DEFAULT '1' NOT NULL ,
`mail_host` VARCHAR( 40 ) DEFAULT 'mail.smtp.com' NOT NULL ,
`mail_isauth` INT( 1 ) DEFAULT '0' NOT NULL ,
`mail_user` VARCHAR( 20 ) ,
`mail_pass` VARCHAR( 50 ) ,
`mail_from` VARCHAR( 40 ) DEFAULT 'ocomon@yourdomain.com' NOT NULL ,
`mail_ishtml` INT( 1 ) DEFAULT '1' NOT NULL ,
PRIMARY KEY ( `mail_cod` )
) COMMENT = 'Tabela de configuracao para envio de e-mails';


CREATE TABLE `msgconfig` (
`msg_cod` INT( 4 ) NOT NULL AUTO_INCREMENT ,
`msg_event` VARCHAR( 40 ) DEFAULT 'evento' NOT NULL ,
`msg_fromname` VARCHAR( 40 ) DEFAULT 'from' NOT NULL ,
`msg_replyto` VARCHAR( 40 ) DEFAULT 'ocomon@yourdomain.com' NOT NULL ,
`msg_subject` VARCHAR( 40 ) DEFAULT 'subject' NOT NULL ,
`msg_body` TEXT,
`msg_altbody` TEXT,
PRIMARY KEY ( `msg_cod` )
) COMMENT = 'Tabela de configuracao das mensagens de e-mail';


ALTER TABLE `msgconfig` ADD UNIQUE (
`msg_event`
); 

INSERT INTO `mailconfig` ( `mail_cod` , `mail_issmtp` , `mail_host` , `mail_isauth` , `mail_user` , `mail_pass` , `mail_from` , `mail_ishtml` )
VALUES (
'', '1', 'mail.smtp.com', '0', NULL , NULL , 'mail@yourdomain.com', '1'
);

INSERT INTO `msgconfig` VALUES (1, 'abertura-para-usuario', 'Sistema Ocomon', 'reply-to', 'CHAMADO ABERTO NO SISTEMA', 'Caro %usuario%,<br />Seu chamado foi aberto com sucesso no sistema de atendimento.<br />O n&uacute;mero do chamado &eacute; %numero%<br />Aguarde o atendimento pela equipe de suporte.<br />%site%', 'Caro %usuario%,\r\nSeu chamado foi aberto com sucesso no sistema de atendimento.\r\nO número do chamado é %numero%\r\nAguarde o atendimento pela equipe de suporte.\r\n%site%');
INSERT INTO `msgconfig` VALUES (2, 'abertura-para-area', 'Sistema Ocomon', 'reply-to', 'CHAMADO ABERTO PARA %area%', 'Sistema Ocomon<br />Foi aberto um novo chamado t&eacute;cnico para ser atendido pela &aacute;rea %area%.<br />O n&uacute;mero do chamado &eacute; %numero%<br />Descri&ccedil;&atilde;o: %descricao%<br />Contato: %contato%<br />Setor: %setor%<br />Ramal: %ramal%<br />Chamado aberto pelo operador: %operador%<br />%site%', 'Sistema Ocomon\r\nFoi aberto um novo chamado técnico para ser atendido pela área %area%.\r\nO número do chamado é %numero%\r\nDescrição: %descricao%\r\nContato: %contato%\r\nSetor: %setor%\r\nRamal: %ramal%\r\nChamado aberto pelo operador: %operador%\r\n%site%');
INSERT INTO `msgconfig` VALUES (3, 'encerra-para-area', 'SISTEMA OCOMON', 'reply-to', 'OCOMON - CHAMADO ENCERRADO', 'Sistema Ocomon<br />O chamado %numero% foi fechado pelo operador %operador%<br />Descri&ccedil;&atilde;o t&eacute;cnica: %descricao%<br />Solu&ccedil;&atilde;o: %solucao%', 'Sistema Ocomon\r\nO chamado %numero% foi fechado pelo operador %operador%\r\nDescrição técnica: %descricao%\r\nSolução: %solucao%');
INSERT INTO `msgconfig` VALUES (4, 'encerra-para-usuario', 'SISTEMA OCOMON', 'reply-to', 'OCOMON -CHAMADO ENCERRADO NO SISTEMA', 'Caro %contato%<br />Seu chamado foi encerrado no sistema de atendimento.<br />N&uacute;mero do chamado: %numero%<br />Para maiores informa&ccedil;&otilde;es acesso o sistema com seu nome de usu&aacute;rio e senha no endere&ccedil;o abaixo:<br />%site%', 'Caro %contato%\r\nSeu chamado foi encerrado no sistema de atendimento.\r\nNúmero do chamado: %numero%\r\nPara maiores informações acesso o sistema com seu nome de usuário e senha no endereço abaixo:\r\n%site%');
INSERT INTO `msgconfig` VALUES (5, 'edita-para-area', 'SISTEMA OCOMON', 'reply-to', 'CHAMADO EDITADO PARA %area%', '<span style="color: rgb(0, 0, 0);">Sistema Ocomon</span><br />Foram adicionadas informa&ccedil;&otilde;es ao chamado %numero% para a &aacute;rea %area%<br />Descri&ccedil;&atilde;o: %descricao%<br />Altera&ccedil;&atilde;o mais recente: %assentamento%<br />Contato: %contato%<br />Ramal: %ramal%<br />Ocorr&ecirc;ncia editada pelo operador: %operador%<br />%site%', 'Sistema Ocomon\r\nForam adicionadas informações ao chamado %numero% para a área %area%\r\nDescrição: %descricao%\r\nAlteração mais recente: %assentamento%\r\nContato: %contato%\r\nRamal: %ramal%\r\nOcorrência editada pelo operador: %operador%\r\n%site%');
INSERT INTO `msgconfig` VALUES (6, 'edita-para-usuario', 'SISTEMA OCOMON', 'reply-to', 'OCOMON - ALTERAÇÕES NO SEU CHAMADO', 'Caro %contato%,<br />O chamado %numero% foi editado no sistema de atendimento.<br />Altera&ccedil;&atilde;o mais recente: %assentamento%<br />Para maiores informa&ccedil;&otilde;es acesse o sistema com seu usu&aacute;rio e senha no endere&ccedil;o abaixo:<br />%site%', 'Caro %contato%,\r\nO chamado %numero% foi editado no sistema de atendimento.\r\nAlteração mais recente: %assentamento%\r\nPara maiores informações acesse o sistema com seu usuário e senha no endereço abaixo:\r\n%site%');
INSERT INTO `msgconfig` VALUES (7, 'edita-para-operador', 'SISTEMA OCOMON', 'reply-to', 'CHAMADO PARA %operador%', 'Caro %operador%,<br />O chamado %numero% foi editado e est&aacute; direcionado a voc&ecirc;.<br />Descri&ccedil;&atilde;o: %descricao%<br />Altera&ccedil;&atilde;o mais recente: %assentamento%<br />Contato: %contato%&nbsp;&nbsp; <br />Ramal: %ramal%<br />Ocorr&ecirc;ncia editada pelo operador: %editor%<br />%site%', 'Caro %operador%,\r\nO chamado %numero% foi editado e está direcionado a você.\r\nDescrição: %descricao%\r\nAlteração mais recente: %assentamento%\r\nContato: %contato%\r\nRamal: %ramal%\r\nOcorrência editada pelo operador: %editor%\r\n%site%');
INSERT INTO `msgconfig` VALUES (8, 'cadastro-usuario', 'SISTEMA OCOMON', 'reply-to', 'OCOMON - CONFIRMAÇÃO DE CADASTRO', 'Prezado %usuario%,<br />Sua solicita&ccedil;&atilde;o para cria&ccedil;&atilde;o do login &quot;%login%&quot; foi bem sucedida!<br />Para confirmar sua inscri&ccedil;&atilde;o clique no link abaixo:<br />%linkconfirma%', 'Prezado %usuario%,\r\nSua solicitação para criação do login &quot;%login%&quot; foi bem sucedida!\r\nPara confirmar sua inscrição clique no link abaixo:\r\n%linkconfirma%');
   

INSERT INTO `msgconfig` VALUES ('', 'cadastro-usuario-from-admin', 'SISTEMA OCOMON', 'reply-to', 'OCOMON - CONFIRMAÇÃO DE CADASTRO', 'Prezado %usuario%<br />Seu cadastro foi efetuado com sucesso no sistema de chamados do Helpdesk<br />Seu login &eacute;: %login%<br />Para abrir chamados acesse o site %site%<br />Atenciosamente Helpdesk Unilasalle', 'Prezado %usuario%\r\nSeu cadastro foi efetuado com sucesso no sistema de chamados do Helpdesk\r\nSeu login é: %login%\r\nPara abrir chamados acesse o site %site%\r\nAtenciosamente Helpdesk Unilasalle');

ALTER TABLE `mailconfig` CHANGE `mail_user` `mail_user` VARCHAR( 40 ) DEFAULT NULL;


CREATE TABLE `imagens` (
`img_cod` INT( 4 ) NOT NULL AUTO_INCREMENT ,
`img_oco` INT( 4 ) NOT NULL ,
`img_nome` VARCHAR( 30 ) NOT NULL ,
`img_tipo` VARCHAR( 20 ) NOT NULL ,
`img_bin` LONGBLOB NOT NULL ,
PRIMARY KEY ( `img_cod` ) ,
INDEX ( `img_oco` )
) COMMENT = 'Tabela de imagens anexas aos chamados';

ALTER TABLE `imagens` ADD `img_largura` INT( 4 ) NULL ,
ADD `img_altura` INT( 4 ) NULL ;


ALTER TABLE `imagens` ADD `img_inv` INT( 6 ) NULL AFTER `img_oco` ,
ADD `img_model` INT( 4 ) NULL AFTER `img_inv` ;

ALTER TABLE `imagens` ADD INDEX ( `img_inv` , `img_model` ) ;

 ALTER TABLE `imagens` CHANGE `img_oco` `img_oco` INT( 4 ) NULL;

ALTER TABLE `imagens` ADD `img_inst` INT( 4 ) NULL AFTER `img_oco` ;

ALTER TABLE `imagens` ADD INDEX ( `img_inst` ) ;



ALTER TABLE `configusercall` ADD `conf_scr_upload` INT( 1 ) NOT NULL DEFAULT '0';



CREATE TABLE `config` (
`conf_cod` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`conf_sql_user` VARCHAR( 20 ) NOT NULL DEFAULT 'ocomon',
`conf_sql_passwd` VARCHAR( 50 ) NULL ,
`conf_sql_server` VARCHAR( 40 ) NOT NULL DEFAULT 'localhost',
`conf_sql_db` VARCHAR( 40 ) NOT NULL DEFAULT 'ocomon',
`conf_db_ccusto` VARCHAR( 40 ) NOT NULL DEFAULT 'ocomon',
`conf_tb_ccusto` VARCHAR( 40 ) NOT NULL DEFAULT 'CCUSTO',
`conf_ccusto_id` VARCHAR( 20 ) NOT NULL DEFAULT 'codigo',
`conf_ccusto_desc` VARCHAR( 20 ) NOT NULL DEFAULT 'descricao',
`conf_ccusto_cod` VARCHAR( 20 ) NOT NULL DEFAULT 'codccusto',
`conf_ocomon_site` VARCHAR( 50 ) NOT NULL DEFAULT 'http://localhost/ocomon/',
`conf_inst_terceira` INT( 4 ) NOT NULL DEFAULT '-1',
`conf_log_path` VARCHAR( 50 ) NOT NULL DEFAULT '../../includes/logs/',
`conf_logo_path` VARCHAR( 50 ) NOT NULL DEFAULT '../../includes/logos',
`conf_icons_path` VARCHAR( 50 ) NOT NULL DEFAULT '../../includes/icons/',
`conf_help_icon` VARCHAR( 50 ) NOT NULL DEFAULT '../../includes/icons/solucoes2.png',
`conf_help_path` VARCHAR( 50 ) NOT NULL DEFAULT '../../includes/help/',
`conf_language` VARCHAR( 15 ) NOT NULL DEFAULT 'pt_BR.php',
`conf_auth_type` VARCHAR( 30 ) NOT NULL DEFAULT 'SYSTEM',
`conf_upld_size` INT( 10 ) NOT NULL DEFAULT '307200',
`conf_upld_width` INT( 5 ) NOT NULL DEFAULT '800',
`conf_upld_height` INT( 5 ) NOT NULL DEFAULT '600'
)  COMMENT = 'Tabela de configurações diversas do sistema';


INSERT INTO `config` ( `conf_cod` , `conf_sql_user` , `conf_sql_passwd` , `conf_sql_server` , `conf_sql_db` , `conf_db_ccusto` , `conf_tb_ccusto` , `conf_ccusto_id` , `conf_ccusto_desc` , `conf_ccusto_cod` , `conf_ocomon_site` , `conf_inst_terceira` , `conf_log_path` , `conf_logo_path` , `conf_icons_path` , `conf_help_icon` , `conf_help_path` , `conf_language` , `conf_auth_type` , `conf_upld_size` , `conf_upld_width` , `conf_upld_height` )
VALUES (
NULL , 'ocomon', NULL , 'localhost', 'ocomon', 'ocomon', 'CCUSTO', 'codigo', 'descricao', 'codccusto', 'http://localhost/ocomon/', '-1', '../../includes/logs/', '../../includes/logos', '../../includes/icons/', '../../includes/icons/solucoes2.png', '../../includes/help/', 'pt_BR.php', 'SYSTEM', '307200', '800', '600' );


CREATE TABLE `hw_alter` (
`hwa_cod` INT( 4 ) NOT NULL AUTO_INCREMENT ,
`hwa_inst` INT( 4 ) NOT NULL ,
`hwa_inv` INT( 6 ) NOT NULL ,
`hwa_item` INT( 4 ) NOT NULL ,
`hwa_user` INT( 4 ) NOT NULL ,
PRIMARY KEY ( `hwa_cod` ) ,
INDEX ( `hwa_inst` , `hwa_inv` , `hwa_item` , `hwa_user` )
)  COMMENT = 'Tabela para armazenar alteracoes de hw';

ALTER TABLE `hw_alter` ADD `hwa_data` DATETIME NOT NULL ;


CREATE TABLE `ocodeps` (
`dep_pai` INT( 6 ) NOT NULL ,
`dep_filho` INT( 6 ) NOT NULL ,
PRIMARY KEY ( `dep_pai` ) ,
INDEX ( `dep_filho` )
)  COMMENT = 'Tabela para controle de sub-chamados';

ALTER TABLE `ocodeps` DROP PRIMARY KEY;
ALTER TABLE `ocodeps` ADD INDEX ( `dep_pai` );


CREATE TABLE `doc_time` (
`doc_oco` INT( 6 ) NOT NULL ,
`doc_open` INT( 10 ) DEFAULT '0' NOT NULL ,
`doc_edit` INT( 10 ) DEFAULT '0' NOT NULL ,
`doc_close` INT( 10 ) DEFAULT '0' NOT NULL ,
PRIMARY KEY ( `doc_oco` )
) COMMENT = 'Tabela para armazenar o tempo de documentacao de cada chamado';


ALTER TABLE `doc_time` ADD `doc_user` INT( 4 ) NOT NULL ;

ALTER TABLE `doc_time` ADD INDEX ( `doc_user` ) ;
ALTER TABLE `doc_time` DROP PRIMARY KEY;

ALTER TABLE `doc_time` ADD `doc_id` INT( 6 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;
ALTER TABLE `doc_time` ADD INDEX ( `doc_oco` );

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
)  ;

CREATE TABLE `prob_tipo_2` (
`probt2_cod` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`probt2_desc` VARCHAR( 30 ) NOT NULL
)  ;

CREATE TABLE `prob_tipo_3` (
`probt3_cod` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`probt3_desc` VARCHAR( 30 ) NOT NULL
)  ;


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
)  ;

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
)  ;

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
)  COMMENT = 'Tabela de Temas por usuario';


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
)  COMMENT = 'Tabela de Contatos';


CREATE TABLE `lock_oco` (
`lck_id` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`lck_uid` INT( 4 ) NOT NULL ,
`lck_oco` INT( 5 ) NOT NULL ,
INDEX ( `lck_uid` ) ,
UNIQUE (
`lck_oco`
)
)  COMMENT = 'Tabela de Lock para chamados em edição';

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
)  COMMENT = 'Tabela de associacao de equipamentos com componentes';


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
)  COMMENT = 'Tabela de controle para envio de email sobre prazo de garantias';


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
)  COMMENT = 'Tabela de scripts de solucoes';

ALTER TABLE `ocorrencias` ADD `oco_script_sol` INT( 4 ) NULL ;

ALTER TABLE `ocorrencias` ADD INDEX ( `oco_script_sol` ) ;


CREATE TABLE `mail_templates` (
`tpl_cod` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`tpl_sigla` VARCHAR( 10 ) NOT NULL ,
`tpl_desc` TEXT NOT NULL ,
`tpl_msg_html` TEXT NOT NULL ,
`tpl_msg_text` TEXT NOT NULL
)  COMMENT = 'Tabela de templates de e-mails';

ALTER TABLE `mail_templates` CHANGE `tpl_desc` `tpl_subject` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

CREATE TABLE `mail_list` (
`ml_cod` INT( 4 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ml_sigla` VARCHAR( 15 ) NOT NULL ,
`ml_desc` TEXT NOT NULL ,
`ml_address` TEXT NOT NULL
)  COMMENT = 'Tabela de listas de distribuicao';

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
)  COMMENT = 'Tabela de histórico de emails enviados';

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




-- INICIO DA VERSAO 2.0RC6 ---------------

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
) ENGINE=MyISAM;


INSERT INTO areaXarea_abrechamado (area, area_abrechamado) VALUES (1, 1);


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

-- FINAL DA VERSAO 2.0RC6 ---------------
