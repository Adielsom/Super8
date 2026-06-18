<?php
require_once '../utils/json_helper.php';

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$dados = json_decode($input, true);

if (!isset($dados['indice_rodada'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Dados inválidos.']);
    exit;
}

$torneio = ler_json('../data/rodadas.json');
$indice = (int) $dados['indice_rodada'];

if (!isset($torneio['rodadas'][$indice])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Rodada não encontrada.']);
    exit;
}

$torneio['rodadas'][$indice]['partidas'][0]['placar1'] = isset($dados['p0_1']) ? (int) $dados['p0_1'] : 0;
$torneio['rodadas'][$indice]['partidas'][0]['placar2'] = isset($dados['p0_2']) ? (int) $dados['p0_2'] : 0;
$torneio['rodadas'][$indice]['partidas'][1]['placar1'] = isset($dados['p1_1']) ? (int) $dados['p1_1'] : 0;
$torneio['rodadas'][$indice]['partidas'][1]['placar2'] = isset($dados['p1_2']) ? (int) $dados['p1_2'] : 0;

if (gravar_json('../data/rodadas.json', $torneio)) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['sucesso' => false, 'erro' => 'Falha ao gravar os placares.']);
}