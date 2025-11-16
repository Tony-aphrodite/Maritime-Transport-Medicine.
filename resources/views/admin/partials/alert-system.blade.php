<!-- Real-time Alert System Component -->
<div class="alerts-container" id="alertsContainer"></div>

<!-- Alert Toggle Button -->
<button class="alert-toggle" id="alertToggle" title="Alternar alertas en tiempo real">
    <i class="fas fa-bell"></i>
    <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
</button>

<!-- Alert Status Indicator -->
<div class="alert-status" id="alertStatus">
    🟢 Conectado - Alertas activas
</div>

<style>
/* Real-time Alert System Styles */
.alerts-container {
    position: fixed;
    top: 100px;
    right: 20px;
    z-index: 9999;
    width: 400px;
    max-width: 90vw;
    pointer-events: none;
}

.alert-popup {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border-left: 5px solid;
    position: relative;
    transform: translateX(420px);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    pointer-events: all;
    cursor: pointer;
}

.alert-popup.show {
    transform: translateX(0);
    opacity: 1;
}

.alert-popup.success {
    border-color: #10b981;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(255, 255, 255, 0.9) 100%);
}

.alert-popup.error {
    border-color: #ef4444;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(255, 255, 255, 0.9) 100%);
}

.alert-popup.info {
    border-color: #3b82f6;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(255, 255, 255, 0.9) 100%);
}

.alert-popup .alert-title {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-popup.success .alert-title {
    color: #059669;
}

.alert-popup.error .alert-title {
    color: #dc2626;
}

.alert-popup.info .alert-title {
    color: #2563eb;
}

.alert-popup .alert-message {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.4;
}

.alert-popup .alert-timestamp {
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-size: 0.75rem;
    color: #9ca3af;
    font-weight: 500;
}

.alert-popup .alert-close {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #9ca3af;
    cursor: pointer;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.alert-popup .alert-close:hover {
    background: rgba(0,0,0,0.1);
    color: #6b7280;
}

/* Alert toggle button */
.alert-toggle {
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #0F4C75 0%, #3282B8 100%);
    color: white;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    cursor: pointer;
    box-shadow: 0 10px 25px rgba(15, 76, 117, 0.4);
    z-index: 10000;
    transition: all 0.3s;
}

.alert-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 15px 35px rgba(15, 76, 117, 0.6);
}

.alert-toggle .notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
    100% { transform: scale(1); opacity: 1; }
}

/* Alert system status */
.alert-status {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
    color: #6b7280;
    z-index: 9998;
    border: 1px solid rgba(0,0,0,0.1);
}

.alert-status.connected {
    color: #059669;
}

.alert-status.disconnected {
    color: #dc2626;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .alerts-container {
        width: 350px;
        right: 10px;
        top: 80px;
    }
    
    .alert-toggle {
        top: 15px;
        right: 15px;
        width: 45px;
        height: 45px;
    }
    
    .alert-status {
        bottom: 15px;
        right: 15px;
        font-size: 0.7rem;
    }
}
</style>

<script>
// Global alert system variables
window.AdminAlertSystem = window.AdminAlertSystem || {
    alertsEnabled: true,
    lastCheckTimestamp: null,
    alertInterval: null,
    unreadCount: 0,
    alertsContainer: null,
    alertToggle: null,
    notificationBadge: null,
    alertStatus: null,
    initialized: false
};

// Initialize alert system when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (!window.AdminAlertSystem.initialized) {
        initializeAdminAlertSystem();
    }
});

