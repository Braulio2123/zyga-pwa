@extends('user.layouts.app')

@section('title', 'Zyga | Inicio')
@section('page-title', 'Inicio')

@section('content')
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: #f8f9fa;
    font-family: 'Poppins', sans-serif;
}

.service-container {
    width: 100%;
    max-width: 100%;
    margin: 0;
    background: #f8f9fa;
    min-height: 100vh;
    padding-bottom: 30px;
}

/* Header - ocupará todo el ancho */
.header {
    background: linear-gradient(135deg, #ff6a00 0%, #ff8c2e 100%);
    color: white;
    padding: 25px 20px;
    border-radius: 0 0 25px 25px;
    margin-bottom: 20px;
    width: 100%;
}

.header h1 {
    font-size: 24px;
    margin-bottom: 5px;
    font-weight: 600;
}

.header p {
    opacity: 0.9;
    font-size: 14px;
}

/* Cards - con márgenes laterales reducidos */
.form-card {
    background: white;
    margin: 16px;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

/* Para pantallas muy pequeñas, reducir márgenes */
@media (max-width: 480px) {
    .form-card {
        margin: 12px;
        padding: 16px;
    }
    
    .header {
        padding: 20px 16px;
    }
}

.card-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    border-left: 3px solid #ff6a00;
    padding-left: 12px;
}

/* Ubicación */
.location-card {
    background: #fff9f0;
    border: 1px solid #ffe0b5;
}

.location-display {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 12px;
    margin-bottom: 12px;
}

.location-address {
    font-size: 14px;
    color: #333;
    font-weight: 500;
}

.location-coords {
    font-size: 12px;
    color: #666;
    margin-top: 4px;
}

.select-location-btn {
    background: none;
    border: 1px solid #ff6a00;
    color: #ff6a00;
    padding: 10px;
    border-radius: 12px;
    width: 100%;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.select-location-btn:hover {
    background: #ff6a00;
    color: white;
}

/* Opciones en grid */
.options-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.option-item {
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.option-item.selected {
    background: #ff6a00;
    border-color: #ff6a00;
    color: white;
}

.option-item.selected .option-icon {
    color: white;
}

.option-icon {
    font-size: 24px;
    margin-bottom: 8px;
    display: block;
}

.option-label {
    font-size: 13px;
    font-weight: 500;
}

/* Vehículo */
.vehicle-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 12px;
    margin-bottom: 12px;
}

.vehicle-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.vehicle-plate {
    font-size: 12px;
    color: #666;
    margin-top: 4px;
}

.change-vehicle-btn {
    background: none;
    border: none;
    color: #ff6a00;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    margin-top: 8px;
}

/* Notas */
.note-input {
    width: 100%;
    padding: 12px;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    font-size: 14px;
    resize: vertical;
    font-family: inherit;
}

.note-input:focus {
    outline: none;
    border-color: #ff6a00;
}

/* Método de pago */
.payment-methods {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.payment-method {
    flex: 1;
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-method.selected {
    background: #ff6a00;
    border-color: #ff6a00;
    color: white;
}

.payment-icon {
    font-size: 24px;
    margin-bottom: 4px;
    display: block;
}

.payment-label {
    font-size: 12px;
}

/* Botón enviar */
.btn-submit {
    width: calc(100% - 32px);
    margin: 0 16px 20px 16px;
    background: #ff6a00;
    border: none;
    padding: 16px;
    border-radius: 16px;
    color: white;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    background: #ff8c2e;
    transform: translateY(-2px);
}

.btn-back {
    width: calc(100% - 32px);
    margin: 0 16px 20px 16px;
    background: #6c757d;
}

.btn-back:hover {
    background: #5a6268;
}

/* Mensajes */
.message-box {
    margin: 0 16px 20px 16px;
    padding: 12px;
    border-radius: 12px;
    display: none;
}

.message-box.success {
    background: #d4edda;
    color: #155724;
    display: block;
}

.message-box.error {
    background: #f8d7da;
    color: #721c24;
    display: block;
}

/* Loading */
.loading {
    text-align: center;
    padding: 20px;
    color: #999;
}
</style>

<div class="service-container">
    <div class="header" id="serviceHeader">
        <h1 id="serviceTitle">Solicitar Servicio</h1>
        <p id="serviceSubtitle">Completa los detalles para solicitar asistencia</p>
    </div>

    <!-- Ubicación -->
    <div class="form-card location-card">
        <div class="card-title">📍 Ubicación</div>
        <div class="location-display" id="locationDisplay">
            <div class="location-address" id="locationAddress">Obteniendo ubicación...</div>
            <div class="location-coords" id="locationCoords"></div>
        </div>
        <button class="select-location-btn" onclick="selectLocation()">
            Seleccionar otra ubicación
        </button>
    </div>

    <!-- Motivo del servicio (dinámico según servicio) -->
    <div class="form-card" id="reasonCard">
        <div class="card-title" id="reasonTitle">Motivo del servicio</div>
        <div id="reasonOptions"></div>
    </div>

    <!-- Información del vehículo -->
    <div class="form-card">
        <div class="card-title">🚗 Información del vehículo</div>
        <div id="vehicleInfo">
            <div class="vehicle-card">
                <div class="vehicle-name" id="vehicleName">Cargando vehículos...</div>
                <div class="vehicle-plate" id="vehiclePlate"></div>
                <button class="change-vehicle-btn" onclick="selectVehicle()">
                    Seleccionar otro vehículo
                </button>
            </div>
        </div>
    </div>

    <!-- Datos adicionales -->
    <div class="form-card">
        <div class="card-title">📝 Datos adicionales</div>
        <textarea id="notes" class="note-input" rows="3" placeholder="Añade una nota (opcional)"></textarea>
    </div>

    <!-- Método de pago -->
    <div class="form-card">
        <div class="card-title">💳 Método de pago</div>
        <div class="payment-methods" id="paymentMethods">
            <div class="payment-method" onclick="selectPayment('cash')">
                <span class="payment-icon">💵</span>
                <span class="payment-label">Efectivo</span>
            </div>
            <div class="payment-method" onclick="selectPayment('card')">
                <span class="payment-icon">💳</span>
                <span class="payment-label">Tarjeta</span>
            </div>
            <div class="payment-method" onclick="selectPayment('transfer')">
                <span class="payment-icon">🏦</span>
                <span class="payment-label">Transferencia</span>
            </div>
        </div>
    </div>

    <button class="btn-submit" id="submitBtn" onclick="submitRequest()">
        Solicitar servicio
    </button>
    <button class="btn-submit btn-back" onclick="window.location.href='/home'">
        Volver al inicio
    </button>
</div>

<div id="messageBox" class="message-box"></div>

<script>

    const API_BASE = 'https://zyga-api-production.up.railway.app/api/v1';
    const token = localStorage.getItem('zyga_token');

let currentLocation = null;
let selectedReason = null;
let selectedPayment = 'cash';
let userVehicles = [];
let selectedVehicle = null;
let serviceType = null;

// Configuración por tipo de servicio
const serviceConfig = {
    'tow': {
        title: 'Solicitar grúa',
        subtitle: 'Completa los detalles para solicitar la grúa',
        reasonTitle: 'Motivo del servicio',
        reasons: [
            { value: 'mechanical', label: 'Falla mecánica', icon: '🔧' },
            { value: 'electrical', label: 'Problema eléctrico/batería', icon: '🔋' },
            { value: 'keys', label: 'Llaves dentro del auto', icon: '🔑' },
            { value: 'accident', label: 'Accidente leve', icon: '⚠️' },
            { value: 'other', label: 'Otro (especificar en notas)', icon: '📝' }
        ],
        submitText: 'Solicitar grúa'
    },
    'tire': {
        title: 'Cambio de llanta',
        subtitle: 'Completa los detalles para el cambio de llanta',
        reasonTitle: 'Motivo del servicio',
        reasons: [
            { value: 'flat', label: 'Llanta ponchada', icon: '🛞' },
            { value: 'blowout', label: 'Reventón', icon: '💥' },
            { value: 'spare', label: 'No tengo refacción', icon: '🔄' },
            { value: 'other', label: 'Otro (especificar en notas)', icon: '📝' }
        ],
        submitText: 'Solicitar cambio de llanta'
    },
    'fuel': {
        title: 'Solicitar combustible',
        subtitle: 'Completa los detalles para recibir combustible',
        reasonTitle: 'Tipo de combustible',
        reasons: [
            { value: 'gasoline', label: 'Gasolina (Magna)', icon: '⛽' },
            { value: 'premium', label: 'Gasolina (Premium)', icon: '🏆' },
            { value: 'diesel', label: 'Diésel', icon: '🛢️' },
            { value: 'other', label: 'Otro', icon: '📝' }
        ],
        submitText: 'Solicitar combustible'
    }
};

// Obtener tipo de servicio de la URL
function getServiceType() {
    const urlParams = new URLSearchParams(window.location.search);
    const service = urlParams.get('service');
    
    if (service === 'tow') return 'tow';
    if (service === 'tire') return 'tire';
    if (service === 'fuel') return 'fuel';
    return 'tow';
}

// Inicializar vista según servicio
function initServiceView() {
    serviceType = getServiceType();
    const config = serviceConfig[serviceType];
    
    if (!config) return;
    
    // Actualizar header
    document.getElementById('serviceTitle').textContent = config.title;
    document.getElementById('serviceSubtitle').textContent = config.subtitle;
    document.getElementById('reasonTitle').textContent = config.reasonTitle;
    document.getElementById('submitBtn').textContent = config.submitText;
    
    // Generar opciones de motivo
    const reasonOptions = document.getElementById('reasonOptions');
    reasonOptions.innerHTML = `
        <div class="options-grid">
            ${config.reasons.map(reason => `
                <div class="option-item" onclick="selectReason('${reason.value}')" data-value="${reason.value}">
                    <span class="option-icon">${reason.icon}</span>
                    <span class="option-label">${reason.label}</span>
                </div>
            `).join('')}
        </div>
    `;
}

// Seleccionar motivo
function selectReason(value) {
    selectedReason = value;
    
    // Remover selección de todos
    document.querySelectorAll('#reasonOptions .option-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Agregar selección al actual
    const selectedElement = document.querySelector(`#reasonOptions .option-item[data-value="${value}"]`);
    if (selectedElement) {
        selectedElement.classList.add('selected');
    }
}

// Obtener ubicación
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            async (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                currentLocation = { lat, lng };
                
                // Obtener dirección
                const address = await getAddressFromCoords(lat, lng);
                currentLocation.address = address;
                
                document.getElementById('locationAddress').textContent = address;
                document.getElementById('locationCoords').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            },
            (error) => {
                console.error('Error de geolocalización:', error);
                document.getElementById('locationAddress').textContent = 'No se pudo obtener ubicación';
                showMessage('error', 'Activa la ubicación para continuar');
            }
        );
    } else {
        document.getElementById('locationAddress').textContent = 'Tu navegador no soporta geolocalización';
    }
}

// Obtener dirección desde coordenadas
async function getAddressFromCoords(lat, lng) {
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
        );
        const data = await response.json();
        
        if (data.display_name) {
            const parts = data.display_name.split(',');
            if (parts.length >= 3) {
                return `${parts[0]}, ${parts[1]}, ${parts[2]}`;
            }
            return data.display_name;
        }
        return `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
    } catch (error) {
        return `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
    }
}

