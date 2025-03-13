import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_PHP_API_URL;

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
  
export const registerUser = (formData: RegisterForm) => {
    return axios.post(`${API_BASE_URL}/user/inscription`, formData, {
        headers: {
            'Content-Type': 'application/json',
        },
    });
};
