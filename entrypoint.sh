#!/bin/bash

php artisan migrate:fresh --seed

exec "$@"