<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 */
class AppendIterator implements I\AppendIterator, I\Iterator {
	use T\AppendIterator, T\FilterIterator, T\Iterator, T\MapIterator;

	private function __construct(private I\Iterator $first, private I\Iterator $second) {
	}

	/**
	 * @param I\Iterator<A> $first First iterator to yield items from.
	 * @return \Closure(I\Iterator<A>): AppendIterator<A>
	 */
	public static function new(I\Iterator $first): \Closure {
		/**
		 * @param I\Iterator<A> $second Second iterator to yields items from.
		 */
		return fn (I\Iterator $second): self => new self(
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
}
