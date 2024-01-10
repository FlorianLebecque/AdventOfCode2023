<?php

    include_once("../utils.php");


    function GetIndexesOfStringInString($string, $substring){
        $indexes = array();
        $index = 0;
        while(($index = strpos($string, $substring, $index)) !== false){
            array_push($indexes, $index);
            $index++;
        }
        return $indexes;
    }

    function GetMapOfIndexes($string, $substrings){
        $map = array();
        foreach($substrings as $sub){
            $indexes = GetIndexesOfStringInString($string, $sub);
            $map[$sub] = $indexes;
        }

        //remove empty indexes
        foreach($map as $key => $value){
            if(count($value) == 0){
                unset($map[$key]);
            }
        }

        return $map;
    }

    function GetLowestAndMax($map){
        $lowest = PHP_INT_MAX;
        $max = PHP_INT_MIN;

        $lowestKey = "";
        $maxKey = "";

        foreach($map as $key => $value){
            if($value[0] < $lowest){
                $lowest = $value[0];
                $lowestKey = $key;
            }

            if($value[count($value) - 1] > $max){
                $max = $value[count($value) - 1];
                $maxKey = $key;
            }
        }

        return array( "min" => $lowestKey, "max" => $maxKey);
    }

    function ConvertTextToNumber($text){
        $text = str_replace("one", "1", $text);
        $text = str_replace("two", "2", $text);
        $text = str_replace("three", "3", $text);
        $text = str_replace("four", "4", $text);
        $text = str_replace("five", "5", $text);
        $text = str_replace("six", "6", $text);
        $text = str_replace("seven", "7", $text);
        $text = str_replace("eight", "8", $text);
        $text = str_replace("nine", "9", $text);
        $text = str_replace("zero", "0", $text);

        return $text;
    }

    function GetNumberOfInput($input, $substrings){
        $indexes = GetMapOfIndexes($input, $substrings);

        $result = GetLowestAndMax($indexes);

        $result = ConvertTextToNumber($result['min']) . ConvertTextToNumber($result['max']);

        return intval($result);
    }

    $substrings_part1 = [
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
    ];

    $substrings_part2 = [
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
        "one",
        "two",
        "three",
        "four",
        "five",
        "six",
        "seven",
        "eight",
        "nine"
    ];


    $inputs = [
        "two1nine"        ,        
        "eightwothree"    ,        
        "abcone2threexyz" ,        
        "xtwone3four"     ,        
        "4nineeightseven2",        
        "zoneight234"     ,        
        "7pqrstsixteen"   ,        
        "7eight7"         ,        
        "3stuffthree"     ,        
        "9sixqnine9jk9six",
        "twotwosevenvkzzhrpgninecqvf9",
        "88trnvjtqsmseight8",
        "58qtpqqz58888cmhs",
        "962sixoneonectfgpknl8nine",
        "nine",
        "cf8",
        "one",
        'j7'
    ];

    $inputs = Utils::ReadFileAsArray("input.txt");
    
    $sum_part_1 = 0;
    $sum_part_2 = 0;

    $result_map = [];

    foreach($inputs as $input){
        $number_1 = GetNumberOfInput($input, $substrings_part1);
        $number_2 = GetNumberOfInput($input, $substrings_part2);

        $result_map[$input] = [$number_1, $number_2];

        $sum_part_1 += $number_1;
        $sum_part_2 += $number_2;

    }
    
    print_r(["part 1" => $sum_part_1, "part 2" => $sum_part_2]);
?>