
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Configurador de PC</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">NebaService</a></li>
            <li><a href="catalogo.php">Catálogo</a></li>
            <li><a href="servicos.php">Serviços</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div>
        <h1>Configurador de PC</h1>
        <form id="configuratorForm">
            <label for="cpu">Selecione o Processador:</label>
            <select id="cpu" name="cpu">
                <option value="intel_i5">Intel i5</option>
                <option value="intel_i7">Intel i7</option>
                <option value="amd_ryzen_5">AMD Ryzen 5</option>
                <option value="amd_ryzen_7">AMD Ryzen 7</option>
            </select>

            <label for="gpu">Selecione a Placa Gráfica:</label>
            <select id="gpu" name="gpu">
                <option value="nvidia_gtx_1660">NVIDIA GTX 1660</option>
                <option value="nvidia_rtx_3060">NVIDIA RTX 3060</option>
                <option value="amd_rx_6700">AMD RX 6700</option>
            </select>

            <label for="ram">Selecione a Memória RAM:</label>
            <select id="ram" name="ram">
                <option value="8gb">8 GB</option>
                <option value="16gb">16 GB</option>
                <option value="32gb">32 GB</option>
            </select>

            <input type="submit" value="Montar o Meu PC">
        </form>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 NebaService. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>
