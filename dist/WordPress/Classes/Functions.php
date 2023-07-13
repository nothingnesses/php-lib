<?php

//phpcs:disable Generic.Commenting,Generic.WhiteSpace,PEAR.Classes.ClassDeclaration,PEAR.Commenting.ClassComment,PEAR.Commenting.FunctionComment,PEAR.Formatting.MultiLineAssignment,PEAR.Functions.FunctionCallSignature,PEAR.Functions.FunctionDeclaration,PEAR.NamingConventions,PEAR.WhiteSpace
declare(strict_types=1);

namespace Nothingnesses\Lib\WordPress\Classes;

use Nothingnesses\Lib\Classes as C;
use WP_Post;

class Functions {
	/**
	 * @param string $post_type
	 * @return array<WP_Post> The posts of a particular post type.
	 */
	public static function get_posts_by_type($post_type): array {
		return get_posts(["order" => "ASC", "orderby" => "menu_order", "posts_per_page" => -1, "post_type" => $post_type]);
	}

	/**
	 * Gets root posts, i.e., those without a parent post.
	 * @param array<WP_Post> $posts The posts to search through.
	 * @return C\DoubleEndedFilterIterator<WP_Post> An iterator over the root posts.
	 */
	public static function get_root_posts($posts) {
		return C\ArrayIterator::new($posts)
			->map(function ($item) {
				return $item[1];
			})
			->filter(function (WP_Post $post) {
				return $post->post_parent === 0;
			});
	}

	/**
	 * @param array<WP_Post> $posts Array of posts to get the first page of.
	 * @return C\Maybe<string> The url of the first page.
	 */
	public static function get_first_page_url($posts): C\Maybe {
		return self::get_root_posts($posts)
			->next()
			->map(function ($post) {
				return get_permalink($post->ID);
			});
	}

	/**
	 * Returns the post matching the given ID, if it exists.
	 * @param array<WP_Post> $posts The array to search posts in.
	 * @return \Closure(int): C\Maybe<WP_Post>
	 */
	public static function get_post($posts): \Closure {
		/**
		 * @param int $id The ID of the post to get.
		 * @return Maybe<WP_Post> The post wrapped in a `Maybe`.
		 */
		return function (int $id) use ($posts) : C\Maybe {
			return C\ArrayIterator::new($posts)
 			->map(function ($item) {
					return $item[1];
				})
 			->find(function (WP_Post $post) use ($id) : bool {
					return $id === $post->ID;
				});
		};
	}

	/**
	 * Returns the parent of a post, if it exists.
	 * @param array<WP_Post> $posts The array to search posts in.
	 * @return \Closure(WP_Post): C\Maybe<WP_Post>
	 */
	public static function get_post_parent($posts): \Closure {
		/**
		 * @param WP_Post $post The post to get the parent of.
		 * @return Maybe<WP_Post> The parent of the post.
		 */
		return function (WP_Post $post) use ($posts) : C\Maybe {
			return self::get_post($posts)($post->post_parent);
		};
	}

	/**
	 * Returns an iterator over the given post's children, according to the order in the CMS.
	 * @param array<WP_Post> $posts The array to search posts in.
	 * @return \Closure(WP_Post): C\DoubleEndedFilterIterator<WP_Post>
	 */
	public static function get_children_iterator($posts): \Closure {
		/**
		 * @param WP_Post $post The post to get the children of.
		 * @return C\DoubleEndedFilterIterator<WP_Post> An iterator over the children.
		 */
		return function (WP_Post $post) use ($posts) : C\DoubleEndedFilterIterator {
			return C\ArrayIterator::new($posts)
 			->map(function ($item) {
					return $item[1];
				})
 			->filter(function (WP_Post $current_post) use ($post) {
					return $post->ID === $current_post->post_parent;
				});
		};
	}

	/**
	 * Returns an iterator over the given post's siblings and itself, according to the order in the CMS.
	 * @param array<WP_Post> $posts The array to search posts in.
	 * @return \Closure(WP_Post): C\DoubleEndedFilterIterator<WP_Post>
	 */
	public static function get_siblings_iterator($posts): \Closure {
		/**
		 * @note: Can't use `get_post` for this, since ID 0 isn't a post. 
		 * @param WP_Post $post The post to get the siblings of.
		 * @return DoubleEndedFilterIterator<WP_Post> An iterator over the siblings.
		 */
		return function (WP_Post $post) use ($posts) : C\DoubleEndedFilterIterator {
			return C\ArrayIterator::new($posts)
 			->map(function ($item) {
					return $item[1];
				})
 			->filter(function (WP_Post $current_post) use ($post) {
					return $post->post_parent === $current_post->post_parent;
				});
		};
	}

