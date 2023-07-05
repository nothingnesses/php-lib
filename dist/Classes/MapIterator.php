<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 */
class MapIterator implements I\Iterator, I\MapIterator {
	/**
	 * @var I\Iterator
	 */
	private $iterator;
	/**
	 * @var \Closure
	 */
	private $mapper;
	use T\AppendIterator, T\FilterIterator, T\Iterator, T\MapIterator;

	private function __construct(I\Iterator $iterator, \Closure $mapper)
	{
		$this->iterator = $iterator;
		$this->mapper = $mapper;
	}

	/**
	 * @template B
	 * @param callable(A): B $mapper Function applied to the items being iterated over.
	 * @return \Closure(I\Iterator): MapIterator<A>
	 */
	public static function new($mapper): \Closure {
		/**
		 * @param I\Iterator $iterator Iterator to map.
		 */
		$callable = $mapper;
		/**
		 * @param I\Iterator $iterator Iterator to map.
		 */
		return function (I\Iterator $iterator) use ($callable) : self {
			return new self($iterator, function () use ($callable) {
				return $callable(...func_get_args());
			});
		};
	}

	public function next(): Maybe {
		return $this->iterator->next()->map($this->mapper);
	}
}
