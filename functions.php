<?php

define('TYPE_TEXT',               0);
define('TYPE_NUMBER',             1);
define('TYPE_EMAIL',              2);
define('TYPE_NUM_TEXT',           3);
define('TYPE_NUM_TEXT_SPACE',     4);
define('TYPE_TEXT_SPACE',         5);
define('TYPE_DATE',               6);
define('TYPE_NUMBER_FLOAT',       7);
define('TYPE_NUMBER_UNDERSCORE',  8);
define('TYPE_NUM_TEXT_SPACE_DOT', 9);
define('TYPE_EMAIL_SPACE',        10);
define('TYPE_NUMERIC',            11);
define('TYPE_TEXT_UNDERSCORE',    12);


/**
 * Read a request variable and sanitize it with the given $type
 *
 * @param string $input_name The name of the request variable
 * @param integer $length The maximum length of the returned string value of the request variable
 * @param integer $type The type that the inputted data represents. Can be one of the following:
	TYPE_TEXT
	TYPE_NUMBER
	TYPE_NUM_TEXT
	TYPE_EMAIL
	TYPE_NUM_TEXT_SPACE
	TYPE_TEXT_SPACE
	TYPE_DATE
	TYPE_NUMBER_FLOAT
	TYPE_TEXT_UNDERSCORE
 * @param integer $index the index of the element to use as the transformed string if the input type is an array
 */
function input_handle($input_name, $length, $type = TYPE_NUM_TEXT, $index = null, $required = true)
{
	if (array_key_exists($input_name, $_REQUEST))
	{
		if (!is_null($index))
		{
			$str = $_REQUEST[$input_name][$index];
		}
		else
		{
			$str = $_REQUEST[$input_name];
		}
	}

	if ($str === array() || $str === '' || $str === null)
	{
		if ($required === true)
			return false;
		else
			return;
	}

	if     ($type === TYPE_NUMBER)           $str = preg_replace( '/[^0-9]/' ,                 '', $str ); // replace all non-alphanumeric
	elseif ($type === TYPE_NUM_TEXT)         $str = preg_replace( '/[^a-z0-9]/i' ,             '', $str ); // replace all non-alphanumeric
	elseif ($type === TYPE_EMAIL)            $str = preg_replace( '/[^a-z_\.@\-\d]/i',         '', $str ); // replace all non-alphanumeric
	elseif ($type === TYPE_NUM_TEXT_SPACE)   $str = preg_replace( '/[^a-z0-9 ]/i',             '', $str ); // replace all non-alphanumeric
	elseif ($type === TYPE_TEXT_SPACE)       $str = preg_replace( '/[^a-z ]/i',                '', $str ); // replace all non-alphanumeric
	elseif ($type === TYPE_DATE)             $str = preg_replace( '/[^0-9\-]/',                '', $str ); // replace all non-digits and dashes
	elseif ($type === TYPE_NUMBER_FLOAT)     $str = preg_replace( '/[^0-9\.]/',                '', $str ); // replace all non-digits and non-dots
	elseif ($type === TYPE_TEXT_UNDERSCORE)  $str = preg_replace( '/[^a-z_]/i',                '', $str ); // replace all non-alphanumeric
	elseif($type == TYPE_NUM_TEXT_SPACE_DOT) $str = preg_replace( '/[^a-z0-9\.\/\-\)\( ]/i',   '', $str ); // replace all non-alphanumeric
	elseif($type == TYPE_NUMERIC)            $str = preg_replace( '/[^0-9]/',                  '', $str );
	elseif($type == TYPE_EMAIL_SPACE)        $str = preg_replace( '/[^a-z_\.\-\d\w\)\(\/ ]/i', '', $str ); // replace all non-alphanumeric
	else                                     $str = preg_replace( '/[^a-z0-9\-]/i',            '', $str ); // default

	return substr($str, 0, $length);
}

//exit the script and print to the screen.
function fail($msg, $die = true)
{
	if ($die === true)
		die($msg . "\n");
	else
		echo $msg . "\n";
}
