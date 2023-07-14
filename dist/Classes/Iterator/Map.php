<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 * @implements I\Iterator<A>
 */
class Map implements I\Iterator {
	/**
	 * @var I\Iterator<B>
	 */
	private $iterator;
	/**
	 * @var \Closure(B):A
	 */
	private $fn;
	/**
	 * @use T\Iterator<A>
	 */
	use T\Iterator;

	/**
	 * @template B
	 * @param I\Iterator<B> $iterator Iterator to map the items of.
	 * @param \Closure(B): A $fn Function applied to the items yielded.
	 */
	private function __construct(I\Iterator $iterator, \Closure $fn)
	{
		$this->iterator = $iterator;
		$this->fn = $fn;
	}

	/**
	 * @template B
	 * @param callable(B): A $fn Function applied to the items being iterated over.
	 * @return \Closure(I\Iterator<B>): I\Iterator<A>
	 */
	public static function new($fn): \Closure {
		/**
		 * @param I\Iterator<B> $iterator Iterator to map.
		 * @return I\Iterator<A>
		 */
		$callable = $fn;
		/**
		 * @param I\Iterator<B> $iterator Iterator to map.
		 * @return I\Iterator<A>
		 */
		return function (I\Iterator $iterator) use ($callable) : self {
			return new self($iterator, function () use ($callable) {
				return $callable(...func_get_args());
			});
		};
	}

	/**
	 * @return C\Maybe<A>
	 */
	public function next(): C\Maybe {
		return $this->iterator->next()->map($this->fn);
	}
}
