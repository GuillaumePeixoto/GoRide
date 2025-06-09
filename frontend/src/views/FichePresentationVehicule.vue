<template>
    <main v-if="vehicule" class="container mx-auto px-4 py-8">
        <div class="bg-zinc-200 rounded-lg shadow-lg p-6">
            <div class="mb-4">
                <button @click="GoBackToResearch()" class="custom-shadow-button">&larr; Retour</button>
            </div>
            <div class="md:flex">
                <!-- Image du véhicule -->
                <div class="md:w-1/2 border-1 border-solid border-gray-300 rounded-lg">
                    <img :src="apiUrl+'/'+vehicule.photoPresentation" alt="Véhicule en location" class="w-full rounded-lg">
                </div>
                <!-- Détails du véhicule -->
                <div class="md:w-1/2 md:pl-8 mt-6 md:my-auto text-center md:text-left">
                    <h2 class="text-3xl font-bold text-gray-800"> {{ vehicule.marque }} {{ vehicule.modele }} </h2>
                    <p class="text-gray-600 mt-2"> {{ vehicule.presentationVehicule }} </p>
                    
                    <!-- Caractéristiques -->
                    <h3 class="text-xl font-semibold mt-4">Caractéristiques</h3>
                    <ul class="list-none pl-5 text-gray-600 mt-2" id="caracteristiques">
                        <li><strong>Type :</strong> <span>{{ vehicule.typeVehicule }}</span></li>
                        <li><strong>Type de carburant :</strong> <span>{{ vehicule.typeCarburant }}</span></li>
                        <li><strong>Boite de vitesse :</strong> <span>{{ vehicule.boiteDeVitesse }}</span></li>
                        <li><strong>Couleur :</strong> <span>{{ vehicule.couleur }}</span></li>
                        <li><strong>Kilométrage :</strong> <span>{{ vehicule.kilometrage }} km </span></li>
                        <li><strong>Places :</strong> <span>{{ vehicule.nbPorte }}</span></li>
                    </ul>

                    <!-- Prix -->
                    <!-- <h3 class="text-xl font-semibold mt-4">Tarif de location</h3>
                    <p class="text-2xl text-blue-600 font-bold mt-2"> {{ vehicule.prixJour }} € / jour</p> -->

                </div>
            </div>
            <form class="border rounded-lg border-gray-400 mt-6">
                <div class="m-2">
                    <div class="mb-4">
                        <h2 class="mb-2 font-bold"> Date de réservation du véhicule : </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Date de début -->
                            <div>
                                <label for="dateDebut" class="block text-gray-700 font-semibold mb-1">Date de début :</label>
                                <input type="date" id="dateDebut" @change="updateSessionDate" v-model="startDateFilter" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />    
                            </div>
                            <div>
                                <label for="dateDebut" class="block text-gray-700 font-semibold mb-1">Date de fin :</label>
                                <input type="date" id="dateDebut" @change="updateSessionDate" v-model="endDateFilter" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />    
                            </div>
                        </div>
                    </div>
                    <h2 class="mb-2 font-bold"> Options supplémentaires disponibles : </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Siège bébé -->
                        <label class="border rounded-xl p-4 flex gap-3 items-start cursor-pointer hover:shadow transition">
                            <input type="checkbox" id="optionSiegeBebe" class="my-auto accent-blue-600" />
                            <div class="my-auto">
                                <span class="font-semibold text-gray-800">Siège bébé</span>
                                <p class="text-sm text-gray-600">
                                    7 €/jour, réduit à 5 €/jour pour toute réservation de plus de 7 jours.
                                </p>
                            </div>
                        </label>

                        <!-- Kilométrage illimité -->
                        <label class="border rounded-xl p-4 flex gap-3 items-start cursor-pointer hover:shadow transition">
                            <input type="checkbox" id="optionKilometrage" class="my-auto accent-blue-600" />
                            <div class="my-auto">
                                <span class="font-semibold text-gray-800">Kilométrage illimité</span>
                                <p class="text-sm text-gray-600">
                                    Si l’option n’est pas sélectionnée, vous bénéficiez de 200 km par jour inclus. Des frais s’appliquent en cas de dépassement
                                </p>
                            </div>
                        </label>

                        <!-- Dépôt dans une autre agence -->
                        <label class="border rounded-xl p-4 flex gap-3 items-start cursor-pointer hover:shadow transition">
                            <input type="checkbox" id="optionDepot" class="my-auto accent-blue-600" />
                            <div class="my-auto">
                                <span class="font-semibold text-gray-800">Dépôt dans une autre agence</span>
                                <p class="text-sm text-gray-600">
                                    15 € + 0,40 €/km entre les agences. ( La somme sera à payer à la restitution du véhicule )
                                </p>
                            </div>
                        </label>

                        <!-- Assurance tous risques -->
                        <label class="border rounded-xl p-4 flex gap-3 items-start cursor-pointer hover:shadow transition">
                            <input type="checkbox" id="optionAssurance" class="my-auto accent-blue-600" />
                            <div class="my-auto">
                                <span class="font-semibold text-gray-800">Assurance tous risques</span>
                                <p class="text-sm text-gray-600">
                                    15 % à 25 % du prix de la location hors option selon la durée.
                                    <span title="Le tarif est proportionnel à la durée : 25 % pour 1-2 jours, 20 % pour 3-6 jours, 15 % au-delà." class="cursor-help text-blue-500">ℹ️</span>
                                </p>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end ">
                        <button @click.prevent="editVehicule(vehicule.id)" class="mt-6 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                            Valider la réservation
                        </button>
                    </div>
                </div>
            </form>


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

    const startDateFilter = ref('')
    const endDateFilter = ref('')

    const editVehicule = (id: number) => {
        
    }

    const GoBackToResearch = () => {
        router.push({ name: 'home'})
    }

    const storedStart = localStorage.getItem('startDateFilter');
    const storedEnd = localStorage.getItem('endDateFilter');

    if (storedStart && storedStart !== '') {
        startDateFilter.value = storedStart
    }
    if (storedEnd && storedEnd !== '') {
        endDateFilter.value = storedEnd
    }

    const updateSessionDate = () => {
        localStorage.setItem('startDateFilter', startDateFilter.value);
        localStorage.setItem('endDateFilter', endDateFilter.value);
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

    .custom-shadow-button {
        cursor: pointer;
        padding: 0.5rem 1rem;
        background-color: white;
        color: black;
        border: 1px solid rgb(180, 180, 180);
        border-radius: 5rem;
        overflow: hidden;
        z-index: 0;
        transition: 0.3s ease-in-out;
    }

    .custom-shadow-button:hover{
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        background-color: #221f1f;
        color: white;
    }



</style>