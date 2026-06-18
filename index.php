<?php
date_default_timezone_set('America/Fortaleza');
require_once 'utils/json_helper.php';
require_once 'utils/pontuacao.php';

if (isset($_GET['zerar']) && $_GET['zerar'] == '1') {
    $participantes = ler_json('data/participantes.json');
    $torneio = ler_json('data/rodadas.json');

    if (!empty($participantes) && !empty($torneio)) {
        $classificacao = calcular_classificacao($participantes, $torneio);
        $historico = ler_json('data/historico.json');

        if (!is_array($historico)) {
            $historico = [];
        }

        $historico[] = [
            'id' => time(),
            'data' => date('d/m/Y \à\s H:i'),
            'classificacao' => $classificacao
        ];

        gravar_json('data/historico.json', $historico);
    }

    if (file_exists('data/participantes.json')) {
        unlink('data/participantes.json');
    }
    if (file_exists('data/rodadas.json')) {
        unlink('data/rodadas.json');
    }
    header('Location: index.php');
    exit;
}

$temTorneio = file_exists('data/participantes.json');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super 8 - Início</title>
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

        .hero {
            padding: 60px 20px;
            text-align: center;
        }

        .hero h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        .hero p {
            font-size: 1.2rem;
            color: #555;
            max-width: 600px;
            margin: 0 auto;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px 60px;
            width: 100%;
            flex: 1;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .card-btn {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px 25px;
            text-align: center;
            text-decoration: none;
            color: var(--text-dark);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            border: 2px solid transparent;
        }

        .card-btn:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .card-btn.start {
            border-color: var(--accent);
            background: linear-gradient(145deg, #ffffff, #f0fff4);
        }

        .card-btn.start:hover {
            background: var(--accent);
        }

        .card-btn.rank:hover {
            border-color: var(--primary);
            background: #e0fbfc;
        }

        .card-btn.history:hover {
            border-color: #f77f00;
            background: #fff8f0;
        }

        .card-btn.danger {
            background: #fff0f0;
        }

        .card-btn.danger:hover {
            background: var(--secondary);
            color: #fff;
        }

        .card-btn.danger:hover .btn-desc {
            color: #ffd0d0;
        }

        .card-btn.danger:hover .icon-svg {
            stroke: #fff;
        }

        .icon-svg {
            width: 48px;
            height: 48px;
            stroke: var(--primary);
            margin-bottom: 10px;
        }

        .btn-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
        }

        .btn-desc {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.4;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.2rem;
            }

            .nav-links {
                display: none;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <a href="index.php" class="nav-brand">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 22c0-4.4-3.6-8-8-8"></path>
                <path d="M12 2c0 4.4 3.6 8 8 8"></path>
            </svg>
            Super<span>8</span>
        </a>
        <div class="nav-links">
            <a href="index.php">Início</a>
            <?php if ($temTorneio): ?>
                <a href="rodadas/rodadas.php">Partidas</a>
            <?php else: ?>
                <a href="participantes/cadastro.php">Novo Torneio</a>
            <?php endif; ?>
            <a href="classificacao/classificacao.php">Ranking</a>
            <a href="historico/historico.php">Histórico</a>
        </div>
    </nav>

    <header class="hero">
        <h1>Circuito Beach Tennis</h1>
        <p>Gerencie chaves, lance placares na areia e acompanhe o ranking em tempo real com o formato Super 8.</p>
    </header>

    <main class="container">
        <div class="grid">

            <?php if ($temTorneio): ?>
                <a href="rodadas/rodadas.php" class="card-btn start">
                    <svg class="icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polygon points="10 8 16 12 10 16 10 8"></polygon>
                    </svg>
                    <span class="btn-title">Continuar Torneio</span>
                    <span class="btn-desc">O torneio está em andamento. Clique para lançar os próximos placares.</span>
                </a>
            <?php else: ?>
                <a href="participantes/cadastro.php" class="card-btn start">
                    <svg class="icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                    <span class="btn-title">Novo Torneio</span>
                    <span class="btn-desc">Cadastre os 8 jogadores, defina o formato e gere a tabela de jogos.</span>
                </a>
            <?php endif; ?>

            <a href="classificacao/classificacao.php" class="card-btn rank">
                <svg class="icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                    <path d="M4 22h16"></path>
                    <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                    <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                    <path d="M18 2H6v7c0 6 3 7 6 7s6-1 6-7V2z"></path>
                </svg>
                <span class="btn-title">Classificação</span>
                <span class="btn-desc">Veja o ranking atualizado, pontos acumulados e o saldo de games.</span>
            </a>

            <a href="historico/historico.php" class="card-btn history">
                <svg class="icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <polyline points="12 14 12 18 16 18"></polyline>
                </svg>
                <span class="btn-title">Histórico</span>
                <span class="btn-desc">Consulte os resultados e os campeões dos torneios anteriores.</span>
            </a>

            <?php if ($temTorneio): ?>
                <a href="index.php?zerar=1"
                    onclick="return confirm('ATENÇÃO: Este torneio será arquivado e um novo será iniciado. Deseja continuar?')"
                    class="card-btn danger">
                    <svg class="icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    <span class="btn-title">Arquivar & Zerar</span>
                    <span class="btn-desc">Salva o ranking atual no histórico e limpa os dados para um novo evento.</span>
                </a>
            <?php endif; ?>

        </div>
    </main>

</body>

</html>