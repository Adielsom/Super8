<?php
$temTorneio = file_exists('../data/participantes.json');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuração - Super 8</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Nunito:wght@400;700&display=swap');

        :root {
            --primary: #003049;
            --secondary: #d62828;
            --accent: #80ed99;
            --accent-hover: #57cc99;
            --bg: #fdfcdc;
            --text-dark: #001219;
            --card-bg: rgba(255, 255, 255, 0.95);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--bg);
            background-image: radial-gradient(circle at top right, #e0fbfc 0%, #fdfcdc 100%);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: var(--primary);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand {
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-brand span {
            color: var(--accent);
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--accent);
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            padding: 0 20px 60px;
            width: 100%;
            flex: 1;
        }

        .header-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 25px;
            text-align: center;
            text-transform: uppercase;
        }

        .form-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 35px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .radio-group {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 2px solid #eef2f5;
            border-radius: 12px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
            background: #fff;
        }

        .radio-group:hover {
            border-color: var(--accent);
            background: #f0fff4;
        }

        .radio-group input[type="radio"] {
            margin-right: 15px;
            transform: scale(1.5);
            cursor: pointer;
        }

        .radio-label {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--primary);
        }

        .radio-desc {
            display: block;
            font-size: 0.9rem;
            color: #666;
            margin-top: 5px;
            font-weight: 600;
        }

        button {
            display: block;
            width: 100%;
            padding: 16px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 800;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            background-color: #001f30;
        }

        #mensagemConfig {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            padding: 15px;
            border-radius: 10px;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="../index.php" class="nav-brand">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 22c0-4.4-3.6-8-8-8"></path>
                <path d="M12 2c0 4.4 3.6 8 8 8"></path>
            </svg>
            Super<span>8</span>
        </a>
        <div class="nav-links">
            <a href="../index.php">Início</a>
            <?php if ($temTorneio): ?>
                <a href="../rodadas/rodadas.php">Partidas</a>
            <?php endif; ?>
            <a href="../classificacao/classificacao.php">Ranking</a>
            <a href="../historico/historico.php">Histórico</a>
        </div>
    </nav>

    <main class="container">
        <h1 class="header-title">Formato de Jogo</h1>

        <div class="form-card">
            <form id="formConfiguracao">
                <label class="radio-group">
                    <input type="radio" name="formato" value="rotativas" checked>
                    <div>
                        <span class="radio-label">Opção A — Duplas Rotativas</span>
                        <span class="radio-desc">Sorteio inteligente. Todos jogam com e contra todos nas 7
                            rodadas.</span>
                    </div>
                </label>

                <label class="radio-group">
                    <input type="radio" name="formato" value="fixas">
                    <div>
                        <span class="radio-label">Opção B — Duplas Fixas</span>
                        <span class="radio-desc">As 4 duplas são formadas no início e se enfrentam diretamente.</span>
                    </div>
                </label>

                <button type="submit">Gerar Tabela de Jogos</button>
            </form>
            <div id="mensagemConfig"></div>
        </div>
    </main>

    <script src="../js/ui.js?v=<?= time() ?>"></script>
</body>

</html>