function initializeAdminAlertSystem() {
    const sys = window.AdminAlertSystem;
    
    sys.alertsContainer = document.getElementById('alertsContainer');
    sys.alertToggle = document.getElementById('alertToggle');
    sys.notificationBadge = document.getElementById('notificationBadge');
    sys.alertStatus = document.getElementById('alertStatus');

    console.log('🚨 Inicializando sistema de alertas admin...');
    console.log('Elements found:', {
        alertsContainer: !!sys.alertsContainer,
        alertToggle: !!sys.alertToggle,
        notificationBadge: !!sys.notificationBadge,
        alertStatus: !!sys.alertStatus
    });

    // Set initial timestamp (start from 5 minutes ago to catch recent events)
    sys.lastCheckTimestamp = new Date(Date.now() - 5 * 60 * 1000).toISOString();
    console.log('Initial timestamp set to:', sys.lastCheckTimestamp);

    // Start polling for alerts
    startAdminAlertPolling();

    // Alert toggle button handler
    sys.alertToggle.addEventListener('click', function() {
        sys.alertsEnabled = !sys.alertsEnabled;
        console.log('Alert toggle clicked, enabled:', sys.alertsEnabled);
        if (sys.alertsEnabled) {
            startAdminAlertPolling();
            sys.alertStatus.textContent = '🟢 Conectado - Alertas activas';
            sys.alertStatus.className = 'alert-status connected';
            sys.alertToggle.innerHTML = '<i class="fas fa-bell"></i><span class="notification-badge" id="notificationBadge" style="display: none;">0</span>';
            sys.notificationBadge = sys.alertToggle.querySelector('.notification-badge');
        } else {
            stopAdminAlertPolling();
            sys.alertStatus.textContent = '🔴 Desconectado - Alertas pausadas';
            sys.alertStatus.className = 'alert-status disconnected';
            sys.alertToggle.innerHTML = '<i class="fas fa-bell-slash"></i>';
        }
        updateAdminNotificationBadge();
    });

    // Add test function to window for manual testing
    window.testAdminAlert = function() {
        console.log('🧪 Testing admin alert system manually...');
        const testEvent = {
            id: 'test-' + Date.now(),
            alert_type: 'success',
            alert_title: '✅ Prueba de Sistema - Usuario TEST***01',
            message: 'Este es un mensaje de prueba del sistema de alertas admin',
            timestamp: new Date().toLocaleTimeString(),
            event_type: 'test_event'
        };
        showAdminAlert(testEvent);
        sys.unreadCount++;
        updateAdminNotificationBadge();
    };

    sys.initialized = true;
    console.log('🚨 Sistema de alertas admin iniciado');
    console.log('💡 Para probar manualmente, ejecute: testAdminAlert() en la consola');
}

function startAdminAlertPolling() {
    const sys = window.AdminAlertSystem;
    if (!sys.alertsEnabled) return;

    console.log('🔄 Starting admin alert polling...');
    
    // Stop existing interval
    if (sys.alertInterval) {
        clearInterval(sys.alertInterval);
    }
    
    // Poll every 5 seconds for new events
    sys.alertInterval = setInterval(checkForNewAdminEvents, 5000);
    
    // Also check immediately
    console.log('⏰ Checking for events in 2 seconds...');
    setTimeout(checkForNewAdminEvents, 2000);
}

function stopAdminAlertPolling() {
    const sys = window.AdminAlertSystem;
    if (sys.alertInterval) {
        clearInterval(sys.alertInterval);
        sys.alertInterval = null;
    }
}

