<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator\DoubleEnded;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 * @implements I\DoubleEndedIterator<A>
 */
class StepBy implements I\DoubleEndedIterator {
	/**
	 * @use T\Iterator<A>
	 * @use T\Iterator\DoubleEnded<A>
	 */
	use T\Iterator, T\Iterator\DoubleEnded;

	/**
	 * @param bool $is_first States if this is the first time yielding an item.
	 * @param I\DoubleEndedIterator<A> $iterator Iterator to yield items from.
	 * @param int $step Number of items to skip on every step.
	 */
	private function __construct(private bool $is_first, private I\DoubleEndedIterator $iterator, private int $step) {
	}

	/**
	 * @param int $step Number of items to skip on every step.
	 * @return \Closure(I\DoubleEndedIterator<A>): (I\DoubleEndedIterator<A>)
	 */
	public static function new(int $step): \Closure {
		if ($step < 1) {
			exit("Step < 1");
		}
		/**
		 * @param I\DoubleEndedIterator<A> $iterator Iterator to yield items from.
		 */
		return fn (I\DoubleEndedIterator $iterator): self => new self(
			is_first: true,
			iterator: $iterator,
			step: $step
		);
	}

	public function next(): C\Maybe {
		if ($this->is_first) {
			$this->is_first = false;
			return $this->iterator->next();
		} else {
			return $this->iterator->nth($this->step - 1);
		}
	}

	public function next_back(): C\Maybe {
		if ($this->is_first) {
			$this->is_first = false;
			return $this->iterator->next_back();
		} else {
			return $this->iterator->nth_back($this->step - 1);
		}
	}
}
