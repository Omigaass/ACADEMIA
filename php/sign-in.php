<?php
    
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_POST['USER_EMAIL'];
    $user_pw = $_POST['USER_PASSWORD'];

    // Conexão com o banco de dados usando MySQLi
    $mysqli = new mysqli('localhost', 'root', '', 'triboworkout');

    // Verificar a conexão
    if ($mysqli->connect_error) {
        die("Erro na conexão: " . $mysqli->connect_error);
    }
    // Consulta SQL para obter o usuário com base no email
    $query_select = "SELECT USER_EMAIL, USER_PASSWORD FROM user_account WHERE USER_EMAIL = ?";
    $session_id_select = "SELECT USER_COD FROM user_account WHERE USER_EMAIL = ?";
    $stmt = $mysqli->prepare($query_select);
    $stmt->bind_param("s", $user_email);

    // Executar a consulta
    $stmt->execute();

    // Obter o resultado da consulta
    $result = $stmt->get_result();

    // Verificar se o usuário existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['USER_PASSWORD'];

        // Verificar a senha
        if (password_verify($user_pw, $hashed_password)) {
            // Senha correta, redirecionar para a página de sucesso
            $_SESSION['USER_ID'] = $session_id_select;
            header('Location: ../usuario.html');
        } else {
            // Senha incorreta
            echo "<script language='javascript' type='text/javascript'>
            alert('Senha incorreta');window.location.href='../index.html';</script>";
        }
    } else {
        // Usuário não encontrado
        echo "<script language='javascript' type='text/javascript'>
        alert('Usuário não encontrado');window.location.href='../index.html';</script>";
    }

    // Fechar a conexão
    $stmt->close();
    $mysqli->close();
}
?>