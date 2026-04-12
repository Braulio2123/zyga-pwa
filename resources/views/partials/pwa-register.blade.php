<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(function (error) {
                console.error('ZYGA SW registration failed:', error);
            });
        });
    }
</script>
