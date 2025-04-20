# Sistema de Gestión de Reservas

Este proyecto es una API RESTful desarrollada en Laravel 12 para gestionar clientes, mesas y reservas en un restaurante. Incluye un frontend desarrollado con Vue 3 que consume la API.

## 🧰 Tecnologías

- PHP 8.2
- Laravel 12
- MySQL
- Vue 3
- Vue router
- Vuetify
- Axios
- Pinia
- Autenticación: Laravel Sanctum
- Documentado con Swagger
- Docker y Docker compose

## 📦 Instalación

<ul>
  <li>
    Clonar el repositorio
  </li>
  
  <li>
    Copiar el archivo .env.example a .env
  </li>

  <li>
    Inicia los contenedores: <b><i>docker-compose up -d --build</i></b>
  </li>

  <li>
    Generar el app key: <b><i>docker-compose run --rm artisan key:gen</i></b>
  </li>
  
  <li>
    Instalar dependencias: <b><i>docker-compose run --rm --user restaurant composer install</i></b>
  </li>
  
  <li> 
    Ejecutar las migraciones: <b><i>docker-compose run --rm artisan migrate</i></b>
  </li>

  <li>
    Ejecutar los seeders: <b><i>docker-compose run --rm artisan db:seed</i></b>
  </li>

  <li>
    Acceder a http://localhost
  </li>

  <li>
    Iniciar sesión con las credenciales: <br>
        email: test@example.com
        contraseña: password
  </li>

  <li>
    Para acceder a la documentación de la API entrar a: http://localhost/api/documentation
  </li>
</ul>
