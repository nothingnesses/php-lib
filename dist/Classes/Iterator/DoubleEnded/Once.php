<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Classes\Iterator\DoubleEnded;

use Nothingnesses\Lib\Classes as C;
use Nothingnesses\Lib\Classes\Maybe;
use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Traits as T;

/**
 * @template A
 * @implements I\DoubleEndedIterator<A>
 */
class Once implements I\DoubleEndedIterator {
	/**
	 * @var bool
	 */
	private $is_finished;
	/**
	 * @var A
	 */
	private $item;
	/**
	 * @use T\Iterator<A>
	 * @use T\Iterator\DoubleEnded<A>
	 */
	use T\Iterator, T\Iterator\DoubleEnded;

	/**
	 * @param bool $is_finished States if this iterator is finished.
	 * @param mixed $item The item to output.
	 */
	private function __construct(bool $is_finished, $item)
	{
		$this->is_finished = $is_finished;
		$this->item = $item;
	}

	/**
	 * @param mixed $item The item to output.
	 * @return I\DoubleEndedIterator<A>
	 */
	public static function new($item): I\DoubleEndedIterator {
		return new self(false, $item);
	}

	public function next(): C\Maybe {
		if ($this->is_finished) {
			return C\Maybe::none();
		} else {
			$this->is_finished =  true;
			return C\Maybe::some($this->item);
		}
	}

	public function next_back(): Maybe {
		return $this->next();
	}
}