async function checkForNewAdminEvents() {
    const sys = window.AdminAlertSystem;
    if (!sys.alertsEnabled) return;

    try {
        const url = `/admin/api/recent-events?since=${encodeURIComponent(sys.lastCheckTimestamp)}`;
        console.log('🌐 Checking for new admin events:', url);
        
        const response = await fetch(url);
        console.log('📡 API Response status:', response.status);
        
        if (!response.ok) {
            if (response.status === 401) {
                console.warn('🔐 Authentication required - user not logged in as admin');
                sys.alertStatus.textContent = '🔒 No autenticado - Inicie sesión como admin';
                sys.alertStatus.className = 'alert-status disconnected';
                return;
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('📊 API Response data:', data);
        
        if (data.events && data.events.length > 0) {
            console.log(`🎉 ${data.events.length} nuevos eventos encontrados:`, data.events);
            
            // Process each new event
            data.events.forEach(event => {
                console.log('🔔 Showing alert for event:', event);
                showAdminAlert(event);
                sys.unreadCount++;
            });
            
            updateAdminNotificationBadge();
        } else {
            console.log('📝 No hay eventos nuevos');
        }

        // Update timestamp for next check
        sys.lastCheckTimestamp = data.timestamp;
        console.log('⏰ Updated timestamp for next check:', sys.lastCheckTimestamp);
        
        // Update status to show connection is working
        if (sys.alertsEnabled) {
            sys.alertStatus.textContent = '🟢 Conectado - Alertas activas';
            sys.alertStatus.className = 'alert-status connected';
        }

    } catch (error) {
        console.error('❌ Error checking for new admin events:', error);
        sys.alertStatus.textContent = '🔴 Error de conexión';
        sys.alertStatus.className = 'alert-status disconnected';
    }
}

function showAdminAlert(event) {
    const sys = window.AdminAlertSystem;
    const alertElement = document.createElement('div');
    alertElement.className = `alert-popup ${event.alert_type}`;
    alertElement.dataset.eventId = event.id;
    
    alertElement.innerHTML = `
        <div class="alert-timestamp">${event.timestamp}</div>
        <button class="alert-close" onclick="closeAdminAlert(this)">×</button>
        <div class="alert-title">${event.alert_title}</div>
        <div class="alert-message">${event.message}</div>
    `;

    // Add click handler to mark as read
    alertElement.addEventListener('click', function() {
        if (!this.classList.contains('read')) {
            this.classList.add('read');
            sys.unreadCount = Math.max(0, sys.unreadCount - 1);
            updateAdminNotificationBadge();
        }
    });

    // Insert at the beginning of container
    sys.alertsContainer.insertBefore(alertElement, sys.alertsContainer.firstChild);
    
    // Trigger animation
    setTimeout(() => {
        alertElement.classList.add('show');
    }, 100);

    // Auto-hide after 8 seconds for success/info, 12 seconds for errors
    const autoHideDelay = event.alert_type === 'error' ? 12000 : 8000;
    setTimeout(() => {
        closeAdminAlert(alertElement.querySelector('.alert-close'));
    }, autoHideDelay);

    // Play notification sound
    playAdminNotificationSound(event.alert_type);

    // Limit the number of alerts shown (max 5)
    const alerts = sys.alertsContainer.querySelectorAll('.alert-popup');
    if (alerts.length > 5) {
        alerts[alerts.length - 1].remove();
    }

    console.log(`🔔 Admin alert mostrada: ${event.alert_title}`);
}

function closeAdminAlert(closeButton) {
    const alertElement = closeButton.parentElement;
    alertElement.classList.remove('show');
    
    setTimeout(() => {
        if (alertElement.parentNode) {
            alertElement.parentNode.removeChild(alertElement);
        }
    }, 400);
}

function updateAdminNotificationBadge() {
    const sys = window.AdminAlertSystem;
    if (sys.unreadCount > 0 && sys.alertsEnabled) {
        sys.notificationBadge.textContent = sys.unreadCount > 99 ? '99+' : sys.unreadCount;
        sys.notificationBadge.style.display = 'flex';
    } else {
        sys.notificationBadge.style.display = 'none';
    }
}

function playAdminNotificationSound(alertType) {
    // Create a subtle notification sound using Web Audio API
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        // Different frequencies for different alert types
        const frequencies = {
            'success': [523.25, 659.25, 783.99], // C, E, G
            'error': [493.88, 369.99], // B, F#
            'info': [523.25, 783.99] // C, G
        };
        
        const freqArray = frequencies[alertType] || frequencies['info'];
        let currentFreq = 0;
        
        function playNote(frequency, duration) {
            oscillator.frequency.setValueAtTime(frequency, audioContext.currentTime);
            gainNode.gain.setValueAtTime(0, audioContext.currentTime);
            gainNode.gain.linearRampToValueAtTime(0.05, audioContext.currentTime + 0.01);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + duration);
            
            if (currentFreq < freqArray.length - 1) {
                setTimeout(() => {
                    currentFreq++;
                    if (currentFreq < freqArray.length) {
                        playNote(freqArray[currentFreq], 0.1);
                    }
                }, duration * 1000);
            }
        }
        
        oscillator.start();
        playNote(freqArray[0], 0.1);
        
        setTimeout(() => {
            try {
                oscillator.stop();
                audioContext.close();
            } catch (e) {
                // Ignore errors when stopping audio
            }
        }, 1000);
        
    } catch (error) {
        // Silent fail for audio - not critical
    }
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    const sys = window.AdminAlertSystem;
    if (sys.alertInterval) {
        clearInterval(sys.alertInterval);
    }
});
</script>