import './bootstrap';
import Alpine from 'alpinejs';
import 'flowbite';
import AOS from 'aos';
import 'aos/dist/aos.css';
import { createIcons, icons } from 'lucide';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    AOS.init({ duration: 600, once: true, offset: 40 });
    createIcons({ icons });
});
