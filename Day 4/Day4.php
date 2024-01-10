<?php
    include_once("../utils.php");

    function ClearNumbers($numbers){
        $temp_array = [];
        foreach($numbers as $key => $number){
            if($number == ""){
                unset($numbers[$key]);
            }else{
                $temp_array[] = intval($number);
            }
        }

        return $temp_array;
    }

    function ParseInputs($inputs){

        $cards = [];

        foreach($inputs as $line){
            $parts = explode(":", $line);

            $id = intval(str_replace("Card ","",$parts[0]));   // Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53 -> Card 1 -> 1

            $winningNumbers = explode("|", $parts[1])[0];      // Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53 -> 41 48 83 86 17
            $winningNumbers = explode(" ", $winningNumbers);   // Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53 -> 41, 48, 83, 86, 17

            $playedNumbers = explode("|", $parts[1])[1];       // Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53 -> 83 86  6 31 17  9 48 53
            $playedNumbers = explode(" ", $playedNumbers);     // Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53 -> 83, 86, 6, 31, 17, 9, 48, 53

            $winningNumbers = ClearNumbers($winningNumbers);
            $playedNumbers = ClearNumbers($playedNumbers);


            $cards[$id] = [
                "copy"  => 1,
                "processed" => 0,
                "winningNumbers" => $winningNumbers,
                "playedNumbers" => $playedNumbers
            ];
        }

        return $cards;
    }

    function CountMatches($card){
        $matches = [];

        foreach($card["playedNumbers"] as $number){
            if(in_array($number, $card["winningNumbers"])){
                $matches[] = $number;
            }
        }

        return $matches;
    }

    function GetScore($card){

        $matches = CountMatches($card);

        if(count($matches) == 0){
            return 0;
        }

        return 2 ** (count($matches) - 1);
    }

    function IsAllCardProcessed($cards){
        foreach($cards as $card){
            if($card["processed"] < $card["copy"]){
                return false;
            }
        }

        return true;
    }

    function NumberOfCard($cards){
        $count = 0;
        foreach($cards as $card){
            $count += $card["copy"];
        }

        return $count;
    }

    $inputs = [
        "Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53",
        "Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19",
        "Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1",
        "Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83",
        "Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36",
        "Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11",
    ];

    $inputs = utils::ReadFileAsArray("inputs.txt");

    $games = ParseInputs($inputs);

    $part_1 = 0;
    $part_2 = 0;

    foreach($games as $id => $game){
        $score = GetScore($game);
        $matches = CountMatches($game);


        $part_1 += $score;
    }

    for($i = 1 ; $i <= count($games) ; $i++){
        
        $number_of_matches = count(CountMatches($games[$i]));
        $number_of_copy = $games[$i]["copy"];

        for($j = 0; $j < $number_of_copy ; $j++){
            
            for($k = $i+1; $k < $number_of_matches+$i+1 ; $k++){
                $games[$k]["copy"]++;
                $number_of_copy_k = $games[$k]["copy"];
            }
        }
    }



    print_r([
        "Part 1" => $part_1,
        "Part 2" => NumberOfCard($games)
    ]);
?>