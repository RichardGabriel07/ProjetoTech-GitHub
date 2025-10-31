-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 30-Out-2025 às 23:58
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
  `id_instrutor` varchar(100) DEFAULT NULL,
  `status` enum('Pendente','Confirmado','Cancelado') DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `agendamento`
--

INSERT INTO `agendamento` (`id_agendamento`, `data`, `horario`, `local`, `id_usuario`, `id_instrutor`, `status`) VALUES
(1, '2026-06-11', '15:15:00', 'rua teste1', 4, 'richard', 'Confirmado'),
(2, '2026-12-25', '12:30:00', 'Rua tanto ', 4, 'kayke', 'Cancelado'),
(3, '2026-03-19', '12:00:00', 'R. Augusta Candiani, 64 - Inhoaíba RJ - Rio de Janeiro, CEP 23070-020', 11, 'rhuan', 'Pendente'),
(4, '2025-10-20', '12:32:00', 'mensagem, xique xique, BA', 11, 'kayke', 'Cancelado'),
(5, '2026-06-11', '14:00:00', 'R. Engenheiro de Softeare, 64 - Inhoaíba RJ - Rio de Janeiro, CEP 23070-020', 11, 'richard', 'Confirmado');

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
(6, 'Photoshop CS6', 'Fisico', 'Curso de Photoshop: aprenda a criar, editar e manipular imagens como um profissional. Domine ferramentas essenciais, retoque fotos, crie artes digitais e desenvolva designs criativos para mídias sociais e projetos gráficos.', '90 Horas'),
(7, 'Informática básica', 'Fisico', 'Curso de Informática Básica: aprenda a usar o computador com segurança e eficiência. Domine o uso do Windows, Word, Excel, PowerPoint e navegação na internet, desenvolvendo habilidades essenciais para o dia a dia e o mercado de trabalho.', '120 Horas'),
(8, 'Programação Web Completa', 'Online', 'Aprenda a criar sites e aplicações web do zero! Domine HTML, CSS, JavaScript, PHP e MySQL. Crie projetos reais e construa seu portfólio. Inclui certificado digital ao final do curso.', '160 Horas'),
(9, 'Design Gráfico Profissional', 'Online', 'Curso completo de Design Gráfico com Photoshop, Illustrator e Figma. Aprenda a criar logotipos, banners, posts para redes sociais e muito mais. Certificado incluído!', '120 Horas');

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
(15, 'Turma de Informática básica', 7, 13, 'R. Augusta Candiani, 64 - Inhoaíba RJ - Rio de Janeiro, CEP 23070-020', '2025-10-30', '2025-12-10', '16h ás 19h', 12, 1, 11, 'Aberta', 'Um curso de informática básica ensina os fundamentos do uso do computador, incluindo como ligar e desligar a máquina, manusear o sistema operacional (como o Windows), usar aplicativos de escritório (como Word, Excel e PowerPoint), navegar na internet e entender noções de hardware e software', '2025-10-25 22:33:54');

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

-- --------------------------------------------------------

--
-- NOVAS TABELAS PARA SISTEMA DE CURSOS ONLINE
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulos`
-- Módulos organizam as aulas dentro de um curso
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

-- --------------------------------------------------------

--
-- Estrutura da tabela `aulas`
-- Aulas contêm o conteúdo (vídeo, PDF, texto)
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

-- --------------------------------------------------------

--
-- Estrutura da tabela `matriculas_online`
-- Matrículas em cursos online (diferente de turmas presenciais)
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

-- --------------------------------------------------------

--
-- Estrutura da tabela `progresso_aulas`
-- Controla quais aulas o aluno já concluiu
--

CREATE TABLE `progresso_aulas` (
  `id_progresso` int(11) NOT NULL,
  `id_matricula` int(11) NOT NULL,
  `id_aula` int(11) NOT NULL,
  `concluida` tinyint(1) DEFAULT '0',
  `data_conclusao` datetime DEFAULT NULL,
  `tempo_assistido` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `certificados`
-- Certificados gerados ao concluir 100% do curso
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

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacoes`
-- Avaliações dos cursos (1 a 5 estrelas)
--

