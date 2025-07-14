<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$servidor = "localhost";
$utilizador = "root";
$senha = "";
$base_dados = "nebaservice";
$porta = 3306;

$conexao = new mysqli($servidor, $utilizador, $senha, '', $porta);
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

$sql_create_db = "CREATE DATABASE IF NOT EXISTS `" . str_replace('`', '``', $base_dados) . "`";
if ($conexao->query($sql_create_db) === TRUE) {
    echo "Base de dados criada com sucesso ou já existe.\n";
} else {
    echo "Erro ao criar base de dados: " . $conexao->error . "\n";
}

$conexao->select_db($base_dados);

$sql_drop_tables = [
    "DROP TABLE IF EXISTS `compatibilidade`;",
    "DROP TABLE IF EXISTS `configuracaoprodutos`;",
    "DROP TABLE IF EXISTS `configuracaopc`;",
    "DROP TABLE IF EXISTS `encomendaprodutos`;",
    "DROP TABLE IF EXISTS `encomendas`;",
    "DROP TABLE IF EXISTS `especificacoes`;",
    "DROP TABLE IF EXISTS `kit_componentes`;",
    "DROP TABLE IF EXISTS `kits`;",
    "DROP TABLE IF EXISTS `produtos`;",
    "DROP TABLE IF EXISTS `tipocomponente`;",
    "DROP TABLE IF EXISTS `marcas`;",
    "DROP TABLE IF EXISTS `categorias`;",
    "DROP TABLE IF EXISTS `utilizadores`;"
];

foreach ($sql_drop_tables as $drop_query) {
    if ($conexao->query($drop_query) === TRUE) {
        echo "Tabela dropada com sucesso: " . substr($drop_query, 0, 30) . "...\n";
    } else {
        echo "Erro ao dropar tabela: " . $conexao->error . "\n";
    }
}

