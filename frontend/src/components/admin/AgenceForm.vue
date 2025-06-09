<template>
  <form @submit.prevent="submitForm" class="space-y-4 bg-white p-6 rounded-lg shadow-md">
    <div>
      <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
      <input v-model="agence.nom" type="text" id="nom" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
    </div>
    
    <div>
      <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
      <input v-model="agence.adresse" type="text" id="adresse" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
    </div>
    
    <div>
      <label for="ville" class="block text-sm font-medium text-gray-700">Ville</label>
      <input v-model="agence.ville" type="text" id="ville" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
    </div>
    
    <div>
      <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
      <input v-model="agence.telephone" type="tel" id="telephone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
    </div>
    
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Ajouter</button>
  </form>
</template>

<script setup>
import { ref } from 'vue';
import AgenceServices from '@/services/AgenceServices';

const agence = ref({
    nom: '',
    adresse: '',
    ville: '',
    telephone: '',
});

const submitForm = async () => {
  try {
    await AgenceServices.addAgence(agence.value);
    alert('Agence ajoutée avec succès');
    agence.value = { nom: '', adresse: '', ville: '', telephone: '' }; // Reset form
  } catch (error) {
    console.error("Erreur lors de l'ajout de l'agence", error);
  }
};
</script>
