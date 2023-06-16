<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * An iterator over items of another iterator, in reverse.
 * 
 * @template A
 */
class ReversedIterator implements I\DoubleEndedIterator {
	/**
	 * @var I\DoubleEndedIterator
	 */
	private $iterator;
	use T\DoubleEndedIterator, T\DoubleEndedFilterIterator, T\DoubleEndedMapIterator, T\Iterator;

	private function __construct(I\DoubleEndedIterator $iterator)
	{
		$this->iterator = $iterator;
	}

	/**
	 * @param I\DoubleEndedIterator<A> $iterator Iterator to return a reversed version of.
	 * @return ReversedIterator<A>
	 */
	public static function new($iterator): self {
		return new self($iterator);
	}

	public function next(): Maybe {
		return $this->iterator->next_back();
	}

	public function next_back(): Maybe {
		return $this->iterator->next();
	}
}
