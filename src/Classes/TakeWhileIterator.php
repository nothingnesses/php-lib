<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An iterator that yields items only as long they satisfy a predicate function.
 * 
 * @template A
 */
class TakeWhileIterator implements I\Iterator {
	use T\AppendIterator, T\FilterIterator, T\Iterator, T\MapIterator;

	private function __construct(private I\Iterator $iterator, private \Closure $predicate, private bool $is_finished) {
	}

	/**
	 * @param callable(A): bool $predicate Function applied to the items being iterated over to filter those that match a condition.
	 * @return \Closure(I\Iterator): TakeWhileIterator<A>
	 */
	public static function new(callable $predicate): \Closure {
		/**
		 * @param I\Iterator $iterator Iterator to take items from.
		 */
		return fn (I\Iterator $iterator): self => new self(
			predicate: \Closure::fromCallable($predicate),
			iterator: $iterator,
			is_finished: false
		);
	}

	public function next(): Maybe {
		if ($this->is_finished) return Maybe::none();
		return $this->iterator->next()->bind(function ($item) {
			$predicate = $this->predicate;
			if (!$predicate($item)) {
				$this->is_finished = true;
				return Maybe::none();
			}
			return Maybe::some($item);
		});
	}
}