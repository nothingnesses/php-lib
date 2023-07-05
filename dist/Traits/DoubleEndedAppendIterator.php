<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Traits;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Classes as C;

trait DoubleEndedAppendIterator {
	// @note Ideally, this parameter should be a DoubleEndedIterator.
	/**
	 * @param I\Iterator $second
	 */
	public function append($second): C\DoubleEndedAppendIterator {
		return C\DoubleEndedAppendIterator::new($this)($second);
	}
}
