1. Create a driver configuration <a name="driver_configuration"></a>
===

Go on your Back Office, then configure the IndexEngine module.

Click the "Driver configuration" button, then click the "+" one on the new page.

Enter the driver code and choose the driver you want to use.

Then on the update page, you'll have to give the driver's inner configuration, save and you're done. 

2. Create an index configuration <a name="index_configuration"></a>
===

Go on your Back Office, then configure the IndexEngine module then click the "+" button, enter the code, the name and choose a driver configuration.

Then on the update page, you'll have to choose the index type.

A. Create a database type index
---

Choose the ```database``` type.
Then choose the table that you want to index.
After, you can choose the columns that you want to save into the index.

If you want to filter the rows that will be sent to the search server, you can use criteria.

Save once before configuring the mapping.

B. Create a loop type index
---

Choose the ```loop``` type.
Then choose the table that you want to index.
After, you can choose the variables that you want to save into the index.
The variables may not be available if the loop can't be executed "as-is".
If you're having this, you'll have to enter them manually.

If you want to filter the rows that will be sent to the search server, you can use criteria.
You can give the loop arguments too with the "Loop criteria" section.

Save once before configuring the mapping.

C. Create a sql query type index
---

Choose the ```sql query``` type.

Enter your sql query. You can test the query result.

Then you'll have to do the mapping manually.

D. The mapping
---

The mapping is the part where you define your columns type for the search engine.

If a column isn't present in the mapping, it won't be exported to the search server.

3. Call the index service manually <a name="tasks"></a>
===

Go on your Back Office, then configure the IndexEngine module.

Click the "Execute a task" button, then chose the task, enter its configuration and click "Run".

4. Index building automation <a name="cron"></a>
===

The tasks can be executed in a terminal too.

If you want to automatically refresh your index, you can define a cron with the following command:
 
```sh
$ php Thelia index:update your_index_code
```

5. Use the search engine in the template <a name="template"></a>
---

You can use the search engine directly in your templates with the ```index``` smarty function.

This function takes a mandatory parameter ```code``` that is your index configuration code.

Then you can use all the parameters to define your filters.
Just give your field's name as parameter name, then given a single value for an equal comparison,
or an array with 2 entries: the first is the comparison ( =, <>, <, <=, >, >=, LIKE ), the second is the compare value.

Example:
```smarty
{$results={index code="foo"}}

{* Or you can do *}

{foreach from={index code="foo" order="id,ref-reverse" id=[">", 5] ref=["like", "some text"]} item=result}
    {$result.id} {$result.ref}
    ...
{/foreach}
```

6. Querying the public API with the javascript client <a name="api_client"></a> 
===

First, you have to require the client's file asset in your template:

```smarty
{javascripts file="assets/js/SearchEngine.js" source="IndexEngine"}
    <script src="{$asset_url}"></script>
{/javascripts}
```

Then, you can create an instance of the client:

```js
<script>
    (function ($) {
        var se = new SearchEngine("{url path='/api/public/search'}");
    })(jQuery);
</script>
```

Then you can query the search engine:

```js
<script>
    (function ($) {
        var results = se.find(
          "unique_code", // Then index configuration code
          {
              // Column filters, here you can define the results order and the filters to apply
              // Available filters: >, >=, <, <=, =, <>, LIKE
              "order": "id",
              "id": [">", 1],
              "visible": true,
              
              // You can use an OR operator between multiple criteria:
              "or" => [
                ["id", "=", 1],  
                ["id", "=", 2],  
                ["code", "like", "foo"],  
              ]
          },
          {
              // Here you can play with the client's behaviour
              // You can define the results limit and offset ( for pagination )
              limit:5,
              offset:2,
              // And give your own success/fail callback
              fail: function(xhr) {
                  //alert(xhr.status);
              }
          }
      );
    })(jQuery);
</script>
```

The ```find``` method returns an array with 3 keys:
- total_count: the total number of results ( ignoring offset and limit )
- count: the current number of results
- results: The results matrix


7. Querying the API without the client <a name="api_without_client"></a> 
---

The IndexEngine comes with a public API to retrieve data easily.
This API is on the route ```/api/public/search/your-index-configuration-code```.

You can use the query string to add your search filters: It works like smarty function.

Example:

```
GET api/public/search/unique_code?order=id,ref-reverse&id[]=%3E&id[]=5&ref[]=like&ref[]=some%20text
```