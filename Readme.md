IndexEngine module for Thelia
===

Summary
---

1. [Install](#install_en_US)
2. [Configure your driver](#configuration_en_US)
3. [Configure the data to index](#data_en_US)
4. [Use the search engine in the template](#template_en_US)
5. [API](#api_en_US)
6. [Documentation](#documentation_en_US)


1. Install <a name="install_en_US"></a>
---

With composer:
```sh
$ php composer.phar require thelia/index-engine-module:~1.0
```

Then install the libraries you need.
Example:
```sh
$ php composer.phar require 
```

2. Configure your driver <a name="configuration_en_US"></a>
---

3. Configure the data to index <a name="data_en_US"></a>
---

4. Use the search engine in the template <a name="template_en_US"></a>
---

You can use the search engine directly in your templates with the ```index``` smarty function.

This function takes a mandatory parameter ```code``` that is your index configuration code.
The other parameters are the same as the API's.

Here how they work:

~~~~
@todo
~~~~

Example:
```smarty
{$results={index code="foo"}}

{* Or you can do *}

{foreach from={index code="foo" order="id,ref-reverse" id=[">", 5] ref=["like", "some text"]} item=result}
    {$result.id} {$result.ref}
    ...
{/foreach}
```

5. API <a name="api_en_US"></a>
---

The IndexEngine comes with a public API to retrieve data easily.
This API is on the route ```/api/public/search/your-index-configuration-code```.

You can use the query string to add your search filters: It works like smarty function.

Example:

```http
GET api/public/search/unique_code?order=id,ref-reverse&id[]=%3E&id[]=5&ref[]=like&ref[]=some%20text
```

6. Documentation <a name="documentation_en_US"></a>
---

For more information about the module's architecture and its development, please see the documentation located in
```your-thelia/local/modules/IndexEngine/Resources/documentation```