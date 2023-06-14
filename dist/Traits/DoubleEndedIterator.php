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
