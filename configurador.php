
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Configurador de PC</title>
</head>

<body>

  <?php include 'navbar.php'; ?>

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

            <input type="hidden" id="produtoID" name="produtoID" value="123"> <!-- Example product ID -->
            <button type="button" onclick="addToCart()">Adicionar ao Carrinho</button>
        </form>
        <div id="addToCartResult"></div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
        function addToCart() {
            var produtoID = document.getElementById('produtoID').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'adicionar_carrinho.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('addToCartResult').innerHTML = xhr.responseText;
                } else {
                    document.getElementById('addToCartResult').innerHTML = 'Erro ao adicionar ao carrinho.';
                }
            };
            xhr.send('produtoID=' + produtoID);
        }
    </script>

</body>
</html>
