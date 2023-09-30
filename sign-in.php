<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["USER_NICKNAME"];
    $password = $_POST["USER_PASSWORD"];

    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "triboworkout";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM USER_ACCOUNT WHERE USER_NICKNAME = '$username' AND USER_PASSWORD = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION["USER_NICKNAME"] = $username;
        header("Location: home.html");
        exit();
    } else {
        if (isset($erro)) {
            exibirPopupErro($erro);
        }
    }

    $conn->close();
}
?>