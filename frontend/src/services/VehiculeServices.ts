import axios from "axios";
import AuthService from "@/services/AuthService";

const API_URL = "http://localhost:8000/api/vehicules";

export default {
    async getVehicules() {
        try {
            const response = await axios.get(API_URL);
            return response.data;
        } catch (error) {
            console.error("Erreur lors de la récupération des véhicules:", error);
            throw error;
        }
    },

    async addVehicule(vehiculeData: any) {
        try {
            const response = await axios.post(API_URL, vehiculeData, {
                headers: {
                    Authorization: `Bearer ${AuthService.getToken()}`,
                },
            });
            return response.data;
        } catch (error) {
            console.error("Erreur lors de l'ajout du véhicule:", error);
            throw error;
        }
    },
};
