<?php
session_start();
include('connect.php');

// Use userID, it's more reliable and secure
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$successMessage = '';
$errorMessages = []; // Usar um array para múltiplas mensagens de erro.

// Obtém os detalhes atuais do utilizador ANTES de processar o formulário.
// Isto garante que a variável $user está disponível para comparações.
$stmt_fetch = $conn->prepare("SELECT nomeCompleto, morada, codigoPostal, localidade, pfpURL, telefone, nif FROM utilizadores WHERE ID = ?");
$stmt_fetch->bind_param("i", $userID);
$stmt_fetch->execute();
$result = $stmt_fetch->get_result();
$user = $result->fetch_assoc();
$stmt_fetch->close();

// Processa a submissão do formulário para atualizar os dados.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    
    // Arrays para construir a query de forma dinâmica.
    $update_fields = [];
    $params = [];
    $types = "";

    // Valida e prepara cada campo para atualização.
    $nomeCompleto = trim($_POST['nomeCompleto']);
    if (!empty($nomeCompleto)) {
        if (!preg_match('/^[\p{L}\s.-]+$/u', $nomeCompleto)) {
            $errorMessages[] = "O nome completo apenas pode conter letras, espaços, pontos e hífens.";
        } else {
            $update_fields[] = "nomeCompleto = ?";
            $params[] = $nomeCompleto;
            $types .= "s";
        }
    }

    $telefone = trim($_POST['telefone']);
    if (!empty($telefone)) {
        if (!preg_match('/^\d{9}$/', $telefone)) {
            $errorMessages[] = "O seu número de telefone deve conter exatamente 9 dígitos.";
        } else {
            $update_fields[] = "telefone = ?";
            $params[] = $telefone;
            $types .= "s";
        }
    }

    $nif = trim($_POST['nif']);
    if (!empty($nif)) {
        if (!preg_match('/^\d{9}$/', $nif)) {
            $errorMessages[] = "O seu NIF deve conter exatamente 9 dígitos.";
        } else {
            $update_fields[] = "nif = ?";
            $params[] = $nif;
            $types .= "s";
        }
    }

    $morada = trim($_POST['morada']);
    if (!empty($morada)) {
        $update_fields[] = "morada = ?";
        $params[] = $morada;
        $types .= "s";
    }

    $codigoPostal = trim($_POST['codigoPostal']);
    if (!empty($codigoPostal)) {
        if (!preg_match('/^\d{4}-\d{3}$/', $codigoPostal)) {
            $errorMessages[] = "O seu código postal deve estar no formato XXXX-XXX (ex: 1234-567).";
        } else {
            $update_fields[] = "codigoPostal = ?";
            $params[] = $codigoPostal;
            $types .= "s";
        }
    }

    $localidade = trim($_POST['localidade']);
    if (!empty($localidade)) {
        if (!preg_match('/^[\p{L}\s.-]+$/u', $localidade)) {
            $errorMessages[] = "A sua localidade apenas pode conter letras, espaços, pontos e hífens.";
        } else {
            $update_fields[] = "localidade = ?";
            $params[] = $localidade;
            $types .= "s";
        }
    }

    $password = trim($_POST['password']);
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $update_fields[] = "passwordHash = ?";
        $params[] = $hashedPassword;
        $types .= "s";
    }

    if (!empty($_POST['pfp']) && $_POST['pfp'] !== $user['pfpURL']) {
        $pfp = $_POST['pfp'];
        $update_fields[] = "pfpURL = ?";
        $params[] = $pfp;
        $types .= "s";
        $_SESSION['pfpURL'] = $pfp; // Atualiza a sessão imediatamente.
    }

    // Executa a atualização apenas se não houver erros de validação e houver campos para alterar.
    if (empty($errorMessages) && !empty($update_fields)) {
        $sql = "UPDATE utilizadores SET " . implode(', ', $update_fields) . " WHERE ID = ?";
        $params[] = $userID;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $successMessage = "Perfil atualizado com sucesso!";
        } else {
            $errorMessages[] = "Ocorreu um erro ao atualizar o perfil. Tente novamente.";
        }
        $stmt->close();
    } elseif (empty($update_fields) && empty($errorMessages)) {
        $errorMessages[] = "Nenhum campo foi preenchido para atualização.";
    }

    // Após a atualização, obtém novamente os dados do utilizador para exibir os valores mais recentes no formulário.
    $stmt_fetch_refresh = $conn->prepare("SELECT nomeCompleto, morada, codigoPostal, localidade, pfpURL, telefone, nif FROM utilizadores WHERE ID = ?");
    $stmt_fetch_refresh->bind_param("i", $userID);
    $stmt_fetch_refresh->execute();
    $user = $stmt_fetch_refresh->get_result()->fetch_assoc();
    $stmt_fetch_refresh->close();
}

// List of available profile pictures
$available_pfps = ['imagens/pfp.png', 'imagens/pfp2.png', 'imagens/pfp3.png', 'imagens/pfp4.png'];
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
        /* CSS for the profile picture selector */
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
                            <input type="radio" name="pfp" value="<?php echo htmlspecialchars($pfp_path); ?>" <?php echo (isset($user['pfpURL']) && $user['pfpURL'] == $pfp_path) ? 'checked' : ''; ?>>
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
