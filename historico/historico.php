<?php
require_once '../utils/json_helper.php';

$historico = ler_json('../data/historico.json');
if (!is_array($historico)) {
    $historico = [];
}
$historico = array_reverse($historico);

$temTorneio = file_exists('../data/participantes.json');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico - Super 8</title>
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
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
            margin-bottom: 30px;
            border: 2px solid #eef2f5;
        }

        .table-container h2 {
            font-family: 'Montserrat', sans-serif;
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.3rem;
            border-bottom: 2px solid #eef2f5;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
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
            font-weight: 800;
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
            font-weight: 800;
            font-size: 1.1rem;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #666;
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
            <?php else: ?>
                <a href="../participantes/cadastro.php">Novo Torneio</a>
            <?php endif; ?>
            <a href="../classificacao/classificacao.php">Classificação</a>
            <a href="historico.php">Histórico</a>
        </div>
    </nav>

    <main class="container">
        <h1 class="header-title">Histórico de Torneios</h1>

        <?php if (empty($historico)): ?>
            <div class="table-container empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ccc"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 15px;">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                <h2>Nenhum torneio arquivado</h2>
                <p>Assim que você concluir e zerar um torneio, o resultado final aparecerá aqui.</p>
            </div>
        <?php else: ?>
            <?php foreach ($historico as $torneioAntigo): ?>
                <div class="table-container">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Torneio de <?= $torneioAntigo['data'] ?>
                    </h2>
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
                                <th>Pts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($torneioAntigo['classificacao'] as $index => $jogador): ?>
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
                                    <td class="pts-highlight"><?= $jogador['pontos'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

</body>

</html>