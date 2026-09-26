# NotificatioHub

Hub de notificaciones como servicio, esto para tener un lugar centralizado para canalizar notificaciones a diferentes proveedores.

## Stack

- PHP 8.5 and Laravel 13
- MySQL, Redis

## Instalación local

Como requsitos minimos es necesario tener Docker y Git instalados de un inicio. Luego seguir los sigientes pasos que van desde
clonar el repositorio a poder ejecutar las colas de mensajeria para el procesamiento en background.

```bash
git clone https://github.com/wafto/notification-hub.git
cd notification-hub

# copiamos el example env a la que usaremos, por el momento usaremos los valores por defecto.
cp .env.example .env

# instalamos las bibliotecas de PHP con composer
docker run --rm -v "$(pwd)":/app -w /app composer install

# para docker usamos una biblioteca de laravel la cual hace más sencillo lanzarlo.
./vendor/bin/sail up -d

# generamos un app key nuevo
./vendor/bin/sail artisan key:generate

# corremos migraciones
./vendor/bin/sail artisan migrate

# ya que usamos colas de mensajeria ejecutamos los workers que se necesiten para este caso con uno es suficiente.
./vendor/bin/sail artisan queue:work
```

Visitamos el sitio [http://127.0.0.1:8080](http://127.0.0.1:8080). Para ver la documentacion del **único** endpoint para la prueba
podemos entrar a [http://127.0.0.1:8080/docs/api#/operations/v1.notifications.store](http://127.0.0.1:8080/docs/api#/operations/v1.notifications.store) ahi podemos inclusive mandar el api request y verificar en la consola donde corre nuestro worker que consume el evento de manera async. si mandamos el mismo body sin cambiar el event_id mandara 422, esto como medida preventiva para evitar multiple eventos duplicados, el cliente es el encargado de manejar el event_id.

## Correr pruebas unitarias y de integración

Teniendo levantado el ambiente local correr en la terminal:

```bash
./vendor/bin/sail artisan test
```

## Detenener el ambiente local

Para detener el ambiente con tan solo ejecutar:

```bash
./vendor/bin/sail down
```

Si se llega a tener problemas por configuración y demás ya que este proyecto es de prueba y no importa de momento la data guardada, podemos eliminar todo con el flag -v.

```bash
./vendor/bin/sail down -v
```
