<?php
session_start();
include('connect.php');

// Usar userID, é mais fiável e seguro
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$mensagemSucesso = '';
$mensagensErro = []; // Usar um array para múltiplas mensagens de erro.

// Obtém os detalhes atuais do utilizador ANTES de processar o formulário.
// Isto garante que a variável $utilizador está disponível para comparações.
$stmt_fetch = $conexao->prepare("SELECT nomeCompleto, morada, codigoPostal, localidade, pfpURL, telefone, nif FROM utilizadores WHERE ID = ?");
$stmt_fetch->bind_param("i", $userID);
$stmt_fetch->execute();
$resultado = $stmt_fetch->get_result();
$utilizador = $resultado->fetch_assoc();
$stmt_fetch->close();

// Processa a submissão do formulário para atualizar os dados.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    
    // Arrays para construir a query de forma dinâmica.
    $campos_atualizar = [];
    $parametros = [];
    $tipos = "";

    // Valida e prepara cada campo para atualização.
    $nomeCompleto = trim($_POST['nomeCompleto']);
    if (!empty($nomeCompleto)) {
        if (!preg_match('/^[\p{L}\s.-]+$/u', $nomeCompleto)) {
            $mensagensErro[] = "O nome completo apenas pode conter letras, espaços, pontos e hífens.";
        } else {
            $campos_atualizar[] = "nomeCompleto = ?";
            $parametros[] = $nomeCompleto;
            $tipos .= "s";
        }
    }

    $telefone = trim($_POST['telefone']);
    if (!empty($telefone)) {
        if (!preg_match('/^\d{9}$/', $telefone)) {
            $mensagensErro[] = "O seu número de telefone deve conter exatamente 9 dígitos.";
        } else {
            $campos_atualizar[] = "telefone = ?";
            $parametros[] = $telefone;
            $tipos .= "s";
        }
    }

    $nif = trim($_POST['nif']);
    if (!empty($nif)) {
        if (!preg_match('/^\d{9}$/', $nif)) {
            $mensagensErro[] = "O seu NIF deve conter exatamente 9 dígitos.";
        } else {
            $campos_atualizar[] = "nif = ?";
            $parametros[] = $nif;
            $tipos .= "s";
        }
    }

    $morada = trim($_POST['morada']);
    if (!empty($morada)) {
        $campos_atualizar[] = "morada = ?";
        $parametros[] = $morada;
        $tipos .= "s";
    }

    $codigoPostal = trim($_POST['codigoPostal']);
    if (!empty($codigoPostal)) {
        if (!preg_match('/^\d{4}-\d{3}$/', $codigoPostal)) {
            $mensagensErro[] = "O seu código postal deve estar no formato XXXX-XXX (ex: 1234-567).";
        } else {
            $campos_atualizar[] = "codigoPostal = ?";
            $parametros[] = $codigoPostal;
            $tipos .= "s";
        }
    }

    $localidade = trim($_POST['localidade']);
    if (!empty($localidade)) {
        if (!preg_match('/^[\p{L}\s.-]+$/u', $localidade)) {
            $mensagensErro[] = "A sua localidade apenas pode conter letras, espaços, pontos e hífens.";
        } else {
            $campos_atualizar[] = "localidade = ?";
            $parametros[] = $localidade;
            $tipos .= "s";
        }
    }

    $password = trim($_POST['password']);
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $campos_atualizar[] = "passwordHash = ?";
        $parametros[] = $hashedPassword;
        $tipos .= "s";
    }

    if (!empty($_POST['pfp']) && $_POST['pfp'] !== $utilizador['pfpURL']) {
        $pfp = $_POST['pfp'];
        $campos_atualizar[] = "pfpURL = ?";
        $parametros[] = $pfp;
        $tipos .= "s";
        $_SESSION['pfpURL'] = $pfp; // Atualiza a sessão imediatamente.
    }

    // Executa a atualização apenas se não houver erros de validação e houver campos para alterar.
    if (empty($mensagensErro) && !empty($campos_atualizar)) {
        $sql = "UPDATE utilizadores SET " . implode(', ', $campos_atualizar) . " WHERE ID = ?";
        $parametros[] = $userID;
        $tipos .= "i";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param($tipos, ...$parametros);

        if ($stmt->execute()) {
            $mensagemSucesso = "Perfil atualizado com sucesso!";
        } else {
            $mensagensErro[] = "Ocorreu um erro ao atualizar o perfil. Tente novamente.";
        }
        $stmt->close();
    } elseif (empty($campos_atualizar) && empty($mensagensErro)) {
        $mensagensErro[] = "Nenhum campo foi preenchido para atualização.";
    }

    // Após a atualização, obtém novamente os dados do utilizador para exibir os valores mais recentes no formulário.
    $stmt_fetch_refresh = $conexao->prepare("SELECT nomeCompleto, morada, codigoPostal, localidade, pfpURL, telefone, nif FROM utilizadores WHERE ID = ?");
    $stmt_fetch_refresh->bind_param("i", $userID);
    $stmt_fetch_refresh->execute();
    $utilizador = $stmt_fetch_refresh->get_result()->fetch_assoc();
    $stmt_fetch_refresh->close();
}

