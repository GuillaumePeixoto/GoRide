<script setup lang="ts">
import { ref, onMounted } from 'vue'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'
import '@/assets/custom-flatpickr.css'
import { French } from 'flatpickr/dist/l10n/fr.js'
import axios from 'axios'
import AgenceServices from '@/services/AgenceServices';
import VehiculeServices from '@/services/VehiculeServices';

// Déclaration des données
const villeSearch = ref('')
const isUtilityVehicle = ref(false)
const startDate = ref<string | null>(null)
const endDate = ref<string | null>(null)
const villes = ref<string[]>([])

const erreur = ref('');

// Références pour Flatpickr
const startDateInput = ref<HTMLInputElement | null>(null)
const endDateInput = ref<HTMLInputElement | null>(null)

// Fonction pour initialiser les datepickers
onMounted(async () => {

    try {
        villes.value = await AgenceServices.getDistinctVilles();
    } catch (error) {
        console.error("Erreur lors de la récupération des villes", error);
    }

    if (startDateInput.value) {
        flatpickr(startDateInput.value, {
            dateFormat: 'Y-m-d',
            inline: true,
            locale: French,
            minDate: 'today',
            onChange: (selectedDates) => {
                startDate.value = selectedDates[0] ? selectedDates[0].toLocaleDateString('fr-CA') : null;
            }
        })
    }

    if (endDateInput.value) {
        flatpickr(endDateInput.value, {
            dateFormat: 'Y-m-d',
            inline: true,
            locale: French,
            minDate: 'today',
            onChange: (selectedDates) => {
                endDate.value = selectedDates[0] ? selectedDates[0].toLocaleDateString('fr-CA') : null
            }
        })
    }
})

// Fonction de recherche
const searchVehicles = () => {

    erreur.value = '';
    if(startDate.value > endDate.value || endDate.value < startDate.value){
        erreur.value = "Les dates sélectionnées sont incorrectes";
        return;
    }

    let formValues = {
        startDate: startDate.value,
        endDate: endDate.value,
        ville: villeSearch.value,
        type: isUtilityVehicle.value ? 1 : 0
    }

    const listVehicules = VehiculeServices.searchVehicules(formValues);
    console.log("Liste des véhicules :", listVehicules);

}
</script>

<!-- TODO : Modifier la zone des filtres et tout mettre sur une seule ligne -->

<template>
    <div class="search-container">
        <div v-if="erreur" class="message-erreur">
            {{ erreur }}
        </div>
        <div class="search-date-filter">
            <div class="bloc-date-picker">
                <p>Date de départ</p>
                <input type="text" class="date-flatpicker" ref="startDateInput" placeholder="Date de départ">
            </div>
            <div class="bloc-date-picker">
                <p>Date d'arrivée</p>
                <input type="text" class="date-flatpicker" ref="endDateInput" placeholder="Date d'arrivée">
            </div>
        </div>
        
        <div class="search-type-city">
            <select id="ville-select" class="rounded-md pe-2 text-black" v-model="villeSearch">
                <option value="" selected>Choisir une ville</option>
                <option v-for="(ville, index) in villes" :key="index" :value="ville">{{ ville }}</option>

            </select>

            <div class="switch-container">
                <span>Véhicule</span>
                <label class="switch">
                    <input type="checkbox" v-model="isUtilityVehicle">
                    <span class="slider"></span>
                </label>
                <span>Utilitaire</span>
            </div>
        </div>
        <div class="search-button-valid">
            <button @click="searchVehicles">Rechercher</button>
        </div>
    </div>
</template>

<style scoped>

    .date-flatpicker{
        display: none;
    }

    /* Container principal */
    .search-container {
        display: block;
        width: 60%;
        padding: 20px;
        margin: 0 auto;
        flex-direction: column;
        gap: 10px;
        background-color: #3d3b3be1;
        border-radius: 8px;
    }

    .search-button-valid{
        margin-top: 20px;
        display: flex;
        justify-content: center;
        vertical-align: middle;
    }

    .search-button-valid button{
        width: 130px;
        height: 30px;
        border-radius: 5px;
        font-weight: bold;
        background-color: rgb(189, 182, 182);
        border: 2px solid black;
    }

    .search-button-valid button:hover{
        background-color: #c49a52;
    }

    .search-date-filter{
        display: flex;
        justify-content: space-around;
        vertical-align: middle;
    }

    .search-type-city{
        display: flex;
        margin-top: 20px;
        justify-content: space-around;
        color: rgb(210, 210, 210);
    }

    .bloc-date-picker p{
        text-align: center;
        margin-top: 0px;
        margin-bottom: 5px;
        color: rgb(210, 210, 210);
        font-size: 18px;
    }

    input {
        padding: 8px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    /* Suggestions */
    .suggestions-list {
        list-style: none;
        padding: 0;
        margin: 0;
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        display: none;
        background: white;
    }

    .suggestions-list li {
        padding: 8px;
        cursor: pointer;
    }

    .suggestions-list li:hover {
        background: #f0f0f0;
    }

    /* Switch */
    .switch-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-weight: bold;
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