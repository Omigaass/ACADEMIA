<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["USER_NICKNAME"];
    $email = $_POST["USER_EMAIL"];
    $password = $_POST["USER_PASSWORD"];

    if (empty($username) || empty($email) || empty($password)) {
        $erro = "Todos os campos devem ser preenchidos.";
    } else {
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "triboworkout";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        if ($conn->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }

        $check_username_sql = "SELECT * FROM USER_ACCOUNT WHERE USER_NICKNAME = '$username'";
        $check_username_result = $conn->query($check_username_sql);

        if ($check_username_result->num_rows > 0) {
            $erro = "Nome de usuário já está em uso. Escolha outro nome de usuário.";
        } else {
            $insert_sql = "INSERT INTO USER_ACCOUNT (USER_NICKNAME, USER_EMAIL, USER_PASSWORD)
                            VALUES ('$username', '$email', '$password')";

            if ($conn->query($insert_sql) === TRUE) {
                $_SESSION["USER_NICKNAME"] = $username;
                header("Location: home.html");
                exit();
            } else {
                $erro = "Erro ao cadastrar usuário: " . $conn->error;
            }
        }
        $conn->close();
    }
}
?>