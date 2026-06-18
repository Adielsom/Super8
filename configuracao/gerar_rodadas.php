<?php
require_once '../utils/json_helper.php';
require_once '../utils/sorteio.php';

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$dados = json_decode($input, true);

if (!isset($dados['formato'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Formato não selecionado.']);
    exit;
}

$participantes = ler_json('../data/participantes.json');

if (count($participantes) !== 8) {
    echo json_encode(['sucesso' => false, 'erro' => 'É necessário ter exatamente 8 participantes.']);
    exit;
}

$ids = array_column($participantes, 'id');
$formato = $dados['formato'];

if ($formato === 'rotativas') {
    $rodadas = gerar_rotativas($ids);
} else {
    $rodadas = gerar_fixas($ids);
}

$dadosTorneio = [
    'formato' => $formato,
    'rodadas' => $rodadas
];

if (gravar_json('../data/rodadas.json', $dadosTorneio)) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['sucesso' => false, 'erro' => 'Falha ao gravar as rodadas.']);
}