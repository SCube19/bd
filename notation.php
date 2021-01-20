<?php
function chessN($players)
{
    $white = rand(0, 1);
    $rNotation = '1.'.$players[$white].' 2.'.$players[1-$white].' ';
    
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

function checkersN()
{
    return ",'placeholder history'";
}

function soccerN()
{
    return ",'placeholder history'";
}

function piecesN()
{
    return ",'placeholder history'";
}

function ludoN()
{
    return ",'placeholder history'";
}
?>