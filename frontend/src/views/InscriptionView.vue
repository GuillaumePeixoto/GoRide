<script setup lang="ts">

    import { reactive, computed } from 'vue';
    import { registerUser, type RegisterForm } from '/workspace/frontend/src/services/UserServices';
    import { useRouter } from 'vue-router'


    const form = reactive<RegisterForm>({
        nom: '',
        prenom: '',
        birthdate: '',
        email: '',
        password: '',
        confirmPassword: '',
        adresse: '',
        ville: '',
        codePostal: ''
    });

    const router = useRouter()

    const isFormValid = computed(() => {
        return (
            form.nom.trim() !== '' &&
            form.prenom.trim() !== '' &&
            form.birthdate !== '' &&
            form.email.trim() !== '' &&
            form.password !== '' &&
            form.confirmPassword !== '' &&
            form.password === form.confirmPassword &&
            form.adresse.trim() !== '' &&
            form.ville.trim() !== '' &&
            form.codePostal.trim() !== ''
        )
    })

    const handleSubmit = async () => {
        try {
            const response = await registerUser(form);
            console.log('Inscription réussie:', response.data);
            router.push('/')
        } catch (error: any) {
            if (error.response) {
                console.error('Erreur:', error.response.data);
            } else {
                console.error('Erreur réseau:', error.message);
            }
        }
    };


</script>

<template>
    <main class='background-light-black-opacity sm:w-3/4 md:w-2/3 lg:w-2/4 xl:w-1/4"'>
        <form @submit.prevent="handleSubmit" class="">
            <h1>Inscription</h1>
            <p class="text-sm py-0 pl-2 text-gray-400 italic text-right">Tous les champs sont obligatoires</p>
            <div class="form-champ-ligne flex flex-col md:flex-row w-full gap-4">
                <div class="input-container w-full md:w-1/2 p-2">
                    <input v-model="form.nom" type="text" id="nom" required>
                    <label for="nom">Nom</label>
                </div>
                <div class="input-container w-full md:w-1/2 p-2">
                    <input v-model="form.prenom" type="text" id="prenom" required>
                    <label for="prenom">Prénom</label>
                </div>
            </div>
            <div class="form-champ-ligne flex flex-col md:flex-row w-full gap-4">
                <div class="input-container w-full p-2">
                    <input v-model="form.birthdate" type="date" id="birthdate" name="birthdate" class="border rounded-lg p-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"/>
                    <label for="birthdate">Date de naissance</label>
                </div>
            </div>
            <div class="form-champ-ligne flex flex-col md:flex-row w-full gap-4">
                <div class="input-container w-full p-2">
                    <input v-model="form.email" type="text" id="email" required>
                    <label for="email">E-mail</label>
                </div>
            </div>
            <p v-if="form.confirmPassword && form.password !== form.confirmPassword" class="border-solid border-2 border-red-600 bg-red-950 text-sm rounded-md p-2 mx-2 mt-2 text-red-500">
                Les mots de passe ne correspondent pas.
            </p>
            <div class="form-champ-ligne flex flex-col md:flex-row w-full gap-4">
                <div class="input-container w-full md:w-1/2 p-2">
                    <input v-model="form.password" type="password" id="password" required>
                    <label for="password">Mot de passe</label>
                </div>
                <div class="input-container w-full md:w-1/2 p-2">
                    <input v-model="form.confirmPassword" type="password" id="confirmPassword" required>
                    <label for="confirmPassword">Répéter le mot de passe</label>
                </div>
            </div>
            <div class="form-champ-ligne flex flex-col md:flex-row w-full gap-4">
                <div class="input-container w-full p-2">
                    <input v-model="form.adresse" type="text" id="adresse" required>
                    <label for="adresse">Adresse</label>
                </div>
            </div>
            <div class="form-champ-ligne flex flex-col md:flex-row w-full gap-4">
                <div class="input-container w-full md:w-1/2 p-2">
                    <input v-model="form.ville" type="text" id="ville" required>
                    <label for="ville">Ville</label>
                </div>
                <div class="input-container w-full md:w-1/2 p-2">
                    <input v-model="form.codePostal" type="text" id="codePostal" required>
                    <label for="codePostal">Code postal</label>
                </div>
            </div>
            <div class="form-champ-ligne flex flex-col md:flex-row w-full gap-4">
                <div class="input-container w-full p-2">
                    <button
                        type="submit"
                        :disabled="!isFormValid"
                        :class="[
                            'w-full py-2 rounded-lg transition font-semibold',
                            isFormValid
                            ? 'valid-button transition-transform text-white'
                            : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                        ]"
                        >
                        S'inscrire
                    </button>
                </div>
            </div>
        </form>
    </main>
</template>

<style scoped>

    .form-champ-ligne{
        display: flex;
        justify-content: space-between;
        vertical-align: middle;
        margin-top: 25px;
    }

    #form-inscription{
        margin-top: 10px;
    }


    .input-container {
        position: relative;
    }

    .input-container label {
        position: absolute;
        top: 50%;
        left: 20px;
        transform: translateY(-50%);
        transition: 0.3s;
        color: #4d4d4d;
        pointer-events: none;
    }

    .input-container input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .input-container input:focus + label,
    .input-container input:valid + label {
        top: -5px;
        left: 10px;
        font-size: 12px;
        color: #f1f1f1;
    }

    .valid-button{
        background-color: #c49a52;
    }

    .valid-button:hover{
        background-color: #a57b32;
    }


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
        color: rgb(230, 230, 230);
    }
</style>
