self.addEventListener('install', (event) => {
  self.skipWaiting();
});
self.addEventListener('activate', (event) => {
  // cleanup old caches if used later
});
self.addEventListener('fetch', (event) => {
  // basic passthrough; could add caching later
});