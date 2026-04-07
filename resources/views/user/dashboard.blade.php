@extends('user.layouts.app')

@section('title', 'Zyga | Inicio')
@section('page-title', 'Inicio')

@section('content')
<style>
/* ============================================
   ESTILOS NUEVOS (de la vista que me pasaste)
   ============================================ */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: #ffffff;
    font-family: 'Poppins', sans-serif;
}

/* Hero Card */
.hero-card {
    background: linear-gradient(135deg, #ff6a00 0%, #ff8c2e 100%);
    color: rgb(255, 255, 255);
    padding: 30px 24px;
    border-radius: 0 0 30px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.hero-kicker {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 8px;
}

.hero-card h2 {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 8px;
}

.hero-card .muted {
    opacity: 0.85;
    font-size: 14px;
}

.hero-badge {
    background: rgba(255,255,255,0.2);
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 500;
}

/* Section Block */
.section-block {
    margin: 24px 16px;
}

.section-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.section-head h3 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.pill {
    background: #e9ecef;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    color: #666;
}

.pill-warning {
    background: #fff3cd;
    color: #856404;
}

.pill-success {
    background: #d4edda;
    color: #155724;
}

/* Services Grid */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 16px;
}

.service-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #f0f0f0;
}

.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(255,106,0,0.15);
    border-color: #ff6a00;
}

.service-icon {
    font-size: 40px;
    margin-bottom: 12px;
}

.service-card h4 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.service-card p {
    font-size: 12px;
    color: #666;
    line-height: 1.4;
}

