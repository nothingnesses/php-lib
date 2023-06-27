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
	 * @var \Nothingnesses\Lib\Classes\RangeIterator
	 */
	private $index;
	use T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;

	private function __construct(\SplFixedArray $array, RangeIterator $index)
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
			Range::new(0)(count($array) > 0 ? count($array) - 1 : 0)->iterate()
		);
	}

	public function next(): Maybe {
		return count($this->array) > 0
			? $this->index
			->next()
			->map(function (int $index) {
				return $this->array[$index];
			})
			: Maybe::none();
	}

	public function next_back(): Maybe {
		return count($this->array) > 0
			? $this->index
			->next_back()
			->map(function (int $index) {
				return $this->array[$index];
			})
			: Maybe::none();
	}
}
