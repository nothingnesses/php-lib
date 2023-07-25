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
 * @implements I\DoubleEnded<C\Pair<A,V>>
 * @implements I\Iterator<C\Pair<A,V>>
 */
class ArrayIterator implements I\DoubleEnded, I\Iterator {
	/**
	 * @var array<A, V>
	 */
	private $array;
	/**
	 * @var C\Maybe<C\Iterator\SplFixedArray<A>>
	 */
	private $key;
	/**
	 * @use T\Iterator<C\Pair<A,V>>
	 * @use T\Iterator\DoubleEnded<C\Pair<A,V>>
	 */
	use T\Iterator, T\Iterator\DoubleEnded {
		T\Iterator\DoubleEnded::chain insteadOf T\Iterator;
		T\Iterator\DoubleEnded::filter insteadOf T\Iterator;
		T\Iterator\DoubleEnded::map insteadOf T\Iterator;
		T\Iterator\DoubleEnded::skip insteadOf T\Iterator;
		T\Iterator\DoubleEnded::step_by insteadOf T\Iterator;
	}

	/**
	 * @param array<A,V> $array Array to return an iterator of.
	 * @param C\Maybe<C\Iterator\SplFixedArray<A>> $key Iterator over the keys.
	 */
	private function __construct(array $array, C\Maybe $key)
	{
		$this->array = $array;
		$this->key = $key;
	}

	/**
	 * @param array<A,V> $array Array to return an iterator of.
	 * @return C\Iterator\ArrayIterator<A,V>
	 */
	public static function new($array): self {
		return new self(
			$array,
			count($array) > 0
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
			function (C\Iterator\SplFixedArray $key) : C\Maybe {
				return $key
 				->next()
 				->map(
 					/**
 					 * @param C\Pair<int,A> $key
 					 * @return A
 					 */
 					function (C\Pair $key) {
							return $key->second;
						}
 				)
 				->map(
 					/**
 					 * @param A $key
 					 * @return C\Pair<A,V>
 					 */
 					function ($key) : C\Pair {
							return C\Pair::new($key)($this->array[$key]);
						}
 				);
			}
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
			function (C\Iterator\SplFixedArray $key) : C\Maybe {
				return $key
 				->next_back()
 				->map(
 					/**
 					 * @param C\Pair<int,A> $key
 					 * @return A
 					 */
 					function (C\Pair $key) {
							return $key->second;
						}
 				)
 				->map(
 					/**
 					 * @param A $key
 					 * @return C\Pair<A,B>
 					 */
 					function ($key) : C\Pair {
							return C\Pair::new($key)($this->array[$key]);
						}
 				);
			}
		);
	}
}
