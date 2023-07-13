<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An iterator over items in an array.
 * 
 * @template A
 */
class SplFixedArrayIterator implements I\DoubleEndedIterator {
	/**
	 * @var \SplFixedArray
	 */
	private $array;
	/**
	 * @var \Nothingnesses\Lib\Classes\Maybe
	 */
	private $index;
	use T\DoubleEndedAppendIterator, T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;

	private function __construct(\SplFixedArray $array, Maybe $index)
	{
		$this->array = $array;
		$this->index = $index;
	}

	/**
	 * @param \SplFixedArray<A> $array Array to return an iterator of.
	 * @return Self<A>
	 */
	public static function new($array): self {
		return new self(
			$array,
			count($array) > 0
				? Maybe::some(Range::new(0)(count($array) - 1)->iterate())
				: Maybe::none()
		);
	}

	public function next(): Maybe {
		return $this->index->bind(
			function ($index) {
				return $index
 				->next()
 				->map(function ($index) {
 					$output = new \SplFixedArray(2);
 					$output[0] = $index;
 					$output[1] = $this->array[$index];
 					return $output;
 				});
			}
		);
	}

	public function next_back(): Maybe {
		return $this->index->bind(
			function ($index) {
				return $index
 				->next_back()
 				->map(function ($index) {
 					$output = new \SplFixedArray(2);
 					$output[0] = $index;
 					$output[1] = $this->array[$index];
 					return $output;
 				});
			}
		);
	}
}
