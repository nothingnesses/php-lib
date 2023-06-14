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
	use T\FilterIterator, T\Iterator, T\MapIterator;
	/**
	 * @var I\Iterator
	 */
	private $iterator;
	/**
	 * @var \Closure
	 */
	private $predicate;

	private function __construct(I\Iterator $iterator, \Closure $predicate)
	{
		$this->iterator = $iterator;
		$this->predicate = $predicate;
	}

	/**
	 * @param callable(A): bool $predicate Function applied to the items being iterated over to filter those that match a condition.
	 * @return \Closure(I\Iterator): FilterIterator<A>
	 */
	public static function new($predicate): \Closure {
		/**
		 * @param I\Iterator $iterator Iterator to filter.
		 */
		return function (I\Iterator $iterator) use ($predicate) : self {
			return new self(
 			$iterator,
				\Closure::fromCallable($predicate)
 		);
		};
	}

	public function next(): Maybe {
		return $this->iterator->find($this->predicate);
	}
}