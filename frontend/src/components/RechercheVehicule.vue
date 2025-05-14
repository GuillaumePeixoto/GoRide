<script setup lang="ts">

    import { ref, onMounted } from 'vue'
    import AgenceServices from '@/services/AgenceServices';

    // Déclaration des données

    const form = ref({
        startDate: '',
        endDate: '',
        ville: '',
        type: 0
    });

    const villes = ref<string[]>([]);
    const erreur = ref('');

    // Fonction pour initialiser les datepickers
    onMounted(async () => {
        try {
            villes.value = await AgenceServices.getDistinctVilles();
        } catch (error) {
            console.error("Erreur lors de la récupération des villes", error);
        }
    })


    const emit = defineEmits<{
    (e: 'submit', form: { startDate: string; endDate: string; ville: string; type: number; }): void;
    }>();

    function handleSubmit() {
        if(form.value.startDate && form.value.endDate && (form.value.startDate > form.value.endDate)){
            erreur.value = "Les dates sélectionnées ne sont pas valides";
            return;
        }
        erreur.value = '';
        emit('submit', form.value);
    }

</script>

<template>
    <div v-if="erreur !== ''" class="message-erreur">
        {{ erreur }}
    </div>
    <form @submit.prevent="handleSubmit" class="search-container">

        <input type="date" class="filterInput" placeholder="Date de départ" v-model="form.startDate">

        <input type="date" class="filterInput" placeholder="Date d'arrivée"  v-model="form.endDate">

        <select id="ville-select" class="pe-2 text-black filterInput" v-model="form.ville">
            <option value="" selected>Choisir une ville</option>
            <option v-for="(ville, index) in villes" :key="index" :value="ville">{{ ville }}</option>
        </select>

        <select class="pe-2 text-black filterInput" v-model="form.type">
            <option value="0" selected>Véhicule</option>
            <option value="1">Utilitaire</option>
        </select>

        <button type="submit" id="filterButton" class="filterInput" >Rechercher</button>

    </form>
</template>

<style scoped>

    .search-container input:first-of-type{
        border-radius: 8px 0 0 8px;
        border-left: 2px solid black;
    }

    .search-container > *{
        width: 20%;
        border: 2px solid black;
    }

    /* Container principal */
    .search-container {
        display: block;
        width: 70%;
        height: 60px;
        margin: 10px auto;
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

    #filterButton{
        border-radius: 0px 8px 8px 0px;
        font-weight: bold;
        background-color: #3f3b3b;
        color: rgb(214, 214, 214);
        border-right: 2px solid black;
        border-left: 2px solid black;
        transition: background-color 0.4s ease;
    }

    #filterButton:hover{
        background-color: #c49a52;
        color: black;
    }

    .filterInput {
        padding: 8px;
        font-size: 16px;
        border-left: 1px solid #525252;
        border-right: 1px solid #525252;
        height: 60px !important;
        line-height: normal;
    }

</style>