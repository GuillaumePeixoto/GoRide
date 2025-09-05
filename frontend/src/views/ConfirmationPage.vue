<template>
    <main class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4 text-center">Récapitulatif de la réservation</h1>

            <div v-if="reservation" class="border rounded p-4 w-full max-w-xl mx-auto bg-white">

                <h2 class="mb-4 flex justify-between"><span class="font-semibold">Véhicule</span> {{ vehiculeInfos.marque + " " + vehiculeInfos.modele }} </h2>
                <h2 class="mb-4 flex justify-between"><span class="font-semibold">Dates :</span> {{ new Date(reservation.startDate).toLocaleDateString('fr-FR') }} au {{ new Date(reservation.endDate).toLocaleDateString('fr-FR') }}</h2>


                <h2 class=" flex justify-between font-semibold">Détails de la facture :</h2>
                <ul>
                    <li v-for="detail in reservation.priceDetails" :key="detail.key" class="flex justify-between border-b py-2">
                        <span>{{ detail.displayName }}</span>
                        <span>{{ detail.amount.toFixed(2) }} €</span>
                    </li>
                </ul>

                <div class="flex justify-between font-bold mt-4 pt-2 text-lg">
                    <span>Total</span>
                    <span>
                        {{ reservation.totalPrice }} €
                    </span>
                </div>
            </div>
            <button class="p-2 bg-blue-500 rounded flex ml-auto cursor-pointer" @click="sendReservation">Confirmer la réservation</button>
        </div>
    </main>
</template>

<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import VehiculeServices from '@/services/VehiculeServices';
import ReservationServices from '@/services/ReservationServices';
import ReservationsManagement from './admin/ReservationsManagement.vue';
import { createFormData } from '@/main.ts';

const { isLoggedIn } = useAuth()

const router = useRouter()
const reservation = ref<any>(null)
const selectedDetails = ref<any[]>([])
const vehiculeInfos = ref<any>(null)

onMounted(async () => {
    const data = localStorage.getItem('reservation')
    if (!data) {
        router.push({ name: 'home' })
        return;
    }

    reservation.value = JSON.parse(data)

    if (reservation.value?.vehiculeId) {
        try {
            vehiculeInfos.value = await VehiculeServices.getVehicule(reservation.value.vehiculeId);
        } catch (error) {
            console.error('Erreur lors de la récupération du véhicule :', error);
        }
    }
})

const sendReservation = async () => {

    try {
        let reservationInformations = createFormData(reservation)
        await ReservationServices.sendReservationInfos(reservationInformations)
    } catch (error) {
        console.error('Erreur :', error)
    }
}

</script>
