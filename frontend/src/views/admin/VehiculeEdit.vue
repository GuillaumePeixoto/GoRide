<template>
    <main v-if="vehicule">
        <div  class="background-light-black-opacity sm:w-4/4 md:w-3/4 lg:w-5/6 xl:w-11/12 mx-auto md:mt-2 rounded p-6">
            <h1 class="text-white mb-3 text-xl">Modifier le véhicule</h1>
            <VehiculeForm :vehicule-initial="vehicule" />
        </div>
    </main>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import VehiculeServices from '@/services/VehiculeServices'
import VehiculeForm from '@/components/admin/VehiculeForm.vue';

const route = useRoute()
const vehicule = ref<any>(null)

onMounted(async () => {
  const id = route.params.id
  vehicule.value = await VehiculeServices.getVehicule(id);
})

</script>
