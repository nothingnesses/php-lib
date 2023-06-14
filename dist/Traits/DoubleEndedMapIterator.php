<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Traits;

use Nothingnesses\Lib\Interfaces as I;
use Nothingnesses\Lib\Classes as C;

trait DoubleEndedMapIterator {
	/**
	 * @param callable $mapper
	 */
	public function map($mapper): I\MapIterator {
		return C\DoubleEndedMapIterator::new($mapper)($this);
	}
}
