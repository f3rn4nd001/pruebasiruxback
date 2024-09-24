Asunto: Uso de Código para Examen o Prueba

Quisiera recordarle amablemente que el código que he proporcionado es de mi autoría y está destinado exclusivamente para la evaluacion. Le solicito que este código no sea utilizado ni distribuido sin mi consentimiento previo.

Gracias por su comprensión y respeto a mi trabajo.

Objetivo: 
Desarrollar una aplicación web que funcione como una libreta de direcciones 
avanzada, permitiendo a los usuarios gestionar sus contactos con información 
adicional, y realizar búsquedas basadas en múltiples atributos. Tecnologías 
(Angular, Laravel, MySQL, Git) 

Copiar en el archivo .env: cp extra.php .env

Librerías Usadas: JWT Authentication: composer require tymon/jwt-auth

CORS Middleware: composer require fruitcake/laravel-cors

UI Scaffolding: composer require laravel/ui

Axios para peticiones HTTP en el frontend: npm i axios

Generar la autenticación con Bootstrap: php artisan ui bootstrap --auth

Crear la base de datos: CREATE DATABASE sirux;

En el archivo scrip se encuentra la base de datos y los prosedimientos almacenados

cree un  seeder de estatus pero me di cuenta que tardaria mucho creando los demas asi que no es recomendabre correr (php artisan migrate:fresh --seed)


Ejecución del Proyecto: php artisan serve --host 0.0.0.0

Notas Adicionales: Implementa los middlewares necesarios para que los usuarios permitidos e activos puedan puedan acceder a las funciones sus.
Si tienes alguna duda o necesitas más detalles, no dudes en preguntar.
