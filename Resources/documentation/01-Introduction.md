1. Getting started with search engines
===

Here's a definition of a search engine, found on [Wikipedia](https://en.wikipedia.org/wiki/Search_engine_%28computing%29):

```
A search engine is an information retrieval system designed to help find information stored on a computer system.
The search results are usually presented in a list and are commonly called hits.
Search engines help to minimize the time required to find information and the amount of information which must be consulted, akin to other techniques for managing information overload.
```

2. What does this module ?
===

This module provides a Back Office interfaces to manage its index and drivers configurations, execute tasks.

Moreover, the search engines are abstracted, so you can design your schemas without having to care about the search engine.

That means that you can create as many driver configuration you want with the given drivers ( Elasticsearch, OpenSearchServer, ... )
and use them regardless of the endpoint server.

An index configuration is a collection of data and criteria that are executed to retrieve data from Thelia database.
A task is like a command, but a command that can be executed from the Back Office interface.

3. Installation
===

With composer:

```sh
$ php composer.phar require thelia/index-engine-module:~1.0
```

Or download the archive and extract it in ```your-thelia/local/modules```

Keep in mind that drivers often need external libraries that you'll have to install. They are placed as suggestion in ```composer.json```