-- --------------------------------------------------------
-- Servidor:                     localhost
-- Versão do servidor:           PostgreSQL 9.5.14, compiled by Visual C++ build 1800, 64-bit
-- OS do Servidor:               
-- HeidiSQL Versão:              10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES  */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura para tabela public.item_todo
CREATE TABLE IF NOT EXISTS "item_todo" (
	"id" INTEGER NOT NULL DEFAULT nextval('item_todo_id_seq'::regclass) COMMENT E'',
	"id_kanban_item" INTEGER NULL DEFAULT NULL COMMENT E'',
	"nome" CHARACTER VARYING NULL DEFAULT NULL COMMENT E'',
	"ativo" BOOLEAN NULL DEFAULT true COMMENT E'',
	PRIMARY KEY ("id")
);

-- Copiando dados para a tabela public.item_todo: 2 rows
/*!40000 ALTER TABLE "item_todo" DISABLE KEYS */;
INSERT INTO "item_todo" ("id", "id_kanban_item", "nome", "ativo") VALUES
	(11, 28, E'sfasdfasd', E'false'),
	(12, 28, E'task2', E'true'),
	(13, 29, E'fazer linha', E'true'),
	(16, 5, E'teste', E'true'),
	(17, 5, E'zzzz', E'true'),
	(14, 2, E'teste', E'false'),
	(15, 2, E'testes', E'true'),
	(18, 2, E'teste', E'true');
/*!40000 ALTER TABLE "item_todo" ENABLE KEYS */;

-- Copiando estrutura para tabela public.kanban_item
CREATE TABLE IF NOT EXISTS "kanban_item" (
	"title" TEXT NULL DEFAULT NULL COMMENT E'',
	"content" TEXT NULL DEFAULT NULL COMMENT E'',
	"color" TEXT NULL DEFAULT NULL COMMENT E'',
	"item_order" INTEGER NULL DEFAULT NULL COMMENT E'',
	"stage_id" INTEGER NULL DEFAULT NULL COMMENT E'',
	"id" INTEGER NOT NULL DEFAULT nextval('kanban_item_id_seq'::regclass) COMMENT E'',
	"prazo" TIMESTAMP WITHOUT TIME ZONE NULL DEFAULT NULL COMMENT E'',
	"prioridade" CHARACTER VARYING(10) NULL DEFAULT NULL COMMENT E'',
	"data_inicial" TIMESTAMP WITHOUT TIME ZONE NULL DEFAULT NULL COMMENT E'',
	PRIMARY KEY ("id")
);

-- Copiando dados para a tabela public.kanban_item: 15 rows
/*!40000 ALTER TABLE "kanban_item" DISABLE KEYS */;
INSERT INTO "kanban_item" ("title", "content", "color", "item_order", "stage_id", "id", "prazo", "prioridade", "data_inicial") VALUES
	(E'Adicionar datas', E'<p>Adicionar data de inicio, data de fim</p><p>Criar uma barra de desempenho de acordo com as TO DO\'s</p><p>Mostrar as atrasadas ou notificar</p>', E'#0fad71', 3, 1, 6, NULL, NULL, E'2020-06-18 19:52:22.312826'),
	(E'Sistema de Ticket', E'<p>Criar um sistema de ticket integrado, que pode virar uma tarefa.</p>', E'#25647f', 4, 1, 8, NULL, NULL, E'2020-06-18 19:52:22.314305'),
	(E'Adicionar to do', E'<p>Adicionar lista de itens para fazer e ir marcando</p>', E'#5c990e', 2, 2, 3, E'2020-06-22 00:00:00', E'Urgente', E'2020-06-18 19:52:22.194231'),
	(E'datainici', E'asdfsfasdfasfdadsfa', NULL, 3, 2, 28, E'2020-06-19 12:00:00', E'Média', E'2020-06-18 12:00:00'),
	(E'Adicionar Log', E'<p>Adicionar log de mudanças no item do kanban</p>', E'#2f64a3', 4, 2, 4, E'2020-07-18 00:00:00', E'Baixa', E'2020-06-18 19:52:22.303693'),
	(NULL, NULL, NULL, NULL, NULL, 29, NULL, NULL, NULL),
	(NULL, NULL, NULL, NULL, NULL, 30, NULL, NULL, NULL),
	(NULL, NULL, NULL, NULL, NULL, 31, NULL, NULL, NULL),
	(NULL, NULL, NULL, NULL, NULL, 32, NULL, NULL, NULL),
	(NULL, NULL, NULL, NULL, NULL, 33, NULL, NULL, NULL),
	(NULL, NULL, NULL, NULL, NULL, 34, NULL, NULL, NULL),
	(NULL, NULL, NULL, NULL, NULL, 35, NULL, NULL, NULL),
	(E'Ver como funciona', E'Ver como o sistema de kanban funciona no código', E'#ee3838', 1, 5, 1, E'2020-06-20 00:00:00', E'Baixa', E'2020-06-18 19:52:22.315445'),
	(E'Adicionar Prioridade', E'<p>Adicionar uma combo com prioridade do Kanban</p>', E'#f40000', 2, 1, 5, E'2020-06-28 19:52:00', E'Baixa', E'2020-06-18 19:52:22.304984'),
	(E'Criar Projetos', E'Criar projetos, e filtrar os kanbans por projeto, lembrar de alterar nos 3 forms do kanban', E'#8e0e0e', 1, 2, 2, E'2020-06-29 00:00:00', E'Média', E'2020-06-18 19:52:22.302044');
