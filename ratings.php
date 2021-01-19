<?php

function overrideLetters($formula, $mapping)
{
    $expr = explode(" ", $formula);
    for ($i = 0; $i < count($expr); $i++)
        if (($key = array_search($expr[$i], $mapping)) != false)
            $expr[$i] = $mapping[$key + 1];

    return implode(" ", $expr);
}

function rating($formula, $rating, $opponentRating)
{
    $mapping = array();
    $mapping[] = "Easteregg";
    $mapping[] = "E";
    $mapping[] = strval($opponentRating);
    $mapping[] = "R";
    $mapping[] = strval($rating);

    for ($i = 3; $i < func_num_args(); $i++) {
        $mapping[] = strval(func_get_arg($i)[0]);
        $mapping[] = strval(func_get_arg($i)[1]);
    }

    return RPN::Calculate(overrideLetters($formula, $mapping));
}

class RPN
{
    public static $pattern;

    public static function Calculate($pattern)
    {
        // Could have used a simple getter here...
        self::$pattern = $pattern;
        $numbers = array();

        $pattern_array = explode(' ', str_replace("  ", " ", trim(self::$pattern)));
        $acceptable_operators = array("+", "-", "/", "*", "^");
        $calculationResult = '';

        if (count($pattern_array) == 1)
            return 'RPN::Calculate() requires more than 2 characters.';
        elseif (!in_array(end($pattern_array), $acceptable_operators))
            return 'RPN::Calculate() requires the last character to be an operator';

        foreach ($pattern_array as $value) {
            if (is_numeric($value)) {
                $numbers[] = $value;
            } elseif (in_array($value, $acceptable_operators)) {
                $first_number = array_pop($numbers);
                $second_number = array_pop($numbers);

                switch ($value) {
                    case '+':
                        $calculationResult = $second_number + $first_number;
                        break;
                    case '-':
                        $calculationResult = $second_number - $first_number;
                        break;
                    case '/':
                        $calculationResult = $second_number / $first_number;
                        break;
                    case '*':
                        $calculationResult = $second_number * $first_number;
                        break;
                    case '^':
                        $calculationResult = pow($second_number, $first_number);
                        break;
                }
                array_push($numbers, $calculationResult);
            } else {
                return 'RPN::Calculate() found an invalid character of ' . $value . '. This character is not allowed.';
            }
        }
        return floor($calculationResult);
    }
}

