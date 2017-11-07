# NewCity Timber Extensions

This Wordpress plugin pre-sets some common configuration options for Timber and Twig. It also
creates a new post class, `NC_TimberPost`, that extends the default `TimberPost` class with
commonly-needed functions and properties. When writing new TimberPost classes, you should extend
`NC_TimberPost` instead of the default `TimberPost` class.

## Settings

* Enables Timber caching and moves the cache directory to `wp-content/uploads/timber-cache` (required on Pantheon for filesystem write-access)
* Expands the locations that Timber will seek Twig templates and partials. Any `.twig` file placed inside either the `/templates` or `/partials` directory will be detected, no matter how many directories deep it may be.

## `NC_TimberPost`

This class currently does very little beyond the default `TimberPost` behavior. Its only extended behavior is the
addition of `{{ post.newcity_info }}`, which is a demo function that returns a string. More functions will be added in the future.
