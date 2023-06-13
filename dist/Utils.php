<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Utils;

/**
 * Formats an array of values for debugging purposes.
 *
 * @param array $args - The array of values to be dumped.
 * @return string - The formatted string representation of the values.
 */
function dump(array $args): string {
	$body = array_reduce(array_map(function ($arg) {
		return var_export($arg, true);
	}, $args), function ($carry, $item) {
		return <<<HTML
\t{$carry}
\t{$item}
HTML;
	}, "");
	return <<<HTML
\t<pre>
\t\t{$body}
\t</pre>
HTML;
}

/**
 * Formats an array of values for debugging purposes and ends script execution.
 *
 * @param array $args - The array of values to be dumped.
 * @return void
 */
function dd(array $args) {
	echo dump($args);
	die();
}

/**
 * Identity function that returns the input value as is.
 *
 * @template A
 * @param A $a - The input value.
 * @return A - The input value itself.
 */
function id($a) {
	return $a;
}

/**
 * Echoes a string. Wraps over the `echo` language construct.
 *
 * @param string $string - The string to be echoed.
 * @return void
 */
$echo = function (string $string) {
	echo ($string);
};
