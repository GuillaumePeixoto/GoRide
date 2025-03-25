<template>
    <main class='background-light-black-opacity sm:w-3/4 md:w-2/3 lg:w-2/4 xl:w-1/4"'>
        <div class="p-4 max-w-md mx-auto">
            <h1 class="text-xl mb-4">Connexion</h1>
            <form @submit.prevent="handleLogin">
                <input v-model="email" placeholder="Email" class="input" />
                <input v-model="password" type="password" placeholder="Mot de passe" class="input mt-2" />
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Se connecter
                    </button>
                </div>

            </form>
        </div>
    </main>
</template>

<script lang="ts" setup>
    import { ref } from 'vue'
    import { useRouter } from 'vue-router'
    import { useAuth } from '@/composables/useAuth'

    const email = ref('')
    const password = ref('')
    const { loginUser } = useAuth()
    const router = useRouter()

    const handleLogin = async () => {
        try {
            await loginUser(email.value, password.value)
            router.push('/') // redirection après login
        } catch (err) {
            console.error('Erreur de connexion', err)
        }
    }
</script>

<style scoped>
    main{
        font-family: Arial, Helvetica, sans-serif;
        padding: 20px;
        margin: 0 auto;
        margin-top: 4em;
        border-radius: 6px;
    }

    h1{
        text-align: center;
        font-size: 24px;
        margin: 0px;
        margin-bottom: 20px;
        color: rgb(230, 230, 230);
    }

    .input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .btn {
        background: #1e90ff;
        color: white;
        padding: 0.5rem;
        border-radius: 6px;
    }
</style>
