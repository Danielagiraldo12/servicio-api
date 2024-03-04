<?php
require 'conexion.php';

$conexion = new Conexion();
$pdo = $conexion->obtenerConexion();

// Listar registros y consultar registro
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql = "SELECT * FROM usuario";
    $params = [];

    if (isset($_GET['id'])) {
        $sql .= " WHERE id_usuario=:id";
        $params[':id'] = $_GET['id'];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    header("HTTP/1.1 200 OK");
    echo json_encode($stmt->fetchAll());
    exit;
}

// Insertar registro
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $sql = "INSERT INTO usuario (cedula, nombres, apellidos, celular , correo, direccion, id_tipo_documento, id_perfil, id_estado, login, password) VALUES (:cedula, :nombres, :apellidos, :celular, :correo, :direccion, :id_tipo_documento,:id_perfil,:id_estado,:login, :password)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':cedula', $_POST['cedula']);
    $stmt->bindValue(':nombres', $_POST['nombres']);
    $stmt->bindValue(':apellidos', $_POST['apellidos']);
    $stmt->bindValue(':celular', $_POST['celular']);
    $stmt->bindValue(':correo', $_POST['correo']);
    $stmt->bindValue(':direccion', $_POST['direccion']);
    $stmt->bindValue(':id_tipo_documento', $_POST['id_tipo_documento']);
    $stmt->bindValue(':id_perfil', $_POST['id_perfil']);
    $stmt->bindValue(':id_estado', $_POST['id_estado']);
    $stmt->bindValue(':login', $_POST['login']);
    $stmt->bindValue(':password', $_POST['password']);

    $stmt->execute();
    $idPost = $pdo->lastInsertId();
    if ($idPost) {
        header("HTTP/1.1 200 Ok");
        echo json_encode($idPost);
        exit;
    }
}

// Actualizar registro
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $sql = "UPDATE contactos SET nombre=:nombre, telefono=:telefono, email=:email WHERE id_usuario=:id";
    $sql = "UPDATE usuario SET cedula=:cedula, nombres=:nombres, apellidos=:apellidos, celular=:celular, correo=:correo, direccion=:direccion, id_tipo_documento=:id_tipo_documento, id_perfil=:id_perfil, id_estado=:id_estado, login=:login, password=:password WHERE id_usuario=:id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_usuario', $_GET['id_usuario']);
    $stmt->bindValue(':cedula', $_GET['cedula']);
    $stmt->bindValue(':nombres', $_GET['nombres']);
    $stmt->bindValue(':apellidos', $_GET['apellidos']);
    $stmt->bindValue(':celular', $_GET['celular']);
    $stmt->bindValue(':correo', $_GET['correo']);
    $stmt->bindValue(':direccion', $_GET['direccion']);
    $stmt->bindValue(':id_tipo_documento', $_GET['id_tipo_documento']);
    $stmt->bindValue(':id_perfil', $_GET['id_perfil']);
    $stmt->bindValue(':id_estado', $_GET['id_estado']);
    $stmt->bindValue(':login', $_GET['login']);
    $stmt->bindValue(':password', $_GET['password']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    exit;
}

//Eliminar registro
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $sql = "DELETE FROM contactos WHERE id_usuario=:id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_usuario', $_GET['id_usuario']);
    $stmt->execute();
    header("HTTP/1.1 200 Ok");
    exit;
}

// Si no coincide con ningún método de solicitud, devolver Bad Request
header("HTTP/1.1 400 Bad Request");
