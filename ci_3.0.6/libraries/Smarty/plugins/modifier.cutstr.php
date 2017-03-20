<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *               optionally splitting in the middle of a word, and
 *               appending the $etc string or inserting $etc into the middle.
 *
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php truncate (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @param string  $string      input string
 * @param integer $length      length of truncated text
 * @param string  $etc         end string
 * @param boolean $break_words truncate at word boundary
 * @param boolean $middle      truncate in the middle of text
 * @return string truncated string
 */
function smarty_modifier_cutstr($str, $length = 80, $append = '...') {
	$str = trim($str);
	$strlength = strlen($str);
	if ($length == 0 || $length >= $strlength) {
		return $str;
	} elseif ($length < 0) {
		$length = $strlength + $length;
		if ($length < 0) {
			$length = $strlength;
		}
	}
	if ( function_exists('mb_substr') ) {
		$newstr = mb_substr($str, 0, $length, 'utf-8');
	} elseif ( function_exists('iconv_substr') ) {
		$newstr = iconv_substr($str, 0, $length, 'utf-8');
	} else {
		//$newstr = trim_right(substr($str, 0, $length));
		$newstr = substr($str, 0, $length);
	}
	if ($append && $str != $newstr) {
		$newstr .= $append;
	}
	return $newstr;
}

?>