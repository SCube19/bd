<?php

$chess_pieces = ['', 'B', 'Q', 'K', 'R', 'N'];
$chess_letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
$p_pieces = ['i', 'l', 'u', 'v', 'w', 'X'];

function chessN()
{
    global $chess_pieces;
    global $chess_letters;

    $rNotation = '';
    $chosen_piece = $chess_pieces[array_rand($chess_pieces)];
    $rNotation .= $chosen_piece;
    if (rand(0, 10) > 6) {
        if ($chosen_piece == '')
            $rNotation .= $chess_letters[array_rand($chess_letters)];
        $rNotation .= 'x';
    }
    $rNotation .= $chess_letters[array_rand($chess_letters)] . rand(1, 8);
    if (rand(0, 100) > 85)
        $rNotation .= '+';
    $rNotation .= ' ';

    return $rNotation;
}

function checkersN()
{
    $rNotation = '';
    $rNotation .= rand(1, 32);
    if (rand(0, 10) > 8)
        $rNotation .= 'x';
    else
        $rNotation .= '-';
    $rNotation .= rand(1, 32) . ' ';

    return $rNotation;
}

function soccerN()
{
    $rNotation = '';
    $bounces = rand(1, 5);
    for ($q = 0; $q < $bounces; $q++)
        $rNotation .= rand(1, 8);
    $rNotation .= ' ';

    return $rNotation;
}

function piecesN()
{
    global $p_pieces;
    return $p_pieces[array_rand($p_pieces)] . ' ';
}

function ludoN()
{
    $rNotation = '';

    $d6 = rand(1, 6);
    $rNotation .= $d6;
    if (rand(0, 100) > 90)
        $rNotation .= '^';
    else if ($d6 == 6 && rand(0, 100) > 85)
        $rNotation .= '+';
    else if (rand(0, 100) > 75)
        $rNotation .= '-';
    $rNotation .= ' ';

    return $rNotation;
}

function notation($game, $players)
{
    $pls = $players;
    shuffle($pls);

    $rNotation = '[ ';
    for ($i = 0; $i < count($pls); $i++)
        $rNotation .= ($i + 1) . '.' . $pls[$i] . ' ';
    $rNotation .= '] ';

    $maxSize = 500;
    $maxMove = 16;
    if ($game == 'bierki')
        $maxMove = 12;

    $maxMoves = floor($maxSize / $maxMove);
    $moves = rand(floor($maxMoves / 4), $maxMoves);

    for ($i = 1; $i <= $moves; $i++) {
        $rNotation .= $i . '. ';
        for ($j = 0; $j < count($pls); $j++) {
            switch ($game) {
                case 'bierki':
                    $rNotation .= piecesN();
                    break;
                case 'chinczyk':
                    $rNotation .= ludoN();
                    break;
                case 'pilka':
                    $rNotation .= soccerN();
                    break;
                case 'szachy':
                    $rNotation .= chessN();
                    break;
                case 'warcaby':
                    $rNotation .= checkersN();
                    break;
                default:
            }
        }
    }

    return ",'" . $rNotation . "'";
}
