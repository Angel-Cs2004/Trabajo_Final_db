<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>

  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  />

  <style>
    :root{
      --green-1:#63d14b;
      --green-2:#0ea85c;
      --green-3:#0b7a55;
      --bg-right:#f5f6f8;
      --border:#e5e7eb;
    }

    /* FULL SCREEN real */
    html, body{
      height: 100%;
      width: 100%;
      margin: 0;
    }

    body{
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      background: #eef0f2;
      overflow: hidden; /* evita scroll raro */
    }

    /* Contenedor principal ocupa toda la pestaña */
    .auth-screen{
      height: 100vh;
      width: 100vw;
      display: flex;
    }

    /* Card principal FULL */
    .auth-card{
      flex: 1;
      display: flex;
      border-radius: 0;     /* sin bordes redondeados, full screen */
      box-shadow: none;     /* sin sombra */
      overflow: hidden;
    }

    /* Paneles */
    .left-panel{
      flex: 1;
      min-height: 100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding: 60px 36px;

      background: linear-gradient(180deg, var(--green-1) 0%, var(--green-2) 55%, var(--green-3) 100%);
      color: #fff;
    }

    .right-panel{
      flex: 1;
      min-height: 100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding: 60px 36px;
      background: var(--bg-right);
    }

    /* Contenido izquierda */
    .brand{
      text-align:center;
      max-width: 520px;
    }

    .brand .icon{
      width: 66px;
      height: 66px;
      margin: 0 auto 22px auto;
      display:grid;
      place-items:center;
      border-radius: 18px;
      background: rgba(255,255,255,.14);
      backdrop-filter: blur(3px);
    }

    .brand small{
      display:block;
      font-weight: 600;
      opacity: .95;
      margin-bottom: 14px;
      line-height: 1.3;
    }

    .brand .title{
      margin: 0;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .45em;
      font-size: clamp(26px, 3.5vw, 40px);
      text-shadow: 0 2px 12px rgba(0,0,0,.18);
    }

    /* Caja del formulario derecha */
    .form-box{
      width: min(560px, 92%);
    }

    .form-title{
      text-align:center;
      font-weight: 900;
      color: #16a34a;
      margin-bottom: 34px;
      line-height: 1.12;
      font-size: clamp(26px, 2.2vw, 36px);
    }

    .form-label{
      font-weight: 700;
      margin-bottom: 10px;
      color: #111827;
    }

    .soft-input{
      border: 1px solid var(--border);
      background: #fff;
      border-radius: 10px;
      padding: 14px 14px;
      box-shadow: 0 1px 0 rgba(0,0,0,.02);
    }

    .soft-input:focus{
      border-color: rgba(34,197,94,.65);
      box-shadow: 0 0 0 .25rem rgba(34,197,94,.18);
    }

    .btn-green{
      background: #22c55e;
      border: none;
      padding: 12px 46px;
      border-radius: 10px;
      font-weight: 800;
      box-shadow: 0 14px 26px rgba(34,197,94,.25);
      transition: transform .08s ease, filter .12s ease;
    }
    .btn-green:hover{
      filter: brightness(.95);
    }
    .btn-green:active{
      transform: translateY(1px);
    }

    /* Para pantallas chicas: se apila y no se corta */
    @media (max-width: 992px){
      body{ overflow: auto; }
      .auth-screen{ height: auto; min-height: 100vh; }
      .auth-card{ flex-direction: column; }
      .left-panel, .right-panel{ min-height: auto; padding: 44px 22px; }
      .brand .title{ letter-spacing: .28em; }
    }
  </style>
</head>

<body>
  <div class="auth-screen">
    <div class="auth-card">

      <!-- IZQUIERDA -->
      <section class="left-panel">
        <div class="brand">
          <div class="icon" aria-hidden="true">
            <!-- Icono simple (cámbialo por tu logo si quieres) -->
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
              <path d="M3 12c3-5 8-8 18-8-3 2-4 3-5 4 1 1 2 2 5 4-10 0-15-3-18-8Z"
                    stroke="white" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M8 12h.01" stroke="white" stroke-width="3" stroke-linecap="round"/>
              <path d="M4 16c3-1 6-1 9 0M4 18c3-1 6-1 9 0"
                    stroke="white" stroke-opacity=".9" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
          </div>

          <small>Asociación de Vendedoras de Comida San Miguel de</small>
          <h2 class="title">YAHUARCOCHA</h2>
        </div>
      </section>

      <!-- DERECHA -->
      <section class="right-panel">
        <div class="form-box">
          <h2 class="form-title">Administración de<br/>Negocios y productos</h2>

          <?php if (!empty($mensajeError)): ?>
            <div class="alert alert-danger mb-4">
              <?php echo htmlspecialchars($mensajeError); ?>
            </div>
          <?php endif; ?>

          <form action="" method="POST" autocomplete="off">
            <div class="mb-4">
              <label for="correo" class="form-label">Correo</label>
              <input
                type="text"
                name="correo"
                id="correo"
                class="form-control soft-input"
                required
              />
            </div>

            <div class="mb-4">
              <label for="password" class="form-label">Contraseña</label>
              <input
                type="password"
                name="password"
                id="password"
                class="form-control soft-input"
                required
              />
            </div>

            <div class="d-flex justify-content-center mt-4">
              <button type="submit" class="btn btn-green">Ingresar</button>
            </div>
          </form>
        </div>
      </section>

    </div>
  </div>

  <!-- Bootstrap JS -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
  ></script>
</body>
</html>
