<?php
if (isset($_POST['btn_alta'])) {$servername = "localhost";
    $username = "root"; 
    $password = ""; 
    $dbname = "sistema_usuarios"; 

    $conn = new mysqli($servername,$username, $password,$dbname, 3307);
    
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $usuario =$_POST['usuario'];
    $contrasena =$_POST['contrasena'];

    // Hashear la contraseña por seguridad
    $password_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    $stmt =$conn->prepare("INSERT INTO usuarios (nombre_usuario, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $usuario,$password_hash);

    try {
        if ($stmt->execute()) {
            echo "<script>alert('Usuario registrado con éxito.'); window.location='mostrar.php';</script>";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo "<script>alert('El nombre de usuario \"$usuario\" ya existe.');</script>";
        } else {
            echo "<script>alert('Error al registrar: " . addslashes($e->getMessage()) . "');</script>";
        }
    }

    $stmt->close();$conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Usuario - Escuela PRoA</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; padding: 30px 20px;
            background-color: #f0f4f8;
            background-image: url('logo proa.jfif');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 350px;
            position: relative; min-height: 100vh;
            box-sizing: border-box;
            display: flex; flex-direction: column; align-items: center;
        }
        body::before {
            content: ""; position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(240, 244, 248, 0.88); z-index: 1;
        }
        .container { position: relative; z-index: 2; width: 100%; max-width: 400px; }
        h2 { color: #0277bd; text-align: center; }
        form { 
            padding: 25px; border: 1px solid #b3e5fc; border-radius: 8px; 
            background-color: #ffffff; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        label { color: #0277bd; font-weight: bold; font-size: 13px; }
        input[type="text"], input[type="password"] { 
            display: block; width: 100%; margin: 5px 0 12px 0; padding: 8px; 
            box-sizing: border-box; border: 1px solid #81d4fa; border-radius: 4px; outline: none;
        }
        input[type="submit"] { 
            width: 100%; background-color: #29b6f6; color: white; border: none; 
            padding: 10px; font-weight: bold; border-radius: 4px; cursor: pointer; margin-top: 10px;
        }
        input[type="submit"]:hover { background-color: #0288d1; }
        .volver { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #0277bd; font-weight: bold; }
        .volver:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Alta de Nuevo Usuario</h2>
        <form action="" method="POST">
            <label>Nombre de Usuario:</label>
            <input type="text" name="usuario" required placeholder="Ej: jperez">

            <label>Contraseña:</label>
            <input type="password" name="contrasena" required placeholder="Contraseña">

            <input type="submit" name="btn_alta" value="Guardar Usuario">
        </form>
        <a class="volver" href="index.php">← Volver al Inicio</a>
    </div>
</body>
</html>