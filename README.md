# NewCity Timber Extensions

This Wordpress plugin pre-sets some common configuration options for Timber and Twig. It also
creates a new post class, `NC_TimberPost`, that extends the default `TimberPost` class with
commonly-needed functions and properties. When writing new TimberPost classes, you should extend
`NC_TimberPost` instead of the default `TimberPost` class.

## Settings

* Enables Timber caching and moves the cache directory to `wp-content/uploads/timber-cache` (required on Pantheon for filesystem write-access)
* Expands the locations that Timber will seek Twig templates and partials. Any `.twig` file placed inside either the `/templates` or `/partials` directory will be detected, no matter how many directories deep it may be.

## Filters

* `|quoteswap( $mode[optional] )`  
    Changes all curly double-quotes to curly single quotes. Useful for nesting quotes.  
    You can also pass a `$mode` argument of `s_to_d` to change single quotes to double. This is not usually
    adviseable, since the filter doesn't currently know the different between single quotes and apostrophes.
* `|print_r`  
    Mimics the PHP `print_r` function, returning a formatted array or object wrapped in a `<pre>` tag for easier reading.

## `NC_TimberPost`

* `post.ancestors`  
  Returns an array of post IDs for all ancestors of the current (hierarchical) page or post, starting with
  the top-level ancestor and ending with the post's direct parent. If the post has no ancestors, this returns `false`.
* `post.ancestors( $pos )`  
  Passing an integer to `post.ancestors` returns a single post ID of the ancestor `$pos` steps removed from
  the current post. For example, `post.ancestors(1)` would return the current post's parent, and `post.ancestors(2)`
  would return its grandparent.  
  A position of `0` will return the top-level ancestor, as will passing any value greater than the total number of
  ancestors.
* `post.top_ancestor`  
  Shorthand for `post.ancestors(0)`
* `post.breadcrumbs`  
  Returns an array of objects containing information about the current post's ancestors, suitable for building
  a breadcrumb navigation list. Each object contains the following values:
  * `title`: the title of the page/post
  * `ID`: The post ID of the page/post
  * `url`: The permalink to the page/post
