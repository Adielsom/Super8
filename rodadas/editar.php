<?php
require_once '../utils/json_helper.php';

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
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Placares - Super 8</title>
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
            background: var(--secondary);
            color: white;
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
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
            font-size: 1rem;
            font-weight: 800;
            color: var(--primary);
        }

        .scoreboard {
            width: 30%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .score-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 900;
            font-family: 'Montserrat', sans-serif;
            color: var(--primary);
            background: #f4f7f6;
            border: 2px solid #dde5e3;
            border-radius: 10px;
        }

        .score-input:focus {
            outline: none;
            border-color: var(--secondary);
            background: #fff;
        }

        .score-input::-webkit-outer-spin-button,
        .score-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .score-input[type=number] {
            -moz-appearance: textfield;
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
            margin-top: 35px;
            text-transform: uppercase;
        }

        button:hover {
            background-color: #001f30;
        }

        #mensagemEdicao {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            padding: 15px;
            border-radius: 10px;
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
            <a href="rodadas.php">Partidas</a>
            <a href="../classificacao/classificacao.php">Ranking</a>
        </div>
    </nav>

    <main class="container">
        <h1 class="header-title">Editar Placares</h1>

        <form id="formEdicao">
            <?php
            $temPlacar = false;
            foreach ($torneio['rodadas'] as $rIndex => $rodada):
                foreach ($rodada['partidas'] as $pIndex => $partida):
                    if ($partida['placar1'] !== null && $partida['placar2'] !== null):
                        $temPlacar = true;
                        ?>
                        <div class="match-card">
                            <div class="court-badge">Rodada <?= $rodada['numero'] ?> - Quadra <?= $pIndex + 1 ?></div>
                            <div class="match-content">
                                <div class="team team-1">
                                    <span class="player-name"><?= htmlspecialchars($nomes[$partida['dupla1'][0]]) ?> &
                                        <?= htmlspecialchars($nomes[$partida['dupla1'][1]]) ?></span>
                                </div>
                                <div class="scoreboard">
                                    <input type="number" name="r<?= $rIndex ?>_p<?= $pIndex ?>_1" value="<?= $partida['placar1'] ?>"
                                        min="0" required class="score-input">
                                    <span style="font-weight: bold; color: #ccc;">X</span>
                                    <input type="number" name="r<?= $rIndex ?>_p<?= $pIndex ?>_2" value="<?= $partida['placar2'] ?>"
                                        min="0" required class="score-input">
                                </div>
                                <div class="team team-2">
                                    <span class="player-name"><?= htmlspecialchars($nomes[$partida['dupla2'][0]]) ?> &
                                        <?= htmlspecialchars($nomes[$partida['dupla2'][1]]) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php
                    endif;
                endforeach;
            endforeach;

            if (!$temPlacar):
                ?>
                <div style="text-align: center; color: #666; margin-top: 50px;">
                    <h2>Nenhum jogo finalizado ainda.</h2>
                    <p>Volte para a tela de partidas e lance o primeiro placar.</p>
                </div>
            <?php else: ?>
                <button type="submit">Salvar Correções</button>
            <?php endif; ?>
        </form>
        <div id="mensagemEdicao"></div>
    </main>

    <script>
        document.getElementById('formEdicao')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const dados = Object.fromEntries(formData.entries());

            fetch('salvar_edicao.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dados)
            })
                .then(res => res.json())
                .then(data => {
                    const msg = document.getElementById('mensagemEdicao');
                    if (data.sucesso) {
                        msg.style.color = '#28a745';
                        msg.textContent = 'Placares atualizados com sucesso!';
                        setTimeout(() => window.location.href = '../classificacao/classificacao.php', 1500);
                    } else {
                        msg.style.color = '#d62828';
                        msg.textContent = 'Erro: ' + data.erro;
                    }
                });
        });
    </script>
</body>

</html>