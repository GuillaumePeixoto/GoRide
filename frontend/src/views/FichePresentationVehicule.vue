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
                                <label for="startDate" class="block text-gray-700 font-semibold mb-1">Date de début :</label>
                                <input type="date" id="startDate" @change="updateDatePicker" v-model="startDateFilter" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />    
                            </div>
                            <div>
                                <label for="endDate" class="block text-gray-700 font-semibold mb-1">Date de fin :</label>
                                <input type="date" id="endDate" @change="updateDatePicker" v-model="endDateFilter" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required />    
                            </div>
                        </div>
                    </div>
                    <h2 class="mb-2 font-bold"> Options supplémentaires disponibles : </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Siège bébé -->

                        <label class="border rounded-xl p-4 flex gap-3 items-start cursor-pointer hover:shadow transition" v-for="(option, key) in optionsList" :key="key">
                            <input type="checkbox" :value="key" v-model="selectedOptions" class="my-auto accent-blue-600" />
                            <div class="my-auto">
                                <span class="font-semibold text-gray-800"> {{ option.label }} </span>
                                <p class="text-sm text-gray-600">
                                    {{ option.description }}
                                </p>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-between mt-6 ">
                        <div class="my-auto font-bold text-xl">
                            Total : {{ totalPrice }} €
                        </div>
                        <button :disabled="!datesValid" @click.prevent="goToConfirmation()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700  disabled:bg-gray-400 disabled:cursor-not-allowed disabled:hover:bg-gray-400">
                            Valider la réservation
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</template>

<script setup lang="ts">
    import { onMounted, ref, watch, computed } from 'vue'
    import { useRoute, useRouter } from 'vue-router'
    import VehiculeServices from '@/services/VehiculeServices';

    const router = useRouter()
    const route = useRoute()
    const vehicule = ref<any>(null)
    const apiUrl = import.meta.env.VITE_PHP_API_URL;

    const startDateFilter = ref('')
    const endDateFilter = ref('')
    const nbDaysLocation = ref(0)
    const pricePerDays = ref(vehicule.value?.prixJour ?? 0);
    const totalPrice = ref('0')
    const selectedOptions = ref<string[]>([]);
    const priceDetails = ref<string[]>([]);
    const optionsList = ref({});

    const GoBackToResearch = () => {
        router.push({ name: 'home'})
    }

    const storedStart = localStorage.getItem('startDateFilter');
    const storedEnd = localStorage.getItem('endDateFilter');

    if (storedStart && storedStart !== '' && verifyDateUntilTomorrow(storedStart)) {
        startDateFilter.value = storedStart
    }
    if (storedEnd && storedEnd !== '' && verifyDateUntilTomorrow(storedEnd)) {
        endDateFilter.value = storedEnd
    }


    onMounted(async () => {
        const id = Number(route.params.id)
        vehicule.value = await VehiculeServices.getVehicule(id);
        optionsList.value = VehiculeServices.getOptionsList(vehicule.value.prixJour);

        loadFromQueryParams();

        if(startDateFilter.value != '' && endDateFilter.value != '') {
            majPriceValues();
        }
    })


    const datesValid = computed(() => {
        if (!startDateFilter.value || !endDateFilter.value) return false

        const start = new Date(startDateFilter.value)
        const end = new Date(endDateFilter.value)

        return start <= end && verifyDateUntilTomorrow(startDateFilter.value) && verifyDateUntilTomorrow(endDateFilter.value)
    });
    
    watch(selectedOptions, (newVal, oldVal) => {
        // Tu peux aussi appeler une fonction spécifique
        majPriceValues();
    });

    const updateDatePicker = () => {

        startDateFilter.value = verifyDateUntilTomorrow(startDateFilter.value) ? startDateFilter.value : '';
        endDateFilter.value = verifyDateUntilTomorrow(endDateFilter.value) ? endDateFilter.value : '';
        
        localStorage.setItem('startDateFilter', startDateFilter.value);
        localStorage.setItem('endDateFilter', endDateFilter.value);

        majPriceValues();
    }

    function loadFromQueryParams() {
        const query = route.query

        startDateFilter.value = verifyDateUntilTomorrow(query.start as string) ? query.start as string : '';
        endDateFilter.value = verifyDateUntilTomorrow(query.end as string) ? query.end as string : '';

        if (query.options) {
            selectedOptions.value = (query.options as string).split('-')
        } else {
            selectedOptions.value = []
        }
    }

    function majPriceValues(){
        nbDaysLocation.value = VehiculeServices.calculateDaysDifference(startDateFilter.value, endDateFilter.value);
        pricePerDays.value = VehiculeServices.getTarifJournalier(nbDaysLocation.value, vehicule.value.prixJour);
        optionsList.value.kilometrageIllimite.price = pricePerDays.value * 0.05 * nbDaysLocation.value;
        optionsList.value.assuranceTousRisque.price = VehiculeServices.calculateAssuranceTousRisquesPrice(pricePerDays.value, nbDaysLocation.value);

        let prixSiegeBebe = 7
        if(nbDaysLocation.value >= 7) {
            prixSiegeBebe = 5;
        }
        optionsList.value.siegeBebe.price = prixSiegeBebe * nbDaysLocation.value;

        totalPrice.value = VehiculeServices.calculateTotalPrice(nbDaysLocation.value, pricePerDays.value, selectedOptions.value, optionsList.value);

        priceDetails.value = VehiculeServices.getPriceDetails(nbDaysLocation.value, pricePerDays.value, selectedOptions.value, optionsList.value);

        router.push({ 
            query: {
                start: startDateFilter.value,
                end: endDateFilter.value,
                options: selectedOptions.value.join('-'),
            }
        })
    }

    function verifyDateUntilTomorrow(date: string): boolean {
        const today = new Date();
        const selectedDate = new Date(date);

        return selectedDate > today;
    }

    function goToConfirmation() {

        const reservationData = {
            startDate: startDateFilter.value,
            endDate: endDateFilter.value,
            selectedOptions: selectedOptions.value,
            priceDetails: priceDetails.value,
            vehiculeId: vehicule.value.id,
            totalPrice: totalPrice.value
        }

        localStorage.setItem('reservation', JSON.stringify(reservationData))
        router.push({ name: 'confirmation' })
    }

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