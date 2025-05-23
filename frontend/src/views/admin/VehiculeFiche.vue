<template>
    <main v-if="vehicule" class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 md:flex">
            <!-- Image du véhicule -->
            <div class="md:w-1/2">
                <img :src="apiUrl+'/'+vehicule.photo_presentation" alt="Véhicule en location" class="w-full rounded-lg">
            </div>
            <!-- Détails du véhicule -->
            <div class="md:w-1/2 md:pl-6 mt-6 md:mt-0">
                <h2 class="text-3xl font-bold text-gray-800"> {{ vehicule.marque }} {{ vehicule.modele }} </h2>
                <p class="text-gray-600 mt-2"> {{ vehicule.presentation_vehicule }} </p>
                
                <!-- Caractéristiques -->
                <h3 class="text-xl font-semibold mt-4">Caractéristiques</h3>
                <ul class="list-none pl-5 text-gray-600 mt-2" id="caracteristiques">
                    <li><strong>Type :</strong> <span>{{ vehicule.type_vehicule }}</span></li>
                    <li><strong>Type de carburant :</strong> <span>{{ vehicule.typeCarburant }}</span></li>
                    <li><strong>Boite de vitesse :</strong> <span>{{ vehicule.boiteDeVitesse }}</span></li>
                    <li><strong>Couleur :</strong> <span>{{ vehicule.couleur }}</span></li>
                    <li><strong>Kilométrage :</strong> <span>{{ vehicule.kilometrage }} km </span></li>
                    <li><strong>Places :</strong> <span>{{ vehicule.nb_porte }}</span></li>
                </ul>

                <!-- Prix -->
                <h3 class="text-xl font-semibold mt-4">Tarif de location</h3>
                <p class="text-2xl text-blue-600 font-bold mt-2"> {{ vehicule.prixJour }} € / jour</p>

                <!-- Bouton de réservation -->
                <button @click="editVehicule(vehicule.id)" class="mt-6 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Modifier le véhicule
                </button>
            </div>
        </div>
    </main>
</template>

<script setup lang="ts">
    import { onMounted, ref } from 'vue'
    import { useRoute, useRouter } from 'vue-router'
    import VehiculeServices from '@/services/VehiculeServices';

    const router = useRouter()
    const route = useRoute()
    const vehicule = ref<any>(null)
    const apiUrl = import.meta.env.VITE_PHP_API_URL;

    const editVehicule = (id: number) => {
        router.push({ name: 'vehicule-edit', params: { id } })
    }

    onMounted(async () => {
        const id = Number(route.params.id)
        vehicule.value = await VehiculeServices.getVehicule(id);
        console.log(vehicule.value);
    })

    document.querySelectorAll('#caracteristiques li span').forEach(span => {
        let text = span.textContent;
        console.log(text);
        if (text.length > 0) {
            span.textContent = text.charAt(0).toUpperCase() + text.slice(1);
        }
    });
</script>

<style scoped>
    #caracteristiques li span {
        font-family: Arial, sans-serif;
    }
    #caracteristiques li span::first-letter {
        text-transform: uppercase;
    }
</style>