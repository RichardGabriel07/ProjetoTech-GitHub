-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 25-Out-2025 às 17:55
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
  `id_instrutor` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `agendamento`
--

INSERT INTO `agendamento` (`id_agendamento`, `data`, `horario`, `local`, `id_usuario`, `id_instrutor`) VALUES
(1, '2026-06-11', '15:15:00', 'rua teste1', 4, 'richard'),
(2, '2026-12-25', '12:30:00', 'Rua tanto ', 4, 'kayke'),
(3, '2026-03-19', '12:00:00', 'R. Augusta Candiani, 64 - Inhoaíba RJ - Rio de Janeiro, CEP 23070-020', 11, 'rhuan'),
(4, '2025-10-20', '12:32:00', 'mensagem, xique xique, BA', 11, 'kayke'),
(5, '2026-06-11', '14:00:00', 'R. Engenheiro de Softeare, 64 - Inhoaíba RJ - Rio de Janeiro, CEP 23070-020', 11, 'richard');

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL,
  `nome_curso` varchar(100) NOT NULL,
  `descricao` text,
  `duracao` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `curso`
--

INSERT INTO `curso` (`id_curso`, `nome_curso`, `descricao`, `duracao`) VALUES
(6, 'Photoshop CS6', 'Curso de Photoshop: aprenda a criar, editar e manipular imagens como um profissional. Domine ferramentas essenciais, retoque fotos, crie artes digitais e desenvolva designs criativos para mídias sociais e projetos gráficos.', '90 Horas'),
(7, 'Informática básica', 'Curso de Informática Básica: aprenda a usar o computador com segurança e eficiência. Domine o uso do Windows, Word, Excel, PowerPoint e navegação na internet, desenvolvendo habilidades essenciais para o dia a dia e o mercado de trabalho.', '120 Horas');

-- --------------------------------------------------------

--
-- Estrutura da tabela `instrutor`
--

CREATE TABLE `instrutor` (
  `id_instrutor` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `sexo` enum('M','F','Outro') DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
(9, 'alee', 'alee@gmail.com', '$2y$10$qr3XIpM3KzCGpM6XWAg7XuwEtjdqVknRA42jIAFLHym7/6xsWz/UK', '115.315.313-51'),
(8, 'tetinho caos trap', 'teste@gmail.com', '$2y$10$nXANszbW6XCkAc02N6ur6evXR7jBHPgGk1BvLCIy4FPxrvSd7oPHy', '111.111.111-15'),
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
-- Indexes for table `instrutor`
--
ALTER TABLE `instrutor`
  ADD PRIMARY KEY (`id_instrutor`);

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
  MODIFY `id_agendamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `curso`
--
ALTER TABLE `curso`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `instrutor`
--
ALTER TABLE `instrutor`
  MODIFY `id_instrutor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
