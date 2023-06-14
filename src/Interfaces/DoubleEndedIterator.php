<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Interfaces;

use Nothingnesses\Lib\Classes as C;

/**
 * @template A
 *
 * An iterator able to yield elements from both ends.
 */
interface DoubleEndedIterator extends Iterator {
	/**
	 * Returns the next item from the back.
	 *
	 * @return C\Maybe<A> The next item from the back.
	 */
	public function next_back(): C\Maybe;

	/**
	 * Returns an iterator that iterates items in reverse, up to the last item to be iterated before the reversal.
	 *
	 * @return C\ReversedIterator<A> An iterator that iterates items in reverse.
	 */
	public function reverse(): C\ReversedIterator;

	/**
	 * Returns the first item from the back that matches a condition.
	 *
	 * @param callable(A): bool $predicate Function applied to the item being iterated over to test if it matches a condition.
	 * @return C\Maybe<A> `Maybe.some` containing the first item from the back that matches the condition, if it exists; otherwise, `Maybe.none`.
	 */
	public function reverse_find(callable $predicate): C\Maybe;
}
