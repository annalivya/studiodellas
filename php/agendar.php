<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validação simples dos dados
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $servicos = isset($_POST['servicos']) ? implode(', ', $_POST['servicos']) : '';
    $data = $_POST['data'] ?? '';
    $horario = $_POST['horario'] ?? '';

    if ($nome && $telefone && $email && $servicos && $data && $horario) {
        // Conectar com o banco de dados
        $conexao = new mysqli('localhost', 'root', '', 'studio_dellas');

        if ($conexao->connect_error) {
            die("Falha na conexão: " . $conexao->connect_error);
        }

        // Preparar e executar a consulta SQL
        $stmt = $conexao->prepare("INSERT INTO agendamentos (nome, telefone, email, servicos, data, horario) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $telefone, $email, $servicos, $data, $horario);

        if ($stmt->execute()) {
            echo "Agendamento realizado com sucesso!";
        } else {
            echo "Erro ao realizar o agendamento: " . $stmt->error;
        }

        // Fechar a consulta e a conexão
        $stmt->close();
        $conexao->close();
    } else {
        echo "Todos os campos são obrigatórios.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento - Studio D'ellas</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <script src="../js/script.js"></script>

    <header>
        <h1>Agende seu serviço no Studio D'ellas</h1>
    </header>

    <form action="agendar.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="servicos">Escolha os serviços:</label>
        <select name="servicos[]" id="servicos" multiple required>
            <option value="unhas">Unhas</option>
            <option value="cabelo">Cabelo</option>
            <option value="sobrancelha">Sobrancelha</option>
            <option value="cilios">Cílios</option>
            <option value="limpeza_pele">Limpeza de Pele</option>
        </select>

        <label for="data">Data:</label>
        <input type="date" id="data" name="data" required>

        <label for="horario">Horário:</label>
        <input type="time" id="horario" name="horario" required>

        <button type="submit" name="submit">Agendar</button>
    </form>
</body>
</html>
