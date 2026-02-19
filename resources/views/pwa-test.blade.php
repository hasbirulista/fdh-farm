<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PWA Test</title>

    <!-- Manifest harus di head -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd">

</head>

<body>
    <h1>PWA Test</h1>
    <p>Service Worker & Manifest testing</p>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => console.log('ServiceWorker registered', reg))
                .catch(err => console.log('ServiceWorker failed', err));
        }
    </script>
</body>

</html>