// SQL para criar tabelas
$sql_create_tables_array = [
    "CREATE TABLE IF NOT EXISTS `categorias` (
      `ID` int NOT NULL AUTO_INCREMENT,
      `nome` varchar(50) NOT NULL,
      `fatherID` int DEFAULT NULL,
      PRIMARY KEY (`ID`),
      KEY `fatherID` (`fatherID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `compatibilidade` (
      `produtoID` int NOT NULL,
      `tipoComponenteID` int NOT NULL,
      `compativelcom` json DEFAULT NULL,
      PRIMARY KEY (`produtoID`,`tipoComponenteID`),
      KEY `tipoComponenteID` (`tipoComponenteID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `configuracaopc` (
      `ID` int NOT NULL AUTO_INCREMENT,
      `utilizadorID` int DEFAULT NULL,
      `nomeConfiguracao` varchar(100) DEFAULT NULL,
      `dataCriacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`ID`),
      KEY `utilizadorID` (`utilizadorID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `configuracaoprodutos` (
      `configuracaoID` int NOT NULL,
      `produtoID` int DEFAULT NULL,
      `tipoComponenteID` int NOT NULL,
      PRIMARY KEY (`configuracaoID`,`tipoComponenteID`),
      KEY `produtoID` (`produtoID`),
      KEY `tipoComponenteID` (`tipoComponenteID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `encomendaprodutos` (
      `ID` int NOT NULL AUTO_INCREMENT,
      `encomendaID` int DEFAULT NULL,
      `produtoID` int DEFAULT NULL,
      `quantidade` int NOT NULL,
      `precoUnitario` decimal(10,2) NOT NULL,
      PRIMARY KEY (`ID`),
      KEY `encomendaID` (`encomendaID`),
      KEY `produtoID` (`produtoID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `encomendas` (
      `ID` int NOT NULL AUTO_INCREMENT,
      `utilizadorID` int DEFAULT NULL,
      `total` decimal(10,2) NOT NULL,
      `estado` enum('Pendente','A processar','Enviada','Entregue','Cancelada') DEFAULT NULL,
      `data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`ID`),
      KEY `utilizadorID` (`utilizadorID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `especificacoes` (
      `ID` int NOT NULL AUTO_INCREMENT,
      `produtoID` int DEFAULT NULL,
      `chaves` varchar(50) NOT NULL,
      `valor` varchar(100) NOT NULL,
      PRIMARY KEY (`ID`),
      KEY `produtoID` (`produtoID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `kits` (
      `ID` int NOT NULL AUTO_INCREMENT,
      `nomeKit` varchar(100) NOT NULL,
      `descricaoKit` text,
      `precoKit` decimal(10,2) DEFAULT NULL,
      PRIMARY KEY (`ID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `kit_componentes` (
      `kitID` int NOT NULL,
      `produtoID` int NOT NULL,
      `quantidade` int DEFAULT '1',
      PRIMARY KEY (`kitID`,`produtoID`),
      KEY `produtoID` (`produtoID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `marcas` (
      `ID` int NOT NULL AUTO_INCREMENT,
      `nome` varchar(50) NOT NULL,
      `logo` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`ID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `produtos` (
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
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `tipocomponente` (
      `ID` int NOT NULL AUTO_INCREMENT,
      `nome` varchar(50) NOT NULL,
      `slug` varchar(50) DEFAULT NULL,
      PRIMARY KEY (`ID`),
      UNIQUE KEY `slug` (`slug`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;",

    "CREATE TABLE IF NOT EXISTS `utilizadores` (
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
      `pfpURL` varchar(255) DEFAULT 'imagens/pfp.png',
      PRIMARY KEY (`ID`),
      UNIQUE KEY `nomeUtilizador` (`nomeUtilizador`),
      UNIQUE KEY `email` (`email`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;"
];

$sql_create_tables_string = implode(";\n", $sql_create_tables_array);

if ($conexao->multi_query($sql_create_tables_string)) {
    do {
        if ($result = $conexao->store_result()) {
            $result->free();
        }
        if ($conexao->more_results()) {
        }
    } while ($conexao->next_result());

    if ($conexao->errno) {
        echo "Erro ao criar tabelas: " . $conexao->error . "\n";
    } else {
        echo "Tabelas criadas com sucesso.\n";
    }
} else {
    echo "Erro ao executar multi_query para criação de tabelas: " . $conexao->error . "\n";
}

// Dados de exemplo
$consultas = [
    "INSERT INTO `categorias` (`nome`) VALUES ('Processadores'), ('Motherboards'), ('Memórias RAM'), ('Placas Gráficas'), ('Coolers'), ('Caixas');",
    
    "INSERT INTO `marcas` (`nome`) VALUES ('Intel'), ('AMD'), ('NVIDIA'), ('ASUS'), ('Gigabyte'), ('Corsair'), ('NZXT');",
    
    "INSERT INTO `tipocomponente` (`nome`, `slug`) VALUES ('Processador', 'cpu'), ('Motherboard', 'motherboard'), ('Memória RAM', 'ram'), ('Placa Gráfica', 'gpu'), ('Cooler', 'cooler'), ('Caixa', 'case');",
    
    "INSERT INTO `produtos` (`nome`, `descricao`, `preco`, `stock`, `categoriaID`, `marcaID`, `imagemPrincipal`) VALUES
    ('Intel Core i9-13900K', 'Processador de 24 núcleos e 32 threads, com frequência de até 5.8GHz.', 699.90, 10, 1, 1, 'i9-13900k.jpg'),
    ('AMD Ryzen 9 7950X', 'Processador de 16 núcleos e 32 threads, com frequência de até 5.7GHz.', 749.90, 10, 1, 2, 'ryzen9-7950x.jpg'),
    ('ASUS ROG Maximus Z790 Hero', 'Motherboard ATX com suporte para processadores Intel Core de 12ª e 13ª geração.', 649.90, 10, 2, 4, 'maximus-z790.jpg'),
    ('Gigabyte X670 AORUS Elite AX', 'Motherboard ATX com suporte para processadores AMD Ryzen 7000 Series.', 329.90, 10, 2, 5, 'aorus-elite-ax.jpg'),
    ('Corsair Vengeance RGB 32GB (2x16GB) DDR5 6000MHz', 'Kit de memória RAM DDR5 de 32GB com iluminação RGB.', 199.90, 10, 3, 6, 'vengeance-rgb-ddr5.jpg'),
    ('NVIDIA GeForce RTX 4090 Founders Edition', 'Placa gráfica com 24GB de memória GDDR6X.', 1999.90, 5, 4, 3, 'rtx-4090.jpg'),
    ('NZXT Kraken Z73 RGB', 'Water cooler de 360mm com display LCD personalizável.', 279.90, 10, 5, 7, 'kraken-z73.jpg'),
    ('NZXT H7 Flow', 'Caixa ATX com painel frontal em malha para máximo fluxo de ar.', 129.90, 10, 6, 7, 'h7-flow.jpg');",
    
    "INSERT INTO `especificacoes` (`produtoID`, `chaves`, `valor`) VALUES
    (1, 'Socket', 'LGA1700'), (1, 'Núcleos', '24'), (1, 'Threads', '32'),
    (2, 'Socket', 'AM5'), (2, 'Núcleos', '16'), (2, 'Threads', '32'),
    (3, 'Socket', 'LGA1700'), (3, 'Formato', 'ATX'), (3, 'Chipset', 'Z790'), (3, 'Tipo RAM', 'DDR5'),
    (4, 'Socket', 'AM5'), (4, 'Formato', 'ATX'), (4, 'Chipset', 'X670'), (4, 'Tipo RAM', 'DDR5'),
    (5, 'Tipo', 'DDR5'), (5, 'Capacidade', '32GB'), (5, 'Velocidade', '6000MHz'),
    (6, 'Memória', '24GB GDDR6X'), (6, 'Interface', 'PCI Express 4.0'),
    (7, 'Tamanho', '360mm'), (7, 'Tipo', 'Water Cooler'),
    (8, 'Formato', 'ATX'), (8, 'Tipo', 'Mid Tower');",
    
    "INSERT INTO `compatibilidade` (`produtoID`, `tipoComponenteID`, `compativelcom`) VALUES
    (3, 1, '{\"socket\": \"LGA1700\"}'),
    (4, 1, '{\"socket\": \"AM5\"}');"
];

foreach ($consultas as $consulta) {
    if ($conexao->query($consulta) === TRUE) {
        echo "Consulta executada com sucesso: " . substr($consulta, 0, 50) . "...\n";
    } else {
        echo "Erro ao executar consulta: " . $conexao->error . "\n";
    }
}

echo "Configuração da base de dados e inserção de dados concluída.\n";

$conexao->close();
?>