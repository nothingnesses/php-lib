<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An iterator over a range.
 */
class RangeIterator implements I\DoubleEndedIterator {
	use T\DoubleEndedAppendIterator, T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;

	private bool $is_increasing;

	private function __construct(
		private int $front,
		private int $back,
		private bool $is_finished
	) {
		$this->is_increasing = $back - $front >= 0;
	}

	/**
	 * @param int $start The value the RangeIterator will start from.
	 * @return \Closure(int): Self
	 */
	public static function new(int $start): \Closure {
		/**
		 * @param int $end The value the RangeIterator will end at.
		 * @return Self
		 */
		return fn (int $end): self => new self(
			front: $start,
			back: $end,
			is_finished: false
		);
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
