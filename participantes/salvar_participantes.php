<?php
require_once '../utils/json_helper.php';

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$dados = json_decode($input, true);

if (!isset($dados['jogadores']) || count($dados['jogadores']) !== 8) {
    echo json_encode(['sucesso' => false, 'erro' => 'Exatamente 8 jogadores são necessários.']);
    exit;
}

$participantesFormatados = [];
foreach ($dados['jogadores'] as $index => $nome) {
    $participantesFormatados[] = [
        'id' => $index + 1,
        'nome' => trim($nome)
    ];
}

$caminhoArquivo = '../data/participantes.json';

if (gravar_json($caminhoArquivo, $participantesFormatados)) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['sucesso' => false, 'erro' => 'Falha ao gravar no arquivo JSON.']);
}