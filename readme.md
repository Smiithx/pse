## Requerimientos

- PHP >= 7.0
- Composer.

## Instalación

Instalar las dependencias del proyecto con el comando

```
composer install
```

Copiar y configurar el archivo de configuracion .env

```
cp .env.example .env
```

Instalar la key de la aplicacion

```
php artisan key:generate
```

Crear la base de datos y ejecutar la migracion junto a los seeders

```
php artisan migrate --seed
```

## Uso

Ejecutar la aplicación a traves del comando o alojarla en algun servidor.

```
 php artisan serve
 ```