<?php

function calcular_classificacao($participantes, $torneio)
{
    $ranking = [];
    foreach ($participantes as $p) {
        $ranking[$p['id']] = [
            'id' => $p['id'],
            'nome' => $p['nome'],
            'jogos' => 0,
            'vitorias' => 0,
            'derrotas' => 0,
            'games_vencidos' => 0,
            'games_perdidos' => 0,
            'pontos' => 0
        ];
    }

    foreach ($torneio['rodadas'] as $rodada) {
        foreach ($rodada['partidas'] as $partida) {
            if ($partida['placar1'] === null || $partida['placar2'] === null) {
                continue;
            }

            $p1 = $partida['placar1'];
            $p2 = $partida['placar2'];

            $vencedor1 = $p1 > $p2;
            $vencedor2 = $p2 > $p1;
            $empate = $p1 === $p2;

            foreach ($partida['dupla1'] as $id) {
                $ranking[$id]['jogos']++;
                $ranking[$id]['games_vencidos'] += $p1;
                $ranking[$id]['games_perdidos'] += $p2;
                $ranking[$id]['pontos'] += $p1;

                if ($vencedor1) {
                    $ranking[$id]['vitorias']++;
                    $ranking[$id]['pontos'] += 2;
                } elseif (!$empate) {
                    $ranking[$id]['derrotas']++;
                }
            }

            foreach ($partida['dupla2'] as $id) {
                $ranking[$id]['jogos']++;
                $ranking[$id]['games_vencidos'] += $p2;
                $ranking[$id]['games_perdidos'] += $p1;
                $ranking[$id]['pontos'] += $p2;

                if ($vencedor2) {
                    $ranking[$id]['vitorias']++;
                    $ranking[$id]['pontos'] += 2;
                } elseif (!$empate) {
                    $ranking[$id]['derrotas']++;
                }
            }
        }
    }

    usort($ranking, function ($a, $b) {
        if ($b['pontos'] !== $a['pontos']) {
            return $b['pontos'] - $a['pontos'];
        }
        $saldoA = $a['games_vencidos'] - $a['games_perdidos'];
        $saldoB = $b['games_vencidos'] - $b['games_perdidos'];
        return $saldoB - $saldoA;
    });

    return $ranking;
}