<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // recebe os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $servicos = isset($_POST['servicos']) ? implode(', ', $_POST['servicos']) : '';
    $data = $_POST['data'] ?? '';
    $horario = $_POST['horario'] ?? '';

    // conecta ao banco de dados
    $conexao = new mysqli('localhost', 'root', '', 'studio_dellas');

    // verifica a conexão
    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // prepara e executa a consulta SQL
    $stmt = $conexao->prepare("INSERT INTO agendamentos (nome, telefone, email, servicos, data, horario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $telefone, $email, $servicos, $data, $horario);

    $mensagem = "";
    if ($stmt->execute()) {
        $mensagem = "<h2>Agendamento realizado com sucesso!</h2>
                     <p>Detalhes do agendamento:</p>
                     <ul>
                         <li><strong>Serviço:</strong> $servicos</li>
                         <li><strong>Data:</strong> $data</li>
                         <li><strong>Horário:</strong> $horario</li>
                     </ul>";
    } else {
        $mensagem = "<p>Erro ao realizar o agendamento: " . $stmt->error . "</p>";
    }

    // fecha a consulta
    $stmt->close();

    // lista os agendamentos do cliente
    $agendamentos_cliente = "";
    $resultado = $conexao->query("SELECT servicos, data, horario FROM agendamentos WHERE email = '$email'");
    if ($resultado->num_rows > 0) {
        $agendamentos_cliente = "<h2>Seus Agendamentos:</h2>";
        while ($row = $resultado->fetch_assoc()) {
            $agendamentos_cliente .= "<p><strong>Serviço:</strong> " . $row['servicos'] . " | 
                                      <strong>Data:</strong> " . $row['data'] . " | 
                                      <strong>Horário:</strong> " . $row['horario'] . "</p>";
        }
    } else {
        $agendamentos_cliente = "<p>Você ainda não tem agendamentos anteriores.</p>";
    }

    // fecha a conexão
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

    <?php 
    // serve para exibir a mensagem de confirmação e os agendamentos do cliente
    if (isset($mensagem)) {
        echo $mensagem;
    }
    if (isset($agendamentos_cliente)) {
        echo $agendamentos_cliente;
    }
    ?>
</body>
</html>
