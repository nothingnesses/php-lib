<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 * @implements I\Iterator<A>
 */
class StepBy implements I\Iterator {
	/**
	 * @var bool
	 */
	private $is_first;
	/**
	 * @var I\Iterator<A>
	 */
	private $iterator;
	/**
	 * @var int
	 */
	private $step;
	/**
	 * @use T\Iterator<A>
	 */
	use T\Iterator;

	/**
	 * @param bool $is_first States if this is the first time yielding an item.
	 * @param I\Iterator<A> $iterator Iterator to yield items from.
	 * @param int $step Number of items to skip on every step.
	 */
	private function __construct(bool $is_first, I\Iterator $iterator, int $step)
	{
		$this->is_first = $is_first;
		$this->iterator = $iterator;
		$this->step = $step;
	}

	/**
	 * @param int $step Number of items to skip on every step.
	 * @return \Closure(I\Iterator<A>): I\Iterator<A>
	 */
	public static function new($step): \Closure {
		if ($step < 1) {
			exit("Step < 1");
		}
		/**
		 * @param I\Iterator<A> $iterator Iterator to yield items from.
		 */
		return function (I\Iterator $iterator) use ($step) : self {
			return new self(
 			true,
 			$iterator,
 			$step
 		);
		};
	}

	public function next(): C\Maybe {
		if ($this->is_first) {
			$this->is_first = false;
			return $this->iterator->next();
		} else {
			return $this->iterator->nth($this->step - 1);
		}
	}
}
