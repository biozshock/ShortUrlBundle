ShortUrlBundle
============

This bundle provides a service and twig extension for getting short urls like http://your.host/~short

Installation
============

### Add this bundle to your project

**Using the vendors script**

Add the following lines in your deps file:

    [BumzShortUrlBundle]
        git=git://github.com/bumz/ShortUrlBundle.git
        target=bundles/Bumz/ShortUrlBundle

Run the vendors script:

```bash
$ php bin/vendors install
```

**Using Git submodule**

```bash
$ git submodule add git://github.com/biozshock/ShortUrlBundle.git vendor/bundles/Bumz/ShortUrlBundle
```

### Add the Bumz namespace to your autoloader

```php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'Bumz' => __DIR__.'/../vendor/bundles',
    // your other namespaces
));
```

### Add this bundle to your application's kernel

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    return array(
        // ...
        new Bumz\ShortUrlBundle\BumzShortUrlBundle(),
        // ...
    );
}
```
### Add bundle's routing

```yaml
# /app/config/routing.yml
BumzShortUrlBundle:
    resource: "@BumzShortUrlBundle/Resources/config/routing.yml"
    prefix:   /
```

Examples
========

### Short url in a controller

```php
<?php

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsersController extends Controller
{
    public function getUserProfileShortAction()
    {

        ...

        $longUrl = $this->get('bumz_short_url.shortener')->shorten('http://example.com');
        // $longUrl = '/~ShE'

        ...
    }
}
```

### Get long url in controller

```php
<?php

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsersController extends Controller
{
    public function getUserProfileShortAction()
    {

        ...

        $shortUrl = 'aUty';
        $longUrl = $this->get('bumz_short_url.shortener')->getLong($shortUrl);
        // $longUrl = 'http://example.com'

        ...
    }
}
```

### Get short url in a twig template

```twig
{{ 'http://example.com' | shortenUrl }}
{# this will output something like /~ShE #}
```

