<?php
    include_once("../utils.php");



    function GetSymbols($inputs){

        $symbols = [];

        foreach($inputs as $input){
            $symbols = array_merge($symbols, str_split($input));
        }

        $symbols = array_unique($symbols);

        $filtered_symbols = [".","1","2","3","4","5","6","7","8","9","0","\n","\r","\t"];

        //remove all symbols that are filtered
        foreach($symbols as $key => $symbol){
            if(in_array($symbol, $filtered_symbols)){
                unset($symbols[$key]);
            }
        }

        return $symbols;
    }


    function StringArrayTo2DMap($string_array){
        $map = [];

        foreach($string_array as $string){
            $map[] = str_split($string);
        }

        return $map;
    }

    function GetPositionOfNumbers($map){
        $numbers = [];

        for($y = 0; $y < count($map); $y++){    //for each row

            
            $current_number = "";
            $number_starting_position = 0;

            for($x = 0; $x < count($map[$y]); $x++){    //for each column
                if(is_numeric($map[$y][$x])){

                    if($current_number == ""){
                        $number_starting_position = $x;
                    }

                    $current_number .= $map[$y][$x];
                }else{
                    if($current_number != ""){
                        $numbers[] = [
                            "number" => intval($current_number),
                            "x" => $number_starting_position,
                            "y" => $y,
                            "length" => $x - $number_starting_position,
                        ];
                            
                        $current_number = "";
                    }
                }
            }

        }


        return $numbers;
    }

    function GetPositionOfGear($map){
        $gears = [];

        for($y = 0; $y < count($map); $y++){    //for each row

            for($x = 0; $x < count($map[$y]); $x++){    //for each column
                if($map[$y][$x] == "*"){
                    $gears[] = [
                        "x" => $x,
                        "y" => $y,
                        "length" => 1,
                    ];
                }
            }

        }
        return $gears;
    }

    function GetBoundingPositions($element){


        $x = $element["x"];
        $y = $element["y"];
        $length = $element["length"];

        for($i = $y - 1; $i <= $y + 1; $i++){
            for($j = $x - 1; $j <= $x + $length; $j++){

                if($i == $y ){
                    if($j >= $x && $j < $x + $length){
                        continue;
                    }
                }

                $positions[] = [
                    "x" => $j,
                    "y" => $i,
                ];
            }
        }

        return $positions;

    }

    function CheckSymbol($number,$map,$symbols) : bool{

        $x = $number["x"];
        $y = $number["y"];
        $length = $number["length"];

        $positions = GetBoundingPositions($number);

        foreach($positions as $position){

            if($position["x"] < 0 || $position["x"] >= count($map[0])){
                continue;
            }

            if($position["y"] < 0 || $position["y"] >= count($map)){
                continue;
            }

            $symbol = $map[$position["y"]][$position["x"]];

            if(in_array($symbol, $symbols)){
                return true;
            }
        }

        return false;
    }

    function GetValideNumbers($numbers,$map,$symbols){

        $valide_numbers = [];

        foreach($numbers as $number){
            if(CheckSymbol($number,$map,$symbols)){
                $valide_numbers[] = $number;
            }
        }

        return $valide_numbers;
    }

    function GetAdjacentNumber($numbers,$element){

        $x = $element["x"];
        $y = $element["y"];
        $length = $element["length"];

        $adjacent_numbers = [];

        foreach($numbers as $number){

            $positions = GetBoundingPositions($number);

            foreach($positions as $position){
                if($position["x"] == $x && $position["y"] == $y){
                    $adjacent_numbers[] = $number;
                }
            }

        }

        return $adjacent_numbers;
    }

    function GetGearRatio($gear,$numbers){

        $adjacent_numbers = GetAdjacentNumber($numbers,$gear);

        if(count($adjacent_numbers) != 2){
            return 0;
        }

        return $adjacent_numbers[0]["number"] * $adjacent_numbers[1]["number"];
    }

    $inputs = [
        '467..114..',
        '...*......',
        '..35..633.',
        '......#...',
        '617*......',
        '.....+.58.',
        '..592.....',
        '......755.',
        '...$.*....',
        '.664.598..',
    ];
    $inputs = utils::ReadFileAsArray("inputs.txt");

    
    $symbols = GetSymbols($inputs);

    print_r($symbols);

    $maps = StringArrayTo2DMap($inputs);
    $numbers = GetPositionOfNumbers($maps);
    $numbers = GetValideNumbers($numbers,$maps,$symbols);

    $gears = GetPositionOfGear($maps);


    $part_1 = 0;
    foreach($numbers as $number){
        $part_1 += intval($number["number"]);
    }

    $part_2 = 0;
    foreach($gears as $gear){
        $part_2 += GetGearRatio($gear,$numbers);
    }

    print_r(["part 1" => $part_1, "part 2" => $part_2]);

?>