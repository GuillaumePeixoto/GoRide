<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
</script>

<template>
    <header>
        <nav class="navbar" >
            <img alt="Vue logo" class="logo" src="@/assets/Logo.png" width="200" height="100" />
            <ul class="menu">
                <li class="no-style-li"><RouterLink class="custom-link-menu" to="/">Accueil</RouterLink></li>
                <li class="no-style-li"><RouterLink class="custom-link-menu" to="/reservations">Réservations</RouterLink></li>
                <li class="no-style-li"><RouterLink class="custom-link-menu" to="/about">À propos</RouterLink></li>
            </ul>
        </nav>
    </header>

    <RouterView />

    <footer class="background-light-black gold-font-color">
        <p>&copy; 2025 GoRide - Tous droits réservés</p>
        <ul>
            <li class="no-style-li"><RouterLink class="custom-link-menu" to="/contact">Contact</RouterLink></li>
            <li class="no-style-li"><RouterLink class="custom-link-menu" to="/terms">Conditions d'utilisation</RouterLink></li>
            <li class="no-style-li"><RouterLink class="custom-link-menu" to="/privacy">Politique de confidentialité</RouterLink></li>
        </ul>
    </footer>

</template>

<style scoped>

</style>
