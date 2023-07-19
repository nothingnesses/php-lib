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
class Chain implements I\Iterator {
	/**
	 * @var I\Iterator<A>
	 */
	private $first;
	/**
	 * @var I\Iterator<A>
	 */
	private $second;
	/**
	 * @use T\Iterator<A>
	 */
	use T\Iterator;

	/**
	 * @param I\Iterator<A> $first First iterator to yield items from.
	 * @param I\Iterator<A> $second Second iterator to yield items from.
	 */
	private function __construct(I\Iterator $first, I\Iterator $second)
	{
		$this->first = $first;
		$this->second = $second;
	}

	/**
	 * @param I\Iterator<A> $first First iterator to yield items from.
	 * @return \Closure(I\Iterator<A>): I\Iterator<A>
	 */
	public static function new($first): \Closure {
		/**
		 * @param I\Iterator<A> $second Second iterator to yields items from.
		 */
		return function (I\Iterator $second) use ($first) : self {
			return new self(
 			$first,
 			$second
 		);
		};
	}

	public function next(): C\Maybe {
		return $this->first->next()->maybe_lazy(
			function () {
				return $this->second->next();
			}
		)(
			function ($item) : C\Maybe {
				return C\Maybe::some($item);
			}
		);
	}
}
