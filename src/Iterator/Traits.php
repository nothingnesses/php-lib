<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Traits;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Classes as C;

trait Iterator {
	public function any(callable $predicate): bool {
		return $this
			->find($predicate)
			->is_some();
	}

	public function find(callable $predicate): C\Maybe {
		$current = $this->next();
		while ($current->is_some()) {
			$result = $current->bind(fn ($item) => $predicate($item)
				? C\Maybe::some($item)
				: C\Maybe::none());
			if ($result->is_some()) {
				return $result;
			}
			$current = $this->next();
		}
		return C\Maybe::none();
	}

	public function foldl(callable $fn): \Closure {
		/**
		 * @param B $initial The initial value to use.
		 * @return B
		 */
		return function ($initial) use ($fn) {
			$carry = $initial;
			$current = $this->next();
			while ($current->is_some()) {
				$current->map(function ($item) use ($fn, &$carry): void {
					$carry = $fn($carry)($item);
				});
				$current = $this->next();
			}
			return $carry;
		};
	}

	public function for_each(callable $fn): void {
		$current = $this->next();
		while ($current->is_some()) {
			$current->map(function ($item) use ($fn): void {
				$fn($item);
			});
			$current = $this->next();
		}
	}

	abstract public function next(): C\Maybe;

	public function to_array(): array {
		$array = [];
		$this->for_each(function ($item) use (&$array): void {
			array_push($array, $item);
		});
		return $array;
	}
}

trait FilterIterator {
	public function filter(callable $predicate): I\FilterIterator {
		return C\FilterIterator::new($predicate)($this);
	}
}

trait MapIterator {
	public function map(callable $mapper): I\MapIterator {
		return C\MapIterator::new($mapper)($this);
	}
}

trait DoubleEndedIterator {
	abstract public function next_back(): C\Maybe;

	public function reverse(): C\ReversedIterator {
		return C\ReversedIterator::new($this);
	}

	public function reverse_find(callable $predicate): C\Maybe {
		$current = $this->next_back();
		while ($current->is_some()) {
			$result = $current->bind(fn ($item) => $predicate($item)
				? C\Maybe::some($item)
				: C\Maybe::none());
			if ($result->is_some()) {
				return $result;
			}
			$current = $this->next_back();
		}
		return C\Maybe::none();
	}
}

trait DoubleEndedMapIterator {
	public function map(callable $mapper): I\MapIterator {
		return C\DoubleEndedMapIterator::new($mapper)($this);
	}
}

trait DoubleEndedFilterIterator {
	public function filter(callable $predicate): I\FilterIterator {
		return C\DoubleEndedFilterIterator::new($predicate)($this);
	}
}
