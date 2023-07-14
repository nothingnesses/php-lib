<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Interfaces;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;

/**
 * Types with `next_back` should implement this.
 * 
 * @template A
 */
interface DoubleEnded {
	/**
	 * Returns the next item from the back.
	 *
	 * @return C\Maybe<A> `some` variant containing the next item from the back if it exists, or the `none` variant if not.
	 */
	public function next_back(): C\Maybe;

	/**
	 * Returns an iterator that yields items from the current iterator in
	 * reverse, up to the last item to be yielded before the reversal.
	 *
	 * @return I\DoubleEnded<A> An type that yields items in reverse.
	 */
	public function reverse(): I\DoubleEnded;

	/**
	 * Returns the first item from the back that satisfies a predicate function.
	 *
	 * @param callable(A): bool $fn Function applied to the items yielded to test if they match a condition.
	 * @return C\Maybe<A> `some` variant containing the first item from the back that matches the condition if it exists, or the `none` variant if not.
	 */
	public function reverse_find(callable $fn): C\Maybe;
}
