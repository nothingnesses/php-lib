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
	 * Advances from the back by up to the specified number of items.
	 * Returns `right<null>` if successful, or `left<int>` if not.
	 * The `left` will contain the remaining number of steps.
	 *
	 * @param int $index The amount to advance the iterator by from the back.
	 * @return C\Either<int,null>
	 */
	public function advance_back_by(int $index): C\Either {
		for ($remaining = $index; $remaining > 0; --$remaining) {
			if (!($this->next_back()->is_some())) {
				return C\Either::left($remaining);
			}
		}
		return C\Either::right(null);
	}

	/**
	 * Returns the next item from the back.
	 *
	 * @return C\Maybe<A> `some` variant containing the next item from the back if it exists, or the `none` variant if not.
	 */
	abstract public function next_back(): C\Maybe;

	/**
	 * Returns the nth item from the back wrapped in `some` if it exists,
	 * or `none` if not.
	 *
	 * @param int $index The integer to index into the instance with.
	 * @return C\Maybe<A>
	 */
	public function nth_back(int $index): C\Maybe {
		$this->advance_back_by($index);
		return $this->next_back();
	}

	/**
	 * Returns an iterator that yields items from the current iterator in
	 * reverse, up to the last item to be yielded before the reversal.
	 *
	 * @return I\DoubleEndedIterator<A> An iterator that iterates items in reverse.
	 */
	public function reverse(): I\DoubleEndedIterator {
		return C\Iterator\Reverse::new($this);
	}

	/**
	 * Returns the first item from the back that satisfies a predicate function.
	 *
	 * @param callable(A): bool $fn Function applied to the items yielded to test if they match a condition.
	 * @return C\Maybe<A> `some` variant containing the first item from the back that matches the condition if it exists, or the `none` variant if not.
	 */
	public function reverse_find(callable $fn): C\Maybe {
		$current = $this->next_back();
		while ($current->is_some()) {
			$result = $current->bind(fn ($item) => $fn($item)
				? C\Maybe::some($item)
				: C\Maybe::none());
			if ($result->is_some()) {
				return $result;
			}
			$current = $this->next_back();
		}
		return C\Maybe::none();
	}
}
