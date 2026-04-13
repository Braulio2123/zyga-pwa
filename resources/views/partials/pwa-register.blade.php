<script>
    (() => {
        if (!('serviceWorker' in navigator)) {
            return;
        }

        window.addEventListener('load', async () => {
            try {
                const registration = await navigator.serviceWorker.register('{{ asset('sw.js') }}', {
                    scope: '/',
                });

                registration.update().catch(() => {});
            } catch (error) {
                console.error('ZYGA SW registration failed:', error);
            }
        }, { once: true });
    })();
</script>
