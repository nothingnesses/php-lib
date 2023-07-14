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
 * @implements I\DoubleEnded<C\Pair<int,A>>
 * @implements I\Iterator<C\Pair<int,A>>
 */
class SplFixedArray implements I\DoubleEnded, I\Iterator {
	/**
	 * @use T\Iterator<C\Pair<int,A>>
	 * @use T\Iterator\DoubleEnded<C\Pair<int,A>>
	 */
	use T\Iterator, T\Iterator\DoubleEnded {
		T\Iterator\DoubleEnded::chain insteadOf T\Iterator;
		T\Iterator\DoubleEnded::filter insteadOf T\Iterator;
		T\Iterator\DoubleEnded::map insteadOf T\Iterator;
	}

	/**
	 * @param \SplFixedArray<A> $array Array to return an iterator of.
	 * @param C\Maybe<C\Iterator\Range> $index Iterator over the indices.
	 */
	private function __construct(private \SplFixedArray $array, private C\Maybe $index) {
	}

	/**
	 * @param \SplFixedArray<A> $array Array to return an iterator of.
	 * @return C\Iterator\SplFixedArray<A>
	 */
	public static function new(\SplFixedArray $array): self {
		return new self(
			array: $array,
			index: count($array) > 0
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
			fn (C\Iterator\Range $index) => $index
				->next()
				->map(
					/**
					 * @param int $index
					 * @return C\Pair<int,A>
					 */
					fn (int $index): C\Pair =>
					C\Pair::new($index)($this->array[$index])
				)
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
			fn (C\Iterator\Range $index) => $index
				->next_back()
				->map(
					/**
					 * @param int $index
					 * @return C\Pair<int,A>
					 */
					fn (int $index): C\Pair =>
					C\Pair::new($index)($this->array[$index])
				)
		);
	}
}
