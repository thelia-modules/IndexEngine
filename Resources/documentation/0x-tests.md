Tests for IndexEngine
===

0. Requirements
---

To run IndexEngine tests, you will need:

- PHPUnit
- A valid installed Thelia >= 2.1 ( thelia/thelia, not a thelia-project )
- The tested search engines:
    - A local Elasticsearch on port 9200
    
    
1. Run tests
---

Run your Thelia test suite, it'll run this module's tests.
Or you can run for Thelia root: ```php bin/phpunit --bootstrap core/vendor/autoload.php local/modules/IndexEngine/Tests```,
or for a cool testdox display: ```php bin/phpunit --testdox --bootstrap core/vendor/autoload.php local/modules/IndexEngine/Tests```

2. Write a test suite for a driver
---

To write a test suite for your driver, you only have to extend ```IndexEngine\Tests\Driver\Bridge\BridgeTestCase``` and implement
```protected function getDriver()``` that only return your driver instance.

If your driver is EventDispatcherAware, you'll have to override the ```protected function getListener()``` method.

If your driver needs a configuration, you can override the ```public function setConfiguration(ArgumentCollection $collection = null)``` method to apply it.