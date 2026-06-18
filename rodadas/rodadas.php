<?php
require_once '../utils/json_helper.php';
require_once '../utils/pontuacao.php';

$participantes = ler_json('../data/participantes.json');
$torneio = ler_json('../data/rodadas.json');

if (empty($participantes) || empty($torneio)) {
    header('Location: ../participantes/cadastro.php');
    exit;
}

$nomes = [];
foreach ($participantes as $p) {
    $nomes[$p['id']] = $p['nome'];
}

$classificacao = calcular_classificacao($participantes, $torneio);

$rodadaAtual = null;
$indiceRodada = -1;

foreach ($torneio['rodadas'] as $index => $rodada) {
    $completa = true;
    foreach ($rodada['partidas'] as $partida) {
        if ($partida['placar1'] === null || $partida['placar2'] === null) {
            $completa = false;
            break;
        }
    }
    if (!$completa) {
        $rodadaAtual = $rodada;
        $indiceRodada = $index;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rodadas - Super 8</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Nunito:wght@400;700;900&display=swap');

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
            max-width: 800px;
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

        .match-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            position: relative;
            border: 2px solid #eef2f5;
        }

        .court-badge {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary);
            color: white;
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .match-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .team {
            width: 35%;
            font-family: 'Montserrat', sans-serif;
        }

        .team-1 {
            text-align: right;
        }

        .team-2 {
            text-align: left;
        }

        .player-name {
            display: block;
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--primary);
        }

        .team-divider {
            display: block;
            font-size: 0.8rem;
            color: #999;
            margin: 2px 0;
            font-weight: 600;
        }

        .scoreboard {
            width: 30%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .score-input {
            width: 60px;
            height: 60px;
            text-align: center;
            font-size: 1.8rem;
            font-weight: 900;
            font-family: 'Montserrat', sans-serif;
            color: var(--primary);
            background: #f4f7f6;
            border: 2px solid #dde5e3;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .score-input:focus {
            outline: none;
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(128, 237, 153, 0.2);
        }

        .score-input::-webkit-outer-spin-button,
        .score-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .score-input[type=number] {
            -moz-appearance: textfield;
        }

        .vs-badge {
            font-size: 1rem;
            font-weight: 800;
            color: #ccc;
        }

        button {
            display: block;
            width: 100%;
            padding: 18px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 15px;
            font-size: 1.2rem;
            font-weight: 800;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 35px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 10px 20px rgba(0, 48, 73, 0.2);
        }

        button:hover {
            background-color: #001f30;
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(0, 48, 73, 0.3);
        }

        .btn-finish {
            background-color: var(--accent);
            color: var(--primary);
            box-shadow: 0 10px 20px rgba(128, 237, 153, 0.3);
        }

        .btn-finish:hover {
            background-color: var(--accent-hover);
            box-shadow: 0 12px 25px rgba(128, 237, 153, 0.4);
        }

        .btn-edit {
            background-color: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            box-shadow: none;
            margin-top: 15px;
            padding: 15px;
            font-size: 1rem;
        }

        .btn-edit:hover {
            background-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 5px 15px rgba(0, 48, 73, 0.2);
        }

        #mensagemRodada {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            padding: 15px;
            border-radius: 10px;
            font-size: 1.1rem;
        }

        .table-container {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
            margin-top: 40px;
            border: 2px solid #eef2f5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th,
        td {
            padding: 12px 10px;
            text-align: center;
            border-bottom: 1px solid #eef2f5;
        }

        th {
            background-color: transparent;
            color: #666;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }

        td {
            font-size: 0.95rem;
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
            font-size: 1.1rem;
            font-weight: 800;
        }

        .pos-2 {
            color: #c0c0c0;
            font-size: 1rem;
            font-weight: 800;
        }

        .pos-3 {
            color: #cd7f32;
            font-size: 1rem;
            font-weight: 800;
        }

        .pts-highlight {
            color: var(--primary);
            font-weight: 800;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .score-input {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .player-name {
                font-size: 0.95rem;
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
            <a href="rodadas.php">Partidas</a>
            <a href="../classificacao/classificacao.php">Ranking</a>
            <a href="../historico/historico.php">Histórico</a>
        </div>
    </nav>

    <main class="container">
        <?php if ($rodadaAtual): ?>
            <h1 class="header-title">Rodada <?= $rodadaAtual['numero'] ?> <span style="color: #999; font-size: 1.5rem;">de
                    7</span></h1>

            <form id="formPlacares">
                <input type="hidden" name="indice_rodada" value="<?= $indiceRodada ?>">

                <?php foreach ($rodadaAtual['partidas'] as $pIndex => $partida): ?>
                    <div class="match-card">
                        <div class="court-badge">Quadra <?= $pIndex + 1 ?></div>

                        <div class="match-content">
                            <div class="team team-1">
                                <span class="player-name"><?= htmlspecialchars($nomes[$partida['dupla1'][0]]) ?></span>
                                <span class="team-divider">&</span>
                                <span class="player-name"><?= htmlspecialchars($nomes[$partida['dupla1'][1]]) ?></span>
                            </div>

                            <div class="scoreboard">
                                <input type="number" name="p<?= $pIndex ?>_1" min="0" required class="score-input">
                                <span class="vs-badge">X</span>
                                <input type="number" name="p<?= $pIndex ?>_2" min="0" required class="score-input">
                            </div>

                            <div class="team team-2">
                                <span class="player-name"><?= htmlspecialchars($nomes[$partida['dupla2'][0]]) ?></span>
                                <span class="team-divider">&</span>
                                <span class="player-name"><?= htmlspecialchars($nomes[$partida['dupla2'][1]]) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <button type="submit">Salvar Placares da Rodada</button>
            </form>
            <button type="button" class="btn-edit" onclick="window.location.href='editar.php'">Corrigir Placares
                Antigos</button>
        <?php else: ?>
            <div style="text-align: center; padding: 40px 0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                    stroke="#57cc99" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    style="margin-bottom: 20px;">
                    <circle cx="12" cy="8" r="7"></circle>
                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                </svg>
                <h1 class="header-title">Torneio Finalizado!</h1>
                <p style="font-size: 1.2rem; color: #555; margin-bottom: 30px;">Todas as 7 rodadas foram concluídas.</p>
                <button class="btn-finish" onclick="window.location.href='../classificacao/classificacao.php'">Ver Ranking
                    Final</button>
                <button type="button" class="btn-edit" onclick="window.location.href='editar.php'">Corrigir Placares
                    Antigos</button>
            </div>
        <?php endif; ?>
        <div id="mensagemRodada"></div>

        <?php if (!empty($classificacao)): ?>
            <h2 class="header-title" style="font-size: 1.6rem; margin-top: 50px;">Ranking em Tempo Real</h2>
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
                                <td style="text-align: left; font-weight: 800; color: var(--primary);"><?= $jogador['nome'] ?>
                                </td>
                                <td><?= $jogador['jogos'] ?></td>
                                <td><?= $jogador['vitorias'] ?></td>
                                <td><?= $jogador['derrotas'] ?></td>
                                <td><?= $jogador['games_vencidos'] ?></td>
                                <td><?= $jogador['games_perdidos'] ?></td>
                                <td><?= $jogador['games_vencidos'] - $jogador['games_perdidos'] ?></td>
                                <td class="pts-highlight"><?= $jogador['pontos'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>

    <script src="../js/ui.js?v=<?= time() ?>"></script>
</body>

</html>