-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 06-Jul-2025 às 12:44
-- Versão do servidor: 8.3.0
-- versão do PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `nebaservice`
--

<<<<<<< Updated upstream
=======
CREATE DATABASE IF NOT EXISTS `nebaservice`;
USE `nebaservice`;

>>>>>>> Stashed changes
-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `fatherID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fatherID` (`fatherID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `compatibilidade`
--

DROP TABLE IF EXISTS `compatibilidade`;
CREATE TABLE IF NOT EXISTS `compatibilidade` (
  `produtoID` int NOT NULL,
  `tipoComponenteID` int NOT NULL,
  `compativelcom` json DEFAULT NULL,
  PRIMARY KEY (`produtoID`,`tipoComponenteID`),
  KEY `tipoComponenteID` (`tipoComponenteID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracaopc`
--

DROP TABLE IF EXISTS `configuracaopc`;
CREATE TABLE IF NOT EXISTS `configuracaopc` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `utilizadorID` int DEFAULT NULL,
  `nomeConfiguracao` varchar(100) DEFAULT NULL,
  `dataCriacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `utilizadorID` (`utilizadorID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracaoprodutos`
--

DROP TABLE IF EXISTS `configuracaoprodutos`;
CREATE TABLE IF NOT EXISTS `configuracaoprodutos` (
  `configuracaoID` int NOT NULL,
  `produtoID` int DEFAULT NULL,
  `tipoComponenteID` int NOT NULL,
  PRIMARY KEY (`configuracaoID`,`tipoComponenteID`),
  KEY `produtoID` (`produtoID`),
  KEY `tipoComponenteID` (`tipoComponenteID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomendaprodutos`
--

DROP TABLE IF EXISTS `encomendaprodutos`;
CREATE TABLE IF NOT EXISTS `encomendaprodutos` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `encomendaID` int DEFAULT NULL,
  `produtoID` int DEFAULT NULL,
  `quantidade` int NOT NULL,
  `precoUnitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `encomendaID` (`encomendaID`),
  KEY `produtoID` (`produtoID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `encomendas`
--

DROP TABLE IF EXISTS `encomendas`;
CREATE TABLE IF NOT EXISTS `encomendas` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `utilizadorID` int DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('Pendente','A processar','Enviada','Entregue','Cancelada') DEFAULT NULL,
  `data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `utilizadorID` (`utilizadorID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `especificacoes`
--

DROP TABLE IF EXISTS `especificacoes`;
CREATE TABLE IF NOT EXISTS `especificacoes` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `produtoID` int DEFAULT NULL,
  `chaves` varchar(50) NOT NULL,
  `valor` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `produtoID` (`produtoID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `kits`
--

DROP TABLE IF EXISTS `kits`;
CREATE TABLE IF NOT EXISTS `kits` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `nomeKit` varchar(100) NOT NULL,
  `descricaoKit` text,
  `precoKit` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `kit_componentes`
--

DROP TABLE IF EXISTS `kit_componentes`;
CREATE TABLE IF NOT EXISTS `kit_componentes` (
  `kitID` int NOT NULL,
  `produtoID` int NOT NULL,
  `quantidade` int DEFAULT '1',
  PRIMARY KEY (`kitID`,`produtoID`),
  KEY `produtoID` (`produtoID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `marcas`
--

DROP TABLE IF EXISTS `marcas`;
CREATE TABLE IF NOT EXISTS `marcas` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE IF NOT EXISTS `produtos` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text,
  `preco` decimal(10,2) NOT NULL,
  `stock` int DEFAULT '0',
  `categoriaID` int DEFAULT NULL,
  `marcaID` int DEFAULT NULL,
  `imagemPrincipal` varchar(255) DEFAULT NULL,
  `dataCriacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `marcaID` (`marcaID`),
  KEY `IDXProdutoNome` (`nome`),
  KEY `IDXProdutoCategoria` (`categoriaID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipocomponente`
--

DROP TABLE IF EXISTS `tipocomponente`;
CREATE TABLE IF NOT EXISTS `tipocomponente` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `slug` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

DROP TABLE IF EXISTS `utilizadores`;
CREATE TABLE IF NOT EXISTS `utilizadores` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `nomeUtilizador` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `nomeCompleto` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `morada` text,
  `codigoPostal` varchar(20) DEFAULT NULL,
  `localidade` varchar(50) DEFAULT NULL,
  `pais` varchar(50) DEFAULT 'Portugal',
  `nif` varchar(20) DEFAULT NULL,
  `dataRegisto` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimoLogin` timestamp NULL DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `tokenRecuperacao` varchar(100) DEFAULT NULL,
  `tokenValidade` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `nomeUtilizador` (`nomeUtilizador`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
