<template>
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
            <button>Liste</button>
            <button>Grille</button>
        </div>

        <VehiculeForm v-if="showForm" @close="showForm = false" @submit="handleVehicules" />

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
                    <td class="py-2 text-center text-white capitalize-first">{{ vehicule.type_vehicule }}</td>
                    <td class="py-2 text-center text-white capitalize-first">{{ vehicule.agence.nom }}</td>
                    <td class="py-2 text-center text-white">
                        <button @click="voirDetails(vehicule.id)" class="rounded-md bg-sky-500/100 px-2 text-white-600 hover:bg-sky-600 hover:text-white">
                            Fiche détails
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Test autre affichage -->
        <!-- <div style="margin-top: 20px; display: flex;">
            <img width="150" :src="imageUrl"/>
            <div style="margin-left: 20px; display: flex; flex-direction: column; justify-content: center; color: white">
                <p>Mercdes GLE Coupe</p>
                <p>Type : SUV</p>
                <p>Kilométrage : 20000 km </p>
                <p>Agence : Agence test</p>
            </div>
        </div> -->

    </div>
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
const imageUrl = import.meta.env.VITE_PHP_API_URL + "/uploads/vehicules/36d9a166250ee0c04abdc3eda06065da736b33200e0e7dfea5aaad6f6b3ba232.png";

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

