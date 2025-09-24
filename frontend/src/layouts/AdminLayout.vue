<script setup lang="ts">

    import { computed } from 'vue'
    import { useRouter, useRoute } from 'vue-router'
    import { useAuth } from '@/composables/useAuth'

    import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
    import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'

    const { loginUser ,logoutUser ,isAuthenticated, isAdmin } = useAuth()

    const router = useRouter()
    const route = useRoute()

    const navigation = computed(() => {
        return [
            { name: 'Dashboard', href: '/admin/dashboard' },
            { name: 'Réservations', href: '/admin/reservations' },
            { name: 'Agences', href: '/admin/agences' },
            { name: 'Véhicules', href: '/admin/vehicules' },
            { name: 'Utilisateurs', href: '/admin/users' }
        ]
    })

    const handleLogout = () => {
        logoutUser()
        router.push('/')
    }

</script>


<template>
    <Disclosure as="nav" class="background-light-black" v-slot="{ open }">
        <div class="mx-auto max-w-full px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <!-- Bouton menu mobile -->
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <DisclosureButton class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="absolute -inset-0.5" />
                        <span class="sr-only">Open main menu</span>
                        <Bars3Icon v-if="!open" class="block size-6" aria-hidden="true" />
                        <XMarkIcon v-else class="block size-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>

                <!-- Logo et menu desktop -->
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex shrink-0 items-center">
                        <img class="w-24" src="@/assets/Logo.png" alt="GoRide" />
                    </div>
                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-2">
                            <RouterLink v-slot="{ isActive }" v-for="item in navigation" :key="item.name" :to="item.href" class='rounded-md px-3 py-2 text-sm font-medium' :aria-current="item.current ? 'page' : undefined">
                                  <span :class="[ isActive ? 'backgound-gold text-white' : 'text-gray-300', 'px-3 py-2 rounded-md']" >{{ item.name }} </span>
                            </RouterLink>
                        </div>
                    </div>
                </div>

                <!-- Icône notifications + profil -->
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">

                    <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                        <div class="hidden sm:ml-6 sm:block">
                            <div class="flex space-x-2">
                                <RouterLink to="/" class='rounded-md px-3 py-2 text-sm font-medium'>
                                    <span class="text-gray-300 px-3 py-2 rounded-md" > Quitter Admin </span>
                                </RouterLink>
                                <div @click="handleLogout" class='rounded-md px-3 py-2 text-sm font-medium'>
                                    <span class="text-gray-300 px-3 py-2 rounded-md" > Déconnexion </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu mobile -->
        <DisclosurePanel class="sm:hidden">
            <div class="space-y-1 px-2 pb-3 pt-2">
                <DisclosureButton v-for="item in navigation" :key="item.name" as="template" >
                    <RouterLink :to="item.href" :class="[item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'block rounded-md px-3 py-2 text-base font-medium']" :aria-current="item.current ? 'page' : undefined">
                        {{ item.name }}
                    </RouterLink>
                </DisclosureButton>
            </div>
        </DisclosurePanel>
    </Disclosure>

    <RouterView />

    <footer class="background-light-black text-gray-300 text-center py-4">
        <p class="py-0">&copy; 2025 GoRide - Tous droits réservés</p>
    </footer>
</template>

<style scoped>

</style>


