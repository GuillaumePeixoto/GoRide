import axios from 'axios';
import AuthService from '@/services/AuthService';

const API_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api/agences';

export default {
    async getAgences() {
        try {
            const response = await axios.get(`${API_URL}`);
            return response.data;
        } catch (error) {
            console.error('Erreur lors de la récupération des agences:', error);
            throw error;
        }
    },

    async addAgence(agenceData: { nom: string; adresse: string; ville: string; telephone: string }) {
        try {
            const token = AuthService.getToken();
            const response = await axios.post(`${API_URL}`, agenceData, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });
            return response.data;
        } catch (error) {
            console.error('Erreur lors de l\'ajout de l\'agence:', error);
            throw error;
        }
    }
};