/*!40000 ALTER TABLE "kanban_item" ENABLE KEYS */;

-- Copiando estrutura para tabela public.kanban_item_stage_log
CREATE TABLE IF NOT EXISTS "kanban_item_stage_log" (
	"id" INTEGER NOT NULL DEFAULT nextval('kanban_item_stage_log_id_seq'::regclass) COMMENT E'',
	"id_kanban_item" INTEGER NULL DEFAULT NULL COMMENT E'',
	"data_modificacao" TIMESTAMP WITHOUT TIME ZONE NULL DEFAULT NULL COMMENT E'',
	"descricao" CHARACTER VARYING NULL DEFAULT NULL COMMENT E'',
	"titulo" CHARACTER VARYING NULL DEFAULT NULL COMMENT E'',
	PRIMARY KEY ("id")
);

-- Copiando dados para a tabela public.kanban_item_stage_log: 0 rows
/*!40000 ALTER TABLE "kanban_item_stage_log" DISABLE KEYS */;
INSERT INTO "kanban_item_stage_log" ("id", "id_kanban_item", "data_modificacao", "descricao", "titulo") VALUES
	(4, 2, E'2020-06-19 12:49:01', E'Movido para a etapa <strong>Fazendo</strong>', E'Alteração de Etapa'),
	(1, 3, E'2020-06-19 12:40:06', E'Movido para a etapa <strong>Fazendo</strong>', E'Alteração de Etapa'),
	(2, 2, E'2020-06-19 12:40:33', E'Movido para a etapa <strong>Fazendo</strong>', E'Alteração de Etapa'),
	(3, 2, E'2020-06-19 12:40:48', E'Movido para a etapa <strong>Fazendo</strong>', E'Alteração de Etapa');
/*!40000 ALTER TABLE "kanban_item_stage_log" ENABLE KEYS */;

-- Copiando estrutura para tabela public.kanban_stage
CREATE TABLE IF NOT EXISTS "kanban_stage" (
	"title" TEXT NULL DEFAULT NULL COMMENT E'',
	"color" TEXT NULL DEFAULT NULL COMMENT E'',
	"stage_order" INTEGER NULL DEFAULT NULL COMMENT E'',
	"id" INTEGER NOT NULL DEFAULT nextval('kanban_stage_id_seq'::regclass) COMMENT E'',
	"id_projeto" INTEGER NULL DEFAULT NULL COMMENT E'',
	PRIMARY KEY ("id")
);

-- Copiando dados para a tabela public.kanban_stage: 5 rows
/*!40000 ALTER TABLE "kanban_stage" DISABLE KEYS */;
INSERT INTO "kanban_stage" ("title", "color", "stage_order", "id", "id_projeto") VALUES
	(E'Concluido', E'blue', 5, 4, 1),
	(E'Projeto', E'#444', 1, 1, 2),
	(E'Fazendo', E'#FFB300', 2, 2, 2),
	(E'Testando', NULL, 3, 5, 2),
	(E'Ajustando', E'red', 4, 3, 2);
/*!40000 ALTER TABLE "kanban_stage" ENABLE KEYS */;

-- Copiando estrutura para tabela public.projeto
CREATE TABLE IF NOT EXISTS "projeto" (
	"id" INTEGER NOT NULL DEFAULT nextval('projeto_id_seq'::regclass) COMMENT E'',
	"nome" CHARACTER VARYING NULL DEFAULT NULL COMMENT E'',
	PRIMARY KEY ("id")
);

-- Copiando dados para a tabela public.projeto: 0 rows
/*!40000 ALTER TABLE "projeto" DISABLE KEYS */;
INSERT INTO "projeto" ("id", "nome") VALUES
	(1, E'Projeto 1'),
	(2, E'Projeto 2'),
	(3, E'Projeto Vazio 2'),
	(4, E'FagnerSql'),
	(5, E'Teste');
/*!40000 ALTER TABLE "projeto" ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
