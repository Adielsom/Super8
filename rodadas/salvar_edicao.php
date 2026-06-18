<?php
require_once '../utils/json_helper.php';

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$dados = json_decode($input, true);

if (!$dados) {
    echo json_encode(['sucesso' => false, 'erro' => 'Dados inválidos.']);
    exit;
}

$torneio = ler_json('../data/rodadas.json');

foreach ($dados as $key => $value) {
    if (preg_match('/^r(\d+)_p(\d+)_(\d+)$/', $key, $matches)) {
        $rIndex = (int) $matches[1];
        $pIndex = (int) $matches[2];
        $tIndex = (int) $matches[3];

        if ($value !== '') {
            $torneio['rodadas'][$rIndex]['partidas'][$pIndex]['placar' . $tIndex] = (int) $value;
        }
    }
}

if (gravar_json('../data/rodadas.json', $torneio)) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['sucesso' => false, 'erro' => 'Falha ao salvar as edições.']);
}