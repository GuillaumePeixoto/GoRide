import axios from "axios";
import AuthService from "@/services/AuthService"; // si tu veux centraliser le token ici aussi

const API_URL = import.meta.env.VITE_PHP_API_URL;

export interface RegisterForm {
    nom: string;
    prenom: string;
    birthdate: string;
    email: string;
    password: string;
    confirmPassword: string;
    adresse: string;
    ville: string;
    codePostal: string;
    // Ajoute d'autres champs si besoin
}

export default {
    async registerUser(formData: RegisterForm) {
        try {
            const response = await axios.post(`${API_URL}/user/inscription`, formData, {
                headers: {
                    "Content-Type": "application/json",
                },
            });
            return response.data;
        } catch (error) {
            console.error("Erreur lors de l'inscription :", error);
            throw error;
        }
    },

    async getUsers() {
        try {
            const response = await axios.get(`${API_URL}/api/users`, {
                headers: {
                    Authorization: `Bearer ${AuthService.getToken()}`,
                },
            });
            return response.data;
        } catch (error) {
            console.error("Erreur lors de la récupération des utilisateurs :", error);
            throw error;
        }
    },
};
