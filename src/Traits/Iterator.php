<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Traits;

use Nothingnesses\Lib\Classes as C;

trait Iterator {
	public function any(callable $predicate): bool {
		return $this
			->find($predicate)
			->is_some();
	}

	public function find(callable $predicate): C\Maybe {
		for (
			$current = $this->next();
			$current->is_some();
			$current = $this->next()
		) {
			$result = $current->bind(fn ($item) => $predicate($item)
				? C\Maybe::some($item)
				: C\Maybe::none());
			if ($result->is_some()) {
				return $result;
			}
		}
		return C\Maybe::none();
	}

	public function fold_left(callable $fn): \Closure {
		/**
		 * @param B $initial The initial value to use.
		 * @return B
		 */
		return function ($initial) use ($fn) {
			$carry = $initial;
			$this->for_each(function ($item) use ($fn, &$carry) {
				$carry = $fn($carry)($item);
			});
			return $carry;
		};
	}

	public function for_each(callable $fn): void {
		for (
			$current = $this->next();
			$current->is_some();
			$current = $this->next()
		) {
			$current->map(function ($item) use ($fn): void {
				$fn($item);
			});
		}
	}

	abstract public function next(): C\Maybe;

	public function take_while(callable $predicate): C\TakeWhileIterator {
		return C\TakeWhileIterator::new($predicate)($this);
	}

	public function to_array(): array {
		$array = [];
		$this->for_each(function ($item) use (&$array): void {
			array_push($array, $item);
		});
		return $array;
	}
}
