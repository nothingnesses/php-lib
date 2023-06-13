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
	use T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;
	/**
	 * @var mixed[]
	 */
	private $array;
	/**
	 * @var \Nothingnesses\Lib\Classes\RangeIterator
	 */
	private $index;

	private function __construct(array $array, RangeIterator $index)
	{
		$this->array = $array;
		$this->index = $index;
	}

	/**
	 * @param array<A> $array Array to return an iterator of.
	 * @return Self<A>
	 */
	public static function new($array): self {
		return new self($array, Range::new(0)(count($array) > 0 ? count($array) - 1 : 0)->iterate());
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

/**
 * @template A
 */
class FilterIterator implements I\FilterIterator, I\Iterator {
	use T\FilterIterator, T\Iterator, T\MapIterator;
	/**
	 * @var I\Iterator
	 */
	private $iterator;
	/**
	 * @var \Closure
	 */
	private $predicate;

	private function __construct(I\Iterator $iterator, \Closure $predicate)
	{
		$this->iterator = $iterator;
		$this->predicate = $predicate;
	}

	/**
	 * @param callable(A): bool $predicate Function applied to the items being iterated over to filter those that match a condition.
	 * @return \Closure(I\Iterator): FilterIterator<A>
	 */
	public static function new($predicate): \Closure {
		/**
		 * @param I\Iterator $iterator Iterator to filter.
		 */
		return function (I\Iterator $iterator) use ($predicate) : self {
			return new self(
 			$iterator,
				\Closure::fromCallable($predicate)
 		);
		};
	}

	public function next(): Maybe {
		return $this->iterator->find($this->predicate);
	}
}

/**
 * @template A
 */
class DoubleEndedFilterIterator implements I\DoubleEndedIterator, I\FilterIterator {
	use T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;
	/**
	 * @var I\DoubleEndedIterator
	 */
	private $iterator;
	/**
	 * @var \Closure
	 */
	private $predicate;

	private function __construct(I\DoubleEndedIterator $iterator, \Closure $predicate)
	{
		$this->iterator = $iterator;
		$this->predicate = $predicate;
	}

	/**
	 * @param callable(A): bool $predicate Function applied to the items being iterated over to filter those that match a condition.
	 * @return \Closure(I\Iterator): DoubleEndedFilterIterator<A>
	 */
	public static function new($predicate): \Closure {
		/**
		 * @param I\Iterator $iterator Iterator to filter.
		 */
		return function (I\Iterator $iterator) use ($predicate) : self {
			return new self(
 			$iterator,
				\Closure::fromCallable($predicate)
 		);
		};
	}

	public function next(): Maybe {
		return $this->iterator->find($this->predicate);
	}

	public function next_back(): Maybe {
		return $this->iterator->reverse_find($this->predicate);
	}
}

/**
 * @template A
 */
class DoubleEndedMapIterator implements I\DoubleEndedIterator, I\MapIterator {
	/**
	 * @var I\DoubleEndedIterator
	 */
	private $iterator;
	/**
	 * @var \Closure
	 */
	private $mapper;
	private function __construct(I\DoubleEndedIterator $iterator, \Closure $mapper)
	{
		$this->iterator = $iterator;
		$this->mapper = $mapper;
	}

	/**
	 * @template B
	 * @param callable(A): B $mapper Function applied to the items being iterated over.
	 * @return \Closure(I\Iterator): DoubleEndedMapIterator<A>
	 */
	public static function new($mapper): \Closure {
		/**
		 * @param I\Iterator $iterator Iterator to map.
		 */
		return function (I\Iterator $iterator) use ($mapper) : self {
			return new self(
 			$iterator,
				\Closure::fromCallable($mapper)
 		);
		};
	}

	public function next(): Maybe {
		return $this->iterator->next()->map($this->mapper);
	}

	public function next_back(): Maybe {
		return $this->iterator->next_back()->map($this->mapper);
	}

	use T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;
}

/**
 * @template A
 */
class MapIterator implements I\Iterator, I\MapIterator {
	use T\FilterIterator, T\Iterator, T\MapIterator;
	/**
	 * @var I\Iterator
	 */
	private $iterator;
	/**
	 * @var \Closure
	 */
	private $mapper;

	private function __construct(I\Iterator $iterator, \Closure $mapper)
	{
		$this->iterator = $iterator;
		$this->mapper = $mapper;
	}

	/**
	 * @template B
	 * @param callable(A): B $mapper Function applied to the items being iterated over.
	 * @return \Closure(I\Iterator): MapIterator<A>
	 */
	public static function new($mapper): \Closure {
		/**
		 * @param I\Iterator $iterator Iterator to map.
		 */
		return function (I\Iterator $iterator) use ($mapper) : self {
			return new self(
 			$iterator,
				\Closure::fromCallable($mapper)
 		);
		};
	}

	public function next(): Maybe {
		return $this->iterator->next()->map($this->mapper);
	}
}

/**
 * An inclusive range.
 * 
 * @template A
 */
class ReversedIterator implements I\DoubleEndedIterator {
	use T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;
	/**
	 * @var I\DoubleEndedIterator
	 */
	private $iterator;

	private function __construct(I\DoubleEndedIterator $iterator)
	{
		$this->iterator = $iterator;
	}

	/**
	 * @param I\DoubleEndedIterator<A> $iterator Iterator to return a reversed version of.
	 * @return ReversedIterator<A>
	 */
	public static function new($iterator): self {
		return new self($iterator);
	}

	public function next(): Maybe {
		return $this->iterator->next_back();
	}

	public function next_back(): Maybe {
		return $this->iterator->next();
	}
}

class Range {
	/**
	 * @var int
	 */
	private $start;
	/**
	 * @var int
	 */
	private $end;
	private function __construct(int $start, int $end)
	{
		$this->start = $start;
		$this->end = $end;
	}

	/**
	 * @return RangeIterator An iterator over the range.
	 */
	public function iterate(): RangeIterator {
		return RangeIterator::new($this->start)($this->end);
	}

	/**
	 * @param int $start The value the Range starts from.
	 * @return \Closure(int): Self
	 */
	public static function new($start): \Closure {
		/**
		 * @param int $end The value the Range ends at.
		 * @return Self
		 */
		return function (int $end) use ($start) : self {
			return new self($start, $end);
		};
	}
}

/** An iterator over a range. */
class RangeIterator implements I\DoubleEndedIterator {
	use T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;

	/**
	 * @var bool
	 */
	private $is_increasing;
	/**
	 * @var int
	 */
	private $front;
	/**
	 * @var int
	 */
	private $back;
	/**
	 * @var bool
	 */
	private $is_finished;

	private function __construct(
		int $front,
		int $back,
		bool $is_finished
	) {
		$this->front = $front;
		$this->back = $back;
		$this->is_finished = $is_finished;
		$this->is_increasing = $back - $front >= 0;
	}

	/**
	 * @param int $start The value the RangeIterator will start from.
	 * @return \Closure(int): Self
	 */
	public static function new($start): \Closure {
		/**
		 * @param int $end The value the RangeIterator will end at.
		 * @return Self
		 */
		return function (int $end) use ($start) : self {
			return new self($start, $end, false);
		};
	}

	public function next(): Maybe {
		if ($this->is_finished) {
			return Maybe::none();
		} else {
			if ($this->is_increasing) {
				if ($this->front <= $this->back) {
					++$this->front;
					return Maybe::some($this->front - 1);
				}
			} else {
				if ($this->front >= $this->back) {
					--$this->front;
					return Maybe::some($this->front + 1);
				}
			}
			$this->is_finished = true;
			return Maybe::none();
		}
	}

	public function next_back(): Maybe {
		if ($this->is_finished) {
			return Maybe::none();
		} else {
			if ($this->is_increasing) {
				if ($this->front <= $this->back) {
					--$this->back;
					return Maybe::some($this->back + 1);
				}
			} else {
				if ($this->front >= $this->back) {
					++$this->back;
					return Maybe::some($this->back - 1);
				}
			}
			$this->is_finished = true;
			return Maybe::none();
		}
	}
}
