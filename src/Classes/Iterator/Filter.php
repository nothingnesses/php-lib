<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 * @implements I\Iterator<A>
 */
class Filter implements I\Iterator {
	/**
	 * @use T\Iterator<A>
	 */
	use T\Iterator;

	/**
	 * @param I\Iterator<A> $iterator Iterator to filter the items of.
	 * @param \Closure(A): bool $fn Function applied to the items being iterated over to filter those that match a condition.
	 */
	private function __construct(private I\Iterator $iterator, private \Closure $fn) {
	}

	/**
	 * @param callable(A): bool $fn Function applied to the items being iterated over to filter those that match a condition.
	 * @return \Closure(I\Iterator<A>): I\Iterator<A>
	 */
	public static function new(callable $fn): \Closure {
		/**
		 * @param I\Iterator<A> $iterator Iterator to filter the items of.
		 */
		return fn (I\Iterator $iterator): self => new self(
			fn: \Closure::fromCallable($fn),
			iterator: $iterator
		);
	}

	public function next(): C\Maybe {
		return $this->iterator->find($this->fn);
	}
}
