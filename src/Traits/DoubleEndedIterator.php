<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Traits;

use Nothingnesses\Lib\Classes as C;

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
