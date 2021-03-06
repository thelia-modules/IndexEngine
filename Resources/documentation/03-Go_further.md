1. Launch test suite <a name="tests"></a>
===

A. Requirements
---

To run IndexEngine tests, you will need:

- PHPUnit
- A valid installed Thelia >= 2.1 ( thelia/thelia, not a thelia-project )
- The tested search engines:
    - A local Elasticsearch on port 9200
    
    
B. Run tests
---

First, you have to insert the test fixtures to run them.
They are in ```module/Resources/tests```.

Run your Thelia test suite, it'll run this module's tests.
Or you can run for Thelia root: ```php bin/phpunit --bootstrap core/vendor/autoload.php local/modules/IndexEngine/Tests```,
or for a cool testdox display: ```php bin/phpunit --testdox --bootstrap core/vendor/autoload.php local/modules/IndexEngine/Tests```

C. Write a test suite for a driver
---

To write a test suite for your driver, you only have to extend ```IndexEngine\Tests\Driver\Bridge\BridgeTestCase``` and implement
```protected function getDriver()``` that only return your driver instance.

If your driver is EventDispatcherAware, you'll have to override the ```protected function getListener()``` method.

If your driver needs a configuration, you can override the ```public function setConfiguration(ArgumentCollection $collection = null)``` method to apply it.


2. Write a task <a name="task"></a>
===

In order to create a task, you have to create a class that extends ```IndexEngine\Driver\Task\AbstractTask```, or that implements ```IndexEngine\Driver\Task\TaskInterface```

Then you have to create a service that have the ```index_engine.task``` tag, and it will be registered in the task registry.

3. Write a driver <a name="driver"></a>
===

There are two types of driver: those that uses events and those that doesn't.
Even if the EventDispatcherAware ones may be slower, their design allow flexibility and modularity, that's why everything has been designed to support them easily.

A) Create a driver
---

First, you have to create a class that implements ```IndexEngine\Driver\DriverInterface```, then you have to create a service with the ```index_engine.driver``` tag.
It will be stored in the driver registry.

It may look like this:

```xml
<service id="my_super_driver" class="MyModule\MyDriver">
  <tag name="index_engine.driver" />
</service>
```

B) Create an EventDispatcherAware driver
---

If you want your driver to be flexible, you can use the following classes.

First, create a class that extends ```IndexEngine\Driver\AbstractEventDispatcherAwareDriver``` and create the service as done in A.
Implement the ```getCode``` method. You can override ```checkDependencies``` to check yours.

Then create a class that extends ```IndexEngine\Driver\DriverEventSubscriber```, then create a service with the ```index_engine.event_subscriber``` tag.

Then you have to add the ```listener``` parameter in your driver's tag, with the listener's id as value.

Example:

```xml
<service id="index_engine.driver.elastic_search" class="%index_engine.driver.elastic_search.class%">
  <tag name="index_engine.driver" listener="index_engine.driver.elastic_search_listener"/>
</service>
<service id="index_engine.driver.elastic_search_listener" class="%index_engine.driver.elastic_search_listener.class%">
  <tag name="index_engine.event_subscriber"/>
</service>
```

4. Add an index type <a name="index_type"></a>
===

In order to add an index type, you'll have to create a class that implements ```Symfony\Component\EventDispatcher\EventSubscriberInterface```,
and create a service with the ```index_engine.event_subscriber``` tag.

You'll have to catch the event ```IndexEngine\Event\Module\IndexEngineEvents::COLLECT_ENTITY_TYPES``` to add your type.

Then you can have a look at:
- ```IndexEngine\Discovering\Collector\DatabaseSubscriber``` and ```IndexEngine\Discovering\Configuration\DatabaseSubscriber``` for a general use case implementation.
- ```IndexEngine\Discovering\Collector\SqlQuerySubscriber``` and ```IndexEngine\Discovering\Configuration\SqlQuerySubscriber``` for something different.
