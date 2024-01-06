<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An iterator over items of another iterator, in reverse.
 * 
 * @template A
 * @implements I\DoubleEndedIterator<A>
 */
class Reverse implements I\DoubleEndedIterator {
	/**
	 * @var I\DoubleEndedIterator<A>
	 */
	private $iterator;
	/**
	 * @use T\Iterator<A>
	 * @use T\Iterator\DoubleEnded<A>
	 */
	use T\Iterator, T\Iterator\DoubleEnded;

	/**
	 * @param I\DoubleEndedIterator<A> $iterator The iterator to yield items of in reverse.
	 */
	private function __construct(I\DoubleEndedIterator $iterator)
	{
		$this->iterator = $iterator;
	}

	/**
	 * @param I\DoubleEndedIterator<A> $iterator Iterator to return a reversed version of.
	 * @return C\Iterator\Reverse<A>
	 */
	public static function new($iterator): self {
		return new self($iterator);
	}

	public function next(): C\Maybe {
		return $this->iterator->next_back();
	}

	public function next_back(): C\Maybe {
		return $this->iterator->next();
	}
}
