# Installation

1. Clone the repositroy

    ```
    git clone git@github.com:galaxyofdrones/galaxyofdrones.git
    ```

2. Configure the database and redis connection

    ```
    cp .env.example .env
    ```

3. Install the composer dependencies

    ```
    composer install --no-dev
    ```

4. Set the application key

    ```
    php artisan key:generate
    ```

5. Run the migrations and seeds

    ```
    php artisan migrate --seed
    ```

6. Generate the Laravel Passport keys

    ```
    php artisan passport:keys
    ```

7. Generate the starmap

    (estimated time: ~1 hour, estimated size: ~4 Gb)

    ```
    php artisan starmap:generate
    ```

8. Run the Laravel Queue

    ```
    php artisan queue:work --sleep=1 --tries=3
    ```

9. Run the Laravel Scheduler

    ```
    * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
    ```

10. Install and setup Laravel Echo Server

    (use the `/ws` as socket.io path)

    ```
    ...
    "socketio": {
      "path": "/ws"
    }
    ...
    ```

11. Setup the websocket in webserver config

    (example nginx configuration)

    ```
    ...
    location /ws {
        proxy_pass http://127.0.0.1:6001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
    ...
    ```

12. Have fun

    ```
    username: koodilab
    password: havefun
    ```
