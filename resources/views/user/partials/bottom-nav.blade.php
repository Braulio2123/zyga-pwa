<style>
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    padding: 12px 20px;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
    border-radius: 25px 25px 0 0;
    z-index: 100;
}

.nav-item {
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 8px 16px;
    border-radius: 12px;
    text-decoration: none;
    color: #666;
    min-width: 70px;
}

.nav-item.active {
    color: #ff6a00;
    background: rgba(255,106,0,0.1);
    transform: translateY(-2px);
}

.nav-icon {
    font-size: 24px;
    margin-bottom: 4px;
    display: block;
}

.nav-item span:last-child {
    font-size: 11px;
    font-weight: 500;
    display: block;
}

/* Botón de cerrar sesión */
.logout-item {
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 8px 16px;
    border-radius: 12px;
    text-decoration: none;
    color: #dc2626;
    min-width: 70px;
    background: none;
    border: none;
}

.logout-item:hover {
    background: rgba(220,38,38,0.1);
    transform: translateY(-2px);
}

.logout-icon {
    font-size: 24px;
    margin-bottom: 4px;
    display: block;
}

.logout-item span:last-child {
    font-size: 11px;
    font-weight: 500;
    display: block;
}
</style>

<nav class="bottom-nav">
    <a href="{{ url('/user') }}" class="nav-item {{ request()->is('user') ? 'active' : '' }}">
        <span class="nav-icon">🏠</span>
        <span>Inicio</span>
    </a>

    <a href="{{ url('/user/historial') }}" class="nav-item {{ request()->is('user/historial') ? 'active' : '' }}">
        <span class="nav-icon">📜</span>
        <span>Historial</span>
    </a>

    <a href="{{ url('/user/billetera') }}" class="nav-item {{ request()->is('user/billetera') ? 'active' : '' }}">
        <span class="nav-icon">👛</span>
        <span>Billetera</span>
    </a>

    <a href="{{ url('/user/cuenta') }}" class="nav-item {{ request()->is('user/cuenta') ? 'active' : '' }}">
        <span class="nav-icon">👤</span>
        <span>Cuenta</span>
    </a>

    <!-- Botón de cerrar sesión -->
    <button class="logout-item" onclick="logout()">
        <span class="logout-icon">🚪</span>
        <span>Salir</span>
    </button>
</nav>

<script>
function logout() {
    if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        localStorage.removeItem('zyga_token');
        localStorage.removeItem('zyga_user');
        window.location.href = '/login';
    }
}
</script>