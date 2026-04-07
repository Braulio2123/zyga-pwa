@extends('user.layouts.app')

@section('title', 'Zyga | Cuenta')
@section('page-title', 'Mi cuenta')

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

.wallet-container {
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

/* Balance Card */
.balance-card {
    background: linear-gradient(135deg, #ff6a00 0%, #ff8c2e 100%);
    color: white;
    text-align: center;
    padding: 25px;
}

.balance-amount {
    font-size: 48px;
    font-weight: 700;
    margin: 10px 0;
}

.balance-label {
    font-size: 14px;
    opacity: 0.9;
}

.deposit-btn {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.5);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    margin-top: 15px;
    transition: all 0.3s ease;
}

.deposit-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
}

.balance-note {
    font-size: 11px;
    opacity: 0.8;
    margin-top: 15px;
    text-align: left;
    background: rgba(255,255,255,0.1);
    padding: 10px;
    border-radius: 12px;
}

/* Tarjetas */
.cards-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.card-item {
    background: #f8f9fa;
    border-radius: 16px;
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.card-item:hover {
    border-color: #ff6a00;
    transform: translateX(5px);
}

.card-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-icon {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.card-details {
    flex: 1;
}

.card-number {
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.card-type {
    font-size: 12px;
    color: #ff6a00;
    margin-top: 4px;
}

.card-expiry {
    font-size: 11px;
    color: #999;
    margin-top: 4px;
}

.card-actions {
    display: flex;
    gap: 12px;
}

.card-action-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.card-action-btn.delete {
    color: #dc2626;
}

.card-action-btn.delete:hover {
    background: #fee2e2;
}

.add-card-btn {
    background: none;
    border: 1px dashed #ff6a00;
    color: #ff6a00;
    padding: 12px;
    border-radius: 12px;
    width: 100%;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 12px;
}

.add-card-btn:hover {
    background: #fff5e6;
}

/* Otros métodos */
.other-methods {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.method-item {
    background: #f8f9fa;
    border-radius: 16px;
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.method-item:hover {
    border-color: #ff6a00;
    transform: translateX(5px);
}

.method-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.method-icon {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.method-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.method-status {
    font-size: 12px;
    color: #ff6a00;
    margin-top: 4px;
}

/* Seguro Zyga */
.insurance-card {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
}

.insurance-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.insurance-title {
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.insurance-price {
    font-size: 24px;
    font-weight: 700;
}

.insurance-description {
    font-size: 13px;
    opacity: 0.9;
    margin-bottom: 16px;
}

.subscribe-btn {
    background: #ff6a00;
    border: none;
    color: white;
    padding: 12px;
    border-radius: 12px;
    width: 100%;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 12px;
}

.subscribe-btn:hover {
    background: #ff8c2e;
    transform: translateY(-2px);
}

.terms-link {
    font-size: 11px;
    text-align: center;
    opacity: 0.7;
    cursor: pointer;
    text-decoration: underline;
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

<div class="wallet-container">
    <div class="header">
        <h1>👛 Mi Billetera</h1>
        <p>Gestiona tus métodos de pago y suscripciones</p>
    </div>

    <!-- Balance -->
    <div class="card balance-card">
        <div class="balance-label">Saldo en mi cuenta</div>
        <div class="balance-amount" id="balanceAmount">$0.00 MXN</div>
        <button class="deposit-btn" onclick="depositMoney()">
            💰 Depositar
        </button>
        <div class="balance-note">
            El saldo es el monto que se puede depositar en la cuenta, sin interés ni gastos. El saldo es un valor nominal y no representa el valor actualizado de las transacciones realizadas.
        </div>
    </div>

    <!-- Mis tarjetas -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="card-title-icon">💳</span>
                <span>Mis tarjetas</span>
            </div>
        </div>
        <div id="cardsList">
            <div class="loading">Cargando tarjetas...</div>
        </div>
        <button class="add-card-btn" onclick="addCard()">
            + Agregar tarjeta
        </button>
    </div>

    <!-- Otros métodos de pago -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span class="card-title-icon">🔄</span>
                <span>Otros métodos de pago</span>
            </div>
        </div>
        <div class="other-methods">
            <div class="method-item" onclick="addPayPal()">
                <div class="method-info">
                    <div class="method-icon">💰</div>
                    <div>
                        <div class="method-name">PayPal</div>
                        <div class="method-status">Agregar cuenta PayPal</div>
                    </div>
                </div>
                <span style="font-size: 20px;">+</span>
            </div>
        </div>
    </div>

    <!-- Pago de seguro Zyga -->
    <div class="card insurance-card">
        <div class="insurance-header">
            <div class="insurance-title">
                <span>🛡️</span>
                <span>Zyga Seguro Zyga</span>
            </div>
            <div class="insurance-price">$199/mes</div>
        </div>
        <div class="insurance-description">
            Protección completa para tu vehículo contra robos, accidentes y asistencia vial 24/7.
        </div>
        <button class="subscribe-btn" onclick="subscribeInsurance()">
            Realizar suscripción
        </button>
        <div class="terms-link" onclick="showTerms()">
            Términos y condiciones
        </div>
    </div>

<!-- Modal Agregar Tarjeta -->
<div id="addCardModal" class="modal">
    <div class="modal-content">
        <h3>Agregar tarjeta</h3>
        <select id="cardType">
            <option value="debit">Débito</option>
            <option value="credit">Crédito</option>
        </select>
        <select id="cardBrand">
            <option value="visa">Visa</option>
            <option value="mastercard">Mastercard</option>
            <option value="amex">American Express</option>
        </select>
        <input type="text" id="cardNumber" placeholder="Número de tarjeta" maxlength="16">
        <div style="display: flex; gap: 12px;">
            <input type="text" id="cardExpiry" placeholder="MM/YYYY" style="flex: 1;">
            <input type="text" id="cardCvv" placeholder="CVV" style="flex: 1;" maxlength="4">
        </div>
        <div class="modal-buttons">
            <button class="save" onclick="saveCard()">Guardar</button>
            <button class="cancel" onclick="closeModal('addCardModal')">Cancelar</button>
        </div>
    </div>
</div>

<!-- Modal Depositar -->
<div id="depositModal" class="modal">
    <div class="modal-content">
        <h3>Depositar saldo</h3>
        <input type="number" id="depositAmount" placeholder="Monto a depositar" min="1">
        <select id="depositMethod">
            <option value="card">Tarjeta de crédito/débito</option>
            <option value="paypal">PayPal</option>
            <option value="transfer">Transferencia bancaria</option>
        </select>
        <div class="modal-buttons">
            <button class="save" onclick="processDeposit()">Depositar</button>
            <button class="cancel" onclick="closeModal('depositModal')">Cancelar</button>
        </div>
    </div>
</div>

<script>
const API_BASE = 'http://127.0.0.1:8000/api/v1';
const token = localStorage.getItem('zyga_token');
let balance = 58.00;
let cards = [];

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

// Cargar tarjetas guardadas
async function loadCards() {
    try {
        const response = await fetch(`${API_BASE}/client/payment-methods`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            cards = data.data || [];
            console.log('Tarjetas desde API:', cards);
        } else {
            console.log('API no disponible, usando datos locales');
            const savedCards = localStorage.getItem('user_cards');
            cards = savedCards ? JSON.parse(savedCards) : [];
        }
        renderCards();
    } catch (error) {
        console.log('Error en API, usando localStorage');
        const savedCards = localStorage.getItem('user_cards');
        cards = savedCards ? JSON.parse(savedCards) : [];
        renderCards();
    }
}

// Cargar saldo
async function loadBalance() {
    try {
        const response = await fetch(`${API_BASE}/client/wallet`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            balance = data.data.balance;
            document.getElementById('balanceAmount').textContent = `$${balance.toFixed(2)} MXN`;
            localStorage.setItem('user_balance', balance);
        } else {
            const savedBalance = localStorage.getItem('user_balance');
            balance = savedBalance ? parseFloat(savedBalance) : 58.00;
            document.getElementById('balanceAmount').textContent = `$${balance.toFixed(2)} MXN`;
        }
    } catch (error) {
        const savedBalance = localStorage.getItem('user_balance');
        balance = savedBalance ? parseFloat(savedBalance) : 58.00;
        document.getElementById('balanceAmount').textContent = `$${balance.toFixed(2)} MXN`;
    }
}

// Renderizar tarjetas
function renderCards() {
    const container = document.getElementById('cardsList');
    
    if (!container) return;
    
    if (cards.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; color: #999; padding: 20px;">
                No tienes tarjetas agregadas
            </div>
        `;
        return;
    }
    
    container.innerHTML = `
        <div class="cards-list">
            ${cards.map(card => `
                <div class="card-item">
                    <div class="card-info">
                        <div class="card-icon">
                            ${card.brand === 'visa' ? '💳' : card.brand === 'mastercard' ? '💳' : '💳'}
                        </div>
                        <div class="card-details">
                            <div class="card-number">${card.number || '**** **** **** ' + (card.last_four || '0000')}</div>
                            <div class="card-type">${card.type === 'debit' ? 'Débito' : 'Crédito'} • ${card.brand === 'visa' ? 'Visa' : card.brand === 'mastercard' ? 'Mastercard' : card.brand}</div>
                            <div class="card-expiry">Vence ${card.expiry || card.expiry_month + '/' + card.expiry_year}</div>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="card-action-btn delete" onclick="deleteCard(${card.id})">
                            🗑️
                        </button>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

// Agregar tarjeta (abre modal)
function addCard() {
    document.getElementById('addCardModal').style.display = 'flex';
}

// Guardar nueva tarjeta (versión ÚNICA con API)
async function saveCard() {
    const cardType = document.getElementById('cardType').value;
    const cardBrand = document.getElementById('cardBrand').value;
    const cardNumber = document.getElementById('cardNumber').value;
    const cardExpiry = document.getElementById('cardExpiry').value;
    const cardCvv = document.getElementById('cardCvv').value;
    
    if (!cardNumber || cardNumber.length < 13) {
        showMessage('error', 'Número de tarjeta inválido');
        return;
    }
    
    if (!cardExpiry) {
        showMessage('error', 'Fecha de expiración requerida');
        return;
    }
    
    const expiryParts = cardExpiry.split('/');
    const month = parseInt(expiryParts[0]);
    const year = parseInt(expiryParts[1]);
    
    if (isNaN(month) || isNaN(year) || month < 1 || month > 12) {
        showMessage('error', 'Fecha de expiración inválida (MM/YYYY)');
        return;
    }
    
    const lastFour = cardNumber.slice(-4);
    const maskedNumber = '**** **** **** ' + lastFour;
    
    const cardData = {
        payment_method_type_id: cardType === 'debit' ? 1 : 2,
        last_four: lastFour,
        brand: cardBrand,
        expiry_month: month,
        expiry_year: year,
        number: maskedNumber
    };
    
    try {
        const response = await fetch(`${API_BASE}/client/payment-methods`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(cardData)
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            showMessage('error', data.message || 'Error al guardar tarjeta');
            return;
        }
        
        showMessage('success', 'Tarjeta agregada correctamente');
        closeModal('addCardModal');
        
        // Limpiar campos
        document.getElementById('cardNumber').value = '';
        document.getElementById('cardExpiry').value = '';
        document.getElementById('cardCvv').value = '';
        
        // Recargar lista de tarjetas
        await loadCards();
        
    } catch (error) {
        console.error('Error saving card:', error);
        showMessage('error', 'Error de conexión');
    }
}

// Eliminar tarjeta
async function deleteCard(cardId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta tarjeta?')) {
        try {
            const response = await fetch(`${API_BASE}/client/payment-methods/${cardId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                await loadCards();
                showMessage('success', 'Tarjeta eliminada');
            } else {
                showMessage('error', 'Error al eliminar tarjeta');
            }
        } catch (error) {
            console.error('Error deleting card:', error);
            showMessage('error', 'Error de conexión');
        }
    }
}

// Depositar dinero
function depositMoney() {
    document.getElementById('depositModal').style.display = 'flex';
}

// Procesar depósito
async function processDeposit() {
    const amount = parseFloat(document.getElementById('depositAmount').value);
    const method = document.getElementById('depositMethod').value;
    
    if (!amount || amount <= 0) {
        showMessage('error', 'Ingresa un monto válido');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}/client/wallet/deposit`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ amount, payment_method: method })
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            showMessage('error', data.message || 'Error al procesar depósito');
            return;
        }
        
        balance = data.data.new_balance;
        document.getElementById('balanceAmount').textContent = `$${balance.toFixed(2)} MXN`;
        closeModal('depositModal');
        showMessage('success', `Depósito de $${amount.toFixed(2)} MXN realizado con éxito`);
        localStorage.setItem('user_balance', balance);
        
    } catch (error) {
        console.error('Error processing deposit:', error);
        showMessage('error', 'Error de conexión');
    }
}

// Agregar PayPal
function addPayPal() {
    const paypalEmail = prompt('Ingresa tu correo de PayPal:');
    if (paypalEmail && paypalEmail.includes('@')) {
        localStorage.setItem('paypal_email', paypalEmail);
        showMessage('success', 'Cuenta de PayPal vinculada correctamente');
    } else if (paypalEmail) {
        showMessage('error', 'Correo inválido');
    }
}

// Suscribirse al seguro
function subscribeInsurance() {
    if (confirm('¿Deseas suscribirte a Zyga Seguro por $199 MXN/mes?')) {
        showMessage('success', 'Suscripción activada. Se te cobrará mensualmente');
    }
}

// Mostrar términos y condiciones
function showTerms() {
    alert('Términos y condiciones de Zyga Seguro:\n\n1. Cobertura contra robo total\n2. Asistencia vial 24/7\n3. Protección contra accidentes\n4. Deducible aplicable\n5. Cancelación con 30 días de anticipación');
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

// Cerrar modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Inicializar
async function init() {
    await loadBalance();
    await loadCards();
}

init();
</script>
@endsection