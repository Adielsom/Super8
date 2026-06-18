<?php

function gerar_rotativas($ids)
{
    $esquema = [
        [[0, 1], [2, 3], [4, 5], [6, 7]],
        [[0, 2], [4, 6], [1, 3], [5, 7]],
        [[0, 3], [5, 6], [1, 2], [4, 7]],
        [[0, 4], [1, 7], [2, 6], [3, 5]],
        [[0, 5], [1, 6], [2, 7], [3, 4]],
        [[0, 6], [3, 7], [1, 4], [2, 5]],
        [[0, 7], [1, 5], [2, 4], [3, 6]]
    ];
    $rodadas = [];
    foreach ($esquema as $i => $rodada) {
        $rodadas[] = [
            'numero' => $i + 1,
            'partidas' => [
                [
                    'dupla1' => [$ids[$rodada[0][0]], $ids[$rodada[0][1]]],
                    'dupla2' => [$ids[$rodada[1][0]], $ids[$rodada[1][1]]],
                    'placar1' => null,
                    'placar2' => null
                ],
                [
                    'dupla1' => [$ids[$rodada[2][0]], $ids[$rodada[2][1]]],
                    'dupla2' => [$ids[$rodada[3][0]], $ids[$rodada[3][1]]],
                    'placar1' => null,
                    'placar2' => null
                ]
            ]
        ];
    }
    return $rodadas;
}

function gerar_fixas($ids)
{
    $duplas = [
        [$ids[0], $ids[1]],
        [$ids[2], $ids[3]],
        [$ids[4], $ids[5]],
        [$ids[6], $ids[7]]
    ];
    $confrontos = [
        [0, 1, 2, 3],
        [0, 2, 1, 3],
        [0, 3, 1, 2]
    ];
    $rodadas = [];
    for ($i = 0; $i < 7; $i++) {
        $c = $confrontos[$i % 3];
        $rodadas[] = [
            'numero' => $i + 1,
            'partidas' => [
                [
                    'dupla1' => $duplas[$c[0]],
                    'dupla2' => $duplas[$c[1]],
                    'placar1' => null,
                    'placar2' => null
                ],
                [
                    'dupla1' => $duplas[$c[2]],
                    'dupla2' => $duplas[$c[3]],
                    'placar1' => null,
                    'placar2' => null
                ]
            ]
        ];
    }
    return $rodadas;
}