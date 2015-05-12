1. Create a driver configuration
===


2. Create an index configuration
===

foobar ....

A. Create a database type index
---

B. Create a loop type index
---

C. Create a sql query type index
---

D. The mapping
---

3. Call the index service manually
===

4. Index building automation
===

5. Querying the public API with the javascript client
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
              "visible": true
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