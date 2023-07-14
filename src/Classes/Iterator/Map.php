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
class Map implements I\Iterator {
	/**
	 * @use T\Iterator<A>
	 */
	use T\Iterator;

	/**
	 * @template B
	 * @param I\Iterator<B> $iterator Iterator to map the items of.
	 * @param \Closure(B): A $fn Function applied to the items yielded.
	 */
	private function __construct(private I\Iterator $iterator, private \Closure $fn) {
	}

	/**
	 * @template B
	 * @param callable(B): A $fn Function applied to the items being iterated over.
	 * @return \Closure(I\Iterator<B>): I\Iterator<A>
	 */
	public static function new(callable $fn): \Closure {
		/**
		 * @param I\Iterator<B> $iterator Iterator to map.
		 * @return I\Iterator<A>
		 */
		return fn (I\Iterator $iterator): self => new self(
			fn: \Closure::fromCallable($fn),
			iterator: $iterator
		);
	}

	/**
	 * @return C\Maybe<A>
	 */
	public function next(): C\Maybe {
		return $this->iterator->next()->map($this->fn);
	}
}
