import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')

router.beforeEach((to, from, next) => {
    // Vérification des dates stockées dans le localStorage
    validateStoredDates();

    next(); // continue la navigation
});

function validateStoredDates() {
    const storedStart = localStorage.getItem('startDateFilter');
    const storedEnd = localStorage.getItem('endDateFilter');
    let invalid = false;

    const today = new Date();
    today.setHours(0, 0, 0, 0); // On normalise la date du jour à minuit

    if (storedStart) {
        const start = new Date(storedStart);
        start.setHours(0, 0, 0, 0);
        if (start < today) {
            invalid = true;
        }
    }

    if (storedEnd) {
        const end = new Date(storedEnd);
        end.setHours(0, 0, 0, 0);
        if (end < today) {
            invalid = true;
        }
    }

    if(invalid){
        console.warn("Les dates stockées dans le localStorage sont invalides. Elles seront supprimées.");
        localStorage.removeItem('startDateFilter');
        localStorage.removeItem('endDateFilter');
    }

}
