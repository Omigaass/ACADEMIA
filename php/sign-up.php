<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['USER_NICKNAME'];
    $user_pw = $_POST['USER_PASSWORD'];
    $user_email = $_POST['USER_EMAIL'];

    // Conexão com o banco de dados usando MySQLi
    $mysqli = new mysqli('localhost', 'root', '', 'triboworkout');

    // Verificar a conexão
    if ($mysqli->connect_error) {
        die("Erro na conexão: " . $mysqli->connect_error);
    }

    // Consulta SQL com prepared statement para evitar SQL injection
    $query_select = "SELECT USER_NICKNAME FROM user_account WHERE USER_NICKNAME = ?";
    $session_id_select = "SELECT USER_COD FROM user_account WHERE USER_EMAIL = ?";
    $stmt = $mysqli->prepare($query_select);
    $stmt->bind_param("s", $user_name);

    // Executar a consulta
    $stmt->execute();

    // Obter o resultado da consulta
    $result = $stmt->get_result();

    // Verificar se o usuário já existe
    if ($result->num_rows > 0) {
        // Usuário já existe
        echo "<script language='javascript' type='text/javascript'>
        alert('Esse login já existe');window.location.href='../index.html';</script>";
        die();
    } else {
        // Usuário não existe, vamos inseri-lo no banco de dados

        // Consulta SQL para inserir usuário
        $query_insert = "INSERT INTO user_account (USER_NICKNAME, USER_PASSWORD, USER_EMAIL) VALUES (?, ?, ?)";
        $stmt_insert = $mysqli->prepare($query_insert);

        // Hash da senha
        $hashed_password = password_hash($user_pw, PASSWORD_DEFAULT);

        // Bind dos parâmetros
        $stmt_insert->bind_param("sss", $user_name, $hashed_password, $user_email);

        // Executar a inserção
        if ($stmt_insert->execute()) {
            // Sucesso no cadastro
            $_SESSION['USER_ID'] = $session_id_select;
            echo "<script language='javascript' type='text/javascript'>
            alert('Usuário cadastrado com sucesso!');window.location.href='../usuario.html';</script>";
        } else {
            // Erro na inserção
            echo "<script language='javascript' type='text/javascript'>
            alert('Não foi possível cadastrar esse usuário');window.location.href='../index.html';</script>";
        }
    }

    // Fechar a conexão
    $stmt->close();
    $stmt_insert->close();
    $mysqli->close();
}