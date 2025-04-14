import axios from "axios";
import { getApp, postApp, postFormData } from "./api";



export const registerUser = async (data) => postApp("/register", data);

export const loginUser = async (data) => postApp('/login', data);

export const getUserInfo = async () => getApp('/user');
export const getUserProfileData = async (typeData) => getApp('/user/' + typeData);
export const getUserInfoWithData = async (type, params) => getApp('/user/' + type, params);
export const updateProfile = (formData) => postFormData('/user/update', formData);
export const changePassword = async (data) => postApp('/user/change-password', data);