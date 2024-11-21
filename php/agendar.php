<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // aqui vai receber os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $servicos = isset($_POST['servicos']) ? implode(', ', $_POST['servicos']) : '';
    $data = $_POST['data'] ?? '';
    $horario = $_POST['horario'] ?? '';

    // conectar com o banco de dados
    $conexao = new mysqli('localhost', 'root', '', 'studio_dellas_novo');

    // verifica a conexão
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // preparar e executar a consulta SQL
    $stmt = $conexao->prepare("INSERT INTO agendamentos (nome, telefone, email, servicos, data, horario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $telefone, $email, $servicos, $data, $horario);

    if ($stmt->execute()) {
        $mensagem = "Agendamento realizado com sucesso!<br>";
        $mensagem .= "Serviço(s): $servicos<br>";
        $mensagem .= "Data: $data<br>";
        $mensagem .= "Horário: $horario<br>";
    } else {
        $mensagem = "Erro ao realizar o agendamento: " . $stmt->error;
    }

    // fechar a consulta e a conexão
    $stmt->close();
    $conexao->close();
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
    <header>
        <h1>Agende seu serviço no Studio D'ellas</h1>
    </header>

    <?php if (isset($mensagem)): ?>
        <div class="mensagem-confirmacao">
            <h2>Confirmação de Agendamento</h2>
            <p><?php echo $mensagem; ?></p>
            <a href="visualizar_agendamentos.php">Visualizar meus agendamentos</a>
        </div>
    <?php endif; ?>

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
