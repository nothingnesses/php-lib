<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An inclusive range.
 */
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
	 * @return C\Iterator\Range An iterator over the range.
	 */
	public function iterate(): C\Iterator\Range {
		return C\Iterator\Range::new($this->start)($this->end);
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
			return new self(
 			$start,
 			$end
 		);
		};
	}
}
