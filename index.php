<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema ABM - Escuela PRoA</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; padding: 0;
            display: flex; justify-content: center; align-items: center;
            min-height: 100vh; background-color: #e1f5fe;
        }
        .menu-container { 
            padding: 35px 30px; border: 2px solid #81d4fa; 
            border-radius: 12px; background-color: #ffffff; 
            box-shadow: 0 4px 15px rgba(0, 119, 182, 0.15);
            text-align: center; max-width: 380px; width: 90%;
        }
        .logo-escuela { width: 140px; height: auto; margin-bottom: 15px; border-radius: 50%; }
        h1 { color: #0277bd; font-size: 22px; margin: 0 0 10px 0; }
        p { color: #555; font-size: 14px; margin-bottom: 25px; }
        .btn-menu { 
            display: block; padding: 12px 20px; margin: 12px auto; 
            text-decoration: none; color: white; font-weight: bold; 
            border-radius: 6px; transition: all 0.3s ease;
        }
        .btn-alta { background-color: #29b6f6; }
        .btn-alta:hover { background-color: #0288d1; }

        .btn-vista { background-color: #0277bd; }
        .btn-vista:hover { background-color: #01579b; }

        .btn-dashboard { background-color: #0097a7; }
        .btn-dashboard:hover { background-color: #006064; }
    </style>
</head>
<body>

    <div class="menu-container">
        <img src="logo proa.jfif" alt="Logo Escuela PRoA" class="logo-escuela">
        <h1>Sistema de Gestión Escolar</h1>
        <p>Escuela PRoA — Selecciona una opción:</p>
        
        <a href="insertar.php" class="btn-menu btn-alta">Alta (Insertar Usuario)</a>
        <a href="mostrar.php" class="btn-menu btn-vista">Mostrar (Baja / Modificar)</a>
        <a href="dashboard.php" class="btn-menu btn-dashboard">📊 Dashboard de la Huerta</a>
    </div>

</body>
</html>