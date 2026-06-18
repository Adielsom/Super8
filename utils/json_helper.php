<?php

function ler_json($caminho)
{
    if (!file_exists($caminho)) {
        return [];
    }

    $conteudo = file_get_contents($caminho);
    $dados = json_decode($conteudo, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }

    return $dados;
}

function gravar_json($caminho, $dados)
{
    $diretorio = dirname($caminho);
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    $json = json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($caminho, $json) !== false;
}