/* Panel Card */
.panel-card {
    background: white;
    border-radius: 24px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-field-full {
    grid-column: span 2;
}

.form-field label {
    font-size: 13px;
    font-weight: 500;
    color: #555;
}

.form-field input,
.form-field select {
    padding: 12px;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    font-size: 14px;
    outline: none;
    transition: all 0.3s ease;
}

.form-field input:focus,
.form-field select:focus {
    border-color: #ff6a00;
    box-shadow: 0 0 0 2px rgba(255,106,0,0.1);
}

.form-actions {
    margin-top: 8px;
}

/* Buttons */
.btn-primary {
    background: linear-gradient(135deg, #ff6a00 0%, #ff8c2e 100%);
    border: none;
    padding: 14px 24px;
    border-radius: 16px;
    color: white;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255,106,0,0.3);
}

.btn-secondary {
    background: #6c757d;
    border: none;
    padding: 10px 20px;
    border-radius: 12px;
    color: white;
    font-weight: 500;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

/* Request Card */
.request-card {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
}

.request-main {
    flex: 1;
}

.request-main h4 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.request-main .muted {
    font-size: 12px;
    color: #666;
    margin-bottom: 8px;
}

.request-main p {
    font-size: 13px;
    color: #555;
    margin-bottom: 4px;
}

/* Stack List */
.stack-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.list-card {
    background: #f8f9fa;
    border-radius: 16px;
    padding: 16px;
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.list-card:hover {
    border-color: #ff6a00;
    transform: translateX(5px);
}

.list-card h4 {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.list-card p {
    font-size: 12px;
    color: #666;
    margin-bottom: 8px;
}

.meta-text {
    font-size: 10px;
    color: #999;
}

/* Map Section */
.map-section {
    margin: 24px 16px;
    background: white;
    border-radius: 24px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.map-section h3 {
    margin-bottom: 16px;
    color: #333;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

#map {
    height: 250px;
    width: 100%;
    border-radius: 16px;
    margin-bottom: 12px;
}

.location-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.location-btn {
    background: #ff6a00;
    border: none;
    padding: 8px 16px;
    border-radius: 12px;
    color: white;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
}

.location-search {
    margin-bottom: 12px;
}

.location-search input {
    width: 100%;
    padding: 12px;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    font-size: 14px;
}

/* Safety Grid */
.safety-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    text-align: center;
}

.safety-item {
    padding: 16px 12px;
    background: #f8f9fa;
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.safety-item:hover {
    background: #fff5e6;
    transform: translateY(-4px);
}

.safety-icon {
    font-size: 28px;
    margin-bottom: 8px;
    display: block;
}

.safety-item p {
    font-size: 11px;
    color: #666;
    font-weight: 500;
}



/* Message Toast */
.toast-message {
    position: fixed;
    bottom: 80px;
    left: 50%;
    transform: translateX(-50%);
    padding: 12px 24px;
    border-radius: 12px;
    z-index: 1000;
    font-size: 14px;
    animation: fadeInUp 0.3s ease;
}

.toast-success {
    background: #10b981;
    color: white;
}

.toast-error {
    background: #ef4444;
    color: white;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

@media (min-width: 768px) {
    .services-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .bottom-nav {
        max-width: 500px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 30px 30px 0 0;
    }
}
</style>

<div class="home-container">
    <!-- Hero Section -->
    <section class="hero-card">
        <div>
            <p class="hero-kicker">Bienvenido</p>
            <h2>Hola, <span id="userName">Cliente</span></h2>
            <p class="muted">
                Solicita apoyo vial, consulta el estado de tu servicio y revisa tus notificaciones.
            </p>
        </div>
        <div class="hero-badge">Cliente</div>
    </section>

    <!-- Servicios disponibles -->
    <section class="section-block">
        <div class="section-head">
            <h3>Servicios disponibles</h3>
            <span class="pill" id="servicesCount">Cargando...</span>
        </div>
        <div class="services-grid" id="servicesList">
            <div class="loading">Cargando servicios...</div>
        </div>
    </section>

    
    <!-- Solicitud en proceso -->
    <section class="section-block" id="activeRequestSection" style="display: none;">
        <div class="section-head">
            <h3>Solicitud en proceso</h3>
            <span class="pill pill-success" id="requestStatus">PENDIENTE</span>
        </div>
        <article class="panel-card request-card" id="activeRequestContent">
            <!-- Se llena dinámicamente -->
        </article>
    </section>

    <!-- Mapa de ubicación -->
    <section class="section-block">
        <div class="map-section">
            <h3>Tu ubicación actual</h3>
            <div class="location-search">
                <input type="text" id="searchLocation" placeholder="Buscar dirección o lugar...">
            </div>
            <div id="map"></div>
            <div class="location-info" id="locationInfo">
                <span>Obteniendo ubicación...</span>
                <button class="location-btn" onclick="getUserLocation()">Mi ubicación</button>
            </div>
        </div>
    </section>

    <!-- Notificaciones recientes -->
    <section class="section-block">
        <div class="section-head">
            <h3>Notificaciones recientes</h3>
            <span class="pill" id="notificationsCount">0</span>
        </div>
        <div class="stack-list" id="notificationsList">
            <div class="loading">Cargando notificaciones...</div>
        </div>
    </section>

    <!-- Seguridad -->
    <section class="section-block">
        <div class="safety-grid">
            <div class="safety-item" onclick="window.location.href='/user/safe-driving'">
                    <span class="safety-icon">🛡️</span>
                <p>Manejo seguro</p>
            </div>
            <div class="safety-item" onclick="reportAccident()">
                <span class="safety-icon">🚑</span>
                <p>Asistencia médica</p>
            </div>
            <div class="safety-item" onclick="reportAccident()">
                <span class="safety-icon">🆘</span>
                <p>Soporte</p>
            </div>
        </div>
    </section>

    
</div>

<script>
const API_BASE = 'https://zyga-api-production.up.railway.app/api/v1';
const token = localStorage.getItem('zyga_token');
let userData = {};
let services = [];
let vehicles = [];
let activeRequest = null;
let notifications = [];

// Verificar autenticación
if (!token) {
    window.location.href = '/login';
}

function showMessage(type, text) {
    const toast = document.createElement('div');
    toast.className = `toast-message toast-${type}`;
    toast.textContent = text;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Cargar usuario
async function loadUserData() {
    try {
        const response = await fetch(`${API_BASE}/me`, {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        if (response.status === 401) {
            localStorage.removeItem('zyga_token');
            window.location.href = '/login';
            return;
        }
        const data = await response.json();
        userData = data.data.user;
        document.getElementById('userName').textContent = userData.name || userData.email?.split('@')[0] || 'Cliente';
    } catch (error) {
        console.error('Error loading user:', error);
    }
}

// Cargar servicios
async function loadServices() {
    try {
        const response = await fetch(`${API_BASE}/services`, {
            headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();
        services = data.data || [];
        document.getElementById('servicesCount').textContent = services.length;
        renderServices();
    } catch (error) {
        console.error('Error loading services:', error);
    }
}

function renderServices() {
    const container = document.getElementById('servicesList');
    
    if (services.length === 0) {
        container.innerHTML = '<div class="loading">No hay servicios disponibles</div>';
        return;
    }
    
    container.innerHTML = services.map(service => {
        // Limpiar el nombre para la URL (sin espacios, minúsculas)
        const serviceSlug = service.name?.toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // eliminar acentos
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '') || 'servicio';
        
        return `
            <article class="service-card" onclick="goToServiceRequest(${service.id}, '${serviceSlug}')">
                <h4>${service.name}</h4>
                <p>${service.description || 'Solicita este servicio de asistencia vial'}</p>
            </article>
        `;
    }).join('');
}

function goToServiceRequest(serviceId, serviceSlug) {
    // Redirigir a la vista de solicitud de servicio
    window.location.href = `/user/service-request?service=${serviceSlug}&id=${serviceId}`;
}

function selectService(serviceId) {
    document.getElementById('service_id').value = serviceId;
    document.querySelector('.panel-card').scrollIntoView({ behavior: 'smooth' });
}

// Cargar vehículos
async function loadVehicles() {
    try {
        const response = await fetch(`${API_BASE}/client/vehicles`, {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const data = await response.json();
        vehicles = data.data || [];
        const vehicleSelect = document.getElementById('vehicle_id');
        vehicleSelect.innerHTML = '<option value="">Selecciona un vehículo</option>' +
            vehicles.map(v => `<option value="${v.id}">${v.brand || ''} ${v.model || 'Vehículo'} · ${v.plate || 'Sin placa'}</option>`).join('');
    } catch (error) {
        console.error('Error loading vehicles:', error);
    }
}

// Crear solicitud
async function createAssistanceRequest() {
    const serviceId = document.getElementById('service_id').value;
    const vehicleId = document.getElementById('vehicle_id').value;
    const address = document.getElementById('pickup_address').value;
    const lat = document.getElementById('lat').value;
    const lng = document.getElementById('lng').value;
    
    if (!serviceId) { showMessage('error', 'Selecciona un servicio'); return; }
    if (!vehicleId) { showMessage('error', 'Selecciona un vehículo'); return; }
    if (!address) { showMessage('error', 'Ingresa la dirección'); return; }
    
    const requestData = {
        service_id: parseInt(serviceId),
        vehicle_id: parseInt(vehicleId),
        pickup_address: address,
        lat: lat ? parseFloat(lat) : 20.6736,
        lng: lng ? parseFloat(lng) : -103.3440
    };
    
    try {
        const response = await fetch(`${API_BASE}/client/assistance-requests`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Error');
        showMessage('success', 'Solicitud creada correctamente');
        setTimeout(() => location.reload(), 2000);
    } catch (error) {
        showMessage('error', error.message);
    }
}

// Cargar solicitud activa
async function loadActiveRequest() {
    try {
        const response = await fetch(`${API_BASE}/client/assistance-requests`, {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const data = await response.json();
        const requests = data.data || [];
        activeRequest = requests.find(r => ['pending', 'assigned', 'in_progress'].includes(r.status));
        
        if (activeRequest) {
            document.getElementById('activeRequestSection').style.display = 'block';
            document.getElementById('requestStatus').textContent = activeRequest.status.toUpperCase();
            const vehicle = activeRequest.vehicle || {};
            document.getElementById('activeRequestContent').innerHTML = `
                <div class="request-main">
                    <h4>${activeRequest.service?.name || 'Servicio'}</h4>
                    <p class="muted">Folio: ${activeRequest.public_id || 'ZYGA-' + activeRequest.id}</p>
                    <p><strong>Vehículo:</strong> ${vehicle.brand || ''} ${vehicle.model || ''} · ${vehicle.plate || 'Sin placa'}</p>
                    <p><strong>Dirección:</strong> ${activeRequest.pickup_address || 'No especificada'}</p>
                    <p><strong>Proveedor:</strong> ${activeRequest.provider?.name || 'Buscando...'}</p>
                </div>
                <button type="button" class="btn-secondary" onclick="cancelRequest(${activeRequest.id})">Cancelar solicitud</button>
            `;
        }
    } catch (error) {
        console.error('Error loading active request:', error);
    }
}

async function cancelRequest(requestId) {
    if (!confirm('¿Cancelar esta solicitud?')) return;
    try {
        const response = await fetch(`${API_BASE}/client/assistance-requests/${requestId}/cancel`, {
            method: 'PATCH',
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        if (response.ok) {
            showMessage('success', 'Solicitud cancelada');
            location.reload();
        }
    } catch (error) {
        showMessage('error', 'Error al cancelar');
    }
}

// Cargar notificaciones
async function loadNotifications() {
    try {
        const response = await fetch(`${API_BASE}/notifications`, {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const data = await response.json();
        notifications = data.data || [];
        document.getElementById('notificationsCount').textContent = notifications.length;
        const container = document.getElementById('notificationsList');
        if (notifications.length === 0) {
            container.innerHTML = '<article class="list-card"><h4>No hay notificaciones</h4><p>No tienes notificaciones recientes.</p></article>';
        } else {
            container.innerHTML = notifications.slice(0, 5).map(n => `
                <article class="list-card">
                    <h4>${n.title || 'Notificación'}</h4>
                    <p>${n.message || ''}</p>
                    <span class="meta-text">${new Date(n.created_at).toLocaleDateString()}</span>
                </article>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

function reportAccident() {
    showMessage('success', 'Reportando accidente...');
    setTimeout(() => window.location.href = '/accident-report', 1000);
}

function navigateTo(section) {
    const routes = { 'home': '/home', 'history': '/history', 'wallet': '/wallet', 'profile': '/profile' };
    window.location.href = routes[section] || '/home';
}

// Google Maps (mantén tu código existente)
let map, marker, geocoder, autocomplete;
let currentLat = 20.659698;
let currentLng = -103.349609;

function waitForGoogleMaps() {
    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
        initMap(currentLat, currentLng);
        initAutocomplete();
    } else {
        setTimeout(waitForGoogleMaps, 500);
    }
}

function initMap(lat, lng) {
    const location = { lat, lng };
    map = new google.maps.Map(document.getElementById("map"), { zoom: 15, center: location });
    marker = new google.maps.Marker({ position: location, map: map, draggable: true });
    marker.addListener('dragend', () => {
        currentLat = marker.getPosition().lat();
        currentLng = marker.getPosition().lng();
        getAddressFromCoords(currentLat, currentLng);
        saveLocation();
    });
    geocoder = new google.maps.Geocoder();
}

function initAutocomplete() {
    const input = document.getElementById('searchLocation');
    if (input) {
        autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (place.geometry) {
                map.setCenter(place.geometry.location);
                marker.setPosition(place.geometry.location);
                currentLat = place.geometry.location.lat();
                currentLng = place.geometry.location.lng();
                document.getElementById('locationInfo').innerHTML = `
                    <span>📍 ${place.formatted_address}</span>
                    <button class="location-btn" onclick="getUserLocation()">Mi ubicación</button>
                `;
                saveLocation();
            }
        });
    }
}

function getUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                currentLat = position.coords.latitude;
                currentLng = position.coords.longitude;
                map.setCenter({ lat: currentLat, lng: currentLng });
                marker.setPosition({ lat: currentLat, lng: currentLng });
                getAddressFromCoords(currentLat, currentLng);
                saveLocation();
                showMessage('success', 'Ubicación actualizada');
            },
            () => showMessage('error', 'No se pudo obtener la ubicación')
        );
    }
}

function getAddressFromCoords(lat, lng) {
    geocoder.geocode({ location: { lat, lng } }, (results, status) => {
        if (status === 'OK' && results[0]) {
            document.getElementById('locationInfo').innerHTML = `
                <span>📍 ${results[0].formatted_address}</span>
                <button class="location-btn" onclick="getUserLocation()">Mi ubicación</button>
            `;
        }
    });
}

function saveLocation() {
    localStorage.setItem('user_lat', currentLat);
    localStorage.setItem('user_lng', currentLng);
}

function loadSavedLocation() {
    const savedLat = localStorage.getItem('user_lat');
    const savedLng = localStorage.getItem('user_lng');
    if (savedLat && savedLng) {
        currentLat = parseFloat(savedLat);
        currentLng = parseFloat(savedLng);
        initMap(currentLat, currentLng);
        getAddressFromCoords(currentLat, currentLng);
    } else {
        getUserLocation();
        waitForGoogleMaps();
    }
}

// Inicializar
async function init() {
    await loadUserData();
    await loadServices();
    await loadVehicles();
    await loadActiveRequest();
    await loadNotifications();
    loadSavedLocation();
}

init();
</script>
@endsection