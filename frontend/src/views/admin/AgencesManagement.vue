<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AgenceService from '@/services/AgenceService';
import AgenceForm from '@/components/admin/AgenceForm.vue';

const agences = ref<any[]>([]);
const showForm = ref(false);

const fetchAgences = async () => {
  try {
    agences.value = await AgenceService.getAgences();
  } catch (error) {
    console.error("Erreur lors de la récupération des agences", error);
  }
};

const handleAddAgence = async (newAgence: any) => {
  await AgenceService.addAgence(newAgence);
  showForm.value = false;
  fetchAgences();
};

onMounted(fetchAgences);
</script>

<template>
    <div class="background-light-black-opacity sm:w-3/4 md:w-2/3 lg:w-2/4 xl:w-11/12 mx-auto mt-2 rounded">
        <div class="p-6 ">
            <h1 class="text-2xl font-bold mb-4 text-white">Gestion des agences</h1>
            <button @click="showForm = true" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Ajouter une agence</button>

            <AgenceForm v-if="showForm" @close="showForm = false" @submit="handleAddAgence" />
            
            <ul>
                <li v-for="agence in agences" :key="agence.id" class="border p-2 mb-2">{{ agence.nom }}</li>
            </ul>
            


        </div>
    </div>
</template>