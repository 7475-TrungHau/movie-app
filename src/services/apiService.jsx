import { getApp, postApp } from "./api";


const APP_MOVIE_URL = '/movies/';

export const getMovies = async (params) => getApp(APP_MOVIE_URL, params);
export const getMovie = async (identifier, params) => getApp(APP_MOVIE_URL + identifier, params);
export const postRateMovie = async (identifier, data) => postApp(APP_MOVIE_URL + identifier + '/rating', data);
export const postFavoriteMovie = async (identifier) => postApp(APP_MOVIE_URL + identifier + '/favorite');
export const postHistoryMovie = async (identifier, data) => postApp(APP_MOVIE_URL + identifier + '/histories', data);
// export const saveWatchHistory = async (data) => {
//     const episodeId = data.episode_id;
//     return postApp(`/movie/histories/${episodeId}`, {
//         progress: data.progress
//     });
// };