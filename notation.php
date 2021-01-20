<?php
function chessN($players)
{
    $white = rand(0, 1);
    $rNotation = '[ 1.'.$players[$white].' 2.'.$players[1-$white].'] ';
    
    $pieces = ['', 'B', 'Q', 'K', 'R', 'N'];
    $letters = ['a','b','c','d','e','f','g','h'];

    $maxSize = 500;
    $maxMove = 16;
    $maxMoves = floor($maxSize/$maxMove);
    $moves = rand(floor($maxMoves/4), $maxMoves);

    for($i = 1; $i <= $moves; $i++)
    {
        $rNotation .= $i.'. ';
        for($j = 0; $j < 2; $j++)
        {
            $chosen_piece = $pieces[array_rand($pieces)];
            $rNotation .= $chosen_piece;
            if (rand(0, 10) > 6) {
                if($chosen_piece == '')
                    $rNotation .= $letters[array_rand($letters)];
                $rNotation .= 'x';
            }
            $rNotation .= $letters[array_rand($letters)].rand(1, 8);
            if(rand(0, 100) > 85)
                $rNotation .= '+';
            $rNotation .= ' ';
        }
    }

    return ",'".$rNotation."'";
}

function checkersN($players)
{
    $white = rand(0, 1);
    $rNotation = '[ 1.'.$players[$white].' 2.'.$players[1-$white].'] ';

    $maxSize = 500;
    $maxMove = 16;
    $maxMoves = floor($maxSize/$maxMove);
    $moves = rand(floor($maxMoves/4), $maxMoves);

    for ($i = 1; $i <= $moves; $i++) {
        $rNotation .= $i.'. ';
        for($j = 0; $j < 2; $j++)
        {
            $rNotation .= rand(1, 32);
            if(rand(0, 10) > 8)
                $rNotation .= 'x';
            else
                $rNotation .= '-';
            $rNotation .= rand(1, 32).' ';
        }

    }
    
    return ",'".$rNotation."'";
}

function soccerN($players)
{
    $white = rand(0, 1);
    $rNotation = '[ 1.'.$players[$white].' 2.'.$players[1-$white].'] ';

    $maxSize = 500;
    $maxMove = 16;
    $maxMoves = floor($maxSize/$maxMove);
    $moves = rand(floor($maxMoves/4), $maxMoves);

    for ($i = 1; $i <= $moves; $i++) {
        $rNotation .= $i.'. ';
        for($j = 0; $j < 2; $j++)
        {
            $bounces = rand(1, 5);
            for($q = 0; $q < $bounces; $q++)
                $rNotation .= rand(1, 8);
            $rNotation .= ' ';
        }

    }

    return ",'".$rNotation."'";
}

function piecesN($players)
{
    $pls = $players;
    shuffle($pls);

    $rNotation = '[ ';
    for($i = 0; $i < count($pls); $i++)
        $rNotation .= ($i + 1).'.'.$pls[$i].' ';
    $rNotation .= '] '; 

    $pieces = ['i', 'l', 'u', 'v', 'w', 'X'];
    $maxSize = 500;
    $maxMove = 12;
    $maxMoves = floor($maxSize/$maxMove);
    $moves = rand(floor($maxMoves/4), $maxMoves);

    for ($i = 1; $i <= $moves; $i++) {
        $rNotation .= $i.'. ';
        for($j = 0; $j < count($pls); $j++)
        {
            $rNotation .= $pieces[array_rand($pieces)].' ';
        }
    }

    return ",'".$rNotation."'";
}

function ludoN($players)
{
    $pls = $players;
    shuffle($pls);

    $rNotation = '[';
    for($i = 0; $i < count($pls); $i++)
        $rNotation .= ($i + 1).'.'.$pls[$i].' ';
    $rNotation .= '] '; 
    return ",'placeholder history'";
}
?>