# Licensor

The license management system built on top of Laravel framework. If you sell your scripts (not necessarily based on Laravel) and would like to manage license keys remotely - this package can be useful to you.
Works in pair with [sribna/licensee](http://github.com/sribna/licensee)

## Features
 - Plans and plan features
 - Middleware to check user access according to purchased plan
 - Key activation/deactivation
 - Periodic key verification

## How it works

The customer chooses a plan and receives a generated public key. The public key is not valid until activated.
To activate, the customer sends you the public key from his server (where your cool script is running).
You send the private key to the domain from which activation request was sent.
The client receives your response and saves the private key on his server. Now his app is fully functional.
In the future, the client will periodically send requests to update his private key.
If no updates are received within a certain period, then his script stops working.

## Installation

Via Composer

``` bash
$ composer require sribna/licensor
```

## Usage

See [documentation](docs)

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.
