import axios from "axios";
import { getApp, postApp } from "./api";



export const registerUser = async (data) => postApp("/register", data);

export const loginUser = async (data) => postApp('/login', data);

export const getUserInfo = async () => getApp('/user');
export const getUserProfileData = async (typeData) => getApp('/user/' + typeData);