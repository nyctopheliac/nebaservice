<?php
session_start();
include 'connect.php';
include 'config.php'; // Incluir para aceder a SHIPPING_COST

// Incluir a biblioteca FPDF
require('fpdf/fpdf.php');

if (isset($_GET['order_id'])) {
    $encomendaID = $_GET['order_id'];
} elseif (isset($_SESSION['last_order_id'])) {
    $encomendaID = $_SESSION['last_order_id'];
    // Opcional: Limpar da sessão se for a primeira vez que está a ser usado
    unset($_SESSION['last_order_id']);
} else {
    // Redirecionar se não houver ID de encomenda
    header('Location: index.php');
    exit();
}

// Ir buscar os detalhes da encomenda
$sql_order = "SELECT e.*, u.nomeCompleto, u.morada, u.codigoPostal, u.localidade, u.pais, u.email 
              FROM encomendas e 
              JOIN utilizadores u ON e.utilizadorID = u.ID 
              WHERE e.ID = ?";
$stmt_order = mysqli_prepare($conexao, $sql_order);
mysqli_stmt_bind_param($stmt_order, "i", $encomendaID);
mysqli_stmt_execute($stmt_order);
$result_order = mysqli_stmt_get_result($stmt_order);
$order = mysqli_fetch_assoc($result_order);

if (!$order) {
    // Encomenda não encontrada
    header('Location: index.php');
    exit();
}

// Ir buscar os produtos da encomenda
$sql_products = "SELECT ep.*, p.nome as produto_nome 
                 FROM encomendaprodutos ep 
                 JOIN produtos p ON ep.produtoID = p.ID 
                 WHERE ep.encomendaID = ?";
$stmt_products = mysqli_prepare($conexao, $sql_products);
mysqli_stmt_bind_param($stmt_products, "i", $encomendaID);
mysqli_stmt_execute($stmt_products);
$result_products = mysqli_stmt_get_result($stmt_products);
$order_products = mysqli_fetch_all($result_products, MYSQLI_ASSOC);

class PDF extends FPDF
{
    // Cabeçalho
    function Header()
    {
        // Logo (assumindo que tem uma imagem de logo na pasta 'imagens')
        // $this->Image('imagens/logo.png', 10, 8, 33);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Mover para a direita
        $this->Cell(80);
        // Título
        $this->Cell(30, 10, utf8_decode('NEBASERVICE'), 1, 0, 'C');
        // Quebra de linha
        $this->Ln(20);
    }

    // Rodapé
    function Footer()
    {
        // Posição a 1.5 cm do fundo
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número da página
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Tabela de produtos
    function ProductTable($header, $data)
    {
        // Cores, largura da linha e fonte em negrito
        $this->SetFillColor(200, 220, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');

        // Cabeçalho
        $w = array(80, 30, 40, 40);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        $this->Ln();
        // Restaurar cores e fonte
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Dados
        $fill = false;
        foreach ($data as $row)
        {
            $this->Cell($w[0], 6, utf8_decode($row['produto_nome']), 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row['quantidade'], 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 6, '€' . number_format($row['precoUnitario'], 2, ',', '.'), 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, '€' . number_format($row['quantidade'] * $row['precoUnitario'], 2, ',', '.'), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Linha de fecho
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

// Instanciação da classe PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Informações da Empresa (Exemplo)
$pdf->SetTextColor(0);
$pdf->Cell(0, 10, utf8_decode('NebaService Lda.'), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Rua de Testes, 123'), 0, 1);
$pdf->Cell(0, 10, utf8_decode('1234-567 Cidade'), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Portugal'), 0, 1);
$pdf->Ln(10);

// Informações do Cliente
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Faturado para:'), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode($order['nomeCompleto']), 0, 1);
$pdf->Cell(0, 10, utf8_decode($order['morada']), 0, 1);
$pdf->Cell(0, 10, utf8_decode($order['codigoPostal']) . ' ' . utf8_decode($order['localidade']), 0, 1);
$pdf->Cell(0, 10, utf8_decode($order['pais']), 0, 1);
$pdf->Cell(0, 10, utf8_decode($order['email']), 0, 1);
$pdf->Ln(10);

// Detalhes da Encomenda
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Detalhes da Encomenda:'), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Número da Encomenda: ' . $order['ID']), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Data da Encomenda: ' . date('d/m/Y', strtotime($order['dataEncomenda']))), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Estado: ' . $order['estado']), 0, 1);
$pdf->Ln(10);

// Tabela de Produtos
$header = array(utf8_decode('Produto'), utf8_decode('Qtd'), utf8_decode('Preço Unitário'), utf8_decode('Total'));
$pdf->ProductTable($header, $order_products);
$pdf->Ln(5);

// Calcular o subtotal
$subtotal = 0;
foreach ($order_products as $product) {
    $subtotal += $product['quantidade'] * $product['precoUnitario'];
}

// Totais
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(130, 8, utf8_decode('Subtotal:'), 0, 0, 'R');
$pdf->Cell(60, 8, 'EUR ' . number_format($subtotal, 2, ',', '.'), 0, 1, 'R');

$pdf->Cell(130, 8, utf8_decode('Portes de Envio:'), 0, 0, 'R');
$pdf->Cell(60, 8, 'EUR ' . number_format(SHIPPING_COST, 2, ',', '.'), 0, 1, 'R');

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(130, 10, utf8_decode('Total da Fatura:'), 0, 0, 'R');
$pdf->Cell(60, 10, 'EUR ' . number_format($order['total'], 2, ',', '.'), 0, 1, 'R');

$pdf->Output('D', 'fatura_' . $encomendaID . '.pdf');

mysqli_close($conexao);
?>