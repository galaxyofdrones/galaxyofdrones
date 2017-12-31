# Galaxy of Drones Online

An open source multiplayer space strategy game.

![Screenshot](public/images/screenshot.png)

## Server

https://galaxyofdrones.com/

## Website

https://koodilab.com/en/game/galaxy-of-drones-online

## About the game

The game story takes place in a virtual galaxy where randomly generated planets produce various raw materials which can be used by the Players to develop their infrastructure and fleet. In addition, players may trade with the Earth or engage in battles, so beyond various military arrangements, economic decisions should be taken as well.

## Installation

### Step 1: Clone the repositroy

```
git clone git@github.com:koodilab/galaxyofdrones-online.git
```

### Step 2: Configure the database and redis connection

```
cp .env.example .env
```

### Step 3: Install the composer dependencies

```
composer install --no-dev
```

### Step 4: Set the application key

```
php artisan key:generate
```

### Step 5: Run the migrations and seeds

```
php artisan migrate --seed
```

### Step 6: Generate the Laravel Passport keys

```
php artisan passport:keys
```

### Step 7: Generate the starmap

(estimated time: ~1 hour, estimated size: ~4 Gb)

```
php artisan starmap:generate
```

### Step 8: Run the Laravel Queue

```
php artisan queue:work --sleep=1 --tries=3
```

### Step 9: Run the Laravel Scheduler

```
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

### Step 10: Install and setup Laravel Echo Server

(use the `/ws` as socket.io path)

```
...
"socketio": {
  "path": "/ws"
}
...
```

### Step 11: Setup the websocket in webserver config

(example nginx configuration)

```
location /ws {
    proxy_pass http://127.0.0.1:6001;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection 'upgrade';
    proxy_set_header Host $host;
    proxy_cache_bypass $http_upgrade;
}
```

## Security

If you discover a security vulnerability within Galaxy of Drones Online, please send an e-mail to Koodilab at support@koodilab.com. All security vulnerabilities will be promptly addressed.

## License

The Galaxy of Drones Online is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
