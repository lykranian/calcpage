<?php

// requires from https://github.com/andig/php-shunting-yard
require_once "RR/Shunt/Context.php";
require_once "RR/Shunt/Parser.php";
require_once "RR/Shunt/Scanner.php";
require_once "RR/Shunt/Token.php";
require_once "RR/Shunt/Exception/ParseError.php";
require_once "RR/Shunt/Exception/RuntimeError.php";
require_once "RR/Shunt/Exception/SyntaxError.php";
use RR\Shunt\Parser;

// allows for + in the url, rather than %2B
if(strpos($_SERVER["QUERY_STRING"], "+") !== false){
   $_SERVER["QUERY_STRING"] = str_replace("+", "%2B", $_SERVER["QUERY_STRING"]);
   parse_str($_SERVER["QUERY_STRING"], $_GET);
}

$exp_raw = trim($_GET['exp']);
if(empty($exp_raw)){
   echo 'empty input';
   exit;
} else {
   $check = preg_match('#[^0-9()\+-.\/\*\^x\ ]#',$exp_raw);
   if($check) {
      $fail_char = preg_replace('#[0-9()\+-.\/\*\^x\ ]#','',$exp_raw);
      echo "error, ";
      echo $fail_char;
      echo " is not a valid input character.";
      exit;
   } else {
      // sanitize
      $exp_san = preg_replace('#[^0-9()\+-.\/\*\^x\ ]#','',$exp_raw);
      // catch a couple off-cases
      $exp_par = str_replace(')(',')*(',$exp_san);
      $exp_pow = str_replace('**','^',$exp_par);
      $exp_fix = str_replace('x','*',$exp_pow);
      // following two lines for debug
      //echo $exp_fix;
      //echo "\n";
      $result = Parser::parse($exp_fix);
      echo $result;
   }
}

