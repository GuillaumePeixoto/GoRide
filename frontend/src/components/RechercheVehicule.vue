<script setup lang="ts">
    import { ref, onMounted } from 'vue'
    import flatpickr from 'flatpickr'
    import 'flatpickr/dist/flatpickr.min.css' // Importer le CSS de Flatpickr
    import { French } from 'flatpickr/dist/l10n/fr.js'

    // Déclaration des données
    const vehicleSearch = ref('')
    const isUtilityVehicle = ref(false)

    const startDate = ref(null)
    const endDate = ref(null)

    // Fonction pour initialiser les datepickers
    onMounted(() => {
    // Vérifie que les éléments sont bien présents dans le DOM avant d'initialiser Flatpickr
    if (startDate.value) {
        flatpickr(startDate.value, {
            dateFormat: 'Y-m-d',
            inline: true, // Affichage en plein format
            locale: French,
        })
    }

    if (endDate.value) {
        flatpickr(endDate.value, {
            dateFormat: 'Y-m-d',
            inline: true, // Affichage en plein format
            locale: French,
        })
    }
    })
</script>

<template>
    <div class="search-container">
        <div class="search-date-filter">
            <div class="bloc-date-picker">
                <p>Date de départ</p>
                <input type="text" class="date-flatpicker" ref="startDate" placeholder="Date de départ">
            </div>
            <div class="bloc-date-picker">
                <p>Date d'arrivée</p>
                <input type="text" class="date-flatpicker" ref="endDate" placeholder="Date d'arrivée">
            </div>
        </div>
        <div class="search-type-city" style="display: flex">
            <input type="text" id="vehicle-search" placeholder="Rechercher un véhicule...">
            <ul id="suggestions" class="suggestions-list"></ul>

            <div class="switch-container">
                <span>Véhicule</span>
                <label class="switch">
                    <input type="checkbox" id="vehicle-type">
                    <span class="slider"></span>
                </label>
                <span>Utilitaire</span>
            </div>
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
        background-color: #3d3b3b;
        border-radius: 8px;
    }

    .search-date-filter{
        display: flex;
        justify-content: space-around;
        vertical-align: middle;
    }

    .search-type-city{
        display: flex;
        margin-top: 40px;
        justify-content: space-around;
        color: rgb(210, 210, 210);
    }

    .bloc-date-picker p{
        text-align: center;
        margin-top: 0px;
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