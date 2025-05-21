<script setup lang="ts">
defineProps<{
  vehicule: {
    marque: string
    modele: string
    couleur: string
    kilometrage: number
    nb_porte: number
    type_vehicule: string
    presentation_vehicule: string
    remarque: string
    agence: { nom: string }
    photo_presentation: string | null
    photo_vehicule: string[]
  }
}>()

const apiUrl = import.meta.env.VITE_PHP_API_URL;

</script>

<template>
    <div class="card mt-3 mx-2">
        <div class="imgCar">
            <img
                v-if="vehicule.photo_presentation"
                :src="apiUrl+'/'+vehicule.photo_presentation"
                alt="photo véhicule"
                class="h-40 max-w-28 object-cover rounded mb-4 mx-auto"
            />
            <div class="contentInfos gold-font-color">
                <div class="text-lg font-bold mb-1">{{ vehicule.marque }} {{ vehicule.modele }}</div>
                <div class="contentInfosHide">
                    <p class="mt-2 text-sm"><strong>Couleur:</strong> {{ vehicule.couleur }}</p>
                    <p class="text-sm"><strong>Kilométrage:</strong> {{ vehicule.kilometrage }} km</p>
                    <p class="text-sm"><strong>Type:</strong> <span style="text-transform: uppercase;"> {{ vehicule.type_vehicule }}</span></p>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- <style scoped>
    .backgroundCard {
        background: linear-gradient(45deg, #868181 40%, #221f1f);
        background-size: 100%; /* Le dégradé occupe 50% de la largeur et 50% de la hauteur */
        background-position: center; /* Centre le dégradé */
        background-repeat: no-repeat; /* Évite que le dégradé se répète */
    }
</style> -->

<style scoped>
    .card{
        cursor: pointer;
        position: relative;
        width: 300px;
        height: 400px;
        background: #221f1f;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card::before{
        content: '';
        position: absolute;
        top: -50%;
        left: 0%;
        width: 100%;
        height: 100%;
        background: #c49a52;
        transform: skewY(345deg);
        transition: 0.7s;
    }

    .card:hover::before{
        top: -70%;
        transform: skewY(390deg);
    }

    .card:hover{
        transform: scale(1.02);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.5);
    }

    .card .imgCar{
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        z-index: 1;
    }

    .card .imgCar img{
        transition: 0.7s;
    }

    .card:hover .imgCar img{
        transform: scale(1.1);
    }

    .card .contentInfos{
        position: relative;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        z-index: 1;
        transform: translateY(70px);
        transition: transform 0.7s ease;
    }

    .card:hover .contentInfos{
        display: flex;
        z-index: 1;
        opacity: 1;
        transform: translateY(0);
    }

    .card .contentInfosHide{
        opacity: 0;
        transition: opacity 0.7s ease;
    }

    .card:hover .contentInfosHide{
        opacity: 1;
    }
    
</style>