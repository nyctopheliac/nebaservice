<?php
// receber dados
$username = $_POST['username'];
$password = $_POST['password'];
$sharedFolder = $_POST['sharedFolder'];

// validar inputs
if (empty($username) || empty($password) || empty($sharedFolder)) {
    die("All fields are required.");
}

// filtrar inputs
$username = escapeshellarg($username);
$password = escapeshellarg($password);
$sharedFolder = escapeshellarg($sharedFolder);

// comando para executar o script ssh
$command = "sudo /path/to/create_user.sh $username $password $sharedFolder";

// execução e captura de output
$output = shell_exec($command);

// verificação
if ($output === null) {
    die("Failed to execute the script.");
}

// mostrar output
echo "<h1>User Creation Output</h1>";
echo "<pre>$output</pre>";
?>