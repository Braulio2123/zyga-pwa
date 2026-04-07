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

.safe-driving-container {
    width: 100%;
    min-height: 100vh;
    background: #f8f9fa;
    padding-bottom: 80px;
}

/* Header - Ocupa todo el ancho */
.header {
    background: linear-gradient(135deg, #ff6a00 0%, #ff8c2e 100%);
    color: white;
    padding: 30px 20px;
    border-radius: 0 0 30px 30px;
    width: 100%;
}

.header h1 {
    font-size: clamp(24px, 5vw, 32px);
    margin-bottom: 8px;
    font-weight: 600;
}

.header p {
    opacity: 0.9;
    font-size: clamp(14px, 4vw, 16px);
}

/* Cards - Full width con márgenes laterales */
.card {
    background: white;
    margin: 20px 16px;
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.card-title {
    font-size: clamp(18px, 5vw, 22px);
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 4px solid #ff6a00;
    padding-left: 12px;
}

/* Tips */
.tip-item {
    display: flex;
    gap: 16px;
    margin-bottom: 28px;
    padding-bottom: 24px;
    border-bottom: 1px solid #f0f0f0;
}

.tip-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.tip-icon {
    width: 70px;
    height: 70px;
    background: #fff5e6;
    border-radius: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
}

.tip-icon img {
    width: 45px;
    height: 45px;
    object-fit: contain;
}

.tip-content {
    flex: 1;
}

.tip-title {
    font-size: clamp(16px, 4vw, 18px);
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.tip-description {
    font-size: clamp(13px, 3.5vw, 14px);
    color: #666;
    line-height: 1.5;
    margin-bottom: 12px;
}

.tip-image {
    margin-top: 12px;
    background: #f8f9fa;
    border-radius: 16px;
    padding: 12px;
    text-align: center;
}

.tip-image img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    max-height: 180px;
    object-fit: contain;
}

/* Emergency Button */
.emergency-btn {
    width: calc(100% - 32px);
    margin: 0 16px 20px 16px;
    background: #dc2626;
    border: none;
    padding: 16px;
    border-radius: 16px;
    color: white;
    font-weight: bold;
    font-size: clamp(16px, 4vw, 18px);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.emergency-btn:hover {
    background: #ef4444;
    transform: translateY(-2px);
}

/* Bottom Navigation - Full width */
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    width: 100%;
    background: white;
    display: flex;
    justify-content: space-around;
    padding: 12px 16px;
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
    font-size: clamp(22px, 6vw, 26px);
    margin-bottom: 4px;
}

.nav-label {
    font-size: clamp(10px, 3vw, 12px);
    font-weight: 500;
}

/* Responsive para tablets */
@media (min-width: 768px) {
    .card {
        margin: 24px 32px;
    }
    
    .tip-icon {
        width: 80px;
        height: 80px;
    }
    
    .tip-icon img {
        width: 50px;
        height: 50px;
    }
    
    .emergency-btn {
        width: calc(100% - 64px);
        margin: 0 32px 20px 32px;
    }
}

/* Responsive para pantallas grandes */
@media (min-width: 1024px) {
    .card {
        max-width: 800px;
        margin: 30px auto;
    }
    
    .emergency-btn {
        max-width: 800px;
        margin: 0 auto 20px auto;
        left: 0;
        right: 0;
        position: relative;
    }
    
    .bottom-nav {
        max-width: 600px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 30px 30px 0 0;
    }
}
</style>

<div class="safe-driving-container">
    <div class="header">
        <h1>¿Está listo tu auto?</h1>
        <p>Revisa estos puntos clave antes de salir a carretera</p>
    </div>

    <!-- Tips de seguridad -->
    <div class="card">
        <div class="card-title">
            <span>Manejo seguro</span>
        </div>

        <!-- Tip 1: Aceite y líquidos -->
        <div class="tip-item">
            <div class="tip-content">
                <div class="tip-title">Revisa los niveles de aceite y líquidos</div>
                <div class="tip-description">
                    Verifica que el aceite del motor, el líquido de frenos, el refrigerante y el líquido de la dirección asistida estén en niveles adecuados.
                </div>
                <div class="tip-image">
                    <div class="tip-image">
                        <img src="/images/safe-driving/aceite.jpg" alt="Niveles de líquidos">
                        <img src="/images/safe-driving/liquidos.jpg" alt="Niveles de líquidos">

                    </div>
                </div>
            </div>
        </div>

        <!-- Tip 2: Llantas -->
        <div class="tip-item">
            <div class="tip-content">
                <div class="tip-title">Revisa las llantas</div>
                <div class="tip-description">
                    Asegúrate de que las llantas tengan la presión correcta y estén en buen estado. Revisa también el desgaste de la banda de rodadura.
                </div>
                <div class="tip-image">
                    <div class="tip-image">
                        <img src="/images/safe-driving/llantas.jpg" alt="Niveles de líquidos">
                        <img src="/images/safe-driving/llantas 3.jpg" alt="Niveles de líquidos">
                        <img src="/images/safe-driving/llantas 2.jpg" alt="Niveles de líquidos">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tip 3: Luces -->
        <div class="tip-item">
            <div class="tip-content">
                <div class="tip-title">Revisa las luces</div>
                <div class="tip-description">
                    Asegúrate de que todas las luces del vehículo, incluyendo las delanteras, traseras, direccionales y de freno, estén funcionando correctamente.
                </div>
                <div class="tip-image">
                    <div class="tip-image">
                        <img src="/images/safe-driving/luces.jpg" alt="Niveles de líquidos">
                        <img src="/images/safe-driving/luces2.jpg" alt="Niveles de líquidos">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tip 4: Frenos -->
        <div class="tip-item">
            <div class="tip-content">
                <div class="tip-title">Frenos en buen estado</div>
                <div class="tip-description">
                    Verifica que los frenos respondan de manera eficiente y que no haya ruidos extraños al frenar.
                </div>
                <div class="tip-image">
                    <div class="tip-image">
                        <img src="/images/safe-driving/frenos.jpg" alt="Niveles de líquidos">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tip 5: Batería -->
        <div class="tip-item">
            <div class="tip-content">
                <div class="tip-title">Batería</div>
                <div class="tip-description">
                    Asegúrate de que la batería esté en buen estado, sin corrosión en los terminales y con suficiente carga.
                </div>
                <div class="tip-image">
                    <div class="tip-image">
                        <img src="/images/safe-driving/bateria.jpg" alt="Niveles de líquidos">
                        <img src="/images/safe-driving/bateria2.jpg" alt="Niveles de líquidos">
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>
function navigateTo(section) {
    const routes = {
        'home': '/home',
        'history': '/history',
        'wallet': '/wallet',
        'profile': '/profile'
    };
    window.location.href = routes[section] || '/home';
}

function emergencyCall() {
    if (confirm('¿Deseas llamar a servicios de emergencia? 🚨')) {
        window.location.href = 'tel:911';
    }
}
</script>
@endsection