const CACHE = 'ovu-v1';
const CORE = ['/', '/masuk', '/daftar', '/manifest.webmanifest'];

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(CACHE).then((c) => c.addAll(CORE)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (event) => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;
  event.respondWith(
    fetch(event.request).catch(() => caches.match(event.request).then((r) => r || caches.match('/')))
  );
});

self.addEventListener('push', (event) => {
  let data = {title: 'Pengingat Ovu', body: 'Ada pengingat untukmu hari ini.'};
  try {
    if (event.data) data = Object.assign(data, event.data.json());
  } catch (e) {}
  event.waitUntil(self.registration.showNotification(data.title, {body: data.body, icon: '/icons/icon.svg'}));
});

self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  event.waitUntil(clients.openWindow('/dasbor'));
});
