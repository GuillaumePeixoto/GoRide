<template>
    <main>
        <div class="background-light-black-opacity sm:w-3/4 md:w-2/3 lg:w-2/4 xl:w-11/12 mx-auto mt-2 rounded p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-white">Gestion des réservations</h1>
                <button @click="showForm = true" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Ajouter une réservation
                </button>
            </div>
            <!-- <UserForm v-if="showForm" @close="showForm = false" @submit="fetchUser" /> -->
            <!-- Sans doute rajouter un système de filtre pour afficher les différents role : Client, "Salarié", Admin -->
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b py-2">
                        <th class="py-2 text-white">Utilisateur</th>
                        <th class="py-2 text-white">Véhicule</th>
                        <th class="py-2 text-white">Date Début</th>
                        <th class="py-2 text-white">Date Fin</th>
                        <th class="py-2 text-white">Statut</th>
                        <th class="py-2 text-white">Remarque</th>
                        <th class="py-2 text-white">Détails reservation</th>
                        <th class="py-2 text-white">Prix</th>
                        <th class="py-2 text-white">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="reservation in reservations" :key="reservation.id"  class="border-b py-2">
                        <td class="py-2 text-center text-white capitalize-first">{{ reservation.user.nom }} {{ reservation.user.prenom }}</td>
                        <td class="py-2 text-center text-white capitalize-first">{{ reservation.vehicule.marque }} {{ reservation.vehicule.modele }}</td>
                        <td class="py-2 text-center text-white">{{ reservation.date_debut }}</td> 
                        <td class="py-2 text-center text-white">{{ reservation.date_fin }}</td>
                        <td class="py-2 text-center text-white">{{ reservation.statut }}</td> 
                        <td class="py-2 text-center text-white">{{ reservation.remarque }}</td>
                        <td class="py-2 text-center text-white">
                            <table class="w-full border-collapse">
                                <tr v-for="detail in reservation.details" :key="detail.key" class="flex justify-between border-b py-2">
                                    <td class="text-left">{{ detail.displayName }}</td>
                                    <td class="text-right">{{ detail.amount }} €</td>
                                </tr>
                            </table>
                        </td>
                        <td class="py-2 text-center text-white">{{ reservation.prix }}  €</td>
                        <td class="py-2 text-center text-white">Fiche / Modif / Delete</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import ReservationService from '@/services/ReservationServices';

const reservations = ref([]);

const fetchReservations = async () => {
  reservations.value = await ReservationService.getAllReservations();
};

onMounted(fetchReservations);
</script>

<style scoped>

    .capitalize-first::first-letter {
        text-transform: uppercase;
    }

</style>

