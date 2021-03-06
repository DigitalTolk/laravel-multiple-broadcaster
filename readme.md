# Multiple Broadcast Driver for Laravel
This driver will enable your app to support multiple drivers for broadcasting.

# How does this driver work?
It will just loop through your specified broadcast connections and execute each just like when you set it as default.

## Requirements
You must already have configured broadcasting. It is my assumption that you already have a working broadcast configuration that you wish to extend it and support other broadcast driver simultaneously.

## Installation
1. `composer require digitaltolk/multiple-broadcaster`
2. Next, go into your `config/broadcasting.php` file and add the following lines accordingly:
    ```php
       return [
           ........
        
           'connections' => [
               'multiple' => [
                   'driver' => 'multiple',
                   'connections' => ['log', 'pusher'],
               ],
           ]
       ];
     ```
3. Lastly, on your `.env` file, set the `BROADCAST_DRIVER` to `multiple`.

## Authors
* [Jillberth Estillore](https://github.com/jestillore)
