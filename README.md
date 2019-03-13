#####Тестирование API
Запуск тестового сервера

```vagrant ssh```

```php -S 127.0.0.1:8080 -t /app/api/web```

Запуск тестов на удаленном сервере

```cd /app/ && vendor/bin/codecept run -- -c api```
