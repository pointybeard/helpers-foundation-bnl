# PHP Helpers: Broadcast & Listen Foundation Classes

-   Version: v1.0.0
-   Date: March 01 2020
-   [Release notes](https://github.com/pointybeard/helpers-foundation-bnl/blob/master/CHANGELOG.md)
-   [GitHub repository](https://github.com/pointybeard/helpers-foundation-bnl)

Provides broadcaster & listener pattern classes

## Installation

This library is installed via [Composer](http://getcomposer.org/). To install, use `composer require pointybeard/helpers-foundation-bnl` or add `"pointybeard/helpers-foundation-bnl": "~1.0.0"` to your `composer.json` file.

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

### Requirements

This library requires PHP7.2 or greater.

This library also makes use of the [PHP Helpers: Readable Trace Exception](https://github.com/pointybeard/helpers-exceptions-readabletrace) (`pointybeard/helpers-exceptions-readabletrace`). It is installed automatically via composer.

To include all the [PHP Helpers](https://github.com/pointybeard/helpers) packages on your project, use `composer require pointybeard/helpers`

## Usage

Include this library in your PHP files with `use pointybeard\Helpers\Foundation\BroadcastAndListen` and implement the `BroadcastAndListen\Interfaces\AcceptsListenersInterface` interface like so:

```php
<?php

declare(strict_types=1);

namespace MyApp;

include __DIR__.'/vendor/autoload.php';

use pointybeard\Helpers\Foundation\BroadcastAndListen;

class Warehouse implements BroadcastAndListen\Interfaces\AcceptsListenersInterface
{
    use BroadcastAndListen\Traits\HasListenerTrait;
    use BroadcastAndListen\Traits\HasBroadcasterTrait;

    public const WORK_STARTED = 'Work Started';
    public const WORK_COMPLETE = 'Work Completed';
    public const WORK_FAILED = 'Work Failed';

    private $location;

    public function __construct(string $location)
    {
        $this->location = $location;
    }

    public function doSomeWork()
    {
        $this->broadcast(self::WORK_STARTED, time());

        try {
            $resultOfHardWork = null;

            // This is where all the work of the factory is done
            $resultOfHardWork = '123456';

            $this->broadcast(self::WORK_COMPLETE, time(), $resultOfHardWork);
        } catch (\Exception $ex) {
            $this->broadcast(self::WORK_FAILED, $ex);
        }
    }

    public function location()
    {
        return $this->location;
    }
}

class WarehouseNewDelhi extends Warehouse
{
    public function __construct()
    {
        parent::__construct("New Delhi");
    }

    public function doSomeWork()
    {
        $this->broadcast(self::WORK_STARTED, time());

        try {
            // Simulate something going wrong
            throw new \Exception('Machinery failed to process job');
        } catch (\Exception $ex) {
            $this->broadcast(self::WORK_FAILED, $ex);
        }
    }
}

class WarehouseCanada extends Warehouse
{
    public function __construct()
    {
        parent::__construct("Canada");
    }

    public function doSomeWork()
    {
        // Add something that isn't a callback to the listener iterator
        $this->listeners->append("apples");
        return parent::doSomeWork();
    }
}

class Office
{
    public function notificationFromWarehouse($type, ...$arguments)
    {
        echo "Recieved Notification from Warehouse in {$arguments[0]->location()}: {$type}".PHP_EOL;

        // Perform logic depending on the notification type
        switch ($type) {
            case Warehouse::WORK_STARTED:
                echo 'Work started at '.date('c', $arguments[1]).PHP_EOL;
                break;

            case Warehouse::WORK_COMPLETE:
                echo 'Work completed successfully at '.date('c', $arguments[1]).PHP_EOL;
                echo "The result of that hard work is: {$arguments[2]}".PHP_EOL.PHP_EOL;
                break;

            case Warehouse::WORK_FAILED:
                echo "Work failed to complete. Returned: {$arguments[1]->getMessage()}".PHP_EOL.PHP_EOL;
                break;
        }
    }
}

$headOffice = new Office;
$headOfficeCallback = [$headOffice, 'notificationFromWarehouse'];

$shanghai = new Warehouse('Shanghai');
$newdelhi = new WarehouseNewDelhi;

// Add the office as a listener to each office location
$shanghai->addListener($headOfficeCallback);
$newdelhi->addListener($headOfficeCallback);

// addListener allows for method chaining
$canada = (new WarehouseCanada)->addListener($headOfficeCallback);

$shanghai->doSomeWork();
// Recieved Notification from Warehouse in Shanghai: Work Started
// Work started at 2020-03-01T10:37:40+00:00
// Recieved Notification from Warehouse in Shanghai: Work Completed
// Work completed successfully at 2020-03-01T10:37:40+00:00
// The result of that hard work is: 123456

$newdelhi->doSomeWork();
// Recieved Notification from Warehouse in New Delhi: Work Started
// Work started at 2020-03-01T10:37:40+00:00
// Recieved Notification from Warehouse in New Delhi: Work Failed
// Work failed to complete. Returned: Machinery failed to process job

try{
    $canada->doSomeWork();
} catch(\Exception $ex) {
    echo "[ERROR] Something has gone wrong! Returned: " . $ex->getMessage() . PHP_EOL;
}
// Recieved Notification from Warehouse in Canada: Work Started
// Work started at 2020-03-01T10:39:03+00:00
// [ERROR] Something has gone wrong! Returned: Invalid callback at position 1 of listener iterator.

```

## Support

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/pointybeard/helpers-foundation-bnl/issues),
or better yet, fork the library and submit a pull request.

## Contributing

We encourage you to contribute to this project. Please check out the [Contributing documentation](https://github.com/pointybeard/helpers-foundation-bnl/blob/master/CONTRIBUTING.md) for guidelines about how to get involved.

## License

"PHP Helpers: Broadcast & Listen Foundation Classes" is released under the [MIT License](http://www.opensource.org/licenses/MIT).
