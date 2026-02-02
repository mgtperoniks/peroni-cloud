import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Register Global Store for Gallery
Alpine.store('gallery', {
    showFilters: false,
    toggleFilter() {
        this.showFilters = !this.showFilters;
        console.log('Global Filter Toggled:', this.showFilters);
    }
});

Alpine.start();
