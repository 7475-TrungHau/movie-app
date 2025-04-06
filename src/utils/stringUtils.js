/**
 * Lấy quốc gia hoặc khu vực từ chuỗi genres
 * @param {string} genresStr
 * @returns {string}
 */
export function extractCountryFromGenres(genresStr) {
    if (!genresStr) return '';
    return genresStr.split(',').pop().trim();
}

