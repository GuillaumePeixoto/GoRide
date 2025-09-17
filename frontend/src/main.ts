import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import setupAxiosInterceptor from './utils/axiosInterceptor'

import App from './App.vue'
import router from './router'

setupAxiosInterceptor() // Active l'intercepteur

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

export function createFormData(data: any): FormData {
    const formData = new FormData();
    const valueToConvert = data.value !== undefined ? data.value : data;

    const buildFormData = (obj: any, parentKey = '') => {
        if (typeof obj !== 'object' || obj === null) {
            formData.append(parentKey, obj);
            return;
        }

        if (Array.isArray(obj)) {
            // Pour les tableaux, on utilise l'indice comme clé
            obj.forEach((item, index) => {
                buildFormData(item, `${parentKey}[${index}]`);
            });
        } else {
            // Pour les objets, on utilise le nom de la propriété
            for (const key in obj) {
                if (Object.prototype.hasOwnProperty.call(obj, key)) {
                    const propValue = obj[key];
                    const newKey = parentKey ? `${parentKey}[${key}]` : key;
                    buildFormData(propValue, newKey);
                }
            }
        }
    };

    buildFormData(valueToConvert);

    // Débogage : vérifier le contenu de FormData
    for (const [key, value] of formData.entries()) {
        console.log(`${key}:`, value);
    }

    return formData;
}