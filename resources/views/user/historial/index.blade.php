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

.history-container {
    max-width: 500px;
    margin: 0 auto;
    background: #f8f9fa;
    min-height: 100vh;
    padding-bottom: 80px;
}

/* Header */
.header {
    background: linear-gradient(135deg, #ff6a00 0%, #ff8c2e 100%);
    color: white;
    padding: 25px 20px;
    border-radius: 0 0 25px 25px;
    margin-bottom: 20px;
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

/* Filtros */
.filters {
    margin: 16px;
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 8px;
}

.filter-btn {
    background: white;
    border: 1px solid #e0e0e0;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.filter-btn.active {
    background: #ff6a00;
    border-color: #ff6a00;
    color: white;
}

.filter-btn:hover {
    background: #ff6a00;
    border-color: #ff6a00;
    color: white;
}

/* Cards */
.request-card {
    background: white;
    margin: 16px;
    border-radius: 20px;
    padding: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.request-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.request-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.request-id {
    font-size: 12px;
    color: #ff6a00;
    font-weight: 600;
    background: #fff5e6;
    padding: 4px 8px;
    border-radius: 8px;
}

.request-date {
    font-size: 11px;
    color: #999;
}

.request-service {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.service-icon {
    width: 50px;
    height: 50px;
    background: #fff5e6;
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.service-info {
    flex: 1;
}

.service-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 4px;
}

.service-detail {
    font-size: 12px;
    color: #666;
}

.request-location {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8f9fa;
    padding: 10px;
    border-radius: 12px;
    margin-bottom: 12px;
}

.location-icon {
    font-size: 16px;
}

.location-address {
    font-size: 12px;
    color: #666;
    flex: 1;
}

/* Status Badges */
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-align: center;
}

.status-created {
    background: #e3f2fd;
    color: #1976d2;
}

.status-assigned {
    background: #fff3e0;
    color: #ff9800;
}

.status-in_progress {
    background: #e8f5e9;
    color: #4caf50;
}

.status-completed {
    background: #e0f2f1;
    color: #009688;
}

.status-cancelled {
    background: #ffebee;
    color: #f44336;
}

.status-quoted {
    background: #f3e5f5;
    color: #9c27b0;
}

.status-confirmed {
    background: #e8f5e9;
    color: #4caf50;
}

/* Timeline Mini */
.timeline-mini {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 12px;
    margin-top: 12px;
}

.timeline-steps {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.timeline-step {
    text-align: center;
    flex: 1;
    position: relative;
    z-index: 1;
}

.timeline-step:not(:last-child):before {
    content: '';
    position: absolute;
    top: 15px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: #e0e0e0;
    z-index: -1;
}

.timeline-step.completed:before {
    background: #ff6a00;
}

.step-dot {
    width: 30px;
    height: 30px;
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-size: 12px;
    transition: all 0.3s ease;
}

.timeline-step.completed .step-dot {
    background: #ff6a00;
    border-color: #ff6a00;
    color: white;
}

.timeline-step.active .step-dot {
    border-color: #ff6a00;
    background: white;
    color: #ff6a00;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255,106,0,0.4);
    }
    70% {
        box-shadow: 0 0 0 8px rgba(255,106,0,0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255,106,0,0);
    }
}

.step-label {
    font-size: 9px;
    color: #999;
    text-transform: uppercase;
}

.timeline-step.completed .step-label,
.timeline-step.active .step-label {
    color: #ff6a00;
    font-weight: 500;
}

/* Amount */
.request-amount {
    text-align: right;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
}

.amount-label {
    font-size: 11px;
    color: #999;
}

.amount-value {
    font-size: 16px;
    font-weight: 700;
    color: #ff6a00;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.empty-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.empty-text {
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}

.empty-btn {
    background: #ff6a00;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.empty-btn:hover {
    background: #ff8c2e;
    transform: translateY(-2px);
}

/* Loading */
.loading {
    text-align: center;
    padding: 40px;
    color: #999;
}

/* Bottom Navigation */
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    max-width: 500px;
    margin: 0 auto;
    background: white;
    display: flex;
    justify-content: space-around;
    padding: 12px 20px;
    box-shadow: 0 -4px 15px rgba(0,0,0,0.05);
    border-radius: 25px 25px 0 0;
    z-index: 100;
}

.nav-item {
    text-align: center;
    cursor: pointer;
    flex: 1;
    transition: all 0.3s ease;
    padding: 5px 0;
}

.nav-item.active {
    color: #ff6a00;
    transform: translateY(-2px);
}

.nav-icon {
    font-size: 24px;
    margin-bottom: 4px;
}

.nav-label {
    font-size: 11px;
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
</style>

<div class="history-container">
    <div class="header">
        <h1>Historial de servicios</h1>
        <p>Todas tus solicitudes de asistencia</p>
    </div>

    <!-- Filtros -->
    <div class="filters" id="filters">
        <button class="filter-btn active" data-filter="all">Todos</button>
        <button class="filter-btn" data-filter="created">Pendientes</button>
        <button class="filter-btn" data-filter="assigned">En proceso</button>
        <button class="filter-btn" data-filter="completed">Completados</button>
        <button class="filter-btn" data-filter="cancelled">Cancelados</button>
    </div>

    <!-- Lista de solicitudes -->
    <div id="requestsList">
        <div class="loading">Cargando historial...</div>
    </div>

<script>
const API_BASE = 'https://zyga-api-production.up.railway.app/api/v1';
const token = localStorage.getItem('zyga_token');
let allRequests = [];
let currentFilter = 'all';

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

// Obtener el icono según el servicio
function getServiceIcon(serviceName) {
    const name = serviceName?.toLowerCase() || '';
    if (name.includes('grúa') || name.includes('grua')) return '🚛';
    if (name.includes('llanta')) return '🛞';
    if (name.includes('combustible')) return '⛽';
    return '🔧';
}

// Obtener el texto del estado
function getStatusText(status) {
    const statusMap = {
        'created': 'Pendiente',
        'assigned': 'Asignado',
        'in_progress': 'En proceso',
        'completed': 'Completado',
        'cancelled': 'Cancelado',
        'quoted': 'Cotizado',
        'confirmed': 'Confirmado'
    };
    return statusMap[status] || status;
}

// Obtener la clase del estado
function getStatusClass(status) {
    const classMap = {
        'created': 'status-created',
        'assigned': 'status-assigned',
        'in_progress': 'status-in_progress',
        'completed': 'status-completed',
        'cancelled': 'status-cancelled',
        'quoted': 'status-quoted',
        'confirmed': 'status-confirmed'
    };
    return classMap[status] || 'status-created';
}

// Formatear fecha
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-MX', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Renderizar timeline
function renderTimeline(status) {
    const steps = [
        { key: 'created', label: 'Solicitado', icon: '📝' },
        { key: 'assigned', label: 'Asignado', icon: '👨‍🔧' },
        { key: 'in_progress', label: 'En camino', icon: '🚛' },
        { key: 'completed', label: 'Completado', icon: '✅' }
    ];
    
    let currentStepIndex = -1;
    const statusOrder = ['created', 'assigned', 'in_progress', 'completed'];
    currentStepIndex = statusOrder.indexOf(status);
    
    return `
        <div class="timeline-mini">
            <div class="timeline-steps">
                ${steps.map((step, index) => `
                    <div class="timeline-step ${index <= currentStepIndex ? 'completed' : ''} ${index === currentStepIndex && status !== 'completed' ? 'active' : ''}">
                        <div class="step-dot">${step.icon}</div>
                        <div class="step-label">${step.label}</div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}

// Cargar solicitudes
async function loadRequests() {
    const container = document.getElementById('requestsList');
    
    try {
        const response = await fetch(`${API_BASE}/client/assistance-requests`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Error al cargar solicitudes');
        }
        
        const data = await response.json();
        allRequests = data.data || [];
        
        filterAndRenderRequests();
        
    } catch (error) {
        console.error('Error loading requests:', error);
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">⚠️</div>
                <div class="empty-title">Error al cargar</div>
                <div class="empty-text">No se pudieron cargar tus solicitudes</div>
                <button class="empty-btn" onclick="loadRequests()">Reintentar</button>
            </div>
        `;
    }
}

// Filtrar y renderizar solicitudes
function filterAndRenderRequests() {
    const container = document.getElementById('requestsList');
    let filteredRequests = allRequests;
    
    if (currentFilter !== 'all') {
        filteredRequests = allRequests.filter(r => r.status === currentFilter);
    }
    
    if (filteredRequests.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-title">No hay solicitudes</div>
                <div class="empty-text">
                    ${currentFilter === 'all' ? 'Aún no has realizado ninguna solicitud' : 'No hay solicitudes en esta categoría'}
                </div>
                <button class="empty-btn" onclick="window.location.href='/home'">
                    Solicitar servicio
                </button>
            </div>
        `;
        return;
    }
    
    container.innerHTML = filteredRequests.map(request => `
        <div class="request-card">
            <div class="request-header">
                <span class="request-id">#${request.public_id || request.id}</span>
                <span class="request-date">${formatDate(request.created_at)}</span>
            </div>
            
            <div class="request-service">
                <div class="service-icon">
                    ${getServiceIcon(request.service?.name)}
                </div>
                <div class="service-info">
                    <div class="service-name">${request.service?.name || 'Servicio'}</div>
                    <div class="service-detail">
                        Vehículo: ${request.vehicle?.brand || ''} ${request.vehicle?.model || ''}
                        ${request.vehicle?.plate ? `(${request.vehicle.plate})` : ''}
                    </div>
                </div>
                <div class="status-badge ${getStatusClass(request.status)}">
                    ${getStatusText(request.status)}
                </div>
            </div>
            
            <div class="request-location">
                <span class="location-icon">📍</span>
                <span class="location-address">${request.pickup_address || 'Dirección no especificada'}</span>
            </div>
            
            ${renderTimeline(request.status)}
            
            ${request.status === 'completed' ? `
                <div class="request-amount">
                    <span class="amount-label">Total pagado</span>
                    <div class="amount-value">$ ${request.total_amount || '0.00'}</div>
                </div>
            ` : ''}
            
            ${request.status === 'quoted' ? `
                <div class="request-amount">
                    <span class="amount-label">Cotización</span>
                    <div class="amount-value">$ ${request.quoted_amount || '0.00'}</div>
                    <button class="empty-btn" style="margin-top: 10px; padding: 8px 16px;" onclick="confirmRequest(${request.id})">
                        Confirmar servicio
                    </button>
                </div>
            ` : ''}
        </div>
    `).join('');
}

// Confirmar solicitud cotizada
async function confirmRequest(requestId) {
    try {
        const response = await fetch(`${API_BASE}/client/service-requests/${requestId}/confirm`, {
            method: 'PATCH',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            showMessage('error', data.message || 'Error al confirmar');
            return;
        }
        
        showMessage('success', 'Servicio confirmado correctamente');
        loadRequests();
        
    } catch (error) {
        console.error('Error confirming request:', error);
        showMessage('error', 'Error de conexión');
    }
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

// Inicializar filtros
function initFilters() {
    const filters = document.querySelectorAll('.filter-btn');
    filters.forEach(btn => {
        btn.addEventListener('click', () => {
            filters.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentFilter = btn.dataset.filter;
            filterAndRenderRequests();
        });
    });
}

// Inicializar
async function init() {
    initFilters();
    await loadRequests();
}

init();
</script>
@endsection