<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <i class="bi bi-calendar-event fs-1"></i>
            <h6 class="sidebar-heading mt-2">Gestión de Eventos</h6>
        </div>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/dashboard/') ? 'active' : ''; ?>" href="../dashboard/index.php">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/eventos/') ? 'active' : ''; ?>" href="../eventos/index.php">
                    <i class="bi bi-calendar-event"></i>
                    Eventos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/ponentes/') ? 'active' : ''; ?>" href="../ponentes/index.php">
                    <i class="bi bi-person-badge"></i>
                    Ponentes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/participantes/') ? 'active' : ''; ?>" href="../participantes/index.php">
                    <i class="bi bi-people"></i>
                    Participantes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], '/inscripciones/') ? 'active' : ''; ?>" href="../inscripciones/index.php">
                    <i class="bi bi-clipboard-check"></i>
                    Registros
                </a>
            </li>
        </ul>

        <hr>
        
    
    </div>
</nav>
