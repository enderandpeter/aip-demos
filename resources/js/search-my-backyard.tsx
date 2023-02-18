import '../sass/search-my-backyard.scss'

import './bootstrap';

import React from 'react';
import { createRoot } from 'react-dom/client';
import {createInertiaApp} from '@inertiajs/inertia-react';
import {InertiaProgress} from '@inertiajs/progress';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {Provider} from "react-redux";
import store from "./redux/store";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'AIP Demos';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // @ts-ignore
    resolve: (name) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
    setup({el, App, props}) {
        const root = createRoot(el);
        return root.render(
            <React.StrictMode>
                <Provider store={store}>
                    <App {...props} />
                </Provider>
            </React.StrictMode>
    );
    },
});

InertiaProgress.init({color: '#4B5563'});