// Seleccionar ubicación manual
async function selectLocation() {
    const newLocation = prompt('Ingresa tu ubicación (dirección o coordenadas):');
    if (newLocation) {
        // Verificar si son coordenadas
        const coordsMatch = newLocation.match(/(-?\d+\.?\d*),\s*(-?\d+\.?\d*)/);
        if (coordsMatch) {
            const lat = parseFloat(coordsMatch[1]);
            const lng = parseFloat(coordsMatch[2]);
            currentLocation = { lat, lng, address: `${lat}, ${lng}` };
            document.getElementById('locationAddress').textContent = currentLocation.address;
            document.getElementById('locationCoords').textContent = `${lat}, ${lng}`;
        } else {
            // Es una dirección, geocodificar
            document.getElementById('locationAddress').textContent = newLocation;
            document.getElementById('locationCoords').textContent = 'Obteniendo coordenadas...';
            currentLocation = { address: newLocation };
            
            const coords = await geocodeAddress(newLocation);
            if (coords) {
                currentLocation.lat = coords.lat;
                currentLocation.lng = coords.lng;
                document.getElementById('locationCoords').textContent = `${coords.lat.toFixed(6)}, ${coords.lng.toFixed(6)}`;
            } else {
                document.getElementById('locationCoords').textContent = 'No se pudieron obtener coordenadas';
            }
        }
    }
}

