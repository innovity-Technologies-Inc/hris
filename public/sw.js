const CACHE_NAME = 'pwa_cache';
let ASSETS_TO_CACHE = [
    '/',
    '/login',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png'
];


const isProduction = false;


self.addEventListener('install', event => {
    if (isProduction) {
        event.waitUntil(
            fetch('/assets-list')
                .then(res => res.json())
                .then(files => {
                    ASSETS_TO_CACHE = ASSETS_TO_CACHE.concat(files);
                    return caches.open(CACHE_NAME).then(cache => cache.addAll(ASSETS_TO_CACHE));
                })
        );
    }
    self.skipWaiting();
});


self.addEventListener('activate', event => {
    clients.claim();
});


self.addEventListener('fetch', event => {
    if (isProduction) {
        event.respondWith(
            caches.match(event.request).then(response => response || fetch(event.request))
        );
    } else {
        event.respondWith(fetch(event.request));
    }
});
