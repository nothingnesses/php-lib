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
class Chain implements I\DoubleEndedIterator {
	/**
	 * @use T\Iterator<A>
	 * @use T\Iterator\DoubleEnded<A>
	 */
	use T\Iterator, T\Iterator\DoubleEnded;

	/**
	 * @param I\DoubleEndedIterator<A> $first First iterator to yield items from.
	 * @param I\DoubleEndedIterator<A> $second Second iterator to yield items from.
	 */
	private function __construct(private I\DoubleEndedIterator $first, private I\DoubleEndedIterator $second) {
	}

	/**
	 * @param I\DoubleEndedIterator<A> $first First iterator to yield items from.
	 * @return \Closure(I\DoubleEndedIterator<A>): (I\DoubleEndedIterator<A>)
	 */
	public static function new(I\DoubleEndedIterator $first): \Closure {
		/**
		 * @param I\DoubleEndedIterator<A> $second Second iterator to yields items from.
		 * @return I\DoubleEndedIterator<A>
		 */
		return fn (I\DoubleEndedIterator $second): self => new self(
			first: $first,
			second: $second
		);
	}

	public function next(): C\Maybe {
		return $this->first->next()->maybe_lazy(
			fn () => $this->second->next()
		)(
			fn ($item): C\Maybe => C\Maybe::some($item)
		);
	}

	public function next_back(): C\Maybe {
		return $this->second->next_back()->maybe_lazy(
			fn () => $this->first->next_back()
		)(
			fn ($item): C\Maybe => C\Maybe::some($item)
		);
	}
}
