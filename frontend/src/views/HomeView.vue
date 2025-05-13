<script setup lang="ts">

    import RechercheVehicule from '../components/RechercheVehicule.vue'
    import VehiculeServices from '@/services/VehiculeServices';

    function handleFormSubmit(data: {startDate: string|null; endDate: string|null; ville: string|null; type: number; }) {
        console.log('Formulaire soumis avec :', data);
        const listVehicules = VehiculeServices.searchVehicules(data);
        console.log('Liste des véhicules :', listVehicules);
        // Tu peux ensuite appeler ton API ici
    }
    
</script>

<template>
    <main>
        <div class="mainDiv" >
            <RechercheVehicule @submit="handleFormSubmit" />
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