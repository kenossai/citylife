web: php artisan octane:start --server=roadrunner --host=0.0.0.0 --port=$PORT
queue: php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600 --queue=default,notifications,emails
scheduler: php artisan schedule:work
