<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator\DoubleEnded;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 * @implements I\DoubleEndedIterator<A>
 */
class Map implements I\DoubleEndedIterator {
	/**
	 * @use T\Iterator<A>
	 * @use T\Iterator\DoubleEnded<A>
	 */
	use T\Iterator, T\Iterator\DoubleEnded;

	/**
	 * @template B
	 * @param I\DoubleEndedIterator<B> $iterator Iterator to map the items of.
	 * @param \Closure(B): A $fn Function applied to the items yielded.
	 */
	private function __construct(private I\DoubleEndedIterator $iterator, private \Closure $fn) {
	}

	/**
	 * @template B
	 * @param callable(B): A $fn Function applied to the items yielded.
	 * @return \Closure(I\DoubleEndedIterator<B>): (I\DoubleEndedIterator<A>)
	 */
	public static function new(callable $fn): \Closure {
		/**
		 * @param I\DoubleEndedIterator<B> $iterator Iterator to map the items of.
		 * @return I\DoubleEndedIterator<A>
		 */
		return fn (I\DoubleEndedIterator $iterator): self => new self(
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

	/**
	 * @return C\Maybe<A>
	 */
	public function next_back(): C\Maybe {
		return $this->iterator->next_back()->map($this->fn);
	}
}
