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

.profile-container {
    width: 100%;
    max-width: 100%;
    margin: 0;
    background: #f8f9fa;
    min-height: 100vh;
    padding-bottom: 80px;
}

/* Header */
.header {
    background: linear-gradient(135deg, #ff6a00 0%, #ff8c2e 100%);
    color: white;
    padding: 30px 20px;
    border-radius: 0 0 30px 30px;
    text-align: center;
    width: 100%;
}

.avatar {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 40px;
    color: #ff6a00;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.header h1 {
    font-size: 22px;
    margin-bottom: 5px;
    font-weight: 600;
}

.header .edit-profile {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    cursor: pointer;
    margin-top: 10px;
    transition: all 0.3s ease;
}

.header .edit-profile:hover {
    background: rgba(255,255,255,0.3);
}

/* Cards */
.card {
    background: white;
    margin: 16px;
    border-radius: 24px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.card-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-title-icon {
    font-size: 22px;
}

.edit-btn {
    background: none;
    border: none;
    color: #ff6a00;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.edit-btn:hover {
    background: #fff5e6;
}

/* Responsive para pantallas pequeñas */
@media (max-width: 480px) {
    .card {
        margin: 12px;
        padding: 16px;
    }
    
    .card-title {
        font-size: 16px;
    }
}

/* Para pantallas más grandes, centrar la barra */
@media (min-width: 768px) {
    .bottom-nav {
        max-width: 500px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 30px 30px 0 0;
    }
}

/* Logout Button */
.logout-btn {
    width: calc(100% - 32px);
    margin: 0 16px 20px 16px;
    background: white;
    border: 1px solid #dc2626;
    color: #dc2626;
    padding: 14px;
    border-radius: 16px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    background: #dc2626;
    color: white;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    max-width: 350px;
    width: 90%;
    border-radius: 24px;
    padding: 24px;
}

/* ... resto de estilos ... */

.modal-content h3 {
    margin-bottom: 16px;
    font-size: 20px;
}

.modal-content input,
.modal-content select {
    width: 100%;
    padding: 12px;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    margin-bottom: 12px;
    font-size: 14px;
    background: white;
}

.modal-content select:focus,
.modal-content input:focus {
    outline: none;
    border-color: #ff6a00;
}

/* Vehicles List */
.vehicles-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.vehicle-item {
    background: #f8f9fa;
    border-radius: 16px;
    padding: 16px;
    position: relative;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.vehicle-item:hover {
    border-color: #ff6a00;
    box-shadow: 0 2px 8px rgba(255,106,0,0.1);
}

.vehicle-item-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.vehicle-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.vehicle-primary-badge {
    background: #ff6a00;
    color: white;
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 12px;
    margin-left: 8px;
}

.vehicle-actions {
    display: flex;
    gap: 8px;
}

.vehicle-action-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    padding: 4px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.vehicle-action-btn.edit {
    color: #ff6a00;
}

.vehicle-action-btn.delete {
    color: #dc2626;
}

.vehicle-action-btn.primary {
    color: #10b981;
}

.vehicle-action-btn:hover {
    transform: scale(1.1);
}

.vehicle-plate {
    font-size: 13px;
    color: #666;
    margin-bottom: 4px;
}

.vehicle-type {
    font-size: 12px;
    color: #ff6a00;
}

.vehicle-detail {
    font-size: 12px;
    color: #888;
    margin-top: 4px;
}

.modal-buttons {
    display: flex;
    gap: 12px;
    margin-top: 16px;
}

.modal-buttons button {
    flex: 1;
    padding: 12px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    font-weight: 500;
}

.modal-buttons .save {
    background: #ff6a00;
    color: white;
}

.modal-buttons .cancel {
    background: #f0f0f0;
    color: #666;
}

</style>

<div class="profile-container">
    <div class="header">
        <div class="avatar" id="avatar">
            👤
        </div>
        <h1 id="userFullName">Cargando...</h1>
        <button class="edit-profile" onclick="editProfile()">
            ✏️ Editar mi perfil
        </button>
    </div>

    <!-- Mis vehículos -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="card-title-icon">🚗</span>
                <span>Mis vehículos</span>
            </div>
            <button class="edit-btn" id="btnAddVehicle" onclick="addVehicle()">
                + Agregar vehículo
            </button>
        </div>
        <div id="vehiclesList">
            <div class="loading">Cargando vehículos...</div>
        </div>
        <div id="vehicleLimitMessage" style="display: none; text-align: center; color: #ff6a00; font-size: 12px; margin-top: 8px;">
            ⚠️ Has alcanzado el límite máximo de 3 vehículos
        </div>
    </div>

    <!-- Servicios -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="card-title-icon">🛡️</span>
                <span>Servicios</span>
            </div>
        </div>
        <div class="services-grid">
            <div class="service-item" onclick="goToService('zyga')">
                <span class="service-icon">🚗</span>
                <span class="service-name">Zyga</span>
                <span class="service-name" style="font-size: 10px; color: #ff6a00;">Seguro Zyga</span>
            </div>
            <div class="service-item" onclick="goToService('security')">
                <span class="service-icon">🛡️</span>
                <span class="service-name">Seguridad</span>
            </div>
            <div class="service-item" onclick="goToService('support')">
                <span class="service-icon">🆘</span>
                <span class="service-name">Soporte</span>
            </div>
        </div>
    </div>


    <button class="logout-btn" onclick="logout()">
        🚪 Salir de la cuenta
    </button>

   
</div>

<!-- Modal Editar Perfil -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <h3>Editar perfil</h3>
        <input type="text" id="editName" placeholder="Nombre completo">
        <input type="email" id="editEmail" placeholder="Correo electrónico">
        <div class="modal-buttons">
            <button class="save" onclick="saveProfile()">Guardar</button>
            <button class="cancel" onclick="closeModal('editProfileModal')">Cancelar</button>
        </div>
    </div>
</div>

<!-- Modal Editar/Agregar Vehículo (ÚNICO) -->
<div id="editVehicleModal" class="modal">
    <div class="modal-content">
        <h3 id="vehicleModalTitle">Agregar vehículo</h3>
        
        <select id="vehicleTypeId" required>
            <option value="">Selecciona el tipo de vehículo *</option>
        </select>
        
        <input type="text" id="vehiclePlateInput" placeholder="Placas * (ej: ABC-1234)" required>
        <input type="text" id="vehicleBrand" placeholder="Marca * (ej: Mazda)" required>
        <input type="text" id="vehicleModel" placeholder="Modelo * (ej: 2)" required>
        <input type="number" id="vehicleYear" placeholder="Año (opcional)" min="1900" max="2100">
        
        <div class="modal-buttons">
            <button class="save" onclick="saveVehicle()">Guardar</button>
            <button class="cancel" onclick="closeModal('editVehicleModal')">Cancelar</button>
        </div>
    </div>
</div>


<script>
const API_BASE = 'https://zyga-api-production.up.railway.app/api/v1';
const token = localStorage.getItem('zyga_token');
let userData = {};
let vehicles = [];
let emergencyContact = {};
let vehicleTypes = [];
const MAX_VEHICLES = 3;

// Verificar autenticación
if (!token) {
    window.location.href = '/login';
}

// Mostrar mensaje
function showMessage(type, text) {
    const toast = document.createElement('div');
    toast.className = `toast-message toast-${type}`;
    toast.textContent = text;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Cargar tipos de vehículos
async function loadVehicleTypes() {
    try {
        const response = await fetch(`${API_BASE}/vehicle-types`, {
            headers: { 'Accept': 'application/json' }
        });
        
        if (response.ok) {
            const data = await response.json();
            vehicleTypes = data.data || [];
            console.log('Tipos de vehículos cargados:', vehicleTypes);
        } else {
            console.error('Error al cargar tipos de vehículos. Status:', response.status);
            vehicleTypes = [];
        }
    } catch (error) {
        console.error('Error loading vehicle types:', error);
        vehicleTypes = [];
    }
}

// Cargar datos del usuario
async function loadUserProfile() {
    try {
        const response = await fetch(`${API_BASE}/me`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.status === 401) {
            localStorage.removeItem('zyga_token');
            window.location.href = '/login';
            return;
        }
        
        const data = await response.json();
        userData = data.data.user;
        
        document.getElementById('userFullName').textContent = userData.name || userData.email?.split('@')[0] || 'Usuario';
        document.getElementById('editName').value = userData.name || '';
        document.getElementById('editEmail').value = userData.email || '';
        
    } catch (error) {
        console.error('Error loading profile:', error);
    }
}

// Cargar vehículos
async function loadVehicles() {
    try {
        const response = await fetch(`${API_BASE}/client/vehicles`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            vehicles = data.data || [];
            console.log('Vehículos cargados:', vehicles.length);
            renderVehicles();
        }
    } catch (error) {
        console.error('Error loading vehicles:', error);
    }
}

function getVehicleTypeName(typeId) {
    const type = vehicleTypes.find(t => t.id === typeId);
    return type ? type.name : 'Vehículo';
}

// Renderizar lista de vehículos
function renderVehicles() {
    const vehiclesContainer = document.getElementById('vehiclesList');
    const addButton = document.getElementById('btnAddVehicle');
    const limitMessage = document.getElementById('vehicleLimitMessage');
    
    if (!vehiclesContainer) return;
    
    if (vehicles.length === 0) {
        vehiclesContainer.innerHTML = `
            <div style="text-align: center; color: #999; padding: 20px;">
                <p>No tienes vehículos registrados</p>
                <p style="font-size: 12px;">Haz clic en "Agregar vehículo" para registrar uno</p>
            </div>
        `;
    } else {
        vehiclesContainer.innerHTML = `
            <div class="vehicles-list">
                ${vehicles.map(vehicle => `
                    <div class="vehicle-item" data-vehicle-id="${vehicle.id}">
                        <div class="vehicle-item-header">
                            <div>
                                <span class="vehicle-name">
                                    ${vehicle.vehicle_type?.name || getVehicleTypeName(vehicle.vehicle_type_id)} 
                                    ${vehicle.brand || ''} ${vehicle.model || ''}
                                </span>
                                ${vehicles.length === 1 || vehicle.is_primary ? '<span class="vehicle-primary-badge">Principal</span>' : ''}
                            </div>
                            <div class="vehicle-actions">
                                ${vehicles.length > 1 && !vehicle.is_primary ? `
                                    <button class="vehicle-action-btn primary" onclick="setPrimaryVehicle(${vehicle.id})" title="Establecer como principal">
                                        ⭐
                                    </button>
                                ` : ''}
                                <button class="vehicle-action-btn edit" onclick="editVehicle(${vehicle.id})" title="Editar">
                                    ✏️
                                </button>
                                <button class="vehicle-action-btn delete" onclick="deleteVehicle(${vehicle.id})" title="Eliminar">
                                    🗑️
                                </button>
                            </div>
                        </div>
                        <div class="vehicle-plate">📋 ${vehicle.plate || 'Sin placa'}</div>
                        <div class="vehicle-type">🚗 ${vehicle.vehicle_type?.name || getVehicleTypeName(vehicle.vehicle_type_id)}</div>
                        ${vehicle.year ? `<div class="vehicle-detail">📅 ${vehicle.year}</div>` : ''}
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    if (addButton) {
        if (vehicles.length >= MAX_VEHICLES) {
            addButton.disabled = true;
            addButton.style.opacity = '0.5';
            addButton.style.cursor = 'not-allowed';
            if (limitMessage) limitMessage.style.display = 'block';
        } else {
            addButton.disabled = false;
            addButton.style.opacity = '1';
            addButton.style.cursor = 'pointer';
            if (limitMessage) limitMessage.style.display = 'none';
        }
    }
}


// Editar perfil
function editProfile() {
    document.getElementById('editProfileModal').style.display = 'flex';
}

// Guardar perfil
async function saveProfile() {
    const name = document.getElementById('editName').value;
    const email = document.getElementById('editEmail').value;
    
    try {
        const response = await fetch(`${API_BASE}/me`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name, email })
        });
        
        if (response.ok) {
            userData.name = name;
            userData.email = email;
            document.getElementById('userFullName').textContent = name || email.split('@')[0];
            closeModal('editProfileModal');
            showMessage('success', 'Perfil actualizado correctamente');
        } else {
            showMessage('error', 'Error al actualizar perfil');
        }
    } catch (error) {
        console.error('Error saving profile:', error);
        showMessage('error', 'Error de conexión');
    }
}

// Editar vehículo específico
function editVehicle(vehicleId) {
    const vehicle = vehicles.find(v => v.id === vehicleId);
    if (!vehicle) return;
    
    document.getElementById('vehicleModalTitle').textContent = 'Editar vehículo';
    
    const select = document.getElementById('vehicleTypeId');
    select.innerHTML = '<option value="">Selecciona el tipo de vehículo *</option>';
    
    vehicleTypes.forEach(type => {
        const option = document.createElement('option');
        option.value = type.id;
        option.textContent = type.name;
        select.appendChild(option);
    });
    
    document.getElementById('vehicleTypeId').value = vehicle.vehicle_type_id || '';
    document.getElementById('vehiclePlateInput').value = vehicle.plate || '';
    document.getElementById('vehicleBrand').value = vehicle.brand || '';
    document.getElementById('vehicleModel').value = vehicle.model || '';
    document.getElementById('vehicleYear').value = vehicle.year || '';
    
    window.editingVehicleId = vehicleId;
    document.getElementById('editVehicleModal').style.display = 'flex';
}

// Agregar vehículo
function addVehicle() {
    if (vehicles.length >= MAX_VEHICLES) {
        showMessage('error', `Solo puedes tener máximo ${MAX_VEHICLES} vehículos`);
        return;
    }
    
    document.getElementById('vehicleModalTitle').textContent = 'Agregar vehículo';
    
    const select = document.getElementById('vehicleTypeId');
    select.innerHTML = '<option value="">Selecciona el tipo de vehículo *</option>';
    
    vehicleTypes.forEach(type => {
        const option = document.createElement('option');
        option.value = type.id;
        option.textContent = type.name;
        select.appendChild(option);
    });
    
    document.getElementById('vehicleTypeId').value = '';
    document.getElementById('vehiclePlateInput').value = '';
    document.getElementById('vehicleBrand').value = '';
    document.getElementById('vehicleModel').value = '';
    document.getElementById('vehicleYear').value = '';
    
    window.editingVehicleId = null;
    document.getElementById('editVehicleModal').style.display = 'flex';
}

// Guardar vehículo
async function saveVehicle() {
    const typeSelect = document.getElementById('vehicleTypeId');
    const plateInput = document.getElementById('vehiclePlateInput');
    const brandInput = document.getElementById('vehicleBrand');
    const modelInput = document.getElementById('vehicleModel');
    const yearInput = document.getElementById('vehicleYear');
    
    if (!plateInput) {
        showMessage('error', 'Error técnico: campo de placas no encontrado');
        return;
    }
    
    const typeValue = typeSelect ? typeSelect.value : '';
    const plateValue = plateInput.value;
    const brandValue = brandInput ? brandInput.value : '';
    const modelValue = modelInput ? modelInput.value : '';
    const yearValue = yearInput ? yearInput.value : '';
    
    if (!plateValue || plateValue.trim() === '') {
        showMessage('error', 'Por favor, ingresa las placas del vehículo');
        plateInput.focus();
        return;
    }
    
    if (!typeValue) {
        showMessage('error', 'Selecciona el tipo de vehículo');
        return;
    }
    
    if (!brandValue || brandValue.trim() === '') {
        showMessage('error', 'La marca es requerida');
        brandInput.focus();
        return;
    }
    
    if (!modelValue || modelValue.trim() === '') {
        showMessage('error', 'El modelo es requerido');
        modelInput.focus();
        return;
    }
    
    const vehicleData = {
        vehicle_type_id: parseInt(typeValue),
        plate: plateValue.toUpperCase().trim(),
        brand: brandValue.trim(),
        model: modelValue.trim(),
        year: yearValue ? parseInt(yearValue) : null
    };
    
    try {
        let response;
        const editingId = window.editingVehicleId;
        
        if (editingId) {
            response = await fetch(`${API_BASE}/client/vehicles/${editingId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(vehicleData)
            });
        } else {
            response = await fetch(`${API_BASE}/client/vehicles`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(vehicleData)
            });
        }
        
        const responseData = await response.json();
        
        if (!response.ok) {
            if (responseData.errors) {
                const errors = Object.values(responseData.errors).flat();
                showMessage('error', errors.join(', '));
            } else if (responseData.message) {
                showMessage('error', responseData.message);
            } else {
                showMessage('error', 'Error al guardar vehículo');
            }
            return;
        }
        
        await loadVehicles();
        closeModal('editVehicleModal');
        showMessage('success', editingId ? 'Vehículo actualizado correctamente' : 'Vehículo agregado correctamente');
        
    } catch (error) {
        console.error('Error saving vehicle:', error);
        showMessage('error', 'Error de conexión');
    }
}

// Establecer vehículo como principal
async function setPrimaryVehicle(vehicleId) {
    try {
        showMessage('success', 'Vehículo establecido como principal');
        vehicles.forEach(v => {
            v.is_primary = (v.id === vehicleId);
        });
        renderVehicles();
    } catch (error) {
        console.error('Error setting primary vehicle:', error);
        showMessage('error', 'Error al establecer vehículo principal');
    }
}

// Eliminar vehículo
async function deleteVehicle(vehicleId) {
    if (vehicles.length === 1) {
        showMessage('error', 'No puedes eliminar el único vehículo. Agrega otro primero.');
        return;
    }
    
    const confirmDelete = confirm('¿Estás seguro de que quieres eliminar este vehículo?');
    if (!confirmDelete) return;
    
    try {
        const response = await fetch(`${API_BASE}/client/vehicles/${vehicleId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            const data = await response.json();
            showMessage('error', data.message || 'Error al eliminar vehículo');
            return;
        }
        
        await loadVehicles();
        showMessage('success', 'Vehículo eliminado correctamente');
        
    } catch (error) {
        console.error('Error deleting vehicle:', error);
        showMessage('error', 'Error de conexión');
    }
}


// Cerrar modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Navegación
function navigateTo(section) {
    const routes = {
        'home': '/home',
        'history': '/history',
        'wallet': '/wallet',
        'profile': '/profile'
    };
    window.location.href = routes[section] || '/home';
}

// Ir a servicio
function goToService(service) {
    showMessage('success', 'Próximamente disponible');
}

// Cerrar sesión
function logout() {
    localStorage.removeItem('zyga_token');
    localStorage.removeItem('zyga_user');
    window.location.href = '/login';
}

// Inicializar
async function init() {
    await loadVehicleTypes();
    await loadUserProfile();
    await loadVehicles();
    loadEmergencyContact();
}

init();
</script>
@endsection