<template>
    <main>
        <div class="background-light-black-opacity sm:w-3/4 md:w-2/3 lg:w-2/4 xl:w-11/12 mx-auto mt-2 rounded p-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-white">Gestion des utilisateurs</h1>
                <button @click="showForm = true" class="bg-blue-500 text-white px-4 py-2 rounded">
                Ajouter un utilisateur
                </button>
            </div>
            <!-- <UserForm v-if="showForm" @close="showForm = false" @submit="fetchUser" /> -->
            <!-- Sans doute rajouter un système de filtre pour afficher les différents role : Client, "Salarié", Admin -->
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b py-2">
                        <th class="py-2 text-white">Nom</th>
                        <th class="py-2 text-white">Prénom</th>
                        <th class="py-2 text-white">Email</th>
                        <th class="py-2 text-white">Adresse</th>
                        <th class="py-2 text-white">Ville</th>
                        <th class="py-2 text-white">Code Postal</th>
                        <th class="py-2 text-white">Role</th>
                        <th class="py-2 text-white">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id"  class="border-b py-2">
                        <td class="py-2 text-center text-white capitalize-first">{{ user.nom }}</td>
                        <td class="py-2 text-center text-white capitalize-first">{{ user.prenom }}</td>
                        <td class="py-2 text-center text-white">{{ user.email }}</td> 
                        <td class="py-2 text-center text-white">{{ user.adresse }}</td>
                        <td class="py-2 text-center text-white">{{ user.ville }}</td> 
                        <td class="py-2 text-center text-white">{{ user.codePostal }}</td>
                        <td class="py-2 text-center text-white">{{ user.roles }}</td>
                        <td class="py-2 text-center text-white">Fiche / Modif / Delete</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import UserService from '@/services/UserServices';

const users = ref([]);

const fetchUsers = async () => {
  users.value = await UserService.getUsers();
};

onMounted(fetchUsers);
</script>

<style scoped>

    .capitalize-first::first-letter {
        text-transform: uppercase;
    }

</style>

