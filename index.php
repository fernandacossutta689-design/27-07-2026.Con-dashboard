<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Menú Principal - ProA</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #ffd9e9, #fff1f7);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
            color: #555;
        }

        .contenedor {
            width: 100%;
            max-width: 1150px;
            min-height: 650px;

            background: white;

            border: 3px solid #f5b6d1;
            border-radius: 30px;

            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);

            padding: 35px;

            display: grid;
            grid-template-columns: 0.9fr 1.4fr;
            gap: 35px;
        }

        /* ==========================
           PANEL IZQUIERDO
        ========================== */

        .bienvenida {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            text-align: center;

            padding: 25px;
        }

        .logo {
            width: 125px;
            height: 125px;

            object-fit: contain;

            border-radius: 20px;

            margin-bottom: 20px;
        }

        .bienvenida h1 {
            color: #d63384;

            font-size: 38px;

            margin-bottom: 8px;
        }

        .bienvenida h2 {
            color: #777;

            font-size: 18px;

            font-weight: normal;

            margin-bottom: 35px;
        }

        .texto {
            color: #999;

            font-size: 14px;

            line-height: 1.6;

            max-width: 320px;

            margin-top: 22px;
        }

        /* ==========================
           BOTÓN ADMINISTRACIÓN
        ========================== */

        .boton-admin {
            width: 100%;
            max-width: 330px;

            padding: 17px;

            background: #ff69b4;

            color: white;

            text-decoration: none;

            border-radius: 15px;

            font-size: 18px;

            font-weight: bold;

            transition: all 0.3s ease;
        }

        .boton-admin:hover {
            background: #e94d9c;

            transform: translateY(-3px);

            box-shadow: 0 8px 18px rgba(255, 105, 180, 0.35);
        }

        /* ==========================
           PANEL DASHBOARD
        ========================== */

        .dashboard {
            background: #fff7fb;

            border: 2px solid #f5c4d9;

            border-radius: 25px;

            padding: 25px;

            display: flex;

            flex-direction: column;
        }

        .dashboard h2 {
            color: #d63384;

            font-size: 26px;

            margin-bottom: 5px;
        }

        .dashboard p {
            color: #999;

            font-size: 14px;

            margin-bottom: 20px;
        }

        /* ==========================
           PREVIEW
        ========================== */

        .preview {
            flex: 1;

            min-height: 390px;

            background: white;

            border: 2px solid #f5c4d9;

            border-radius: 18px;

            overflow: hidden;

            position: relative;
        }

        .preview iframe {
            width: 100%;
            height: 100%;

            min-height: 390px;

            border: none;
        }

        /*
        Evita que el usuario interactúe
        con el iframe y permite que todo
        el preview funcione como botón.
        */

        .preview-link {
            position: absolute;

            inset: 0;

            z-index: 5;

            cursor: pointer;
        }

        /* ==========================
           BOTÓN DASHBOARD
        ========================== */

        .boton-dashboard {
            display: block;

            text-align: center;

            margin-top: 18px;

            padding: 15px;

            background: #d63384;

            color: white;

            text-decoration: none;

            border-radius: 15px;

            font-size: 17px;

            font-weight: bold;

            transition: all 0.3s ease;
        }

        .boton-dashboard:hover {
            background: #b82c70;

            transform: translateY(-2px);

            box-shadow: 0 7px 18px rgba(214, 51, 132, 0.3);
        }

        /* ==========================
           RESPONSIVE
        ========================== */

        @media (max-width: 850px) {

            body {
                padding: 15px;
            }

            .contenedor {
                grid-template-columns: 1fr;

                padding: 22px;

                min-height: auto;
            }

            .bienvenida {
                padding: 15px;
            }

            .bienvenida h1 {
                font-size: 32px;
            }

            .dashboard {
                min-height: 520px;
            }

            .preview {
                min-height: 350px;
            }

            .preview iframe {
                min-height: 350px;
            }
        }

    </style>
</head>

<body>

    <main class="contenedor">

        <!-- ==========================
             BIENVENIDA
        ========================== -->

        <section class="bienvenida">

            <img
                src="logo proa.jfif"
                alt="Logo ProA"
                class="logo"
            >

            <h1>Menú Principal</h1>

            <h2>Escuela Experimental ProA</h2>

            <a
                href="administracion.php"
                class="boton-admin"
            >
                ⚙️ Administración
            </a>

            <p class="texto">
                Desde este menú podés acceder al
                sistema de administración o consultar
                las estadísticas y datos del sistema.
            </p>

        </section>


        <!-- ==========================
             DASHBOARD
        ========================== -->

        <section class="dashboard">

            <h2>📊 Dashboard</h2>

            <p>
                Vista previa del panel de estadísticas
            </p>

            <div class="preview">

                <iframe
                    src="dashboard.php"
                    title="Vista previa del Dashboard">
                </iframe>

                <a
                    href="dashboard.php"
                    class="preview-link"
                    aria-label="Abrir Dashboard">
                </a>

            </div>

            <a
                href="dashboard.php"
                class="boton-dashboard"
            >
                📊 Abrir Dashboard
            </a>

        </section>

    </main>

</body>

</html>
