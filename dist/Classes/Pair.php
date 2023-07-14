<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;

/**
 * Represents a pair of values.
 *
 * @template A
 * @template B
 */
class Pair {
	/**
	 * @var A
	 */
	public $first;
	/**
	 * @var B
	 */
	public $second;
	/**
	 * @param mixed $first
	 * @param mixed $second
	 */
	private function __construct($first, $second)
	{
		$this->first = $first;
		$this->second = $second;
	}

	/**
	 * Makes a `Pair`.
	 *
	 * @param mixed $first
	 * @return \Closure(B): C\Pair<A,B>
	 */
	public static function new($first): \Closure {
		/**
		 * @param B $second
		 * @return C\Pair<A,B>
		 */
		return function ($second) use ($first) : self {
			return new self(
 			$first,
 			$second
 		);
		};
	}

	/**
	 * Returns a `Pair` where the items's positions have been swapped.
	 *
	 * @return C\Pair<B,A>
	 */
	public function swap(): self {
		return new self(
			$this->second,
			$this->first
		);
	}
}
