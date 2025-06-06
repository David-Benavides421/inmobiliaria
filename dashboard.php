<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Inmobiliaria EL GITANO</title>
    <link rel="stylesheet" href="styles.css">
    </head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-title">
                Inmobiliaria EL GITANO
            </div>
            <div class="header-user">
                <span>Bienvenido, [Nombre Usuario]</span>
                <a href="/inmobiliaria/login/login.php">Cerrar Sesión</a>
            </div>
        </header>

        <aside class="dashboard-sidebar">
            <nav>
                <ul>
                    <li><a href="#dashboard" class="active"><span class="icon">🏠</span> Inicio</a></li>

                    <li class="menu-separator">Gestión Principal</li>
                    <li><a href="/inmobiliaria/crud_clientes/clientes.php"><span class="icon">👥</span> Clientes</a></li>
                    <li><a href="/inmobiliaria/propietarios/propietarios.php"><span class="icon">🔑</span> Propietarios</a></li>
                    <li><a href="/inmobiliaria/inmuebles/inmueble_crud.php"><span class="icon">🏘️</span> Inmuebles</a></li>
                    <li><a href="/inmobiliaria/tipo_inmueble/tipo_inmueble.php"><span class="icon">🏷️</span> Tipos de Inmueble</a></li>
                    <li><a href="/inmobiliaria/crud_contratos/contratos.php"><span class="icon">📜</span> Contratos</a></li>
                    <li><a href="/inmobiliaria/visitas/visitas.php"><span class="icon">📅</span> Visitas</a></li>
                    <li><a href="/inmobiliaria/inspeccion/inspecciones.php"><span class="icon">🔍</span> Inspección</a></li>

                    <li class="menu-separator">Gestión Interna</li>
                    <li><a href="/inmobiliaria/crud_empleados/empleados.php"><span class="icon">🧑‍💼</span> Empleados</a></li>
                    <li><a href="#usuarios"><span class="icon">👤</span> Usuarios</a></li>

                    <li><a href="/inmobiliaria/oficinas/oficinas_crud.php"><span class="icon">🏢</span> Oficinas</a></li>

                    <li><a href="/inmobiliaria/cargos/cargos.php"><span class="icon"> 🪑</span> Cargos</a></li>



                    <li class="menu-separator">Gestión Comercial</li>
                    <li><a href="#productos"><span class="icon">📦</span> Productos</a></li>
                    <li><a href="#categoria"><span class="icon">📑</span> Categoría (Prod.)</a></li>
                    <li><a href="#proveedores"><span class="icon">🚚</span> Proveedores</a></li>
                    <li><a href="#ordenes_compra"><span class="icon">🛒</span> Órdenes de Compra</a></li>
                    <li><a href="#almacenes"><span class="icon">🏭</span> Almacenes</a></li>
                    <li><a href="#detalle_orden"><span class="icon">📋</span> Detalle Órdenes</a></li>


                    <li class="menu-separator">Cuenta</li>
                    <li><a href="#perfil"><span class="icon">⚙️</span> Mi Perfil</a></li>
                    <li><a href="/inmobiliaria/login/login.php"><span class="icon">🚪</span> Cerrar Sesión</a></li>
                </ul>
            </nav>
        </aside>

        <main class="dashboard-content">
            <section id="dashboard-content">
                <h1>Bienvenido al Dashboard</h1>
                <p>Selecciona una opción del menú lateral para comenzar a gestionar la información.</p>
                <p>Recuerda que cada módulo debe permitir Agregar, Editar, Eliminar y Consultar registros.</p>
                </section>

            <section id="clientes-content" style="display: none;">
                <h1>Gestión de Clientes</h1>
                <div class="actions-bar">
                    <button class="btn btn-add">[+] Agregar Cliente</button>
                    <input type="text" placeholder="Buscar cliente..." class="search-input">
                </div>
                <table class="data-table">
                    </table>
            </section>

            </main>
    </div>

    
    </body>
</html>