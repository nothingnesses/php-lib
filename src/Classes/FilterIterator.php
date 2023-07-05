<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 */
class FilterIterator implements I\FilterIterator, I\Iterator {
	use T\AppendIterator, T\FilterIterator, T\Iterator, T\MapIterator;

	private function __construct(private I\Iterator $iterator, private \Closure $predicate) {
	}

	/**
	 * @param callable(A): bool $predicate Function applied to the items being iterated over to filter those that match a condition.
	 * @return \Closure(I\Iterator): FilterIterator<A>
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
}
