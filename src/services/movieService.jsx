import { get } from "./api";

const MOVIE_LIST_BANNER_URL = '/danh-sach/phim-moi-cap-nhat';
const MOVIE_LIST_CATEGORY_URL = '/v1/api/the-loai/';

export const getMoveListBanner = (params) => get(MOVIE_LIST_BANNER_URL, params);
export const getMovieListCategory = (category, params) => get(MOVIE_LIST_CATEGORY_URL + category, params);