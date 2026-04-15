(function () {
  const config = window.providerTrackingConfig || null;
  if (!config || !config.enabled || !config.apiBase || !config.apiToken || !config.assistanceRequestId) {
    return;
  }

  const allowedStatuses = ['assigned', 'in_progress'];
  if (!allowedStatuses.includes(String(config.requestStatus || ''))) {
    return;
  }

  const statusEl = document.getElementById('providerTrackingStatus');
  const minIntervalMs = Number(config.minIntervalMs || 15000);
  const minDistanceMeters = Number(config.minDistanceMeters || 40);
  let lastSentAt = 0;
  let lastCoords = null;
  let watchId = null;

  function setStatus(message, tone) {
    if (!statusEl) return;
    statusEl.textContent = message;
    statusEl.style.background = tone === 'error'
      ? 'rgba(255, 99, 71, .16)'
      : tone === 'success'
        ? 'rgba(52, 211, 153, .18)'
        : 'rgba(255,255,255,.12)';
    statusEl.style.color = '#fff';
  }

  function toRad(value) {
    return (value * Math.PI) / 180;
  }

  function distanceMeters(a, b) {
    if (!a || !b) return Infinity;
    const R = 6371000;
    const dLat = toRad(b.lat - a.lat);
    const dLng = toRad(b.lng - a.lng);
    const lat1 = toRad(a.lat);
    const lat2 = toRad(b.lat);

    const h = Math.sin(dLat / 2) ** 2
      + Math.cos(lat1) * Math.cos(lat2) * Math.sin(dLng / 2) ** 2;

    return 2 * R * Math.asin(Math.sqrt(h));
  }

  async function sendLocation(position) {
    const now = Date.now();
    const coords = {
      lat: Number(position.coords.latitude),
      lng: Number(position.coords.longitude),
      accuracy: position.coords.accuracy != null ? Number(position.coords.accuracy) : null,
      heading: position.coords.heading != null ? Number(position.coords.heading) : null,
      speed: position.coords.speed != null ? Number(position.coords.speed) : null,
    };

    const elapsed = now - lastSentAt;
    const distance = distanceMeters(lastCoords, coords);

    if (lastCoords && elapsed < minIntervalMs && distance < minDistanceMeters) {
      return;
    }

    setStatus('Enviando ubicación…', 'idle');

    const payload = {
      assistance_request_id: Number(config.assistanceRequestId),
      lat: coords.lat,
      lng: coords.lng,
      accuracy: coords.accuracy,
      heading: coords.heading,
      speed: coords.speed,
      recorded_at: new Date().toISOString(),
    };

    try {
      const response = await fetch(`${config.apiBase}/api/v1/provider/tracking`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${config.apiToken}`,
        },
        body: JSON.stringify(payload),
      });

      if (!response.ok) {
        const data = await response.json().catch(() => ({}));
        throw new Error(data.message || 'No fue posible registrar la ubicación.');
      }

      lastSentAt = now;
      lastCoords = coords;
      setStatus(`Tracking activo · ${config.requestPublicId || 'solicitud'} `, 'success');
    } catch (error) {
      setStatus('Tracking con error', 'error');
      console.error('provider tracking error', error);
    }
  }

  function startTracking() {
    if (!('geolocation' in navigator)) {
      setStatus('Geolocalización no disponible', 'error');
      return;
    }

    setStatus('Esperando ubicación…', 'idle');

    watchId = navigator.geolocation.watchPosition(
      sendLocation,
      function (error) {
        let message = 'No fue posible obtener tu ubicación.';

        if (error && error.code === 1) {
          message = 'Debes permitir ubicación para compartir tu avance.';
        }

        setStatus(message, 'error');
      },
      {
        enableHighAccuracy: true,
        maximumAge: 5000,
        timeout: 15000,
      }
    );
  }

  window.addEventListener('beforeunload', function () {
    if (watchId !== null && 'geolocation' in navigator) {
      navigator.geolocation.clearWatch(watchId);
    }
  });

  document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'visible' && watchId === null) {
      startTracking();
    }
  });

  startTracking();
})();
