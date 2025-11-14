-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 06-Nov-2025 às 02:19
-- Versão do servidor: 5.7.17
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projeto_tech`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento`
--

CREATE TABLE `agendamento` (
  `id_agendamento` int(11) NOT NULL,
  `data` date NOT NULL,
  `horario` time NOT NULL,
  `local` varchar(100) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `status` enum('Pendente','Confirmado','Cancelado') DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `agendamento`
--

INSERT INTO `agendamento` (`id_agendamento`, `data`, `horario`, `local`, `id_usuario`, `status`) VALUES
(1, '2025-12-17', '21:00:00', 'rua teste de janeiro LLLLLLLLLLLLLLLLLLLLLLLLllll', 0, 'Confirmado'),
(2, '2026-12-25', '12:30:00', 'Rua tanto ', 4, 'Cancelado'),
(3, '2026-03-19', '12:00:00', 'R. Augusta Candiani, 64 - Inhoaíba RJ - Rio de Janeiro, CEP 23070-020', 11, 'Pendente'),
(4, '2025-10-20', '12:32:00', 'mensagem, xique xique, BA', 11, 'Cancelado'),
(5, '2026-06-11', '14:00:00', 'R. Engenheiro de Softeare, 64 - Inhoaíba RJ - Rio de Janeiro, CEP 23070-020', 11, 'Confirmado'),
(6, '2027-04-21', '21:45:00', 'Lugar lindo ', 0, 'Pendente'),
(8, '2025-12-05', '21:45:00', 'MATA ESSE CARA NO DETROIT', 0, 'Pendente');

-- --------------------------------------------------------

--
-- Estrutura da tabela `aulas`
--

CREATE TABLE `aulas` (
  `id_aula` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `tipo` enum('video','texto','pdf','link') COLLATE utf8mb4_unicode_ci DEFAULT 'video',
  `conteudo` text COLLATE utf8mb4_unicode_ci,
  `duracao_minutos` int(11) DEFAULT NULL,
  `ordem` int(11) DEFAULT '1',
  `ativo` tinyint(1) DEFAULT '1',
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `aulas`
--

INSERT INTO `aulas` (`id_aula`, `id_modulo`, `titulo`, `descricao`, `tipo`, `conteudo`, `duracao_minutos`, `ordem`, `ativo`, `data_criacao`) VALUES
(1, 1, 'Bem-vindo ao Curso', 'Introdução ao curso e metodologia', 'video', 'https://www.youtube.com/embed/Ejkb_YpuHWs', 15, 1, 1, '2025-10-31 02:40:59'),
(2, 1, 'O que é Desenvolvimento Web?', 'Entenda como a web funciona', 'video', 'https://www.youtube.com/embed/hwBUU9CP4qI', 25, 2, 1, '2025-10-31 02:40:59'),
(3, 1, 'Ferramentas Necessárias', 'Instalando e configurando o ambiente', 'video', 'https://www.youtube.com/embed/K0y2tc38l2s', 30, 3, 1, '2025-10-31 02:40:59'),
(4, 2, 'Estrutura HTML', 'Tags e elementos HTML5', 'video', 'https://www.youtube.com/embed/epDCjksKMok', 40, 1, 1, '2025-10-31 02:40:59'),
(5, 2, 'CSS Básico', 'Estilizando páginas web', 'video', 'https://www.youtube.com/embed/LWU2OR19ZG4', 45, 2, 1, '2025-10-31 02:40:59'),
(6, 2, 'Flexbox e Grid', 'Layouts modernos com CSS', 'video', 'https://www.youtube.com/embed/3YW65K6LcIA', 50, 3, 1, '2025-10-31 02:40:59'),
(7, 2, 'Projeto Prático: Landing Page', 'Crie sua primeira página completa', 'texto', '<h2>Projeto: Landing Page Responsiva</h2><p>Neste projeto você vai criar uma landing page completa usando HTML5 e CSS3.</p><h3>Requisitos:</h3><ul><li>Header com logo e menu</li><li>Seção hero com CTA</li><li>Cards de serviços</li><li>Footer com redes sociais</li></ul>', 60, 4, 1, '2025-10-31 02:40:59'),
(8, 3, 'Introdução ao JavaScript', 'Sintaxe e conceitos básicos', 'video', 'https://www.youtube.com/embed/Ptbk2af68e8', 35, 1, 1, '2025-10-31 02:40:59'),
(9, 3, 'DOM Manipulation', 'Manipulando elementos da página', 'video', 'https://www.youtube.com/embed/BXqUH86F-kA', 40, 2, 1, '2025-10-31 02:40:59'),
(10, 3, 'Eventos e Interatividade', 'Capturando eventos do usuário', 'video', 'https://www.youtube.com/embed/0fKg7e37bQE', 35, 3, 1, '2025-10-31 02:40:59'),
(11, 4, 'Introdução ao PHP', 'Linguagem server-side', 'video', 'https://www.youtube.com/embed/F7KzJ7e6EAc', 45, 1, 1, '2025-10-31 02:40:59'),
(12, 4, 'MySQL Básico', 'Banco de dados relacional', 'video', 'https://www.youtube.com/embed/Ofktsne-utM', 40, 2, 1, '2025-10-31 02:40:59'),
(13, 4, 'Projeto Final: Sistema CRUD', 'Aplicação web completa', 'texto', '<h2>Projeto Final</h2><p>Desenvolva um sistema completo com:</p><ul><li>Cadastro de usuários</li><li>Login e autenticação</li><li>CRUD completo</li><li>Conexão com banco de dados</li></ul>', 90, 3, 1, '2025-10-31 02:40:59'),
(14, 5, 'Bem-vindo ao Curso', 'Introdução e apresentação do curso', 'video', 'https://www.youtube.com/embed/Ejkb_YpuHWs', 15, 1, 1, '2025-10-31 21:02:10'),
(15, 5, 'O que é Desenvolvimento Web?', 'Entenda como funciona a web e seus componentes', 'video', 'https://www.youtube.com/embed/5qap5aO4i9A', 25, 2, 1, '2025-10-31 21:02:10'),
(16, 5, 'Ferramentas Necessárias', 'Instalando VS Code, navegadores e extensões', 'video', 'https://www.youtube.com/embed/RGOj5yH7evk', 30, 3, 1, '2025-10-31 21:02:10'),
(17, 6, 'Estrutura HTML Básica', 'Tags, elementos e estrutura de um documento HTML', 'video', 'https://www.youtube.com/embed/Ejkb_YpuHWs', 40, 1, 1, '2025-10-31 21:02:11'),
(18, 6, 'CSS Básico', 'Seletores, propriedades e estilos fundamentais', 'video', 'https://www.youtube.com/embed/FRhM6sMOTfg', 45, 2, 1, '2025-10-31 21:02:11'),
(19, 6, 'Flexbox e Grid Layout', 'Layouts modernos e responsivos com CSS', 'video', 'https://www.youtube.com/embed/K74l26pE4YA', 30, 3, 1, '2025-10-31 21:02:11'),
(20, 6, 'Projeto Prático: Landing Page', 'Construa sua primeira página completa', 'texto', '<h2>Projeto Prático</h2><p>Nesta aula você vai criar uma landing page completa aplicando tudo que aprendeu!</p>', 60, 4, 1, '2025-10-31 21:02:11'),
(21, 7, 'Introdução ao JavaScript', 'Variáveis, tipos de dados e operadores', 'video', 'https://www.youtube.com/embed/BXqUH86F-kA', 35, 1, 1, '2025-10-31 21:02:11'),
(22, 7, 'Manipulação do DOM', 'Como interagir com elementos HTML usando JS', 'video', 'https://www.youtube.com/embed/0ik6X4DJKCc', 40, 2, 1, '2025-10-31 21:02:11'),
(23, 7, 'Eventos e Interatividade', 'Eventos de clique, hover e formulários', 'video', 'https://www.youtube.com/embed/3WHkJeO8u_c', 35, 3, 1, '2025-10-31 21:02:11'),
(24, 8, 'Introdução ao PHP', 'Sintaxe básica, variáveis e funções', 'video', 'https://www.youtube.com/embed/F_3l8mnGLCM', 45, 1, 1, '2025-10-31 21:02:11'),
(25, 8, 'MySQL Básico', 'Banco de dados, tabelas e queries SQL', 'video', 'https://www.youtube.com/embed/Cz3WcZLRaWc', 40, 2, 1, '2025-10-31 21:02:11'),
(26, 8, 'Projeto Final: Sistema CRUD', 'Desenvolva um sistema completo com PHP e MySQL', 'texto', '<h2>Projeto Final</h2><p>Crie um sistema completo de cadastro com as 4 operações: Create, Read, Update, Delete</p>', 90, 3, 1, '2025-10-31 21:02:11');

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id_avaliacao` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nota` int(1) NOT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci,
  `data_avaliacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `certificados`
--

CREATE TABLE `certificados` (
  `id_certificado` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_matricula` int(11) NOT NULL,
  `codigo_validacao` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_emissao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_conclusao` date NOT NULL,
  `carga_horaria` int(11) NOT NULL,
  `nota_final` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `certificados`
--

INSERT INTO `certificados` (`id_certificado`, `id_usuario`, `id_curso`, `id_matricula`, `codigo_validacao`, `data_emissao`, `data_conclusao`, `carga_horaria`, `nota_final`) VALUES
(1, 4, 6, 2, 'U3JS78DPZZ', '2025-11-02 22:08:46', '2025-11-02', 30, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL,
  `nome_curso` varchar(100) NOT NULL,
  `tipo_curso` enum('Fisico','Online') DEFAULT 'Fisico',
  `descricao` text,
  `duracao` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `curso`
--

INSERT INTO `curso` (`id_curso`, `nome_curso`, `tipo_curso`, `descricao`, `duracao`) VALUES
(6, 'FullStack Developer', 'Online', 'Desenvolvedor Full Stack, você aprende a criar aplicações web completas, do front-end (interface do usuário com HTML, CSS e JavaScript) ao back-end (lógica do servidor e banco de dados).', '30 Horas'),
(7, 'Informática básica', 'Fisico', 'Curso de Informática Básica: aprenda a usar o computador com segurança e eficiência. Domine o uso do Windows, Word, Excel, PowerPoint e navegação na internet, desenvolvendo habilidades essenciais para o dia a dia e o mercado de trabalho.', '10 Horas');

-- --------------------------------------------------------

--
-- Estrutura da tabela `formar_turmas`
--

CREATE TABLE `formar_turmas` (
  `id_turma` int(11) NOT NULL,
  `nome_turma` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_instrutor` int(11) NOT NULL,
  `local` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `horario` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacidade` int(11) DEFAULT '12',
  `inscritos` int(11) DEFAULT '0',
  `vagas_disponiveis` int(11) DEFAULT '12',
  `status` enum('Aberta','Ativa','Lotada','Encerrada','Cancelada') COLLATE utf8mb4_unicode_ci DEFAULT 'Aberta',
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `formar_turmas`
--

INSERT INTO `formar_turmas` (`id_turma`, `nome_turma`, `id_curso`, `id_instrutor`, `local`, `data_inicio`, `data_fim`, `horario`, `capacidade`, `inscritos`, `vagas_disponiveis`, `status`, `descricao`, `data_criacao`) VALUES
(15, 'TPIB01', 7, 13, 'R. Augusta Candiani, 64 - Inhoaíba RJ - Rio de Janeiro, CEP 23070-020', '2025-10-30', '2025-12-10', '16h ás 19h', 12, 1, 11, 'Aberta', 'Um curso de informática básica ensina os fundamentos do uso do computador, incluindo como ligar e desligar a máquina, manusear o sistema operacional (como o Windows), usar aplicativos de escritório (como Word, Excel e PowerPoint), navegar na internet e entender noções de hardware e software', '2025-10-25 22:33:54');

-- --------------------------------------------------------

--
-- Estrutura da tabela `instrutor`
--

CREATE TABLE `instrutor` (
  `id_instrutor` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `sexo` enum('M','F','Outro') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `instrutor`
--

INSERT INTO `instrutor` (`id_instrutor`, `nome`, `area`, `sexo`) VALUES
(1, 'Thawany', 'Photoshop ', 'F'),
(2, 'Rhuan Barauna', 'Programação de dispositivos móveis', 'Outro'),
(12, 'Kayke', 'UML', 'M'),
(13, 'Richard Gabriel', 'Montagem e Manuteção', 'M');

-- --------------------------------------------------------

--
-- Estrutura da tabela `matriculas_online`
--

CREATE TABLE `matriculas_online` (
  `id_matricula` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `data_matricula` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('ativa','concluida','cancelada') COLLATE utf8mb4_unicode_ci DEFAULT 'ativa',
  `progresso` decimal(5,2) DEFAULT '0.00',
  `data_conclusao` datetime DEFAULT NULL,
  `ultima_aula_acessada` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `matriculas_online`
--

INSERT INTO `matriculas_online` (`id_matricula`, `id_usuario`, `id_curso`, `data_matricula`, `status`, `progresso`, `data_conclusao`, `ultima_aula_acessada`) VALUES
(1, 0, 6, '2025-10-31 20:08:54', 'ativa', '0.00', NULL, NULL),
(2, 4, 6, '2025-10-31 21:19:49', 'ativa', '100.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulos`
--

CREATE TABLE `modulos` (
  `id_modulo` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `ordem` int(11) DEFAULT '1',
  `ativo` tinyint(1) DEFAULT '1',
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `modulos`
--

INSERT INTO `modulos` (`id_modulo`, `id_curso`, `titulo`, `descricao`, `ordem`, `ativo`, `data_criacao`) VALUES
(1, 8, 'Introdução ao Desenvolvimento Web', 'Fundamentos essenciais para começar sua jornada como desenvolvedor web', 1, 1, '2025-10-31 02:40:58'),
(2, 8, 'HTML5 e CSS3', 'Criando páginas web modernas e responsivas', 2, 1, '2025-10-31 02:40:58'),
(3, 8, 'JavaScript Essencial', 'Programação client-side e interatividade', 3, 1, '2025-10-31 02:40:58'),
(4, 8, 'PHP e Banco de Dados', 'Backend e integração com MySQL', 4, 1, '2025-10-31 02:40:58'),
(5, 6, 'Introdução ao Desenvolvimento Web', 'Conceitos básicos e ferramentas necessárias', 1, 1, '2025-10-31 21:02:10'),
(6, 6, 'HTML5 e CSS3', 'Aprenda a estruturar e estilizar páginas web', 2, 1, '2025-10-31 21:02:10'),
(7, 6, 'JavaScript Essencial', 'Programação client-side e manipulação do DOM', 3, 1, '2025-10-31 21:02:10'),
(8, 6, 'PHP e Banco de Dados', 'Desenvolvimento back-end com PHP e MySQL', 4, 1, '2025-10-31 21:02:10');

-- --------------------------------------------------------

--
-- Estrutura da tabela `progresso_aulas`
--

CREATE TABLE `progresso_aulas` (
  `id_progresso` int(11) NOT NULL,
  `id_matricula` int(11) NOT NULL,
  `id_aula` int(11) NOT NULL,
  `concluida` tinyint(1) DEFAULT '0',
  `data_conclusao` datetime DEFAULT NULL,
  `tempo_assistido` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `progresso_aulas`
--

INSERT INTO `progresso_aulas` (`id_progresso`, `id_matricula`, `id_aula`, `concluida`, `data_conclusao`, `tempo_assistido`) VALUES
(1, 2, 14, 1, '2025-10-31 20:26:00', 0),
(2, 2, 25, 1, '2025-10-31 20:29:00', 0),
(3, 2, 22, 1, '2025-10-31 20:35:06', 0),
(4, 2, 15, 1, '2025-11-01 14:43:09', 0),
(5, 2, 16, 1, '2025-11-01 21:47:30', 0),
(6, 2, 24, 1, '2025-11-01 21:48:28', 0),
(7, 2, 17, 1, '2025-11-02 00:19:26', 0),
(8, 2, 18, 1, '2025-11-02 00:19:43', 0),
(9, 2, 19, 1, '2025-11-02 00:19:52', 0),
(10, 2, 20, 1, '2025-11-02 00:20:04', 0),
(11, 2, 21, 1, '2025-11-02 00:20:28', 0),
(12, 2, 23, 1, '2025-11-02 00:20:37', 0),
(13, 2, 26, 1, '2025-11-02 00:20:47', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma_alunos`
--

CREATE TABLE `turma_alunos` (
  `id` int(11) NOT NULL,
  `id_turma` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_matricula` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pendente','Confirmada','Cancelada') COLLATE utf8mb4_unicode_ci DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `turma_alunos`
--

INSERT INTO `turma_alunos` (`id`, `id_turma`, `id_usuario`, `data_matricula`, `status`) VALUES
(1, 15, 4, '2025-10-25 22:54:55', 'Confirmada');

--
-- Acionadores `turma_alunos`
--
DELIMITER $$
CREATE TRIGGER `atualiza_turma_after_insert` AFTER INSERT ON `turma_alunos` FOR EACH ROW BEGIN
                UPDATE formar_turmas 
    SET inscritos = inscritos + 1,
        vagas_disponiveis = vagas_disponiveis - 1
    WHERE id_turma = NEW.id_turma;
    
        IF (SELECT vagas_disponiveis FROM formar_turmas WHERE id_turma = NEW.id_turma) <= 0 THEN
        UPDATE formar_turmas SET status = 'Lotada' WHERE id_turma = NEW.id_turma;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`, `cpf`) VALUES
(1, 'Claudia', 'g@gmail.com', '$2y$10$g7lp8iY7lIGTdPgXq08seOIv54FzzB21PvcE8XEyAejVIHHMIJPdO', '12341234123413'),
(2, 'rhuan', 'r@gmail.com', '$2y$10$Kq7snG3CBlga030JvRKvsOc8.D1nHS0l63h/k6atn3.HiNHaUlfpm', '111.111.111-11'),
(3, 'richard gabriel', 'richard@gmail.com', '$2y$10$aCtlIhzuYw5mI9QCBn24AOA73xHNbtGvQW48vL0YM2vuyLW0QmA4y', '777.777.777-78'),
(4, 'wilma', 'prof@gmail.com', '$2y$10$HHpLMfx4dnY7m/3vsKBUCuwLOpQFqf2epJiyXyjgMpu0pamNxBRrq', '222.222.222-22'),
(5, 'kayke', 'k@gmail.com', '$2y$10$gELjAYibOgJIfIZilF36TOx/MWnMHoZ7QTDq9sqgc1VJo/3vPn.vK', '123.111.111-11'),
(6, 'ben 10', 'ben10@gmail.com', '$2y$10$7Pdi3Idg/AnK6rdVx39EH.GZqPACPNOpXn3zEsEsCVgzf5WGNMv6W', '101.010.101-01'),
(8, 'tetinho caos trap', 'teste@gmail.com', '$2y$10$nXANszbW6XCkAc02N6ur6evXR7jBHPgGk1BvLCIy4FPxrvSd7oPHy', '111.111.111-15'),
(9, 'alee', 'alee@gmail.com', '$2y$10$qr3XIpM3KzCGpM6XWAg7XuwEtjdqVknRA42jIAFLHym7/6xsWz/UK', '115.315.313-51'),
(10, 'teto prevendo a jogada', 'teto@gmail.com', '$2y$10$8jnySsyvjxaPwXBtobZ0tu7F1e/MgGXPwfb9BO1r8lBG6JNgJ.j5a', '616.161.616-16'),
(11, 'Lucas de Carvalho Lima', 'lucas65mtg@gmail.com', '$2y$10$T1smXqq6zQrf2sWY2cuC7ODOab6OCUCkiSf3kd.Vct2YzDCrlzraG', '202.386.117-95'),
(12, 'Norma Torre', 'torrenorma@gmail.com', '$2y$10$EYU.prdBMNyaBvn73WbL0.bk6uxvajChkKTTTl2RiS5JieMY4c6.i', '044.576.665-22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agendamento`
--
ALTER TABLE `agendamento`
  ADD PRIMARY KEY (`id_agendamento`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id_aula`),
  ADD KEY `id_modulo` (`id_modulo`),
  ADD KEY `idx_ordem` (`ordem`);

--
-- Indexes for table `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id_avaliacao`),
  ADD UNIQUE KEY `unica_avaliacao` (`id_usuario`,`id_curso`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indexes for table `certificados`
--
ALTER TABLE `certificados`
  ADD PRIMARY KEY (`id_certificado`),
  ADD UNIQUE KEY `codigo_validacao` (`codigo_validacao`),
  ADD UNIQUE KEY `unico_certificado` (`id_usuario`,`id_curso`),
  ADD KEY `id_matricula` (`id_matricula`);

--
-- Indexes for table `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indexes for table `formar_turmas`
--
ALTER TABLE `formar_turmas`
  ADD PRIMARY KEY (`id_turma`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_instrutor` (`id_instrutor`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_data_inicio` (`data_inicio`);

--
-- Indexes for table `instrutor`
--
ALTER TABLE `instrutor`
  ADD PRIMARY KEY (`id_instrutor`);

--
-- Indexes for table `matriculas_online`
--
ALTER TABLE `matriculas_online`
  ADD PRIMARY KEY (`id_matricula`),
  ADD UNIQUE KEY `unica_matricula_online` (`id_usuario`,`id_curso`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulo`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `idx_ordem` (`ordem`);

--
-- Indexes for table `progresso_aulas`
--
ALTER TABLE `progresso_aulas`
  ADD PRIMARY KEY (`id_progresso`),
  ADD UNIQUE KEY `unico_progresso` (`id_matricula`,`id_aula`),
  ADD KEY `id_aula` (`id_aula`);

--
-- Indexes for table `turma_alunos`
--
ALTER TABLE `turma_alunos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unica_matricula` (`id_turma`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agendamento`
--
ALTER TABLE `agendamento`
  MODIFY `id_agendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id_aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `certificados`
--
ALTER TABLE `certificados`
  MODIFY `id_certificado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `formar_turmas`
--
ALTER TABLE `formar_turmas`
  MODIFY `id_turma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `instrutor`
--
ALTER TABLE `instrutor`
  MODIFY `id_instrutor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `matriculas_online`
--
ALTER TABLE `matriculas_online`
  MODIFY `id_matricula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `progresso_aulas`
--
ALTER TABLE `progresso_aulas`
  MODIFY `id_progresso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `turma_alunos`
--
ALTER TABLE `turma_alunos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `formar_turmas`
--
ALTER TABLE `formar_turmas`
  ADD CONSTRAINT `formar_turmas_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`),
  ADD CONSTRAINT `formar_turmas_ibfk_2` FOREIGN KEY (`id_instrutor`) REFERENCES `instrutor` (`id_instrutor`);

--
-- Limitadores para a tabela `turma_alunos`
--
ALTER TABLE `turma_alunos`
  ADD CONSTRAINT `turma_alunos_ibfk_1` FOREIGN KEY (`id_turma`) REFERENCES `formar_turmas` (`id_turma`) ON DELETE CASCADE,
  ADD CONSTRAINT `turma_alunos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
