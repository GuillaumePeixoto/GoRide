<template>
    <div class="mt-4 sm:mt-0">
        <form @submit.prevent="handleSubmit" class="">
            <div class="flex-fields my-6 md:my-8">
                <div class="input-group my-2 md:my-0">
                    <input v-model="vehicule.marque" id="marque" placeholder=" " required class="input-field" />
                    <label for="marque">Marque</label>
                </div>

                <div class="input-group my-2 md:my-0">
                    <input v-model="vehicule.modele" id="modele" placeholder=" " required class="input-field" />
                    <label for="modele">Modèle</label>
                </div>
            </div>

            <div class="input-group my-6 md:my-8">
                <input type="file" id="photoPresentation" @change="handlePhotoPresentationUpload" accept="image/*" class="input-field" />
                <label for="photoPresentation">Photo de présentation</label>
            </div>

            <div class="input-group my-6 md:my-8">
                <textarea v-model="vehicule.presentation_vehicule" id="presentationVehicule" placeholder=" " class="input-field"></textarea>
                <label for="presentationVehicule">Présentation Véhicule</label>
            </div>

            <div class="flex-fields my-6 md:my-8">

                <div class="input-group my-2 md:my-0">
                    <input v-model.number="vehicule.nb_porte" id="nbPorte" type="number" placeholder=" " required class="input-field" />
                    <label for="nbPorte">Nombre de portes</label>
                </div>

                <div class="input-group my-2 md:my-0">
                    <input v-model="vehicule.couleur" id="couleur" placeholder=" " required class="input-field" />
                    <label for="couleur">Couleur</label>
                </div>

                <div class="input-group my-2 md:my-0">
                    <input v-model.number="vehicule.kilometrage" id="kilometrage" type="number" placeholder=" " required class="input-field" />
                    <label for="kilometrage">Kilométrage</label>
                </div>

            </div>

            <div class="flex-fields my-6 md:my-8">
                <div class="input-group my-2 md:my-0">
                    <select v-model="vehicule.typeCarburant" id="typeCarburant" required class="input-field">
                        <option disabled value="">Sélectionner un type de carburant</option>
                        <option value="Electrique">Electrique</option>
                        <option value="Essence">Essence</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Hybride">Hybride</option>
                        <option value="Hybride rechargeable">Hybride rechargeable</option>
                    </select>
                    <label for="typeCarburant">Type de carburant</label>
                </div>

                <div class="input-group my-2 md:my-0">
                    <select v-model="vehicule.boiteDeVitesse" id="boiteDeVitesse" required class="input-field">
                        <option disabled value="">Sélectionner un type de boite de vitesse</option>
                        <option value="Manuel">Manuel</option>
                        <option value="Automatique">Automatique</option>
                    </select>
                    <label for="boiteDeVitesse">Boîte de vitesse</label>
                </div>

                <div class="input-group my-2 md:my-0">
                    <select v-model="vehicule.type_vehicule" id="typeVehicule" required class="input-field">
                        <option disabled value="">Sélectionner un type de Véhicule</option>
                        <option value="Berline">Berline</option>
                        <option value="Coupe">Coupé</option>
                        <option value="Cabriolet">Cabriolet</option>
                        <option value="Break">Break</option>
                        <option value="SUV">SUV</option>
                        <option value="4x4">4x4</option>
                        <option value="Monospace">Monospace</option>
                        <option value="Pick-up">Pick-up</option>
                        <option value="Utilitaire">Utilitaire</option>
                    </select>
                    <label for="typeVehicule">Type de véhicule</label>
                </div>
            </div>

            <div class="switch-container my-4">
                <span>Véhicule</span>
                <label class="switch">
                    <input type="checkbox" v-model="vehicule.isUtilitaire" />
                    <span class="slider"></span>
                </label>
                <span>Utilitaire</span>
            </div>

            <div class="input-group my-6 md:my-8">
                <textarea v-model="vehicule.remarque" id="remarque" placeholder=" " class="input-field"></textarea>
                <label for="remarque">Remarque</label>
            </div>

            <div class="input-group my-6 md:my-8">
                <input type="file" id="photoVehicule" @change="handlePhotoVehiculeUpload" accept="image/*" multiple class="input-field" />
                <label for="photoVehicule">Photos du véhicule</label>
            </div>

            <div class="input-group my-6 md:my-8">
                <select v-model="vehicule.agence" id="agence" required class="input-field">
                    <option disabled value="">Sélectionner une agence</option>
                    <option v-for="agence in agences" :key="agence.id" :value="agence.id">
                    {{ agence.nom }}
                    </option>
                </select>
                <label for="agence">Agence</label>
            </div>

            <div class="input-group my-6 md:my-8">
                <input v-model="vehicule.prixJour" id="prixJour" placeholder=" " required class="input-field" />
                <label for="prixJour">Prix par jour</label>
            </div>

            
            <button type="submit" class="btn-primary"> {{ buttonText }} </button>
            <button type="button" @click="$emit('close')" class="btn-secondary">Annuler</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router'
