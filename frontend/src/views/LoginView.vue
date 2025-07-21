<template>
    <main class="w-full sm:w-10/12 md:w-3/4 lg:w-2/4 xl:w-2/4  mx-auto p-0 sm:p-2 sm:mt-6">
        <div class="background-light-black-opacity rounded p-4 w-full mx-auto">
            <h1 class="text-xl mb-4">Connexion</h1>
            <form @submit.prevent="handleLogin">

                <div class="input-group my-2 md:my-0">
                    <input v-model="email" placeholder=" " class="input input-field text-white" id="email" />
                    <label for="email">Email</label>
                </div>

                <div class="input-group mt-8 mb-4">
                    <input v-model="password" id="password" type="password"  placeholder=" "  required class="input input-field text-white" />
                    <label for="password">Mot de passe</label>
                </div>

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
            const redirectPath = localStorage.getItem('redirectAfterLogin') || '/';
            localStorage.removeItem('redirectAfterLogin'); // Nettoyage
            router.push(redirectPath);
        } catch (err) {
            console.error('Erreur de connexion', err)
        }
    }
</script>

<style scoped>
    main{
        font-family: Arial, Helvetica, sans-serif;
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

    .input-group {
        position: relative;
        width: 100%; /* adapte selon besoin */
    }

    .input-field {
        width: 100%;
        padding: 12px 8px 8px 8px;
        font-size: 16px;
        border: 1px solid #aaa;
        border-radius: 4px;
        background: none;
        outline: none;
        box-sizing: border-box;
    }

    /* Placeholder vide pour gérer le :placeholder-shown */
    .input-field::placeholder {
        color: transparent;
    }

    /* Label positionné au centre vertical au départ */
    .input-group label {
        position: absolute;
        left: 8px;
        top: 50%;
        transform: translateY(-50%);
        color: #ffffff;
        font-size: 16px;
        pointer-events: none;
        padding: 0 4px;
        transition: 0.2s ease all;
    }

    /* Quand l’input/select/textarea a le focus OU n’est pas vide */
    .input-field:focus + label,
    .input-field:not(:placeholder-shown) + label {
        top: -12px;
        left: 0px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
