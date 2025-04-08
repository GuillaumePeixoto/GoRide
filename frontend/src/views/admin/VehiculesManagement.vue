<template>
    <div class="background-light-black-opacity sm:w-3/4 md:w-2/3 lg:w-2/4 xl:w-11/12 mx-auto mt-2 rounded p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-white">Gestion des véhicules</h1>
            <button @click="showForm = true" class="bg-blue-500 text-white px-4 py-2 rounded">
            Ajouter un véhicule
            </button>
        </div>
        <VehiculeForm v-if="showForm" @close="showForm = false" @submit="fetchVehicules" />

        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b py-2">
                    <th class="py-2 text-white">Marque</th>
                    <th class="py-2 text-white">Modèle</th>
                    <th class="py-2 text-white">Kilométrage</th>
                    <th class="py-2 text-white">Type de véhicule</th>
                    <th class="py-2 text-white">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="vehicule in vehicules" :key="vehicule.id"  class="border-b py-2">
                    <td class="py-2 text-center text-white">{{ vehicule.marque }}</td>
                    <td class="py-2 text-center text-white">{{ vehicule.modele }}</td>
                    <td class="py-2 text-center text-white">{{ vehicule.kilometrage }}</td> 
                    <td class="py-2 text-center text-white capitalize-first">{{ vehicule.type_vehicule }}</td>
                    <td class="py-2 text-center text-white">Fiche / Modif / Delete</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import VehiculeForm from '@/components/admin/VehiculeForm.vue';
import VehiculeServices from '@/services/VehiculeServices';

const vehicules = ref([]);
const showForm = ref(false);

const fetchVehicules = async () => {
  vehicules.value = await VehiculeServices.getVehicules();
};

onMounted(fetchVehicules);
</script>

<style scoped>

    .capitalize-first::first-letter {
        text-transform: uppercase;
    }

</style>

