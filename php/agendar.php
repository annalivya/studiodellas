<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // validação dos dados
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $servicos = $_POST['servicos'] ?? [];
    $data = $_POST['data'] ?? '';
    $horario = $_POST['horario'] ?? '';

    if ($nome && $telefone && $email && $servicos && $data && $horario) {
        // conectar com o banco de dados
        $conexao = new mysqli('localhost', 'root', '', 'studio_dellas');

        if ($conexao->connect_error) {
            die("Falha na conexão: " . $conexao->connect_error);
        }

        // preparar e executar a consulta SQL para inserir o agendamento
        $stmt = $conexao->prepare("INSERT INTO agendamentos (nome, telefone, email, data, horario) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $telefone, $email, $data, $horario);

        if ($stmt->execute()) {
            // pega o ID do agendamento inserido
            $agendamento_id = $stmt->insert_id;

            // para associar os serviços selecionados a tabela agendamentos_servicos
            $stmt_servicos = $conexao->prepare("INSERT INTO agendamentos_servicos (agendamento_id, servico_id) VALUES (?, ?)");

            foreach ($servicos as $servico_id) {
                $stmt_servicos->bind_param("ii", $agendamento_id, $servico_id);
                $stmt_servicos->execute();
            }

            echo "Agendamento realizado com sucesso!";
        } else {
            echo "Erro ao realizar o agendamento: " . $stmt->error;
        }

        // fechar a consulta e a conexão
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
            <?php
            // conectar ao banco para buscar os serviços disponíveis
            $conexao = new mysqli('localhost', 'root', '', 'studio_dellas');
            if ($conexao->connect_error) {
                die("Falha na conexão: " . $conexao->connect_error);
            }

            // obter todos os serviços disponíveis
            $result = $conexao->query("SELECT id, nome FROM servicos");
            while ($servico = $result->fetch_assoc()) {
                echo "<option value='" . $servico['id'] . "'>" . $servico['nome'] . "</option>";
            }

            // fechar a conexão após o uso
            $conexao->close();
            ?>
        </select>

        <label for="data">Data:</label>
        <input type="date" id="data" name="data" required>

        <label for="horario">Horário:</label>
        <input type="time" id="horario" name="horario" required>

        <button type="submit" name="submit">Agendar</button>
    </form>
</body>
</html>
