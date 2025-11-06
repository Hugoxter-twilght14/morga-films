<p align="center">
  <img src="./assets/logo_morga.png" alt="Logotipo de Morga Films" width="200"/>
</p>

<h1 align="center">MORGA FILMS</h1>
<p align="center">
  Aplicación web profesional para gestión de sesiones fotográficas, reservas y administración de clientes.
</p>

<p align="center">

  <!-- Estado y licencia -->
  <img src="https://img.shields.io/badge/Estado-En%20Desarrollo-yellow?style=for-the-badge">
  <img src="https://img.shields.io/badge/Versión-1.0.0-blue?style=for-the-badge">
  <img src="https://img.shields.io/badge/Licencia-MIT-green?style=for-the-badge"><br>

  <!-- Tecnologías principales -->
  <img src="https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white">
  <img src="https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql&logoColor=white">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white">
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white">
  <img src="https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black">
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white">
  <img src="https://img.shields.io/badge/DOMPDF-PDF%20Generator-EC1C24?style=for-the-badge&logo=adobeacrobatreader&logoColor=white"><br>

  <!-- Identidad del proyecto -->
  <img src="https://img.shields.io/badge/MORGA%20FILMS-Cine%20Digital-FFD700?style=for-the-badge&logo=clapperboard&logoColor=black">
  <img src="https://img.shields.io/badge/Sesiones Fotográficas-FF69B4?style=for-the-badge&logo=camera&logoColor=white">
  <img src="https://img.shields.io/badge/Reservas-Web-blue?style=for-the-badge&logo=calendar&logoColor=white">
</p>

---

Descripción general

**Morga Films** es una aplicación web creada para gestionar **sesiones fotográficas, clientes y reservas**.  
Incluye un **panel administrativo**, **generación automática de recibos PDF** mediante DOMPDF y un **diseño responsive** con Bootstrap 5.

El sistema busca **optimizar la organización de sesiones**, mejorar la comunicación con los clientes y **digitalizar los procesos internos** de la empresa.

---

## Características principales

- ✏️ Registro y gestión de sesiones fotográficas  
- 📆 Agenda de citas y reservas  
- 🖨 Generación automática de recibos PDF  
- 👤 Sistema de inicio de sesión seguro con contraseñas cifradas  
- 🖥 Panel administrativo y de cliente separado  
- 📱 Interfaz completamente responsiva y adaptable  

---

## 📥 Instalación y ejecución

**Requisitos previos**
> - PHP 8.0 o superior  
> - MySQL 8.0  
> - Servidor local (XAMPP / Laragon)  
> - Compositor instalado en tu sistema  

### 📥 Pasos de instalación

# 1. Clona el repositorio:
> git clone https://github.com/Hugoxter-twilght14/morga_films

# 2. Copiar los archivos en la carpeta del servidor local:
> C:\xampp\htdocs\MORGA-FILMS

# 3. Crea la base de datos en phpMyAdmin
nombrar la base de datos de la siguiente manera: > morga_films

# 4. Importa el archivo SQL (morga_films.sql)
# (descarga aqui: )

# Configura tus credenciales en el archivo .env
```

### Ejemplo de configuración `.env`


# App
URL_DE_LA_APLICACIÓN=http://localhost/MORGA-FILMS
APP_DEBUG=true
SESSION_NAME=morgafilms_sess

# DB
DB_CONNECTION=mysql
DB_HOST=127.0.0.1 o localhost
PUERTO_DB=3306 (o el que venga de xampp)
DB_NAME=morga_films
DB_USERNAME=root
CONTRASEÑA DE LA BASE DE DATOS= (tu contraseña de mysql)

# Seguridad
SESSION_KEY=LlaveSesionGenerada
JWT_SECRET=TokenSecretoJWT

# Correo SMTP
MAIL_HOST=smtp.tu-dominio.com
PUERTO DE CORREO=587
MAIL_USER=usuario@dominio.com
MAIL_PASS=contraseña_segura
MAIL_FROM=example@dominio.com
```

### Ejecución del proyecto

# 6. Instala las dependencias con:
```
composer install
```

desde la raiz del proyecto.

> Esto instalará todas las librerías necesarias para el correcto funcionamiento del proyecto. <

1. Verifica que **Apache** y **MySQL** estén corriendo en el panel de **XAMPP**.  
2. Abra el proyecto en **Visual Studio Code**.  
3. Si usas la extensión **PHP Server**, ejecuta desde la carpeta `/public`:
   ```
   cd públic
   ```
   Luego haz clic derecho en `index.php`  **Run PHP server** o **Reloaded php server** 
 
4. Abra en el navegador la URL:
   ```
   http://localhost/MORGA-FILMS/public/index.php
   ```

> *Cada vez que inicias tu computadora, asegúrate de que Apache y MySQL están activos en XAMPP antes de abrir el sistema.*

---

## Tecnologías utilizadas

| Categoría | Tecnología | Versión | Propósito |
|------------|-------------|----------|------------|
| Idioma | PHP | 8.0+ | Director de back-end |
| Base de datos | MySQL | 8.0 | Almacenamiento de datos |
| Interfaz | Arranque | 5.3 | Diseño responsivo |
| Guiones | JavaScript (ES6) | - | Interactividad |
| Documentos | DOMPDF | - | Exportación de PDFs |
| Estructura | HTML5 | - | Maquetado |
| Estilos | CSS3 | - | Diseño visual |

---

## Herramientas de desarrollo

![XAMPP](https://img.shields.io/badge/XAMPP-Localhost-F37623?style=for-the-badge&logo=xampp&logoColor=white)
![Código de Visual Studio](https://img.shields.io/badge/VS%20Code-Editor-007ACC?style=for-the-badge&logo=visualstudiocode&logoColor=white)
![Git](https://img.shields.io/badge/Git-Control%20de%20Versiones-F05032?style=for-the-badge&logo=git&logoColor=white)
![GitHub](https://img.shields.io/badge/GitHub-Repositorio-181717?style=for-the-badge&logo=github&logoColor=white)

---

## Host

![Host local](https://img.shields.io/badge/Hosting-Localhost-lightgrey?style=for-the-badge)
![Producción](https://img.shields.io/badge/Producción-Por%20Configurar-yellow?style=for-the-badge)


## &copy Licencia

Este proyecto está bajo la licencia **MIT**.  
Puedes usar, modificar y distribuir libremente el código, **siempre que mantengas el crédito al autor original**.

---

<p align="center">
  <img src="https://img.shields.io/badge/MORGA%20FILMS-Cine%20Digital-FFD700?style=for-the-badge&logo=clapperboard&logoColor=black">
  <img src="https://img.shields.io/badge/Hecho%20con-%E2%9D%A4-red?style=for-the-badge">
</p>