import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_PHP_API_URL;

export const getVehicules = () => axios.get(`${API_BASE_URL}/vehicules`);
export const getVehiculeById = (id) => axios.get(`${API_BASE_URL}/vehicules/${id}`);
export const createVehicule = (data) => axios.post(`${API_BASE_URL}/vehicules`, data);