CREATE TABLE `avaliacoes` (
  `id_avaliacao` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nota` int(1) NOT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci,
  `data_avaliacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados das NOVAS TABELAS (DADOS DE EXEMPLO)
--

-- Módulos do curso "Programação Web Completa" (id_curso = 8)
INSERT INTO `modulos` (`id_modulo`, `id_curso`, `titulo`, `descricao`, `ordem`) VALUES
(1, 8, 'Introdução ao Desenvolvimento Web', 'Fundamentos essenciais para começar sua jornada como desenvolvedor web', 1),
(2, 8, 'HTML5 e CSS3', 'Criando páginas web modernas e responsivas', 2),
(3, 8, 'JavaScript Essencial', 'Programação client-side e interatividade', 3),
(4, 8, 'PHP e Banco de Dados', 'Backend e integração com MySQL', 4);

-- Aulas do Módulo 1
INSERT INTO `aulas` (`id_aula`, `id_modulo`, `titulo`, `descricao`, `tipo`, `conteudo`, `duracao_minutos`, `ordem`) VALUES
(1, 1, 'Bem-vindo ao Curso', 'Introdução ao curso e metodologia', 'video', 'https://www.youtube.com/embed/Ejkb_YpuHWs', 15, 1),
(2, 1, 'O que é Desenvolvimento Web?', 'Entenda como a web funciona', 'video', 'https://www.youtube.com/embed/hwBUU9CP4qI', 25, 2),
(3, 1, 'Ferramentas Necessárias', 'Instalando e configurando o ambiente', 'video', 'https://www.youtube.com/embed/K0y2tc38l2s', 30, 3);

-- Aulas do Módulo 2
INSERT INTO `aulas` (`id_aula`, `id_modulo`, `titulo`, `descricao`, `tipo`, `conteudo`, `duracao_minutos`, `ordem`) VALUES
(4, 2, 'Estrutura HTML', 'Tags e elementos HTML5', 'video', 'https://www.youtube.com/embed/epDCjksKMok', 40, 1),
(5, 2, 'CSS Básico', 'Estilizando páginas web', 'video', 'https://www.youtube.com/embed/LWU2OR19ZG4', 45, 2),
(6, 2, 'Flexbox e Grid', 'Layouts modernos com CSS', 'video', 'https://www.youtube.com/embed/3YW65K6LcIA', 50, 3),
(7, 2, 'Projeto Prático: Landing Page', 'Crie sua primeira página completa', 'texto', '<h2>Projeto: Landing Page Responsiva</h2><p>Neste projeto você vai criar uma landing page completa usando HTML5 e CSS3.</p><h3>Requisitos:</h3><ul><li>Header com logo e menu</li><li>Seção hero com CTA</li><li>Cards de serviços</li><li>Footer com redes sociais</li></ul>', 60, 4);

-- Aulas do Módulo 3
INSERT INTO `aulas` (`id_aula`, `id_modulo`, `titulo`, `descricao`, `tipo`, `conteudo`, `duracao_minutos`, `ordem`) VALUES
(8, 3, 'Introdução ao JavaScript', 'Sintaxe e conceitos básicos', 'video', 'https://www.youtube.com/embed/Ptbk2af68e8', 35, 1),
(9, 3, 'DOM Manipulation', 'Manipulando elementos da página', 'video', 'https://www.youtube.com/embed/BXqUH86F-kA', 40, 2),
(10, 3, 'Eventos e Interatividade', 'Capturando eventos do usuário', 'video', 'https://www.youtube.com/embed/0fKg7e37bQE', 35, 3);

-- Aulas do Módulo 4
INSERT INTO `aulas` (`id_aula`, `id_modulo`, `titulo`, `descricao`, `tipo`, `conteudo`, `duracao_minutos`, `ordem`) VALUES
(11, 4, 'Introdução ao PHP', 'Linguagem server-side', 'video', 'https://www.youtube.com/embed/F7KzJ7e6EAc', 45, 1),
(12, 4, 'MySQL Básico', 'Banco de dados relacional', 'video', 'https://www.youtube.com/embed/Ofktsne-utM', 40, 2),
(13, 4, 'Projeto Final: Sistema CRUD', 'Aplicação web completa', 'texto', '<h2>Projeto Final</h2><p>Desenvolva um sistema completo com:</p><ul><li>Cadastro de usuários</li><li>Login e autenticação</li><li>CRUD completo</li><li>Conexão com banco de dados</li></ul>', 90, 3);

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
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_instrutor` (`id_instrutor`);

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
-- Indexes for table `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulo`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `idx_ordem` (`ordem`);

--
-- Indexes for table `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id_aula`),
  ADD KEY `id_modulo` (`id_modulo`),
  ADD KEY `idx_ordem` (`ordem`);

--
-- Indexes for table `matriculas_online`
--
ALTER TABLE `matriculas_online`
  ADD PRIMARY KEY (`id_matricula`),
  ADD UNIQUE KEY `unica_matricula_online` (`id_usuario`,`id_curso`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `progresso_aulas`
--
ALTER TABLE `progresso_aulas`
  ADD PRIMARY KEY (`id_progresso`),
  ADD UNIQUE KEY `unico_progresso` (`id_matricula`,`id_aula`),
  ADD KEY `id_aula` (`id_aula`);

--
-- Indexes for table `certificados`
--
ALTER TABLE `certificados`
  ADD PRIMARY KEY (`id_certificado`),
  ADD UNIQUE KEY `codigo_validacao` (`codigo_validacao`),
  ADD UNIQUE KEY `unico_certificado` (`id_usuario`,`id_curso`),
  ADD KEY `id_matricula` (`id_matricula`);

--
-- Indexes for table `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id_avaliacao`),
  ADD UNIQUE KEY `unica_avaliacao` (`id_usuario`,`id_curso`),
  ADD KEY `id_curso` (`id_curso`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agendamento`
--
ALTER TABLE `agendamento`
  MODIFY `id_agendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
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
-- AUTO_INCREMENT for table `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id_aula` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `matriculas_online`
--
ALTER TABLE `matriculas_online`
  MODIFY `id_matricula` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `progresso_aulas`
--
ALTER TABLE `progresso_aulas`
  MODIFY `id_progresso` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `certificados`
--
ALTER TABLE `certificados`
  MODIFY `id_certificado` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id_avaliacao` int(11) NOT NULL AUTO_INCREMENT;
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

--
-- Limitadores para a tabela `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `modulos_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `aulas`
--
ALTER TABLE `aulas`
  ADD CONSTRAINT `aulas_ibfk_1` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_modulo`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `matriculas_online`
--
ALTER TABLE `matriculas_online`
  ADD CONSTRAINT `matriculas_online_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `matriculas_online_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `progresso_aulas`
--
ALTER TABLE `progresso_aulas`
  ADD CONSTRAINT `progresso_aulas_ibfk_1` FOREIGN KEY (`id_matricula`) REFERENCES `matriculas_online` (`id_matricula`) ON DELETE CASCADE,
  ADD CONSTRAINT `progresso_aulas_ibfk_2` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id_aula`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `certificados`
--
ALTER TABLE `certificados`
  ADD CONSTRAINT `certificados_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificados_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificados_ibfk_3` FOREIGN KEY (`id_matricula`) REFERENCES `matriculas_online` (`id_matricula`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `avaliacoes_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`) ON DELETE CASCADE,
  ADD CONSTRAINT `avaliacoes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