import AgenceServices from '@/services/AgenceServices';
import VehiculeServices from '@/services/VehiculeServices';

const router = useRouter();

const props = defineProps({
  vehiculeInitial: Object
});

const photoPresentation = ref<File | null>(null)
const photoVehicule = ref([] as File[])
let buttonText = 'Ajouter';

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
    typeCarburant: '',
    boiteDeVitesse: '',
    presentation_vehicule: '',
    remarque: '',
    photo_vehicule: [],
    agence: '',
    type_vehicule: '',
    isUtilitaire: 0,
    prixJour: 0,
});

if (props.vehiculeInitial) {
    vehicule.value.marque = props.vehiculeInitial.marque;
    vehicule.value.modele = props.vehiculeInitial.modele;
    vehicule.value.nb_porte = props.vehiculeInitial.nb_porte;
    vehicule.value.couleur = props.vehiculeInitial.couleur;
    vehicule.value.kilometrage = props.vehiculeInitial.kilometrage;
    vehicule.value.typeCarburant = props.vehiculeInitial.typeCarburant;
    vehicule.value.boiteDeVitesse = props.vehiculeInitial.boiteDeVitesse;
    vehicule.value.presentation_vehicule = props.vehiculeInitial.presentation_vehicule;
    vehicule.value.remarque = props.vehiculeInitial.remarque;
    vehicule.value.agence = props.vehiculeInitial.agence?.id ?? '';
    vehicule.value.type_vehicule = props.vehiculeInitial.type_vehicule;
    vehicule.value.isUtilitaire = props.vehiculeInitial.isUtilitaire;
    vehicule.value.prixJour = props.vehiculeInitial.prixJour;
    buttonText = 'Modifier';
}

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
        formData.append("typeCarburant", vehicule.value.typeCarburant.toString());
        formData.append("boiteDeVitesse", vehicule.value.boiteDeVitesse.toString());
        formData.append("presentation_vehicule", vehicule.value.presentation_vehicule || "");
        formData.append("remarque", vehicule.value.remarque || "");
        formData.append("agence", vehicule.value.agence);
        formData.append("type_vehicule", vehicule.value.type_vehicule);
        formData.append("isUtilitaire", vehicule.value.isUtilitaire ? "1" : "0");
        formData.append("prixJour", vehicule.value.prixJour.toString());

        // Gestion des fichiers
        if (photoPresentation.value) {
            formData.append("photo_presentation", photoPresentation.value);
        }

        if (photoVehicule.value.length) {
            photoVehicule.value.forEach((file) => {
                formData.append('photo_vehicule[]', file);
            });
        }

        if (props.vehiculeInitial && props.vehiculeInitial.id) {
            await VehiculeServices.updateVehicule(props.vehiculeInitial.id, formData);
            alert("Véhicule modifié avec succès");
            router.push('/admin/vehicule/'+props.vehiculeInitial.id) // ou retour vers fiche détail
        } else {
            await VehiculeServices.addVehicule(formData);
            alert("Véhicule ajouté avec succès");
            router.push('/admin/vehicules') // ou retour vers fiche détail
        }
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


    /* --------------------------------------- */

    .flex-fields {
        display: flex;
        flex-direction: column; /* Par défaut : en colonne (mobile) */
        gap: 1rem; /* espacement entre les champs */
    }

    @media (min-width: 768px) {
        .flex-fields {
            flex-direction: row; /* En ligne dès 768px de largeur */
            align-items: flex-start;
        }

        .flex-fields > * {
            flex: 1; /* chaque champ prend la même largeur */
        }
    }

    /* --------------------------------------- */

    input, optgroup, select, textarea{
        color: white;
    }

    select option {
        color: black;
    }

    .input-group {
        position: relative;
        width: 100%; /* adapte selon besoin */
    }

    .input-field {
        width: 100%;
        padding: 12px 8px 8px 8px;
        font-size: 16px;
        border: 1px solid #aaa;
        border-radius: 4px;
        background: none;
        outline: none;
        box-sizing: border-box;
    }

    /* Placeholder vide pour gérer le :placeholder-shown */
    .input-field::placeholder {
        color: transparent;
    }

    /* Label positionné au centre vertical au départ */
    .input-group label {
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
        color: #ffffff;
        font-size: 16px;
        pointer-events: none;
        padding: 0 4px;
        transition: 0.2s ease all;
    }

    /* Quand l’input/select/textarea a le focus OU n’est pas vide */
    .input-field:focus + label,
    .input-field:not(:placeholder-shown) + label {
        top: -12px;
        left: 0px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Pour select, ajouter un padding pour que le label ne cache pas la flèche */
    select.input-field {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url('data:image/svg+xml;utf8,<svg fill="%23777" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 16px 16px;
        padding-right: 30px;
    }

    /* Textarea hauteur un peu plus grande */
    textarea.input-field {
        min-height: 80px;
        resize: vertical;
    }

</style>
