<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

class Functions {
	/**
	 * Formats an array of values for debugging purposes.
	 *
	 * @param array $args - The array of values to be dumped.
	 * @return string - The formatted string representation of the values.
	 */
	public static function dump(array $args): string {
		$body = array_reduce(
			array_map(fn ($arg) => var_export($arg, true), $args),
			fn ($carry, $item) => <<<HTML
				{$carry}
				{$item}
			HTML,
			""
		);
		return <<<HTML
			<pre>
				{$body}
			</pre>
		HTML;
	}

	/**
	 * Formats an array of values for debugging purposes and ends script execution.
	 *
	 * @param array $args - The array of values to be dumped.
	 * @return void
	 */
	public static function dd(array $args): void {
		echo self::dump($args);
		die();
	}
}
