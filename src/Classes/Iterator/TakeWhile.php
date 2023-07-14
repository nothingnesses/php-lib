<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An iterator that yields items only as long they satisfy a predicate function.
 * 
 * @template A
 * @implements I\Iterator<A>
 */
class TakeWhile implements I\Iterator {
	/**
	 * @use T\Iterator<A>
	 */
	use T\Iterator;

	/**
	 * @param I\Iterator<A> $iterator Iterator to filter the items of.
	 * @param \Closure(A): bool $fn Function applied to the items being iterated over to filter those that match a condition.
	 * @param bool $is_finished States if iteration has finished.
	 */
	private function __construct(private I\Iterator $iterator, private \Closure $fn, private bool $is_finished) {
	}

	/**
	 * @param callable(A): bool $fn Function applied to the items being iterated over to filter those that match a condition.
	 * @return \Closure(I\Iterator<A>): I\Iterator<A>
	 */
	public static function new(callable $fn): \Closure {
		/**
		 * @param I\Iterator<A> $iterator Iterator to take items from.
		 */
		return fn (I\Iterator $iterator): self => new self(
			fn: \Closure::fromCallable($fn),
			iterator: $iterator,
			is_finished: false
		);
	}

	public function next(): C\Maybe {
		if ($this->is_finished) return C\Maybe::none();
		return $this->iterator->next()->bind(function ($item) {
			$fn = $this->fn;
			if (!$fn($item)) {
				$this->is_finished = true;
				return C\Maybe::none();
			}
			return C\Maybe::some($item);
		});
	}
}