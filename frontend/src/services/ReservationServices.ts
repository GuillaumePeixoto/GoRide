import axios from "axios";
import AuthService from "@/services/AuthService";

const API_URL = "http://localhost:8000/api";

export default {

    async addReservation(vehiculeData: any) {
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

    async sendReservationInfos(reservationInfos: FormData) {

        try {
            console.log('Données de la réservation :', reservationInfos)

            const response = await axios.post(API_URL+'/reservation', reservationInfos, {
                headers: {
                    Authorization: `Bearer ${AuthService.getToken()}`,
                },
            })

            return response.data;
        } catch (error) {
            console.error('Erreur :', error)
        }
    },

    async getAllReservations() {
        try {
            const response = await axios.get(API_URL + '/reservations', {
                headers: {
                    Authorization: `Bearer ${AuthService.getToken()}`,
                },
            });
            return response.data;
        } catch (error) {
            console.error('Erreur lors de la récupération des réservations :', error);
            throw error;
        }
    }

};
