## Установка

composer install
cp .env .env.example
php artisan key:generate

## Настройка базы данных
```sh
touch database/database.sqlite
```

```sh
php artisan migrate
```

```sh
php artisan orchid:install
```
Выбрать ответ "no"

```sh
php artisan orchid:admin admin 1@1.ru 123456
```


## Вход в панель администратора
Логин: 1@1.ru
Пароль: 123456