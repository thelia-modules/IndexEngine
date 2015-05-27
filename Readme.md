IndexEngine module for Thelia
===

Summary
---

1. [Install](#install_en_US)
2. [Documentation](#documentation_en_US)
3. [Common problems](#problems_en_US)


1. Install <a name="install_en_US"></a>
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


2. Documentation <a name="documentation_en_US"></a>
---

See [here](Resources/documentation/00-Summary.md) for the documentation summary.

3. Common problems <a name="problems_en_US"></a>
---

When I use the console to index data, all my URL leads to http://localhost

*You have to configure the ```url_site``` Thelia system variable to get the proper urls while indexing data with the console*