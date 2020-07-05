-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 05-Jul-2020 às 13:43
-- Versão do servidor: 10.1.37-MariaDB
-- versão do PHP: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suadatabase`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `item_todo`
--

CREATE TABLE `item_todo` (
  `id` int(11) NOT NULL,
  `id_kanban_item` int(11) DEFAULT NULL,
  `nome` longtext,
  `ativo` tinyint(1) DEFAULT '1',
  `trial740` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TRIAL';

--
-- Extraindo dados da tabela `item_todo`
--

INSERT INTO `item_todo` (`id`, `id_kanban_item`, `nome`, `ativo`, `trial740`) VALUES
(11, 28, 'sfasdfasd', 0, 'T'),
(12, 28, 'task2', 1, 'T'),
(13, 29, 'fazer linha', 1, 'T'),
(14, 2, 'teste', 0, 'T'),
(15, 2, 'testes', 1, 'T'),
(16, 5, 'teste', 1, 'T'),
(17, 5, 'zzzz', 1, 'T'),
(18, 2, 'teste', 1, 'T');

-- --------------------------------------------------------

--
-- Estrutura da tabela `kanban_item`
--

CREATE TABLE `kanban_item` (
  `title` longtext,
  `content` longtext,
  `color` longtext,
  `item_order` int(11) DEFAULT NULL,
  `stage_id` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `prazo` datetime DEFAULT NULL,
  `prioridade` varchar(10) DEFAULT NULL,
  `data_inicial` datetime DEFAULT NULL,
  `trial740` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TRIAL';

--
-- Extraindo dados da tabela `kanban_item`
--

INSERT INTO `kanban_item` (`title`, `content`, `color`, `item_order`, `stage_id`, `id`, `prazo`, `prioridade`, `data_inicial`, `trial740`) VALUES
('Ver como funciona', 'Ver como o sistema de kanban funciona no cÃ³digo', '#ee3838', 1, 5, 1, '2020-06-20 00:00:00', 'Baixa', '2020-06-18 19:52:22', 'T'),
('Criar Projetos', 'Criar projetos, e filtrar os kanbans por projeto, lembrar de alterar nos 3 forms do kanban', '#8e0e0e', 1, 2, 2, '2020-06-29 00:00:00', 'MÃ©dia', '2020-06-18 19:52:22', 'T'),
('Adicionar to do', '<p>Adicionar lista de itens para fazer e ir marcando</p>', '#5c990e', 2, 2, 3, '2020-06-22 00:00:00', 'Urgente', '2020-06-18 19:52:22', 'T'),
('Adicionar Log', '<p>Adicionar log de mudanÃ§as no item do kanban</p>', '#2f64a3', 4, 2, 4, '2020-07-18 00:00:00', 'Baixa', '2020-06-18 19:52:22', 'T'),
('Adicionar Prioridade', '<p>Adicionar uma combo com prioridade do Kanban</p>', '#f40000', 2, 1, 5, '2020-06-28 19:52:00', 'Baixa', '2020-06-18 19:52:22', 'T'),
('Adicionar datas', '<p>Adicionar data de inicio, data de fim</p><p>Criar uma barra de desempenho de acordo com as TO DO\'s</p><p>Mostrar as atrasadas ou notificar</p>', '#0fad71', 3, 1, 6, NULL, NULL, '2020-06-18 19:52:22', 'T'),
('Sistema de Ticket', '<p>Criar um sistema de ticket integrado, que pode virar uma tarefa.</p>', '#25647f', 4, 1, 8, NULL, NULL, '2020-06-18 19:52:22', 'T'),
('datainici', 'asdfsfasdfasfdadsfa', NULL, 3, 2, 28, '2020-06-19 12:00:00', 'MÃ©dia', '2020-06-18 12:00:00', 'T'),
(NULL, NULL, NULL, NULL, NULL, 29, NULL, NULL, NULL, 'T'),
(NULL, NULL, NULL, NULL, NULL, 30, NULL, NULL, NULL, 'T'),
(NULL, NULL, NULL, NULL, NULL, 31, NULL, NULL, NULL, 'T'),
(NULL, NULL, NULL, NULL, NULL, 32, NULL, NULL, NULL, 'T'),
(NULL, NULL, NULL, NULL, NULL, 33, NULL, NULL, NULL, 'T'),
(NULL, NULL, NULL, NULL, NULL, 34, NULL, NULL, NULL, 'T'),
(NULL, NULL, NULL, NULL, NULL, 35, NULL, NULL, NULL, 'T');

-- --------------------------------------------------------

--
-- Estrutura da tabela `kanban_item_stage_log`
--

CREATE TABLE `kanban_item_stage_log` (
  `id` int(11) NOT NULL,
  `id_kanban_item` int(11) DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `descricao` longtext,
  `titulo` longtext,
  `trial743` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TRIAL';

--
-- Extraindo dados da tabela `kanban_item_stage_log`
--

INSERT INTO `kanban_item_stage_log` (`id`, `id_kanban_item`, `data_modificacao`, `descricao`, `titulo`, `trial743`) VALUES
(1, 3, '2020-06-19 12:40:06', 'Movido para a etapa <strong>Fazendo</strong>', 'AlteraÃ§Ã£o de Etapa', 'T'),
(2, 2, '2020-06-19 12:40:33', 'Movido para a etapa <strong>Fazendo</strong>', 'AlteraÃ§Ã£o de Etapa', 'T'),
(3, 2, '2020-06-19 12:40:48', 'Movido para a etapa <strong>Fazendo</strong>', 'AlteraÃ§Ã£o de Etapa', 'T'),
(4, 2, '2020-06-19 12:49:01', 'Movido para a etapa <strong>Fazendo</strong>', 'AlteraÃ§Ã£o de Etapa', 'T');

-- --------------------------------------------------------

--
-- Estrutura da tabela `kanban_stage`
--

CREATE TABLE `kanban_stage` (
  `title` longtext,
  `color` longtext,
  `stage_order` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `id_projeto` int(11) DEFAULT NULL,
  `trial743` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TRIAL';

--
-- Extraindo dados da tabela `kanban_stage`
--

INSERT INTO `kanban_stage` (`title`, `color`, `stage_order`, `id`, `id_projeto`, `trial743`) VALUES
('Projeto', '#444', 1, 1, 2, 'T'),
('Fazendo', '#FFB300', 2, 2, 2, 'T'),
('Ajustando', 'red', 4, 3, 2, 'T'),
('Concluido', 'blue', 5, 4, 1, 'T'),
('Testando', NULL, 3, 5, 2, 'T');

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto`
--

