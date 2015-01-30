stackify-api-php
================

Common libraries for [Stackify Monolog handler](https://github.com/stackify/stackify-log-monolog) and [Stackify log4php appender](https://github.com/stackify/stackify-log-log4php).
This package also includes a standalone [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) -compatible logger that can be used without third-party libraries.

Errors and Logs Overview:
http://docs.stackify.com/m/7787/l/189767

Sign Up for a Trial:
http://www.stackify.com/sign-up/

## Standalone logger
Install the latest version with `composer require stackify/logger`

There are three different transport options that can be configured to send data to Stackify.  Below will show how to implement the different transport options:

### <a name="transport"></a>Transport options:


- <b>ExecTransport</b> does not require Stackify agent to be installed because it sends data directly to Stackify services. It collects log entries in a single batch, calls curl using the ```exec``` function, and sends it to the background immediately [```exec('curl ... &')```]. This will affect the performance of your application minimally, but it requires permissions to call ```exec``` inside the PHP script and it may cause silent data loss in the event of any network issues. This transport method does not work on Windows. To configure ExecTransport you need to pass the environment name and API key (license key):
    ```php
    use Stackify\Log\Transport\ExecTransport;
    use Stackify\Log\Monolog\Handler as StackifyHandler;
    
    $transport = new ExecTransport('api_key');
    $handler = new StackifyHandler('application_name', 'environment_name', $transport);
    ```
    ```yml
    # or configuration file example
    services:
        stackify_transport:
            class: "Stackify\\Log\\Transport\ExecTransport"
            arguments: ["api_key"]
        stackify_handler:
            class: "Stackify\\Log\\Monolog\\Handler"
            arguments: ["application_name", "environment_name", "@stackify_transport"]
    ```
###Configuration:
####Proxy
ExecTransport and CurlTransport support data delivery through proxy. Specify proxy using [libcurl format](http://curl.haxx.se/libcurl/c/CURLOPT_PROXY.html): <[protocol://][user:password@]proxyhost[:port]>
    ```php
    $transport = new ExecTransport($apiKey, ['proxy' => 'https://55.88.22.11:3128']);
    ```
####Curl path
It can be useful to specify ```curl``` destination path for ExecTransport. This option is set to 'curl' by default.
```php
$transport = new ExecTransport($apiKey, ['curlPath' => '/usr/bin/curl']);
```
- <b>CurlTransport</b> does not require a Stackify agent to be installed and it also sends data directly to Stackify services. It collects log entries in a single batch and sends data using native [PHP cURL](http://php.net/manual/en/book.curl.php) functions. This way is a blocking one, so it should not be used on production environments. To configure CurlTransport you need to pass environment name and API key (license key):
    ```php
    use Stackify\Log\Transport\CurlTransport;
    use Stackify\Log\Monolog\Handler as StackifyHandler;
    
    $transport = new CurlTransport('api_key');
    $handler = new StackifyHandler('application_name', 'environment_name', $transport);
    ```
    ```yml
    # or configuration file example
    services:
        stackify_transport:
            class: "Stackify\\Log\\Transport\CurlTransport"
            arguments: ["api_key"]
        stackify_handler:
            class: "Stackify\\Log\\Monolog\\Handler"
            arguments: ["application_name", "environment_name", "@stackify_transport"]
    ```
###Configuration:
####Proxy
ExecTransport and CurlTransport support data delivery through proxy. Specify proxy using [libcurl format](http://curl.haxx.se/libcurl/c/CURLOPT_PROXY.html): <[protocol://][user:password@]proxyhost[:port]>
```php
$transport = new CurlTransport($apiKey, ['proxy' => 'https://55.88.22.11:3128']);
```

- <b>AgentTransport</b> does not require additional configuration on PHP side because all data is passed to the [Stackify agent](https://stackify.screenstepslive.com/s/3095/m/7787/l/119709-installation-for-linux), which must be installed on the same machine. Local TCP socket is used, so performance of your application is affected minimally.

## Troubleshooting
If transport does not work, try looking into ```vendor\stackify\logger\src\Stackify\debug\log.log``` file (if it is available for writing). Errors are also written to global PHP [error_log](http://php.net/manual/en/errorfunc.configuration.php#ini.error-log).
Note that ExecTransport does not produce any errors at all, but you can switch it to debug mode:
```php
$transport = new ExecTransport($apiKey, ['debug' => true]);
```

By default handler requires [Stackify agent](https://stackify.screenstepslive.com/s/3095/m/7787/l/119709-installation-for-linux) to be running.
There are other ways to send data, read more in [Monolog package documentation](https://github.com/stackify/stackify-log-monolog),
all transports are available for standalone logger as well.


## License

Copyright 2015 Stackify, LLC.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.