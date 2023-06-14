<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 */
class DoubleEndedFilterIterator implements I\DoubleEndedIterator, I\FilterIterator {
	use T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;
	/**
	 * @var I\DoubleEndedIterator
	 */
	private $iterator;
	/**
	 * @var \Closure
	 */
	private $predicate;

	private function __construct(I\DoubleEndedIterator $iterator, \Closure $predicate)
	{
		$this->iterator = $iterator;
		$this->predicate = $predicate;
	}

	/**
	 * @param callable(A): bool $predicate Function applied to the items being iterated over to filter those that match a condition.
	 * @return \Closure(I\Iterator): DoubleEndedFilterIterator<A>
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

	public function next_back(): Maybe {
		return $this->iterator->reverse_find($this->predicate);
	}
}