CREATE TABLE `projeto` (
  `id` int(11) NOT NULL,
  `nome` longtext,
  `trial747` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TRIAL';

--
-- Extraindo dados da tabela `projeto`
--

INSERT INTO `projeto` (`id`, `nome`, `trial747`) VALUES
(1, 'Projeto 1', 'T'),
(2, 'Projeto 2', 'T'),
(3, 'Projeto Vazio 2', 'T'),
(4, 'FagnerSql', 'T'),
(5, 'Teste', 'T');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item_todo`
--
ALTER TABLE `item_todo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_todo_fk` (`id_kanban_item`);

--
-- Indexes for table `kanban_item`
--
ALTER TABLE `kanban_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kanban_item_stage_log`
--
ALTER TABLE `kanban_item_stage_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kanban_item_stage_log_fk` (`id_kanban_item`);

--
-- Indexes for table `kanban_stage`
--
ALTER TABLE `kanban_stage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projeto`
--
ALTER TABLE `projeto`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item_todo`
--
ALTER TABLE `item_todo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `kanban_item`
--
ALTER TABLE `kanban_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `kanban_item_stage_log`
--
ALTER TABLE `kanban_item_stage_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kanban_stage`
--
ALTER TABLE `kanban_stage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `projeto`
--
ALTER TABLE `projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `item_todo`
--
ALTER TABLE `item_todo`
  ADD CONSTRAINT `item_todo_fk` FOREIGN KEY (`id_kanban_item`) REFERENCES `kanban_item` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `kanban_item_stage_log`
--
ALTER TABLE `kanban_item_stage_log`
  ADD CONSTRAINT `kanban_item_stage_log_fk` FOREIGN KEY (`id_kanban_item`) REFERENCES `kanban_item` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
