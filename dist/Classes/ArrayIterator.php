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
class ArrayIterator implements I\DoubleEndedIterator {
	/**
	 * @var mixed[]
	 */
	private $array;
	/**
	 * @var \Nothingnesses\Lib\Classes\SplFixedArrayIterator
	 */
	private $key;
	use T\DoubleEndedAppendIterator, T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;

	private function __construct(array $array, SplFixedArrayIterator $key)
	{
		$this->array = $array;
		$this->key = $key;
	}

	/**
	 * @param array<A> $array Array to return an iterator of.
	 * @return Self<A>
	 */
	public static function new($array): self {
		return new self(
			$array,
			SplFixedArrayIterator::new(\SplFixedArray::fromArray(array_keys($array)))
		);
	}

	public function next(): Maybe {
		return count($this->array) > 0
			? $this->key
			->next()
			->map(function ($key) {
				return $this->array[$key];
			})
			: Maybe::none();
	}

	public function next_back(): Maybe {
		return count($this->array) > 0
			? $this->key
			->next_back()
			->map(function ($key) {
				return $this->array[$key];
			})
			: Maybe::none();
	}
}
