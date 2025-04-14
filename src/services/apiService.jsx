import { getApp, postApp } from "./api";


const APP_MOVIE_URL = '/movies/';

export const getMovies = async (params) => getApp(APP_MOVIE_URL, params);
export const getMovie = async (identifier, params) => getApp(APP_MOVIE_URL + identifier, params);
export const postRateMovie = async (identifier, data) => postApp(APP_MOVIE_URL + identifier + '/rating', data);
export const postFavoriteMovie = async (identifier) => postApp(APP_MOVIE_URL + identifier + '/favorite');