// Lista de fotos de perfil disponíveis
$pfps_disponiveis = ['imagens/pfp.png', 'imagens/pfp2.png', 'imagens/pfp3.png', 'imagens/pfp4.png'];
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Definições do Perfil</title>
    <style>
        /* CSS para o seletor de foto de perfil */
        .pfp-selector {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .pfp-option {
            cursor: pointer;
            position: relative;
        }
        .pfp-option img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid transparent;
            transition: border-color 0.2s;
        }
        .pfp-option input[type="radio"] {
            display: none; /* Hide the actual radio button */
        }
        .pfp-option input[type="radio"]:checked + img {
            border-color: var(--primary-color);
        }
    </style>
</head>
<body>

  <?php include 'navbar.php'; ?>
  
  <main class="container my-5">
    <div class="col-lg-8 mx-auto">
        <h2>Definições do Perfil</h2>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessages)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errorMessages as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="definicoes.php">
            <div class="mb-4">
                <h5>Escolha a sua foto de perfil</h5>
                <div class="pfp-selector">
                    <?php foreach ($available_pfps as $pfp_path): ?>
                        <label class="pfp-option">
                            <input type="radio" name="pfp" value="<?php echo htmlspecialchars($pfp_path); ?>" <?php echo (isset($utilizador['pfpURL']) && $utilizador['pfpURL'] == $pfp_path) ? 'checked' : ''; ?>>
                            <img src="<?php echo htmlspecialchars($pfp_path); ?>" alt="Foto de Perfil" class="img-thumbnail">
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="nomeCompleto" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto" placeholder="<?php echo htmlspecialchars($user['nomeCompleto'] ?? 'Insira o seu nome completo'); ?>">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="<?php echo htmlspecialchars($user['telefone'] ?? 'Insira o seu telefone'); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nif" class="form-label">NIF</label>
                    <input type="text" class="form-control" id="nif" name="nif" placeholder="<?php echo htmlspecialchars($user['nif'] ?? 'Insira o seu NIF'); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="morada" class="form-label">Morada de Entrega</label>
                <input type="text" class="form-control" id="morada" name="morada" placeholder="<?php echo htmlspecialchars($user['morada'] ?? 'Insira a sua morada'); ?>">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="codigoPostal" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" placeholder="<?php echo htmlspecialchars($user['codigoPostal'] ?? 'Ex: 1234-567'); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="localidade" class="form-label">Localidade</label>
                    <input type="text" class="form-control" id="localidade" name="localidade" placeholder="<?php echo htmlspecialchars($user['localidade'] ?? 'Ex: Lisboa'); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Nova Palavra-passe (deixe em branco para não alterar)</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="********">
            </div>

            <button type="submit" name="update" class="btn btn-primary">Atualizar Perfil</button>
        </form>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="script.js"></script>
</body>
</html>
