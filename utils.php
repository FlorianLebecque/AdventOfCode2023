<?php

    class Utils{
        public static function ReadFileAsArray($file){
            $file = fopen($file, "r");
            $array = array();
            while(!feof($file)){
                $line = fgets($file);
                array_push($array, $line);
            }
            fclose($file);
            return $array;
        }
    }

    

?>