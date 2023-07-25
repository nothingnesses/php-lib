<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An iterator over a range.
 * @implements I\DoubleEnded<int>
 * @implements I\Iterator<int>
 */
class Range implements I\DoubleEnded, I\Iterator {
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
	/**
	 * @use T\Iterator<int>
	 * @use T\Iterator\DoubleEnded<int>
	 */
	use T\Iterator, T\Iterator\DoubleEnded {
		T\Iterator\DoubleEnded::chain insteadOf T\Iterator;
		T\Iterator\DoubleEnded::filter insteadOf T\Iterator;
		T\Iterator\DoubleEnded::map insteadOf T\Iterator;
		T\Iterator\DoubleEnded::skip insteadOf T\Iterator;
	}

	/**
	 * @var bool
	 */
	private $is_increasing;

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
	 * @return \Closure(int): C\Iterator\Range
	 */
	public static function new($start): \Closure {
		/**
		 * @param int $end The value the RangeIterator will end at.
		 * @return C\Iterator\Range
		 */
		return function (int $end) use ($start) : self {
			return new self(
 			$start,
 			$end,
 			false
 		);
		};
	}

	/**
	 * @return C\Maybe<int>
	 */
	public function next(): C\Maybe {
		if ($this->is_finished) {
			return C\Maybe::none();
		} else {
			if ($this->is_increasing) {
				if ($this->front <= $this->back) {
					++$this->front;
					return C\Maybe::some($this->front - 1);
				}
			} else {
				if ($this->front >= $this->back) {
					--$this->front;
					return C\Maybe::some($this->front + 1);
				}
			}
			$this->is_finished = true;
			return C\Maybe::none();
		}
	}

	/**
	 * @return C\Maybe<int>
	 */
	public function next_back(): C\Maybe {
		if ($this->is_finished) {
			return C\Maybe::none();
		} else {
			if ($this->is_increasing) {
				if ($this->front <= $this->back) {
					--$this->back;
					return C\Maybe::some($this->back + 1);
				}
			} else {
				if ($this->front >= $this->back) {
					++$this->back;
					return C\Maybe::some($this->back - 1);
				}
			}
			$this->is_finished = true;
			return C\Maybe::none();
		}
	}
}
