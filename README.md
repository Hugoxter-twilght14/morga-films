![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![DOMPDF](https://img.shields.io/badge/DOMPDF-PDF%20Generator-EC1C24?style=for-the-badge&logo=adobeacrobatreader&logoColor=white)

![Morga Films](https://img.shields.io/badge/MORGA%20FILMS-Cine%20Digital-FFD700?style=for-the-badge&logo=clapperboard&logoColor=black)
![Sesiones Fotográficas](https://img.shields.io/badge/Sesiones%20Fotográficas-FF69B4?style=for-the-badge&logo=camera&logoColor=white)
![Reservas Web](https://img.shields.io/badge/Reservas-Web-blue?style=for-the-badge&logo=calendar&logoColor=white)

# morga-films
> App web profesional para gestión de sesiones fotográficas, reservas y administración de clientes.

📖 Descripción general

Morga Films es una aplicación web creada para gestionar sesiones fotográficas, clientes y reservas.
Cuenta con un panel administrativo, generación de recibos PDF automáticos mediante DOMPDF, y diseño responsive con Bootstrap 5.

Su objetivo es optimizar la organización de sesiones, mejorar la comunicación con los clientes y digitalizar los procesos internos de la empresa.

Características principales:

📸 Registro y gestión de sesiones fotográficas

👤 Módulo de clientes (CRUD completo)

📅  Agenda de citas.

🧾 Generación automática de recibos PDF.

🔐 Sistema de login seguro con contraseñas cifradas.

💬 Panel administrativo y de cliente separados.

📱 Interfaz responsive compatible con móviles y tablets.

⚙️ Instalación y ejecución

> ⚠️ Requisitos previos:

PHP 8.0 o superior.

MySQL 8.0

Servidor local Xampp.

composer.

🔧 Pasos de instalación

# 1️⃣ Clona el repositorio
git clone https://github.com/Hugoxter-twilght14/morga_films

# 2️⃣ Copia los archivos en tu carpeta del servidor local
C:\xampp\htdocs\MORGA-FILMS

# 3️⃣ Crea la base de datos en phpMyAdmin
con el nombre: morga_films

# 4️⃣ Importa el archivo SQL
morga_films.sql descargalo desde:

# 5️⃣ Configura la conexión y credenciales en el .env:

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

# 7️⃣ Ejecutar
> composer install 
en la terminal del proyecto

> Sirve para instalar las librerias y dependencias para el correcto funcionamiento del proyecto.

# 8️⃣ Si ya instalo Xampp, verificar que los servicios Apache y MySQL estén corriendo en el panel de xampp.

# 9️⃣ si se usa VSCode instalar la extensión de PHP server y afines para el correcto funcionamiento del programa.

# 1️⃣0️⃣ entrar a la carpeta public del proyecto desde la termina
> cd public
Buscar el archivo index.php en la carpeta public, abrirlo y dar clic derecho en el código.

Y a continuación, dar clic en la opción: 
> Run php server o reloaded php server esto para lanzar el servidor de php y ejecutarlo en el navegador.

# 1️⃣1️⃣ Cada vez que encienda su computadora debe verificar que xampp este en ejecución con los servicios de Apache y MySQL
una vez corriendo los servicios, para acceder al programa en el navegador ingrese la siguiente url: 

http://localhost/MORGA-FILMS/public/index.php

y listo el programa se ejecutara en su navegador.


💻 Tecnologías utilizadas

Categoría	Tecnología	Versión	Propósito

Lenguaje:	PHP	8.0+	 - Backend principal.
Base de datos:	 MySQL	8.0 - Gestión y	Almacenamiento de datos.
Frontend:	Bootstrap	 5 - Diseño responsivo
JavaScriptES6 -	Interactividad y estilo
DOMPDF	-	Generación y exportación de PDFs
HTML5 - Maquetado web.
CSS3 - Estilos.

![XAMPP](https://img.shields.io/badge/XAMPP-Localhost-F37623?style=for-the-badge&logo=xampp&logoColor=white)
![Visual Studio Code](https://img.shields.io/badge/VS%20Code-Editor-007ACC?style=for-the-badge&logo=visualstudiocode&logoColor=white)
![Git](https://img.shields.io/badge/Git-Control%20de%20Versiones-F05032?style=for-the-badge&logo=git&logoColor=white)
![GitHub](https://img.shields.io/badge/GitHub-Repositorio-181717?style=for-the-badge&logo=github&logoColor=white)


![Localhost](https://img.shields.io/badge/Hosting-Localhost-lightgrey?style=for-the-badge)
![Producción](https://img.shields.io/badge/Producción-Por%20Configurar-yellow?style=for-the-badge)

👨‍💻 Autor:

Desarrollador: Hugo Andres Borraz González - By HB Studios.

Mi portafolio:


Correo: hbstudiosoficial14@gmail.com 

GitHub: @Hugoxter-twilght14 

Web oficial de HB Studios:
https://hb-studios-official.vercel.app/

📘 Licencia

Este proyecto está bajo la licencia MIT.
Puedes usar, modificar y distribuir libremente el código, siempre que se mantenga el crédito al autor original.

![Estado](https://img.shields.io/badge/Estado-En%20Desarrollo-yellow?style=for-the-badge)
![Versión](https://img.shields.io/badge/Versión-1.0.0-blue?style=for-the-badge)
![Licencia](https://img.shields.io/badge/Licencia-MIT-green?style=for-the-badge)