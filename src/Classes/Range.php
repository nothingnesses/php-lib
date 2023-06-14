<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An inclusive range.
 * 
 * @template A
 */
class Range {
	private function __construct(private int $start, private int $end) {
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
	public static function new(int $start): \Closure {
		/**
		 * @param int $end The value the Range ends at.
		 * @return Self
		 */
		return fn (int $end): self => new self(
			start: $start,
			end: $end
		);
	}
}
