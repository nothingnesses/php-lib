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
	 * @param A $first
	 * @param B $second
	 */
	private function __construct(public mixed $first, public mixed $second) {
	}

	/**
	 * Makes a `Pair`.
	 *
	 * @param A $first
	 * @return \Closure(B): C\Pair<A,B>
	 */
	public static function new(mixed $first): \Closure {
		/**
		 * @param B $second
		 * @return C\Pair<A,B>
		 */
		return fn (mixed $second): self => new self(
			first: $first,
			second: $second
		);
	}

	/**
	 * Returns a `Pair` where the items's positions have been swapped.
	 *
	 * @return C\Pair<B,A>
	 */
	public function swap(): self {
		return new self(
			first: $this->second,
			second: $this->first
		);
	}
}
