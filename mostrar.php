<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "sistema_usuarios"; 

$conn = new mysqli($servername,$username, $password,$dbname, 3307);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// ==========================================
// ACCIÓN: BAJA (ELIMINAR)
// ==========================================
if (isset($_GET['eliminar'])) {
    $id_eliminar = (int)$_GET['eliminar'];
    
    $stmt_del =$conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt_del->bind_param("i", $id_eliminar);
    $stmt_del->execute();$stmt_del->close();
    
    echo "<script>alert('Usuario eliminado correctamente.'); window.location='mostrar.php';</script>";
}

// ==========================================
// ACCIÓN: MODIFICACIÓN
// ==========================================
if (isset($_POST['btn_modificar'])) {
    $id_update =$_POST['id'];
    $usuario_update =$_POST['nombre_usuario'];
    $contrasena_update =$_POST['contrasena'];

    // Si ingresó una contraseña nueva, la hasheamos; si no, dejamos la lógica que prefieras.
    $password_hash = password_hash($contrasena_update, PASSWORD_DEFAULT);

    $stmt_up =$conn->prepare("UPDATE usuarios SET nombre_usuario = ?, password_hash = ? WHERE id = ?");
    $stmt_up->bind_param("ssi", $usuario_update, $password_hash,$id_update);

    if ($stmt_up->execute()) {
        echo "<script>alert('Registro actualizado correctamente.'); window.location='mostrar.php';</script>";
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
    $stmt_up->close();
}

// Cargar datos para editar
$row_edit = null;
if (isset($_GET['editar'])) {
    $id_editar = (int)$_GET['editar'];
    $stmt_edit =$conn->prepare("SELECT id, nombre_usuario FROM usuarios WHERE id = ?");
    $stmt_edit->bind_param("i", $id_editar);$stmt_edit->execute();
    $res =$stmt_edit->get_result();
    if ($res &&$res->num_rows > 0) {
        $row_edit =$res->fetch_assoc();
    }
    $stmt_edit->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mostrar Registros - Escuela PRoA</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; padding: 30px 20px;
            background-color: #f0f4f8;
            background-image: url('logo proa.jfif');
            background-repeat: no-repeat; background-position: center; background-size: 400px;
            position: relative; min-height: 100vh; box-sizing: border-box;
        }
        body::before {
            content: ""; position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(240, 244, 248, 0.88); z-index: 1;
        }
        .main-content { position: relative; z-index: 2; max-width: 800px; margin: 0 auto; }
        h2 { color: #0277bd; text-align: center; }
        table { 
            width: 100%; border-collapse: collapse; margin-top: 20px; 
            background: #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            border-radius: 8px; overflow: hidden;
        }
        th, td { padding: 12px 15px; text-align: left; }
        th { background-color: #0277bd; color: white; font-weight: 600; }
        tr:nth-child(even) { background-color: #e1f5fe; }
        tr:hover { background-color: #b3e5fc; }
        .btn { 
            padding: 6px 12px; text-decoration: none; color: white; 
            border-radius: 4px; font-size: 13px; font-weight: bold; display: inline-block;
        }
        .btn-edit { background-color: #29b6f6; }
        .btn-edit:hover { background-color: #0288d1; }
        .btn-delete { background-color: #e53935; }
        .btn-delete:hover { background-color: #c62828; }
        .form-edit { 
            background: #ffffff; padding: 20px; border: 2px solid #29b6f6; 
            border-radius: 8px; max-width: 400px; margin: 0 auto 25px auto; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .form-edit h3 { margin-top: 0; color: #0277bd; }
        .form-edit input[type="text"], .form-edit input[type="password"] {
            width: 100%; padding: 8px; margin: 4px 0 12px 0;
            border: 1px solid #81d4fa; border-radius: 4px; box-sizing: border-box;
        }
        .form-edit input[type="submit"] {
            background-color: #0277bd; color: white; border: none;
            padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold;
        }
        .form-edit a { color: #e53935; margin-left: 10px; text-decoration: none; font-size: 14px; }
        .volver { display: inline-block; margin-top: 20px; text-decoration: none; color: #0277bd; font-weight: bold; }
        .volver:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="main-content">

    <?php if ($row_edit): ?>
        <div class="form-edit">
            <h3>Modificar Usuario (ID: <?php echo $row_edit['id']; ?>)</h3>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $row_edit['id']; ?>">
                
                <label>Nombre de Usuario:</label>
                <input type="text" name="nombre_usuario" value="<?php echo htmlspecialchars($row_edit['nombre_usuario']); ?>" required>
                
                <label>Nueva Contraseña:</label>
                <input type="password" name="contrasena" placeholder="Escribe la nueva contraseña" required>
                
                <input type="submit" name="btn_modificar" value="Guardar Cambios">
                <a href="mostrar.php">Cancelar</a>
            </form>
        </div>
    <?php endif; ?>

    <h2>Listado de Usuarios Registrados</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Hash Contraseña</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, nombre_usuario, password_hash FROM usuarios";
            $resultado = $conn->query($sql);

            if ($resultado &&$resultado->num_rows > 0) {
                while($fila =$resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila["id"] . "</td>";
                    echo "<td>" . htmlspecialchars($fila["nombre_usuario"]) . "</td>";
                    // Se recorta visualmente el hash para no romper el diseño de la tabla
                    echo "<td>" . substr($fila["password_hash"], 0, 20) . "...</td>";
                    echo "<td>
                            <a class='btn btn-edit' href='?editar=" . $fila["id"] . "'>Modificar</a> 
                            <a class='btn btn-delete' href='?eliminar=" . $fila["id"] . "' onclick='return confirm(\"¿Seguro que deseas eliminar este usuario?\")'>Baja</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No hay usuarios registrados</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>

    <a class="volver" href="index.php">← Volver al Inicio</a>

</div>

</body>
</html>