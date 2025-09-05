import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import GameWrapper from './components/GameWrapper';

createInertiaApp({
  resolve: name => import(`./Pages/${name}`),  // Sesuaikan dengan nama halaman yang ada
  setup({ el, App, props }) {
    const root = createRoot(el);
    root.render(<App {...props} />);
  },
});
