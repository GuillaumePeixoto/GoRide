import axios from "axios";
import AuthService from "@/services/AuthService";

const API_URL = "http://localhost:8000/api";

export default {
    async getVehicules() {
        try {
            const response = await axios.get(API_URL+"/vehicules");
            return response.data;
        } catch (error) {
            console.error("Erreur lors de la récupération des véhicules:", error);
            throw error;
        }
    },

    async addVehicule(vehiculeData: any) {
        try {
            const response = await axios.post(API_URL+"/vehicules", vehiculeData, {
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

    async getVehicule(id: number) {
        const response = await axios.get(API_URL+"/vehicule/"+id)
        return response.data
    },

    async updateVehicule(id: number, formData: FormData) {
        return axios.post(API_URL+'/vehicule/'+id+'/edit', formData, {
            headers: { 
                'Content-Type': 'multipart/form-data',
                Authorization: `Bearer ${AuthService.getToken()}`,
            },
        });
    },

    async deleteVehicule(vehiculeId: number) {
        try {
            const response = await axios.delete(API_URL+"/vehicule/"+vehiculeId, {
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

    async searchVehicules(filters: { startDate: string; endDate: string; ville: string; type: number; }){
        try {
            const response = await axios.get(API_URL+"/vehicules/search", {
                params: filters,
            });
            return response.data;
        } catch (error) {
            console.error("Erreur lors de la recherche des véhicules:", error);
            throw error;
        }

    }
};
