<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión | AutoService Pro</title>

    <style>
        :root {
            --verde-oscuro: #10251a;
            --verde-boton: #163e28;
            --lima: #d5f240;
            --fondo: #f2f5f2;
            --borde: #d7e0d8;
            --error: #a32727;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100dvh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            background: var(--fondo);
            color: #182b20;
            font-family: system-ui, sans-serif;
        }

        .login {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr);
            width: min(100%, 680px);
            overflow: hidden;
            border: 1px solid var(--borde);
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 16px 48px rgb(16 37 26 / 9%);
        }

        .marca {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 32px 24px;
            background: var(--verde-oscuro);
            color: #fff;
            font-weight: 700;
        }

        .marca-icono {
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--lima);
        }

        .marca-pro {
            display: block;
            margin-top: 2px;
            color: var(--lima);
            font-size: 11px;
            letter-spacing: .2em;
        }

        .acceso { padding: clamp(24px, 5vw, 48px); }

        h1 {
            margin: 0 0 28px;
            font-size: clamp(26px, 4vw, 34px);
        }

        .campo { margin-bottom: 20px; }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }

        input, button {
            width: 100%;
            min-height: 46px;
            padding: 10px 12px;
            border-radius: 10px;
            font: inherit;
        }

        input {
            border: 1px solid var(--borde);
            background: var(--fondo);
        }

        input:focus-visible, button:focus-visible {
            outline: 2px solid var(--verde-boton);
            outline-offset: 3px;
        }

        input[aria-invalid="true"] { border-color: var(--error); }

        .error {
            margin: 6px 0 0;
            color: var(--error);
            font-size: 14px;
        }

        button {
            border: 0;
            background: var(--verde-boton);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        button:hover { background: var(--verde-oscuro); }

        @media (max-width: 560px) {
            body { padding: 16px; }
            .login { grid-template-columns: 1fr; }
            .marca { padding: 20px 24px; }
        }
    </style>
</head>
<body>
    <main class="login">
        <header class="marca">
            <span class="marca-icono" aria-hidden="true">🔧</span>
            <span>AutoService <span class="marca-pro">PRO</span></span>
        </header>

        <section class="acceso" aria-labelledby="titulo-login">
            <h1 id="titulo-login">Iniciar sesión</h1>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="campo">
                    <label for="email">Correo electrónico</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        required
                        @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
                    >
                    @error('email')
                        <p id="email-error" class="error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="campo">
                    <label for="password">Contraseña</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        @error('password') aria-invalid="true" aria-describedby="password-error" @enderror
                    >
                    @error('password')
                        <p id="password-error" class="error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit">Entrar</button>
            </form>
        </section>
    </main>
</body>
</html>