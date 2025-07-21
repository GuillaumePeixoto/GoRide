<script setup lang="ts">
    import { ref } from 'vue';
    import { useRouter } from 'vue-router'
    import RechercheVehicule from '../components/RechercheVehicule.vue'
    import CarPresentationCard from '../components/CarPresentationCard.vue'
    import VehiculeServices from '@/services/VehiculeServices';

    const router = useRouter()

    const GoToFicheVehicule = (id: number) => {

        let startDate = localStorage.getItem('startDateFilter') || '';
        let endDate = localStorage.getItem('endDateFilter') || '';

        if (startDate == "" || !verifyDateUntilTomorrow(startDate)) {
            startDate = ''
        }

        if (endDate == "" || !verifyDateUntilTomorrow(endDate)) {
            endDate = ''
        }

        console.log('Navigating to fiche-vehicule with id:', id, 'startDate:', startDate, 'endDate:', endDate);

        router.push({
            name: 'fiche-presentation-vehicule',
            params: { id },
            query: {
                start: startDate,
                end: endDate
            }
        })
    }

    const listVehicules = ref([]);
    const filterVehicule = ref({
        startDate: '',
        endDate: '',
        ville: '',
        type: 0, // 0 pour tous les types
    });

    const handleFormSubmit = async (data: {startDate: string; endDate: string; ville: string; type: number; }) => {
        console.log('Données du formulaire :', data);
        filterVehicule.value = data; // Mettre à jour le filtre avec les données du formulaire
        try {
            listVehicules.value = await VehiculeServices.searchVehicules(data)
            console.log('Liste des véhicules :', listVehicules)
        } catch (error) {
            console.error('Erreur lors de la recherche de véhicules :', error)
        }
    }

    function verifyDateUntilTomorrow(date: string): boolean {
        const today = new Date();
        const selectedDate = new Date(date);

        return selectedDate > today;
    }
    
</script>

<template>
    <main>
        <div class="mainDiv" >
            <RechercheVehicule @submit="handleFormSubmit" />
            
            <div v-if="listVehicules.length > 0" class="text-center flex flex-wrap justify-center mb-5">
                <CarPresentationCard v-for="v in listVehicules" :vehicule="v" @click="GoToFicheVehicule(v.id)" />
            </div>

        </div>
    </main>
</template>

<style scoped>
    .mainDiv {
        margin-top: 2em; 
        width: 80%; 
        margin-left: auto; 
        margin-right: auto; 
        padding: 10px; 
        background-color: rgba(99, 99, 99, 0.8);
        border-radius: 12px;
    }
</style>