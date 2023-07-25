<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Traits\Iterator;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;

/**
 * @template A
 */
trait DoubleEnded {
	/**
	 * Returns an iterator that yields items from the current iterator, then from
	 * another iterator.
	 *
	 * @param I\Iterator $second The other iterator to concatenate with the current one.
	 * @return I\DoubleEnded<A>&I\Iterator<A>
	 */
	public function chain($second) {
		return C\Iterator\DoubleEnded\Chain::new($this)($second);
	}

	/**
	 * Returns an iterator that yields items that satisfy a predicate function.
	 *
	 * @param callable(A): bool $fn The function applied to the items yielded to test if they match a condition.
	 * @return I\DoubleEnded<A>&I\Iterator<A>
	 */
	public function filter($fn) {
		return C\Iterator\DoubleEnded\Filter::new($fn)($this);
	}

	/**
	 * Returns an iterator that yields items that are the result of applying a
	 * function to items yielded by the current iterator.
	 *
	 * @template B
	 * @param callable(A): B $fn The function to apply to each item yielded.
	 * @return \Nothingnesses\Lib\Interfaces\DoubleEnded<\Nothingnesses\Lib\Traits\Iterator\B>&\Nothingnesses\Lib\Interfaces\Iterator<\Nothingnesses\Lib\Traits\Iterator\B>
	 */
	public function map($fn) {
		return C\Iterator\DoubleEnded\Map::new($fn)($this);
	}

	/**
	 * Returns the next item from the back.
	 *
	 * @return C\Maybe<A> `some` variant containing the next item from the back if it exists, or the `none` variant if not.
	 */
	abstract public function next_back(): C\Maybe;

	/**
	 * Returns an iterator that yields items from the current iterator in
	 * reverse, up to the last item to be yielded before the reversal.
	 *
	 * @return I\DoubleEnded<A>&I\Iterator<A> An iterator that iterates items in reverse.
	 */
	public function reverse() {
		return C\Iterator\Reverse::new($this);
	}

	/**
	 * Returns the first item from the back that satisfies a predicate function.
	 *
	 * @param callable(A): bool $fn Function applied to the items yielded to test if they match a condition.
	 * @return C\Maybe<A> `some` variant containing the first item from the back that matches the condition if it exists, or the `none` variant if not.
	 */
	public function reverse_find($fn): C\Maybe {
		$current = $this->next_back();
		while ($current->is_some()) {
			$result = $current->bind(function ($item) use ($fn) {
				return $fn($item)
 				? C\Maybe::some($item)
 				: C\Maybe::none();
			});
			if ($result->is_some()) {
				return $result;
			}
			$current = $this->next_back();
		}
		return C\Maybe::none();
	}

	/**
	 * Returns an iterator that yields items after skipping the specified number
	 * of items.
	 *
	 * @param int $n The number of items to skip.
	 * @return I\DoubleEnded<A>&I\Iterator<A>
	 */
	public function skip($n) {
		return C\Iterator\DoubleEnded\Skip::new($n)($this);
	}
}
