<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An iterator that yields items only as long they satisfy a predicate function.
 * 
 * @template A
 */
class TakeWhileIterator implements I\Iterator {
	/**
	 * @var I\Iterator
	 */
	private $iterator;
	/**
	 * @var \Closure
	 */
	private $predicate;
	/**
	 * @var bool
	 */
	private $is_finished;
	use T\AppendIterator, T\FilterIterator, T\Iterator, T\MapIterator;

	private function __construct(I\Iterator $iterator, \Closure $predicate, bool $is_finished)
	{
		$this->iterator = $iterator;
		$this->predicate = $predicate;
		$this->is_finished = $is_finished;
	}

	/**
	 * @param callable(A): bool $predicate Function applied to the items being iterated over to filter those that match a condition.
	 * @return \Closure(I\Iterator): TakeWhileIterator<A>
	 */
	public static function new($predicate): \Closure {
		/**
		 * @param I\Iterator $iterator Iterator to take items from.
		 */
		$callable = $predicate;
		/**
		 * @param I\Iterator $iterator Iterator to take items from.
		 */
		return function (I\Iterator $iterator) use ($callable) : self {
			return new self(
 			$iterator,
				function () use ($callable) {
					return $callable(...func_get_args());
				},
 			false
 		);
		};
	}

	public function next(): Maybe {
		if ($this->is_finished) return Maybe::none();
		return $this->iterator->next()->bind(function ($item) {
			$predicate = $this->predicate;
			if (!$predicate($item)) {
				$this->is_finished = true;
				return Maybe::none();
			}
			return Maybe::some($item);
		});
	}
}