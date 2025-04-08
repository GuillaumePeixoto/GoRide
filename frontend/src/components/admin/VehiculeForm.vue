<template>
    <div>
        <h2 class="text-lg font-semibold mb-4">Ajouter un véhicule</h2>
        <form @submit.prevent="handleSubmit" class="space-y-4">
            <input v-model="vehicule.marque" placeholder="Marque" required class="input-field" />
            <input v-model="vehicule.modele" placeholder="Modèle" required class="input-field" />
            <input type="file" @change="handlePhotoPresentationUpload" accept="image/*" class="input-field" />
            <textarea v-model="vehicule.presentation_vehicule" placeholder="Presentation Vehicule" class="input-field"></textarea>
            <input v-model.number="vehicule.nb_porte" type="number" placeholder="Nombre de portes" required class="input-field" />
            <input v-model="vehicule.couleur" placeholder="Couleur" required class="input-field" />
            <input v-model.number="vehicule.kilometrage" type="number" placeholder="Kilométrage" required class="input-field" />
            <textarea v-model="vehicule.remarque" placeholder="Remarque" class="input-field"></textarea>
            <input type="file" @change="handlePhotoVehiculeUpload" accept="image/*" multiple class="input-field" />

            <select v-model="vehicule.type_vehicule" required class="input-field">
                <option disabled value="">Sélectionner un type de Véhicule</option>
                <option value="berline">Berline</option>
                <option value="coupe">Coupé</option>
                <option value="cabriolet">Cabriolet</option>
                <option value="break">Break</option>
                <option value="suv">SUV</option>
                <option value="4x4">4x4</option>
                <option value="monospace">Monospace</option>
                <option value="pick-up">Pick-up</option>
                <option value="utilitaire">Utilitaire</option>
            </select>

            <div class="switch-container">
                <span>Véhicule</span>
                <label class="switch">
                    <input type="checkbox" v-model="vehicule.isUtilitaire">
                    <span class="slider"></span>
                </label>
                <span>Utilitaire</span>
            </div>
            
            <select v-model="vehicule.agence" required class="input-field">
                <option disabled value="">Sélectionner une agence</option>
                <option v-for="agence in agences" :key="agence.id" :value="agence.id">
                {{ agence.nom }}
                </option>
            </select>
            
            <button type="submit" class="btn-primary">Ajouter</button>
            <button type="button" @click="$emit('close')" class="btn-secondary">Annuler</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AgenceServices from '@/services/AgenceServices';
import VehiculeServices from '@/services/VehiculeServices';

const photoPresentation = ref<File | null>(null)
const photoVehicule = ref([] as File[])

const handlePhotoPresentationUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files) {
    photoPresentation.value = target.files[0]
  }
}

const handlePhotoVehiculeUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files) {
    photoVehicule.value = Array.from(target.files)
  }
}

const vehicule = ref({
  marque: '',
  modele: '',
  nb_porte: 5,
  couleur: '',
  photo_presentation: null,
  kilometrage: 0,
  presentation_vehicule: '',
  remarque: '',
  photo_vehicule: [],
  agence: '',
  type_vehicule: '',
  isUtilitaire: 0
});


const agences = ref([]);

onMounted(async () => {
  try {
    agences.value = await AgenceServices.getAgences();
  } catch (error) {
    console.error("Erreur lors de la récupération des agences", error);
  }
});

const handleSubmit = async () => {
    try {
        const formData = new FormData();
        
        formData.append("marque", vehicule.value.marque);
        formData.append("modele", vehicule.value.modele);
        formData.append("nb_porte", vehicule.value.nb_porte.toString());
        formData.append("couleur", vehicule.value.couleur);
        formData.append("kilometrage", vehicule.value.kilometrage.toString());
        formData.append("presentation_vehicule", vehicule.value.presentation_vehicule || "");
        formData.append("remarque", vehicule.value.remarque || "");
        formData.append("agence", vehicule.value.agence);
        formData.append("type_vehicule", vehicule.value.type_vehicule);
        formData.append("isUtilitaire", vehicule.value.isUtilitaire.toString());

        // Gestion des fichiers
        if (photoPresentation.value) {
            formData.append("photo_presentation", photoPresentation.value);
        }

        if (photoVehicule.value.length) {
            photoVehicule.value.forEach((file) => {
                formData.append('photo_vehicule[]', file);
            });
        }

        await VehiculeServices.addVehicule(formData);
        alert("Véhicule ajouté avec succès");
        // emit("refresh"); // ⚠️ Vue 3 : il faut déclarer `const emit = defineEmits(["refresh"]);` en haut du `<script setup>`
    } catch (error) {
        console.error("Erreur lors de l'ajout du véhicule", error);
    }
};

</script>

<style scoped>
    .input-field {
        display: block;
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .btn-primary {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
    }
    .btn-secondary {
        background-color: #f44336;
        color: white;
        padding: 10px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        margin-left: 10px;
    }

    /* Switch */
    .switch-container {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: bold;
        color: white;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 25px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        border-radius: 25px;
        transition: background-color 0.3s;
    }

    .slider:before {
        content: "";
        position: absolute;
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 3px;
        background-color: #3d3b3b;
        border-radius: 50%;
        transition: transform 0.3s ease-in-out;
    }

    /* Quand le switch est activé */
    input:checked + .slider {
        background-color: #c49a52;
    }

    input:checked + .slider:before {
        transform: translateX(25px);
    }

</style>
