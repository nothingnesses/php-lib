<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator\DoubleEnded;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 * @implements I\DoubleEnded<A>
 * @implements I\Iterator<A>
 */
class Map implements I\DoubleEnded, I\Iterator {
	/**
	 * @use T\Iterator<A>
	 * @use T\Iterator\DoubleEnded<A>
	 */
	use T\Iterator, T\Iterator\DoubleEnded {
		T\Iterator\DoubleEnded::chain insteadOf T\Iterator;
		T\Iterator\DoubleEnded::filter insteadOf T\Iterator;
		T\Iterator\DoubleEnded::map insteadOf T\Iterator;
	}

	/**
	 * @template B
	 * @param I\DoubleEnded<B>&I\Iterator<B> $iterator Iterator to map the items of.
	 * @param \Closure(B): A $fn Function applied to the items yielded.
	 */
	private function __construct(private I\DoubleEnded&I\Iterator $iterator, private \Closure $fn) {
	}

	/**
	 * @template B
	 * @param callable(B): A $fn Function applied to the items yielded.
	 * @return \Closure(I\DoubleEnded<B>&I\Iterator<B>): (I\DoubleEnded<A>&I\Iterator<A>)
	 */
	public static function new(callable $fn): \Closure {
		/**
		 * @param I\DoubleEnded<B>&I\Iterator<B> $iterator Iterator to map the items of.
		 * @return I\DoubleEnded<A>&I\Iterator<A>
		 */
		return fn (I\DoubleEnded&I\Iterator $iterator): self => new self(
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
