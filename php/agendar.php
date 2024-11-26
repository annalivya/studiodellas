<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $servicos = $_POST['servicos'] ?? []; // Array com os IDs dos serviços selecionados
    $data = $_POST['data'] ?? '';
    $horario = $_POST['horario'] ?? '';

    // Verifica se pelo menos um serviço foi selecionado
    if (empty($servicos)) {
        echo "Por favor, selecione pelo menos um serviço.";
        exit;
    }

    // Definir os preços dos serviços
    $precos_servicos = [
        1 => 30.00, // Preço das Unhas
        2 => 80.00, // Preço do Cabelo
        3 => 50.00, // Preço das Sobrancelha
        4 => 100.00, // Preço dos Cílios
        5 => 120.00 // Preço da Limpeza de Pele
    ];

    // Calcular o valor total
    $total = 0;
    foreach ($servicos as $servico_id) {
        if (isset($precos_servicos[$servico_id])) {
            $total += $precos_servicos[$servico_id];
        }
    }

    // Conectar com o banco de dados
    $conexao = new mysqli('localhost', 'root', '', 'studio_dellas_novo');

    if ($conexao->connect_error) {
        die("Falha na conexão: " . $conexao->connect_error);
    }

    // Inserir dados do agendamento
    $stmt = $conexao->prepare("INSERT INTO agendamentos (nome, telefone, email, data, horario, total) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $nome, $telefone, $email, $data, $horario, $total);

    if ($stmt->execute()) {
        $agendamento_id = $stmt->insert_id; // Pega o ID do agendamento inserido

        // Inserir os serviços escolhidos
        foreach ($servicos as $servico_id) {
            $stmt_servico = $conexao->prepare("INSERT INTO agendamentos_servicos (agendamento_id, servico_id) VALUES (?, ?)");
            $stmt_servico->bind_param("ii", $agendamento_id, $servico_id);
            $stmt_servico->execute();
            $stmt_servico->close();
        }

        // Mensagem de confirmação
        echo "Agendamento realizado com sucesso!<br>";
        echo "Serviços: " . implode(", ", $servicos) . "<br>";
        echo "Data: " . $data . "<br>";
        echo "Horário: " . $horario . "<br>";
        echo "Total: R$ " . number_format($total, 2, ',', '.') . "<br>";  // Exibe o valor total
    } else {
        echo "Erro ao realizar o agendamento: " . $stmt->error;
    }

    // Fechar a consulta e a conexão
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
    <header class="header-agendamento">
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
        <div id="servicos">
            <label><input type="checkbox" name="servicos[]" value="1"> Unhas (R$ 30,00)</label><br>
            <label><input type="checkbox" name="servicos[]" value="2"> Cabelo (R$ 80,00)</label><br>
            <label><input type="checkbox" name="servicos[]" value="3"> Sobrancelha (R$ 50,00)</label><br>
            <label><input type="checkbox" name="servicos[]" value="4"> Cílios (R$ 100,00)</label><br>
            <label><input type="checkbox" name="servicos[]" value="5"> Limpeza de Pele (R$ 120,00)</label><br>
        </div>

        <label for="data">Data:</label>
        <input type="date" id="data" name="data" required>

        <label for="horario">Horário:</label>
        <input type="time" id="horario" name="horario" required>

        <button type="submit" name="submit">Agendar</button>
    </form>
</body>
</html>
