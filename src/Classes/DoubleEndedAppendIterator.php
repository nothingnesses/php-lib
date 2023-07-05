<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 */
class DoubleEndedAppendIterator implements I\AppendIterator, I\DoubleEndedIterator {
	use T\DoubleEndedAppendIterator, T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;

	private function __construct(private I\DoubleEndedIterator $first, private I\DoubleEndedIterator $second) {
	}

	/**
	 * @param I\DoubleEndedIterator<A> $first First iterator to yield items from.
	 * @return \Closure(I\DoubleEndedIterator<A>): DoubleEndedAppendIterator<A>
	 */
	public static function new(I\DoubleEndedIterator $first): \Closure {
		/**
		 * @param I\DoubleEndedIterator<A> $second Second iterator to yields items from.
		 */
		return fn (I\DoubleEndedIterator $second): self => new self(
			first: $first,
			second: $second
		);
	}

	public function next(): Maybe {
		return $this->first->next()->maybe_lazy(
			fn () => $this->second->next()
		)(
			fn ($item): Maybe => Maybe::some($item)
		);
	}

	public function next_back(): Maybe {
		return $this->second->next_back()->maybe_lazy(
			fn () => $this->first->next_back()
		)(
			fn ($item): Maybe => Maybe::some($item)
		);
	}
}
