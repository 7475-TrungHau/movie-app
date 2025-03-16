import { get } from "./api";

const MOVIE_LIST_BANNER_URL = '/danh-sach/phim-moi-cap-nhat';
const MOVIE_LIST_CATEGORY_URL = '/v1/api/the-loai/';
const MOVIE_LIST = '/v1/api/danh-sach/';

export const getMoveListBanner = (params) => get(MOVIE_LIST_BANNER_URL, params);
export const getMovieListCategory = (category, params) => get(MOVIE_LIST_CATEGORY_URL + category, params);
export const getMovieList = (typeList, params) => get(MOVIE_LIST + typeList, params);