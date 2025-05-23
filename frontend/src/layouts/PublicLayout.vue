<script setup lang="ts">

import { computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
import { useAuth } from '@/composables/useAuth'

import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const { loginUser ,logoutUser ,isAuthenticated, isAdmin } = useAuth()

const router = useRouter()
const route = useRoute()

console.log(isAuthenticated.value)

const navigation = computed(() => {
  if (isAuthenticated.value) {
    return []
  } else {
    return [
      { name: 'Connexion', href: '/connexion' },
      { name: 'Inscription', href: '/inscription' }
    ]
  }
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
                    <RouterLink class="flex shrink-0 items-center" to="/">
                        <img class="w-24" src="@/assets/Logo.png" alt="GoRide" />
                    </RouterLink>
                </div>

                <!-- Icône notifications + profil -->
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <Menu v-if="!isAuthenticated" as="div" class="hidden sm:block relative ml-3">
                        <RouterLink v-slot="{ isActive }" v-for="item in navigation" :key="item.name" :to="item.href" class='rounded-md px-3 py-2 text-sm font-medium' :aria-current="item.current ? 'page' : undefined">
                            <span :class="[ isActive ? 'backgound-gold text-white' : 'text-gray-300', 'px-3 py-2 rounded-md']" >{{ item.name }} </span>
                        </RouterLink>
                    </Menu>
                    <Menu v-if="isAuthenticated" as="div" class="hidden sm:block relative ml-3">
                        <div>
                            <MenuButton class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 rounded-full" src="@/assets/user-icon.png" alt="Profile" />
                            </MenuButton>
                        </div>
                        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                            <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none">
                                <MenuItem v-slot="{ active }">
                                    <RouterLink :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']" to="/profile">Profil</RouterLink>
                                </MenuItem>
                                <MenuItem v-if="isAdmin" v-slot="{ active }">
                                    <RouterLink :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']" to="/admin/dashboard">Admin</RouterLink>
                                </MenuItem>
                                <MenuItem v-slot="{ active }">
                                    <button @click="handleLogout" :class="[active ? 'bg-gray-100' : '', 'block w-full text-left px-4 py-2 text-sm text-gray-700']" >
                                        Déconnexion
                                    </button>
                                </MenuItem>
                            </MenuItems>
                        </transition>
                    </Menu>
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
                <template v-if="isAuthenticated">
                    <RouterLink to="/profile" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        Profil
                    </RouterLink>
                    <RouterLink v-if="isAdmin" to="/admin/dashboard" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        Admin
                    </RouterLink>
                    <button @click="handleLogout" class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                        Déconnexion
                    </button>
                </template>
            </div>
        </DisclosurePanel>
    </Disclosure>

    <RouterView />

    <footer class="background-light-black text-gray-300 text-center py-4">
        <p class="mb-4">&copy; 2025 GoRide - Tous droits réservés</p>
        <ul class="flex justify-center space-x-4 text-sm">
        <li><RouterLink class="hover:underline" to="/contact">Contact</RouterLink></li>
        <li><RouterLink class="hover:underline" to="/terms">Conditions d'utilisation</RouterLink></li>
        <li><RouterLink class="hover:underline" to="/privacy">Politique de confidentialité</RouterLink></li>
        </ul>
    </footer>
</template>

<style scoped>

</style>


