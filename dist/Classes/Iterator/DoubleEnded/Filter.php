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
class Filter implements I\DoubleEnded, I\Iterator {
	/**
	 * @var I\DoubleEnded<A>&I\Iterator<A>
	 */
	private $iterator;
	/**
	 * @var \Closure(A):bool
	 */
	private $fn;
	/**
	 * @use T\Iterator<A>
	 * @use T\Iterator\DoubleEnded<A>
	 */
	use T\Iterator, T\Iterator\DoubleEnded {
		T\Iterator\DoubleEnded::chain insteadOf T\Iterator;
		T\Iterator\DoubleEnded::filter insteadOf T\Iterator;
		T\Iterator\DoubleEnded::map insteadOf T\Iterator;
		T\Iterator\DoubleEnded::skip insteadOf T\Iterator;
	}

	/**
	 * @param I\DoubleEnded<A>&I\Iterator<A> $iterator Iterator to filter the items of.
	 * @param \Closure(A): bool $fn Function applied to the items being iterated over to filter those that match a condition.
	 */
	private function __construct($iterator, \Closure $fn)
	{
		$this->iterator = $iterator;
		$this->fn = $fn;
	}

	/**
	 * @param callable(A): bool $fn Function applied to the items being iterated over to filter those that match a condition.
	 * @return \Closure(I\DoubleEnded<A>&I\Iterator<A>): C\Iterator\DoubleEnded\Filter<A>
	 */
	public static function new($fn): \Closure {
		/**
		 * @param I\DoubleEnded<A>&I\Iterator<A> $iterator Iterator to filter the items of.
		 */
		$callable = $fn;
		/**
		 * @param I\DoubleEnded<A>&I\Iterator<A> $iterator Iterator to filter the items of.
		 */
		return function ($iterator) use ($callable) : self {
			return new self($iterator, function () use ($callable) {
				return $callable(...func_get_args());
			});
		};
	}

	public function next(): C\Maybe {
		return $this->iterator->find($this->fn);
	}

	public function next_back(): C\Maybe {
		return $this->iterator->reverse_find($this->fn);
	}
}
