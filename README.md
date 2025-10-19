# morga-films
Se debe instalar Xampp en su última versión.
se debe instalar composer.

#Ruta del proyecto
se debe colocar el proyecto en la ruta "C:/Xampp/htdocs"

#Credenciales
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
DB_PASSWORD= Tu contraseña de la base de datos (por lo general xampp no viene con contraseña)

# Seguridad
SESSION_KEY= Llave de sesion proveniente de Auth.js
JWT_SECRET= Token secret json

# Credenciales de correo smtp
MAIL_HOST=smtp.tu-dominio.com
MAIL_PORT=587
MAIL_USER=usuario@dominio.com
MAIL_PASS=contraseña_segura
MAIL_FROM=example@dominio.com

#Instrucciones de instalación y ejecución local.
1. Ejecutar composer install en la terminal del proyecto - Sirve para instalar las librerias y dependencias para el correcto funcionamiento del proyecto.

2. Su ya instalo Xampp, verificar que los servicios Apache y MySQL estén corriendo en el panel de xampp.

3. si se usa VSCode instalar la extensión de PHP server.

4. entrar a la carpeta public del proyecto desde la terminal -- ejecutar cd public y dar enter.

5. buscar el archivo index.php en la carpeta public, abrirlo y dar clic derecho en el código.

A continuación, dar clic en la opción Run php server o reloaded php server - esto para lanzar el servidor de php y ejecutarlo en el navegador.

6. Cada vez que encienda su computadora debe verificar que xampp este en ejecución con los servicios de Apache y MySQL
una vez corriendo los servicios, para acceder al programa en el navegador ingrese la siguiente url: 

http://localhost/MORGA-FILMS/public/index.php

y listo el programa se ejecutara en su navegador
