# morga-films
Se debe instalar Xampp.
se debe colocar el proyecto en la ruta "C:/Xampp/htdocs"

configurar un archivo .env en la raiz del proyecto con las siguientes credenciales:

# App
APP_URL=http://localhost/MORGA-FILMS
APP_DEBUG=true
SESSION_NAME=morgafilms_sess

APP_DEBUG=true

# DB
DB_CONNECTION=mysql
DB_HOST= host donde corre el server (siempre es 127.0.0.1 o localhost)
DB_PORT= El puerto donde corre xampp mysql (por lo general siempre es 3306)
DB_DATABASE=morga_films
DB_USERNAME= Tu usuario de Base de datos
DB_PASSWORD= Tu contrasena de la base de datos (por lo general xampp no viene con contrasena)

# Seguridad
SESSION_KEY= Llave de sesion proveniente de Auth.js
JWT_SECRET= Token secret

# Correo (opcional)
MAIL_HOST=smtp.tu-dominio.com
MAIL_PORT=587
MAIL_USER=usuario@dominio.com
MAIL_PASS=contraseña_super_segura
MAIL_FROM=notificaciones@dominio.com

ejecutar composer install para instalar las librerias y dependencias para el correcto funcionamiento del proyecto.
para correr el programa primero ejecuta los servicios de Apache y MySQL de Xampp y despues ingresa en el navegador la url: http://localhost/MORGA-FILMS/public/index.php

y listo el programa se ejecutara.
