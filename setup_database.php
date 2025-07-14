<?php
include 'connect.php';

$sql = "CREATE TABLE IF NOT EXISTS `carrinho` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `utilizadorID` int NOT NULL,
  `produtoID` int NOT NULL,
  `quantidade` int NOT NULL,
  `dataAdicao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `utilizadorID` (`utilizadorID`),
  KEY `produtoID` (`produtoID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;";

if (mysqli_query($conn, $sql)) {
  echo "Table carrinho created successfully";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);
?>