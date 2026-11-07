import './bootstrap';
import Alpine from 'alpinejs';
import { initSelectedStatus } from './selected_status.js';

window.Alpine = Alpine;

Alpine.start();

initSelectedStatus();
