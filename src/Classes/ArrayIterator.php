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
	use T\DoubleEndedAppendIterator, T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;

	private function __construct(private array $array, private Maybe $key) {
	}

	/**
	 * @param array<A> $array Array to return an iterator of.
	 * @return Self<A>
	 */
	public static function new(array $array): self {
		return new self(
			array: $array,
			key: count($array) > 0
				? Maybe::some(SplFixedArrayIterator::new(\SplFixedArray::fromArray(array_keys($array))))
				: Maybe::none()
		);
	}

	public function next(): Maybe {
		return $this->key->bind(
			fn (SplFixedArrayIterator $key) => $key
				->next()
				->map(function (\SplFixedArray $args) {
					$output = new \SplFixedArray(2);
					$output[0] = $args[1];
					$output[1] = $this->array[$args[1]];
					return $output;
				})
		);
	}

	public function next_back(): Maybe {
		return $this->key->bind(
			fn (SplFixedArrayIterator $key) => $key
				->next_back()
				->map(function (\SplFixedArray $args) {
					$output = new \SplFixedArray(2);
					$output[0] = $args[1];
					$output[1] = $this->array[$args[1]];
					return $output;
				})
		);
	}
}
