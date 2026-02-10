{{-- Hold Timer Partial - Display countdown until slot hold expires --}}
@php
    $holdExpiresAt = session('appointment.hold_expires_at');
    $hasHold = !empty($holdExpiresAt);
@endphp

@if($hasHold)
<style>
.hold-timer-container {
    background: linear-gradient(135deg, #1a5f7a 0%, #11507a 100%);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    color: white;
    text-align: center;
}
.hold-timer-container.warning {
    background: linear-gradient(135deg, #f39c12 0%, #e74c3c 100%);
    animation: timerPulse 1s infinite;
}
.hold-timer-container.expired {
    background: #95a5a6;
}
@keyframes timerPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}
.hold-timer-display {
    font-size: 2rem;
    font-weight: bold;
    font-family: 'Courier New', monospace;
    margin: 0.5rem 0;
}
.hold-timer-label {
    font-size: 0.85rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}
.hold-timer-message {
    font-size: 0.8rem;
    margin-top: 0.5rem;
    opacity: 0.8;
}
</style>

<div class="hold-timer-container" id="holdTimerContainer">
    <div class="hold-timer-label">
        <i class="fas fa-clock"></i>
        <span>Tiempo restante para completar</span>
    </div>
    <div class="hold-timer-display" id="holdTimerDisplay">--:--</div>
    <div class="hold-timer-message" id="holdTimerMessage">Su reserva expirara si no completa el proceso</div>
</div>

<script>
(function() {
    const holdExpiresAt = '{{ $holdExpiresAt }}';
    const timerContainer = document.getElementById('holdTimerContainer');
    const timerDisplay = document.getElementById('holdTimerDisplay');
    const timerMessage = document.getElementById('holdTimerMessage');

    if (!holdExpiresAt || !timerContainer) return;

    const expiresAt = new Date(holdExpiresAt);
    let timerInterval = null;

    function updateTimer() {
        const now = new Date();
        const remainingMs = expiresAt - now;
        const remainingSeconds = Math.max(0, Math.floor(remainingMs / 1000));

        const mins = Math.floor(remainingSeconds / 60);
        const secs = remainingSeconds % 60;
        timerDisplay.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

        // Warning state (less than 3 minutes)
        if (remainingSeconds <= 180 && remainingSeconds > 0) {
            timerContainer.classList.add('warning');
            timerContainer.classList.remove('expired');
            timerMessage.textContent = 'Apurese! Su reserva esta por expirar';
        }

        // Expired state
        if (remainingSeconds <= 0) {
            clearInterval(timerInterval);
            timerContainer.classList.remove('warning');
            timerContainer.classList.add('expired');
            timerDisplay.textContent = '00:00';
            timerMessage.textContent = 'Su reserva ha expirado. Seleccione otro horario.';

            // Redirect to step 1 after showing message
            setTimeout(function() {
                alert('El tiempo para completar el proceso ha expirado. Sera redirigido al inicio.');
                window.location.href = '{{ route("appointments.step1") }}';
            }, 1000);
        }
    }

    // Initial update
    updateTimer();

    // Update every second
    timerInterval = setInterval(updateTimer, 1000);

    // Store interval ID for potential cleanup
    window.holdTimerInterval = timerInterval;
})();
</script>
@endif
