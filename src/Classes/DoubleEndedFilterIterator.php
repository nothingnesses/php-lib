<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 */
class DoubleEndedFilterIterator implements I\DoubleEndedIterator, I\FilterIterator {
	use T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;

	private function __construct(private I\DoubleEndedIterator $iterator, private \Closure $predicate) {
	}

	/**
	 * @param callable(A): bool $predicate Function applied to the items being iterated over to filter those that match a condition.
	 * @return \Closure(I\Iterator): DoubleEndedFilterIterator<A>
	 */
	public static function new(callable $predicate): \Closure {
		/**
		 * @param I\Iterator $iterator Iterator to filter.
		 */
		return fn (I\Iterator $iterator): self => new self(
			predicate: \Closure::fromCallable($predicate),
			iterator: $iterator
		);
	}

	public function next(): Maybe {
		return $this->iterator->find($this->predicate);
	}

	public function next_back(): Maybe {
		return $this->iterator->reverse_find($this->predicate);
	}
}
