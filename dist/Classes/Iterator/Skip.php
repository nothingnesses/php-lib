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
class Skip implements I\Iterator {
	/**
	 * @var I\Iterator<A>
	 */
	private $iterator;
	/**
	 * @use T\Iterator<A>
	 */
	use T\Iterator;

	/**
	 * @param I\Iterator<A> $iterator Iterator to skip the items of.
	 */
	private function __construct(I\Iterator $iterator)
	{
		$this->iterator = $iterator;
	}

	/**
	 * @param int $n The number of items to skip.
	 * @return \Closure(I\Iterator<A>): I\Iterator<A>
	 */
	public static function new($n): \Closure {
		/**
		 * @param I\Iterator<A> $iterator Iterator to skip the items of.
		 */
		return function (I\Iterator $iterator) use ($n): self {
			for ($a = 0; $a < $n; ++$a) {
				$iterator->next();
			}
			return new self($iterator);
		};
	}

	public function next(): C\Maybe {
		return $this->iterator->next();
	}
}
