<?php
require_once '../utils/json_helper.php';
require_once '../utils/pontuacao.php';

$participantes = ler_json('../data/participantes.json');
$torneio = ler_json('../data/rodadas.json');

if (empty($participantes)) {
    header('Location: ../participantes/cadastro.php');
    exit;
}

$classificacao = calcular_classificacao($participantes, $torneio);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classificação - Super 8</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Nunito:wght@400;700;900&display=swap');

        :root {
            --primary: #003049;
            --secondary: #d62828;
            --accent: #80ed99;
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
            max-width: 900px;
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

        .table-container {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
            border: 2px solid #eef2f5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th,
        td {
            padding: 15px 10px;
            text-align: center;
            border-bottom: 1px solid #eef2f5;
        }

        th {
            background-color: transparent;
            color: #666;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }

        td {
            font-size: 1rem;
            font-weight: 600;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: background-color 0.2s;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        .pos-1 {
            color: #d4af37;
            font-size: 1.2rem;
            font-weight: 900;
        }

        .pos-2 {
            color: #c0c0c0;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .pos-3 {
            color: #cd7f32;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .pts-highlight {
            color: var(--primary);
            font-weight: 900;
            font-size: 1.2rem;
            background: rgba(0, 48, 73, 0.05);
            border-radius: 8px;
        }

        button.btn-print {
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 30px auto 0;
            padding: 15px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 800;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            box-shadow: 0 10px 20px rgba(0, 48, 73, 0.2);
        }

        button.btn-print:hover {
            background-color: #001f30;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            th,
            td {
                padding: 10px 5px;
                font-size: 0.9rem;
            }

            .pos-1,
            .pos-2,
            .pos-3,
            .pts-highlight {
                font-size: 1rem;
            }
        }

        @media print {

            .navbar,
            button.btn-print {
                display: none !important;
            }

            body {
                background: white;
                color: black;
            }

            .table-container {
                box-shadow: none;
                border: 1px solid #ccc;
            }

            .header-title {
                color: black;
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
            <a href="../rodadas/rodadas.php">Partidas</a>
            <a href="classificacao.php">Classificação</a>
            <a href="../historico/historico.php">Histórico</a>
        </div>
    </nav>

    <main class="container">
        <h1 class="header-title">Classificação Geral</h1>

        <?php if (!empty($classificacao)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th style="text-align: left;">Jogador</th>
                            <th>J</th>
                            <th>V</th>
                            <th>D</th>
                            <th>GV</th>
                            <th>GP</th>
                            <th>SG</th>
                            <th>Pts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classificacao as $index => $jogador): ?>
                            <tr>
                                <td
                                    class="<?= $index == 0 ? 'pos-1' : ($index == 1 ? 'pos-2' : ($index == 2 ? 'pos-3' : '')) ?>">
                                    <?= $index + 1 ?>º
                                </td>
                                <td style="text-align: left; font-weight: 800; color: var(--primary);">
                                    <?= htmlspecialchars($jogador['nome']) ?></td>
                                <td><?= $jogador['jogos'] ?></td>
                                <td><?= $jogador['vitorias'] ?></td>
                                <td><?= $jogador['derrotas'] ?></td>
                                <td><?= $jogador['games_vencidos'] ?></td>
                                <td><?= $jogador['games_perdidos'] ?></td>
                                <td style="font-weight: 800;"><?= $jogador['games_vencidos'] - $jogador['games_perdidos'] ?>
                                </td>
                                <td class="pts-highlight"><?= $jogador['pontos'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn-print" onclick="window.print()">Imprimir Ranking (PDF)</button>
        <?php else: ?>
            <div style="text-align: center; color: #666; margin-top: 50px;">
                <h2>Nenhum torneio em andamento.</h2>
                <p>Volte ao início e cadastre os participantes.</p>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>