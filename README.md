<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Deployment webhook test: 2026-02-25.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Portal Web de Veterinarias (SaaS)

## Arquitectura General del Sistema

### Estructura de Carpetas

- **app/**: Contendrá los modelos, controladores y lógica de negocio.
  - **Models/**: Modelos Eloquent para las entidades principales (Veterinaria, Cliente, Mascota, etc.).
  - **Http/Controllers/**: Controladores para manejar las solicitudes HTTP.
  - **Http/Middleware/**: Middleware para autenticación y multi-tenancy.
- **config/**: Configuraciones del sistema (base de datos, autenticación, etc.).
- **database/**: Migraciones, seeders y factories para poblar la base de datos.
- **resources/views/**: Vistas Blade para el frontend.
- **routes/**: Definición de rutas (web.php, api.php).
- **public/**: Archivos públicos (CSS, JS, imágenes).
- **storage/**: Archivos generados por el sistema (logs, uploads).
- **tests/**: Pruebas unitarias y funcionales.

### Módulos Principales

1. **Autenticación**
   - Login tradicional (email + contraseña).
   - Login con Google (OAuth).
   - Recuperación de contraseña.

2. **Gestión de Veterinarias (Multi-Tenant)**
   - Cada veterinaria tiene su propio entorno aislado.
   - Middleware para restringir acceso a datos de otras veterinarias.

3. **CRUDs**
   - Clientes: Gestión de dueños de mascotas.
   - Mascotas: Registro y seguimiento de mascotas.
   - Veterinarios: Gestión del personal.
   - Citas: Programación y seguimiento de citas.

4. **Dashboard**
   - Total de clientes, mascotas, citas del día, veterinarios activos.
   - Gráficas y estadísticas.

5. **Funciones Avanzadas**
   - Historial clínico.
   - Recordatorios automáticos.
   - Facturación y pagos.
   - Exportación a PDF/Excel.

### Tecnologías

- **Backend**: Laravel (MVC, Eloquent, Middleware).
- **Base de Datos**: MySQL (relaciones, cascadas, validaciones).
- **Frontend**: Blade + Tailwind CSS.
- **Autenticación**: Laravel Auth + Google OAuth.
- **Estilo**: Tailwind CSS.
- **Arquitectura**: SaaS multi-tenant.

### Próximos Pasos

1. Crear diagrama de base de datos.
2. Generar migraciones y seeders.
3. Implementar modelos Eloquent.
4. Desarrollar controladores y rutas.
5. Diseñar vistas y dashboard.
6. Implementar autenticación y middleware.
7. Realizar pruebas y optimizaciones.
