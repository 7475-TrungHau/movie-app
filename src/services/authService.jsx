import axios from "axios";
import { getApp, postApp, postFormData } from "./api";

export const registerUser = async (data) => postApp("/register", data);

export const loginUser = async (data) => postApp('/login', data);

export const getUserInfo = async () => getApp('/user');
export const getUserProfileData = async (typeData, params) => getApp('/user/' + typeData, params);
export const getUserInfoWithData = async (type, params) => getApp('/user/' + type, params);
export const updateProfile = (formData) => postFormData('/user/update', formData);
export const changePassword = async (data) => postApp('/user/change-password', data);

// Google Authentication
export const getGoogleAuthUrl = async () => {
    const response = await axios.get('http://localhost:8000/api/auth/google');
    return response.data.url;
};

// New function to save watch history
// Change this function
