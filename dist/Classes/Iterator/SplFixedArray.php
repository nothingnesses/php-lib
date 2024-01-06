<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An iterator over items in a fixed array.
 * 
 * @template A
 * @implements I\DoubleEndedIterator<C\Pair<int,A>>
 */
class SplFixedArray implements I\DoubleEndedIterator {
	/**
	 * @var \SplFixedArray<A>
	 */
	private $array;
	/**
	 * @var C\Maybe<C\Iterator\Range>
	 */
	private $index;
	/**
	 * @use T\Iterator<C\Pair<int,A>>
	 * @use T\Iterator\DoubleEnded<C\Pair<int,A>>
	 */
	use T\Iterator, T\Iterator\DoubleEnded;

	/**
	 * @param \SplFixedArray<A> $array Array to return an iterator of.
	 * @param C\Maybe<C\Iterator\Range> $index Iterator over the indices.
	 */
	private function __construct(\SplFixedArray $array, C\Maybe $index)
	{
		$this->array = $array;
		$this->index = $index;
	}

	/**
	 * @param \SplFixedArray<A> $array Array to return an iterator of.
	 * @return C\Iterator\SplFixedArray<A>
	 */
	public static function new($array): self {
		return new self(
			$array,
			count($array) > 0
				? C\Maybe::some(C\Range::new(0)(count($array) - 1)->iterate())
				: C\Maybe::none()
		);
	}

	/**
	 * @return C\Maybe<C\Pair<int,A>>
	 */
	public function next(): C\Maybe {
		return $this->index->bind(
			/**
			 * @param C\Iterator\Range $index
			 * @return C\Maybe<C\Pair<int,A>>
			 */
			function (C\Iterator\Range $index) {
				return $index
 				->next()
 				->map(
 					/**
 					 * @param int $index
 					 * @return C\Pair<int,A>
 					 */
 					function (int $index) : C\Pair {
							return C\Pair::new($index)($this->array[$index]);
						}
 				);
			}
		);
	}

	/**
	 * @return C\Maybe<C\Pair<int,A>>
	 */
	public function next_back(): C\Maybe {
		return $this->index->bind(
			/**
			 * @param C\Iterator\Range $index
			 * @return C\Maybe<C\Pair<int,A>>
			 */
			function (C\Iterator\Range $index) {
				return $index
 				->next_back()
 				->map(
 					/**
 					 * @param int $index
 					 * @return C\Pair<int,A>
 					 */
 					function (int $index) : C\Pair {
							return C\Pair::new($index)($this->array[$index]);
						}
 				);
			}
		);
	}
}
