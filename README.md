## Set up instructions
This application  has memcached as a dependency and it must be installed, below are easy step for setting up memcached.


## Setting Up Memcached

### On the server
Run the following commands to install memcached

```console
sudo apt update
sudo apt install memcached
```

Activate memcached

```console
sudo systemctl enable memcached
sudo systemctl start memcached

```

### Configure Cache Driver in Laravel .env file
```console
CACHE_DRIVER=memcached

```

### Add the following configurations to config/cache.php

```console
'memcached' => [
    'servers' => [
        [
            'host' => env('MEMCACHED_HOST', '127.0.0.1'),
            'port' => env('MEMCACHED_PORT', 11211),
            'weight' => 100,
        ],
    ],
],
```

### Finally, add your open weather apikey to .env file
 ```
 WEATHER_API_KEY=your_openweathermap_api_key

```
