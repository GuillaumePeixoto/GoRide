<template>
    <main>
        <div class="background-light-black-opacity sm:w-3/4 md:w-2/3 lg:w-2/4 xl:w-11/12 mx-auto mt-2 rounded p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-white">Gestion des véhicules</h1>
                <button @click="showForm = true" class="bg-blue-500 text-white px-4 py-2 rounded">
                Ajouter un véhicule
                </button>
            </div>

            <div class="flex justify-end mb-4 gap-2">
                <!-- <button @click="viewMode = 'list'" :class="buttonClass('list')">Liste</button>
                <button @click="viewMode = 'grid'" :class="buttonClass('grid')">Grille</button> -->
                <button class="cursor-pointer">Liste</button>
                <button class="cursor-pointer">Grille</button>
            </div>

            <div  v-if="showForm">
                <h2 class="text-lg font-semibold mb-4">Ajouter un véhicule</h2>
                <VehiculeForm @close="showForm = false" @submit="handleVehicules" />
            </div>

            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b py-2">
                        <th class="py-2 text-white">Marque</th>
                        <th class="py-2 text-white">Modèle</th>
                        <th class="py-2 text-white">Kilométrage</th>
                        <th class="py-2 text-white">Type de véhicule</th>
                        <th class="py-2 text-white">Agence</th>
                        <th class="py-2 text-white">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="vehicule in vehicules" :key="vehicule.id"  class="border-b py-2">
                        <td class="py-2 text-center text-white">{{ vehicule.marque }}</td>
                        <td class="py-2 text-center text-white">{{ vehicule.modele }}</td>
                        <td class="py-2 text-center text-white">{{ vehicule.kilometrage }}</td> 
                        <td class="py-2 text-center text-white capitalize-first">{{ vehicule.typeVehicule }}</td>
                        <td class="py-2 text-center text-white capitalize-first">{{ vehicule.agence.nom }}</td>
                        <td class="py-2 text-center text-white">
                            <button @click="voirDetails(vehicule.id)" class="rounded-md bg-sky-500/100 px-2 text-white-600 hover:bg-sky-600 hover:text-white cursor-pointer">
                                Fiche détails
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router'
import VehiculeForm from '@/components/admin/VehiculeForm.vue';
import VehiculeServices from '@/services/VehiculeServices';

const vehicules = ref([]);
const showForm = ref(false);
const router = useRouter()

const apiUrl = import.meta.env.VITE_PHP_API_URL;

const fetchVehicules = async () => {
  try {
    vehicules.value = await VehiculeServices.getVehicules();
  } catch (error) {
    console.error("Erreur lors de la récupération des agences", error);
  }
};

const handleVehicules = async (newVehicule: any) => {
    await VehiculeServices.addVehicule(newVehicule);
    showForm.value = false;
    fetchVehicules();
};

onMounted(fetchVehicules);

const voirDetails = (id: number) => {
  router.push({ name: 'fiche-vehicule', params: { id } })
}

</script>

<style scoped>

    .capitalize-first::first-letter {
        text-transform: uppercase;
    }

</style>

