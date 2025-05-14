<script setup lang="ts">
    import { ref } from 'vue';
    import RechercheVehicule from '../components/RechercheVehicule.vue'
    import CarPresentationCard from '../components/CarPresentationCard.vue'
    import VehiculeServices from '@/services/VehiculeServices';

    const listVehicules = ref([]);

    const handleFormSubmit = async (data: {startDate: string; endDate: string; ville: string; type: number; }) => {
        console.log('Données du formulaire :', data);
        try {
            listVehicules.value = await VehiculeServices.searchVehicules(data)
            console.log('Liste des véhicules :', listVehicules)
        } catch (error) {
            console.error('Erreur lors de la recherche de véhicules :', error)
        }
    }
    
</script>

<template>
    <main>
        <div class="mainDiv" >
            <RechercheVehicule @submit="handleFormSubmit" />
            
            <div v-if="listVehicules.length > 0" class="text-center flex flex-wrap justify-center mb-5">
                <CarPresentationCard v-for="v in listVehicules" :vehicule="v" />
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