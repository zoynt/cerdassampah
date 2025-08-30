import './bootstrap';

import Alpine from 'alpinejs';
import React from 'react';
import { createRoot } from 'react-dom/client';
import GameWrapper from './components/GameWrapper';

window.Alpine = Alpine;

Alpine.start();

const container = document.getElementById('pilah-sampah');

if (container) {
    const root = createRoot(container);
    root.render(
        <React.StrictMode>
            <GameWrapper />
        </React.StrictMode>
    );
}
