<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Traits;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Classes as C;

trait Iterator {
	/**
	 * @param callable $predicate
	 */
	public function any($predicate): bool {
		return $this
			->find($predicate)
			->is_some();
	}

	/**
	 * @param callable $predicate
	 */
	public function find($predicate): C\Maybe {
		$current = $this->next();
		while ($current->is_some()) {
			$result = $current->bind(function ($item) use ($predicate) {
				return $predicate($item)
 				? C\Maybe::some($item)
 				: C\Maybe::none();
			});
			if ($result->is_some()) {
				return $result;
			}
			$current = $this->next();
		}
		return C\Maybe::none();
	}

	/**
	 * @param callable $fn
	 */
	public function foldl($fn): \Closure {
		/**
		 * @param B $initial The initial value to use.
		 * @return B
		 */
		return function ($initial) use ($fn) {
			$carry = $initial;
			$current = $this->next();
			while ($current->is_some()) {
				$current->map(function ($item) use ($fn, &$carry) {
					$carry = $fn($carry)($item);
				});
				$current = $this->next();
			}
			return $carry;
		};
	}

	/**
	 * @param callable $fn
	 * @return void
	 */
	public function for_each($fn) {
		$current = $this->next();
		while ($current->is_some()) {
			$current->map(function ($item) use ($fn) {
				$fn($item);
			});
			$current = $this->next();
		}
	}

	abstract public function next(): C\Maybe;

	public function to_array(): array {
		$array = [];
		$this->for_each(function ($item) use (&$array) {
			array_push($array, $item);
		});
		return $array;
	}
}

trait FilterIterator {
	/**
	 * @param callable $predicate
	 */
	public function filter($predicate): I\FilterIterator {
		return C\FilterIterator::new($predicate)($this);
	}
}

trait MapIterator {
	/**
	 * @param callable $mapper
	 */
	public function map($mapper): I\MapIterator {
		return C\MapIterator::new($mapper)($this);
	}
}

trait DoubleEndedIterator {
	abstract public function next_back(): C\Maybe;

	public function reverse(): C\ReversedIterator {
		return C\ReversedIterator::new($this);
	}

	/**
	 * @param callable $predicate
	 */
	public function reverse_find($predicate): C\Maybe {
		$current = $this->next_back();
		while ($current->is_some()) {
			$result = $current->bind(function ($item) use ($predicate) {
				return $predicate($item)
 				? C\Maybe::some($item)
 				: C\Maybe::none();
			});
			if ($result->is_some()) {
				return $result;
			}
			$current = $this->next_back();
		}
		return C\Maybe::none();
	}
}

trait DoubleEndedMapIterator {
	/**
	 * @param callable $mapper
	 */
	public function map($mapper): I\MapIterator {
		return C\DoubleEndedMapIterator::new($mapper)($this);
	}
}

trait DoubleEndedFilterIterator {
	/**
	 * @param callable $predicate
	 */
	public function filter($predicate): I\FilterIterator {
		return C\DoubleEndedFilterIterator::new($predicate)($this);
	}
}
