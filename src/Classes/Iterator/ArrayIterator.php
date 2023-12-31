<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An iterator over items in an array.
 * 
 * @template A of int|string
 * @template V
 * @implements I\DoubleEndedIterator<C\Pair<A,V>>
 */
class ArrayIterator implements I\DoubleEndedIterator {
	/**
	 * @use T\Iterator<C\Pair<A,V>>
	 * @use T\Iterator\DoubleEnded<C\Pair<A,V>>
	 */
	use T\Iterator, T\Iterator\DoubleEnded;

	/**
	 * @param array<A,V> $array Array to return an iterator of.
	 * @param C\Maybe<C\Iterator\SplFixedArray<A>> $key Iterator over the keys.
	 */
	private function __construct(private array $array, private C\Maybe $key) {
	}

	/**
	 * @param array<A,V> $array Array to return an iterator of.
	 * @return C\Iterator\ArrayIterator<A,V>
	 */
	public static function new(array $array): self {
		return new self(
			array: $array,
			key: count($array) > 0
				? C\Maybe::some(C\Iterator\SplFixedArray::new(\SplFixedArray::fromArray(array_keys($array))))
				: C\Maybe::none()
		);
	}

	/**
	 * @return C\Maybe<C\Pair<A,V>>
	 */
	public function next(): C\Maybe {
		return $this->key->bind(
			/**
			 * @param C\Iterator\SplFixedArray<C\Pair<int,A>> $key
			 * @return C\Maybe<C\Pair<A,V>>
			 */
			fn (C\Iterator\SplFixedArray $key): C\Maybe => $key
				->next()
				->map(
					/**
					 * @param C\Pair<int,A> $key
					 * @return A
					 */
					fn (C\Pair $key): int|string => $key->second
				)
				->map(
					/**
					 * @param A $key
					 * @return C\Pair<A,V>
					 */
					fn (int|string $key): C\Pair =>
					C\Pair::new($key)($this->array[$key])
				)
		);
	}

	/**
	 * @return C\Maybe<C\Pair<A,V>>
	 */
	public function next_back(): C\Maybe {
		return $this->key->bind(
			/**
			 * @param C\Iterator\SplFixedArray<C\Pair<int,A>> $key
			 * @return C\Maybe<C\Pair<A,V>>
			 */
			fn (C\Iterator\SplFixedArray $key): C\Maybe => $key
				->next_back()
				->map(
					/**
					 * @param C\Pair<int,A> $key
					 * @return A
					 */
					fn (C\Pair $key): int|string => $key->second
				)
				->map(
					/**
					 * @param A $key
					 * @return C\Pair<A,B>
					 */
					fn (int|string $key): C\Pair =>
					C\Pair::new($key)($this->array[$key])
				)
		);
	}
}
