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
	/**
	 * @var I\DoubleEndedIterator
	 */
	private $first;
	/**
	 * @var I\DoubleEndedIterator
	 */
	private $second;
	use T\DoubleEndedAppendIterator, T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;

	private function __construct(I\DoubleEndedIterator $first, I\DoubleEndedIterator $second)
	{
		$this->first = $first;
		$this->second = $second;
	}

	/**
	 * @param I\DoubleEndedIterator<A> $first First iterator to yield items from.
	 * @return \Closure(I\DoubleEndedIterator<A>): DoubleEndedAppendIterator<A>
	 */
	public static function new($first): \Closure {
		/**
		 * @param I\DoubleEndedIterator<A> $second Second iterator to yields items from.
		 */
		return function (I\DoubleEndedIterator $second) use ($first) : self {
			return new self(
 			$first,
 			$second
 		);
		};
	}

	public function next(): Maybe {
		return $this->first->next()->maybe_lazy(
			function () {
				return $this->second->next();
			}
		)(
			function ($item) : Maybe {
				return Maybe::some($item);
			}
		);
	}

	public function next_back(): Maybe {
		return $this->second->next_back()->maybe_lazy(
			function () {
				return $this->first->next_back();
			}
		)(
			function ($item) : Maybe {
				return Maybe::some($item);
			}
		);
	}
}
