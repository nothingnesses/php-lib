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
class Chain implements I\DoubleEnded, I\Iterator {
	/**
	 * @var I\DoubleEnded<A>&I\Iterator<A>
	 */
	private $first;
	/**
	 * @var I\DoubleEnded<A>&I\Iterator<A>
	 */
	private $second;
	/**
	 * @use T\Iterator<A>
	 * @use T\Iterator\DoubleEnded<A>
	 */
	use T\Iterator, T\Iterator\DoubleEnded {
		T\Iterator\DoubleEnded::chain insteadof T\Iterator;
		T\Iterator\DoubleEnded::filter insteadof T\Iterator;
		T\Iterator\DoubleEnded::map insteadof T\Iterator;
	}

	/**
	 * @param I\DoubleEnded<A>&I\Iterator<A> $first First iterator to yield items from.
	 * @param I\DoubleEnded<A>&I\Iterator<A> $second Second iterator to yield items from.
	 */
	private function __construct($first, $second)
	{
		$this->first = $first;
		$this->second = $second;
	}

	/**
	 * @param I\DoubleEnded<A>&I\Iterator<A> $first First iterator to yield items from.
	 * @return \Closure(I\DoubleEnded<A>&I\Iterator<A>): (I\DoubleEnded<A>&I\Iterator<A>)
	 */
	public static function new($first): \Closure {
		/**
		 * @param I\DoubleEnded<A>&I\Iterator<A> $second Second iterator to yields items from.
		 * @return I\DoubleEnded<A>&I\Iterator<A>
		 */
		return function ($second) use ($first) : self {
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

	public function next_back(): C\Maybe {
		return $this->second->next_back()->maybe_lazy(
			function () {
				return $this->first->next_back();
			}
		)(
			function ($item) : C\Maybe {
				return C\Maybe::some($item);
			}
		);
	}
}
