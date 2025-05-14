<template>
    <div v-if="vehicule" class="flex flex-col items-center justify-center bg-gray-100">
        <h2 class="text-2xl font-bold">{{ vehicule.marque }} {{ vehicule.modele }}</h2>
        <p>Couleur : {{ vehicule.couleur }}</p>
        <p>Kilométrage : {{ vehicule.kilometrage }} km</p>
        <p>Agence : {{ vehicule.agence?.nom }}</p>
    </div>
</template>

<script setup lang="ts">
    import { onMounted, ref } from 'vue'
    import { useRoute } from 'vue-router'
    import VehiculeServices from '@/services/VehiculeServices';

    const route = useRoute()
    const vehicule = ref<any>(null)

    onMounted(async () => {
        const id = Number(route.params.id)
        vehicule.value = await VehiculeServices.getVehicule(id);
        console.log(vehicule.value);
    })
</script>