	/**
	 * Returns the next sibling of a post, if it exists.
	 * @param array<WP_Post> $posts The array to search posts in.
	 * @return \Closure(WP_Post): C\Maybe<WP_Post>
	 */
	public static function get_next_sibling($posts): \Closure {
		/**
		 * @param WP_Post $post The post to get the next sibling of.
		 * @return C\Maybe<WP_Post> The next sibling.
		 */
		return function (WP_Post $post) use ($posts): C\Maybe {
			$iterator = self::get_siblings_iterator($posts)($post);
			$will_loop = true;
			while ($will_loop) {
				$will_loop = $iterator
					->next()
					->maybe(false)(function ($current_post) use ($post) {
						return $current_post->ID !== $post->ID;
					});
			}
			return $iterator->next();
		};
	}

	/**
	 * Returns the previous sibling of a post, if it exists.
	 * @param array<WP_Post> $posts The array to search posts in.
	 * @return \Closure(WP_Post): C\Maybe<WP_Post>
	 */
	public static function get_previous_sibling($posts): \Closure {
		/**
		 * @param WP_Post $post The post to get the previous sibling of.
		 * @return C\Maybe<WP_Post> The previous sibling.
		 */
		return function (WP_Post $post) use ($posts): C\Maybe {
			$siblings = self::get_siblings_iterator($posts)($post);
			$will_loop = true;
			while ($will_loop) {
				$will_loop = $siblings
					->next_back()
					->maybe(false)(function ($current_post) use ($post) {
						return $current_post->ID !== $post->ID;
					});
			}
			return $siblings->next_back();
		};
	}

	/**
	 * Returns the first child of a post, if it exists.
	 * @param array<WP_Post> $posts The array to search posts in.
	 * @return \Closure(WP_Post): C\Maybe<WP_Post>
	 */
	public static function get_first_child($posts): \Closure {
		/**
		 * @param WP_Post $post The post to get the first child of.
		 * @return Maybe<WP_Post> The first child
		 */
		return function (WP_Post $post) use ($posts) : C\Maybe {
			return self::get_children_iterator($posts)($post)->next();
		};
	}

	/**
	 * Returns the last child of a post, if it exists.
	 * @param array<WP_Post> $posts The array to search posts in.
	 * @return \Closure(WP_Post): C\Maybe<WP_Post>
	 */
	public static function get_last_child($posts): \Closure {
		/**
		 * @param WP_Post $post The post to get the last child of.
		 * @return Maybe<WP_Post> The last child.
		 */
		return function (WP_Post $post) use ($posts) : C\Maybe {
			return self::get_children_iterator($posts)($post)->next_back();
		};
	}

	/**
	 * Returns the post next to a post, if it exists. This could either be its first child if it has children, or its next sibling if it has one, or the next sibling of its closest ancestor with a next sibling if the post has neither children nor a younger sibling.
	 * @param array<WP_Post> $posts The array to search posts in.
	 * @return Closure(WP_Post): C\Maybe<WP_Post>
	 */
	public static function get_next_post($posts) {
		/**
		 * @param WP_Post $post The post to get the next post of.
		 * @return C\Maybe<WP_Post> The next post.
		 */
		return function (WP_Post $post) use ($posts): C\Maybe {
			$some = function ($a) : C\Maybe {
				return C\Maybe::some($a);
			};
			$first_child = self::get_first_child($posts)($post);
			// Return the first child if it exists, else, return the next sibling if it exists, else, return the next sibling of the closest ancestor with a next sibling.
			return $first_child->maybe_lazy(function () use ($posts, $post, $some): C\Maybe {
				$get_next_sibling = self::get_next_sibling($posts);
				$next_sibling = $get_next_sibling($post);
				return $next_sibling->maybe_lazy(
					function () use ($posts, $post, $get_next_sibling) {
						return AncestorsIterator::new($posts)($post)
 						->filter(function ($ancestor) use ($get_next_sibling) {
								return $get_next_sibling($ancestor)->is_some();
							})
 						->next()
 						->bind(function ($ancestor) use ($get_next_sibling) {
								return $get_next_sibling($ancestor);
							});
					}
				)($some);
			})($some);
		};
	}

	/**
	 * Returns the post before a post, if it exists. This could either be the youngest descendant of its previous sibling if it has one and the sibling has descendant, or its previous sibling if it has one, or the youngest descendant of its parent if it doesn't.
	 * @param array<WP_Post> $posts The array to search posts in.
	 * @return \Closure(WP_Post): C\Maybe<WP_Post>
	 */
	public static function get_previous_post($posts) {
		/**
		 * @param WP_Post $post The post to get the post before it.
		 * @return C\Maybe<WP_Post> The previous post.
		 */
		return function (WP_Post $post) use ($posts): C\Maybe {
			$previous_sibling = self::get_previous_sibling($posts)($post);
			// If the previous sibling doesn't exist, return the parent.
			return $previous_sibling
				->maybe_lazy(function () use ($posts, $post) : C\Maybe {
					return self::get_post_parent($posts)($post);
				})(
				// If previous sibling exists, recurse on its youngest descendants.
				function ($post) use ($posts) {
					$worker = function (WP_Post $post) use ($posts, &$worker): C\Maybe {
						return self::get_last_child($posts)($post)
							->maybe(C\Maybe::some($post))($worker);
					};
					return $worker($post);
				}
			);
		};
	}
}
