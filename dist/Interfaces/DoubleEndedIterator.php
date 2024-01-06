<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\Interfaces;

use Nothingnesses\Lib\Interfaces as I;

/**
 * @template A
 * 
 * @extends I\DoubleEnded<A>
 * @extends I\Iterator<A>
 */
interface DoubleEndedIterator extends DoubleEnded, Iterator {
	/**
	 * Returns an instance that yields items from the current instance in
	 * reverse, up to the last item to be yielded before the reversal.
	 *
	 * @return I\DoubleEndedIterator<A> An type that yields items in reverse.
	 */
	public function reverse();
}