// Cargar vehículos del usuario
async function loadUserVehicles() {
    const token = localStorage.getItem('zyga_token');
    
    try {
    const response = await fetch(`${API_BASE}/client/vehicles`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            userVehicles = data.data || [];
            
            if (userVehicles.length > 0) {
                selectedVehicle = userVehicles[0];
                document.getElementById('vehicleName').textContent = 
                    `${selectedVehicle.brand || ''} ${selectedVehicle.model || 'Vehículo'} ${selectedVehicle.year || ''}`;
                document.getElementById('vehiclePlate').textContent = selectedVehicle.plate || 'Sin placa';
            } else {
                document.getElementById('vehicleName').textContent = 'No hay vehículos registrados';
            }
        } else {
            // Datos de ejemplo
            document.getElementById('vehicleName').textContent = 'Mazda 2 Hatchback';
            document.getElementById('vehiclePlate').textContent = 'ABC-1234';
        }
    } catch (error) {
        console.error('Error loading vehicles:', error);
        document.getElementById('vehicleName').textContent = 'Mazda 2 Hatchback';
        document.getElementById('vehiclePlate').textContent = 'ABC-1234';
    }
}

// Seleccionar vehículo
function selectVehicle() {
    // Abrir modal o prompt para seleccionar vehículo
    alert('Próximamente: selección de vehículos');
}

