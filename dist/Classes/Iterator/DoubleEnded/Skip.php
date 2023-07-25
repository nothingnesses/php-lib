<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator\DoubleEnded;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 * @implements I\DoubleEnded<A>
 * @implements I\Iterator<A>
 */
class Skip implements I\DoubleEnded, I\Iterator {
	/**
	 * @var I\DoubleEnded<A>&I\Iterator<A>
	 */
	private $iterator;
	/**
	 * @use T\Iterator<A>
	 * @use T\Iterator\DoubleEnded<A>
	 */
	use T\Iterator, T\Iterator\DoubleEnded {
		T\Iterator\DoubleEnded::chain insteadOf T\Iterator;
		T\Iterator\DoubleEnded::filter insteadOf T\Iterator;
		T\Iterator\DoubleEnded::map insteadOf T\Iterator;
		T\Iterator\DoubleEnded::skip insteadOf T\Iterator;
		T\Iterator\DoubleEnded::step_by insteadOf T\Iterator;
	}

	/**
	 * @param I\DoubleEnded<A>&I\Iterator<A> $iterator Iterator to skip the items of.
	 */
	private function __construct($iterator)
	{
		$this->iterator = $iterator;
	}

	/**
	 * @param int $n The number of items to skip.
	 * @return \Closure(I\DoubleEnded<A>&I\Iterator<A>): (I\DoubleEnded<A>&I\Iterator<A>)
	 */
	public static function new($n): \Closure {
		/**
		 * @param I\DoubleEnded<A>&I\Iterator<A> $iterator Iterator to skip the items of.
		 */
		return function ($iterator) use ($n): self {
			for ($a = 0; $a < $n; ++$a) {
				$iterator->next();
			}
			return new self($iterator);
		};
	}

	public function next(): C\Maybe {
		return $this->iterator->next();
	}

	public function next_back(): C\Maybe {
		return $this->iterator->next_back();
	}
}
