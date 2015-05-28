IndexEngine module for Thelia
===

Summary
---

1. [Description](#description_en_US)
2. [Install](#install_en_US)
3. [Documentation](#documentation_en_US)
4. [Common problems](#problems_en_US)


1. Description <a name="description_en_US"></a>
---

IndexEngine is a module that let you configure the search engines that you want to use, the data that you want to send to them.

It provides an high-level API to use any search engine with any data indifferently.

Warning: This module is dedicated to developers, you can't use it as a standalone.

If you want to see how to integrate it, please take a look at [ProductSearch](https://github.com/thelia-modules/ProductSearch) module

2. Install <a name="install_en_US"></a>
---

With composer:

```sh
$ php composer.phar require thelia/index-engine-module:~1.0
```

Then install the libraries you need.

Example with Elasticsearch (currently, this is installed by default):

```sh
$ php composer.phar require elasticsearch/elasticsearch:~1.0
```


3. Documentation <a name="documentation_en_US"></a>
---

See [here](Resources/documentation/00-Summary.md) for the documentation summary.

4. Common problems <a name="problems_en_US"></a>
---

When I use the console to index data, all my URL leads to http://localhost

*You have to configure the ```url_site``` Thelia system variable to get the proper urls while indexing data with the console*