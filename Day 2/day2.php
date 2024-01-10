<?php

    include_once("../utils.php");

    function GetKeys($string){

        $possible_keys = ["blue", "red", "green"];

        foreach($possible_keys as $key){
            if(strpos($string, $key) !== false){
                return $key;
            }
        }

        return "";
    }

    function GameParser($string){

        $game_array = explode(":", $string);

        $game_array[1] = str_replace(";", ",", $game_array[1]);

        $game_dices = explode(",", $game_array[1]);

        $game = [
            "id"    => intval(str_replace("Game ", "", $game_array[0])),
        ];

        foreach($game_dices as $dice){
            $dice = trim($dice);
            $dice = explode(" ", $dice);

            
            if(isset($game[GetKeys($dice[1])]) == false){
                $game[GetKeys($dice[1])] = 0;
            }

                //register the highest value
            if($game[GetKeys($dice[1])] < intval($dice[0])){
                $game[GetKeys($dice[1])] = intval($dice[0]);
            }
        }

        return $game;
    }

    function IsGameLegal($game, $game_rule){
        foreach($game_rule as $key => $value){
                //check if the game has more dices than the rule
            if($game[$key] > $value){
                return false;
            }
        }

        return true;
    }

    $inputs = [
        "Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green",
        "Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue",
        "Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red",
        "Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red",
        "Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green",
    ];

    $inputs = Utils::ReadFileAsArray("inputs.txt");

    $game_rule = [
        "blue"  => 14,
        "red"   => 12,
        "green" => 13,
    ];

    $sum = 0;

    $power = 0;

    foreach($inputs as $input){
        $game = GameParser($input);

        if(IsGameLegal($game, $game_rule)){
            $sum += $game['id'];
        }

        $power += $game['blue'] * $game['red'] * $game['green'];
    }

    print_r(["part 1" => $sum, "part 2" => $power]);
?>