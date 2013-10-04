## LogstashBundle

A bundle on top of MonologBundle which provides logging to logstash through redis broker/input.

## Installation

##### Step 1: Download LogstashBundle

Add logstash bundle in your composer.json as below:

```js
"require": {
    ...
    "emran/logstash-bundle": "dev-master"
}
```

Update/install with this command:

```
php composer.phar update "emran/logstash-bundle"
```

##### Step 2:  Enable the bundle

Register the bundle

```php
public function registerBundles()
{
    $bundles = array(
        ...
        new Emran\Bundle\LogstashBundle\EmranLogstashBundle(),
);
```

##### Step 3:  Activate the main configs

```
# app/config/config.yml
emran_logstash:
    redis:
        host: localhost
        port: 6379
        list: logstash
        name: myApp
```

## How to use ?

Assuming for example that you need to log an event from a controller action, get the service in your controller method
and use the appropriate methods (debug, info, critical, etc) as below:

```php
public function indexAction()
{
    $this->get('logstash.logger')->debug('Loading index page.');
    return $this->render('TestBundle:Default:index.html.twig');
}

```

Remember, this is the standard logger service wrapped with a class. You can call any of the standard severity methods on
this class: emergency, alert, critical, error, warning, notice, info, debug, log.