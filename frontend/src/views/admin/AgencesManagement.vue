<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AgenceServices from '@/services/AgenceServices';
import AgenceForm from '@/components/admin/AgenceForm.vue';

const agences = ref<any[]>([]);
const showForm = ref(false);

const fetchAgences = async () => {
  try {
    agences.value = await AgenceServices.getAgences();
    console.log(agences.value);
  } catch (error) {
    console.error("Erreur lors de la récupération des agences", error);
  }
};

const handleAddAgence = async (newAgence: any) => {
  await AgenceServices.addAgence(newAgence);
  showForm.value = false;
  fetchAgences();
};

onMounted(fetchAgences);
</script>

<template>
    <main>
        <div class="background-light-black-opacity sm:w-3/4 md:w-2/3 lg:w-2/4 xl:w-11/12 mx-auto mt-2 rounded">
            <div class="p-6 ">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold text-white">Gestion des agences</h1>
                    <button @click="showForm = true" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Ajouter une agence
                    </button>
                </div>


                <AgenceForm v-if="showForm" @close="showForm = false" @submit="handleAddAgence" />

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b py-2">
                            <th class="py-2 text-white">Nom</th>
                            <th class="py-2 text-white">Adresse</th>
                            <th class="py-2 text-white">Ville</th>
                            <th class="py-2 text-white">Numéro</th>
                            <th class="py-2 text-white">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="agence in agences" :key="agence.id"  class="border-b py-2">
                            <td class="py-2 text-center text-white">{{ agence.nom }}</td>
                            <td class="py-2 text-center text-white">{{ agence.adresse }}</td>
                            <td class="py-2 text-center text-white">{{ agence.ville }}</td> 
                            <td class="py-2 text-center text-white">{{ agence.telephone }}</td>
                            <td class="py-2 text-center text-white">test</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</template>

<style scoped>

    @media (min-width: 1024px) {
        .greetings h1,
        .greetings h3 {
            text-align: left;
        }
    }
</style>