// Seleccionar método de pago
function selectPayment(method) {
    selectedPayment = method;
    
    document.querySelectorAll('.payment-method').forEach(item => {
        item.classList.remove('selected');
    });
    
    const selectedElement = document.querySelector(`.payment-method[onclick*="${method}"]`);
    if (selectedElement) {
        selectedElement.classList.add('selected');
    }
}

// Mostrar mensaje
function showMessage(type, text) {
    const messageBox = document.getElementById('messageBox');
    messageBox.className = `message-box ${type}`;
    messageBox.textContent = text;
    
    setTimeout(() => {
        messageBox.className = 'message-box';
    }, 3000);
}

// Enviar solicitud
async function submitRequest() {
    const token = localStorage.getItem('zyga_token');
    
    if (!token) {
        window.location.href = '/login';
        return;
    }
    
    if (!selectedReason) {
        showMessage('error', 'Por favor, selecciona un motivo');
        return;
    }
    
    if (!currentLocation || (!currentLocation.lat && !currentLocation.address)) {
        showMessage('error', 'Por favor, espera a que se cargue tu ubicación');
        return;
    }
    
    if (!selectedVehicle || !selectedVehicle.id) {
        showMessage('error', 'Por favor, selecciona un vehículo');
        return;
    }
    
    const notes = document.getElementById('notes').value;
    
    // Obtener service_id según el tipo de servicio
    const serviceId = await getServiceId(serviceType);
    
    if (!serviceId) {
        showMessage('error', 'No se pudo identificar el servicio solicitado');
        return;
    }
    
    // Obtener latitud y longitud
    let lat = currentLocation.lat;
    let lng = currentLocation.lng;
    let address = currentLocation.address;
    
    // Si no hay coordenadas pero hay dirección, intentar geocodificar
    if (!lat && address) {
        showMessage('info', 'Obteniendo coordenadas de la dirección...');
        const coords = await geocodeAddress(address);
        if (coords) {
            lat = coords.lat;
            lng = coords.lng;
        } else {
            showMessage('error', 'No se pudieron obtener las coordenadas de la ubicación');
            return;
        }
    }
    
    const requestData = {
        service_id: serviceId,
        vehicle_id: selectedVehicle.id,
        lat: lat,
        lng: lng,
        pickup_address: address || locationStr
    };
    
    console.log('=== DATOS A ENVIAR ===');
    console.log(JSON.stringify(requestData, null, 2));
    console.log('======================');
    
    try {
        const response = await fetch('${API_BASE}/client/assistance-requests', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        });
        
        const data = await response.json();
        
        console.log('=== RESPUESTA ===');
        console.log('Status:', response.status);
        console.log('Data:', data);
        console.log('=================');
        
        if (!response.ok) {
            if (data.errors) {
                const errors = Object.values(data.errors).flat();
                showMessage('error', errors.join(', '));
            } else {
                showMessage('error', data.message || 'Error al solicitar servicio');
            }
            return;
        }
        
        showMessage('success', 'Solicitud enviada correctamente. Tu número de seguimiento es: ' + data.data.public_id);
        
        setTimeout(() => {
            window.location.href = '/home';
        }, 3000);
        
    } catch (error) {
        console.error('Error:', error);
        showMessage('error', 'Error de conexión');
    }
}

// Función para obtener el ID del servicio según el tipo
async function getServiceId(serviceType) {
    try {
        const token = localStorage.getItem('zyga_token');
        const response = await fetch('${API_BASE}/services', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            const services = data.data || [];
            console.log('Servicios disponibles:', services);
            
            // Mapeo de tipos a nombres de servicios (en español)
            const serviceMap = {
                'tow': ['grúa', 'grua', 'tow'],
                'tire': ['llanta', 'neumático', 'tire'],
                'fuel': ['combustible', 'gasolina', 'fuel']
            };
            
            const searchTerms = serviceMap[serviceType] || [];
            const service = services.find(s => {
                const name = s.name?.toLowerCase() || '';
                return searchTerms.some(term => name.includes(term));
            });
            
            if (service) {
                console.log('Servicio encontrado:', service);
                return service.id;
            }
            
            // Si no encuentra, devolver el primer servicio
            if (services.length > 0) {
                console.log('Usando primer servicio disponible:', services[0]);
                return services[0].id;
            }
        }
        
        return null;
        
    } catch (error) {
        console.error('Error getting service ID:', error);
        return null;
    }
}

// Función para geocodificar dirección a coordenadas
async function geocodeAddress(address) {
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`
        );
        const data = await response.json();
        
        if (data && data.length > 0) {
            return {
                lat: parseFloat(data[0].lat),
                lng: parseFloat(data[0].lon)
            };
        }
        return null;
    } catch (error) {
        console.error('Error geocoding:', error);
        return null;
    }
}

// Inicializar
function init() {
    initServiceView();
    getCurrentLocation();
    loadUserVehicles();
    selectPayment('cash');
}

init();
</script>
@endsection