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
class SkipWhile implements I\Iterator {
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
	 * @param callable(A): bool $fn The function applied to the items yielded to test if they match a condition.
	 * @return \Closure(I\Iterator<A>): I\Iterator<A>
	 */
	public static function new($fn): \Closure {
		/**
		 * @param I\Iterator<A> $iterator Iterator to skip the items of.
		 */
		return function (I\Iterator $iterator) use ($fn): self {
			$true = C\Maybe::some(true);
			$current = $iterator->next();
			while ($current->map($fn) === $true) {
				$current = $iterator->next();
			}
			if (!$current->is_some()) {
				return new self($iterator);
			}
			// @todo: Fix the types here, they're pretty fucked.
			$a = null;
			$current->map(function ($item) use (&$a) {
				$a = $item;
			});
			return new self(
				C\Iterator\DoubleEnded\Once::new($a)->chain($iterator)
			);
		};
	}

	public function next(): C\Maybe {
		return $this->iterator->next();
	}
}
