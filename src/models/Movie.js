/**
 * Model Movie để chuẩn hóa dữ liệu phim từ các API khác nhau
 */
export class Movie {
    constructor(data, source = 'default') {
        // Áp dụng mapping tương ứng với nguồn dữ liệu
        const mapped = this.mapFromSource(data, source);

        // Khởi tạo các thuộc tính cơ bản
        this.id = mapped.id || '';
        this.name = mapped.name || '';
        this.origin_name = mapped.origin_name || '';
        this.slug = mapped.slug || '';
        this.thumb_url = mapped.thumb_url || '';
        this.poster_url = mapped.poster_url || '';
        this.overview = mapped.overview || '';
        this.content = mapped.content || '';
        this.type = mapped.type || ''; // 'series' hoặc 'single'
        this.year = mapped.year || '';
        this.country = mapped.country || [];
        this.category = mapped.category || [];
        this.actor = mapped.actor || [];
        this.director = mapped.director || '';
        this.episode_current = mapped.episode_current || '';
        this.episodes = mapped.episodes || [];
        this.rating = mapped.rating || 0;
        this.total_rating = mapped.total_rating || 0;
    }

    /**
     * Ánh xạ dữ liệu từ nguồn khác nhau sang cấu trúc thống nhất
     */
    mapFromSource(data, source) {
        switch (source) {
            case 'phimapi':
                return {
                    id: data._id || data.id,
                    name: data.name,
                    origin_name: data.origin_name,
                    slug: data.slug,
                    thumb_url: data.thumb_url,
                    poster_url: data.poster_url,
                    overview: data.overview,
                    content: data.content,
                    type: data.type,
                    year: data.year,
                    country: data.country,
                    category: data.category,
                    actor: Array.isArray(data.actor) ? data.actor : [],
                    director: Array.isArray(data.director) ? data.director.join(', ') : data.director,
                    episode_current: data.episode_current,
                    episodes: data.episodes || []
                };

            case 'server':
                return {
                    id: data.id,
                    name: data.title,
                    origin_name: data.original_title || '',
                    slug: data.slug,
                    thumb_url: data.thumbnail,
                    poster_url: data.poster,
                    overview: data.description,
                    content: data.description,
                    type: data.is_series ? 'series' : 'single',
                    year: data.release_year?.toString(),
                    country: data.countries || [],
                    category: data.genres || [],
                    actor: data.actors || [],
                    director: data.directors || '',
                    episode_current: data.current_episode || '',
                    episodes: data.episodes || [],
                    rating: data.rating || 0,
                    total_rating: data.total_rating || 0
                };

            default:
                // Trả về dữ liệu gốc nếu không có mapping
                return data;
        }
    }

    /**
     * Chuyển đổi model thành dữ liệu chuẩn để hiển thị
     */
    toDisplayData() {
        return {
            id: this.id,
            name: this.name,
            origin_name: this.origin_name,
            slug: this.slug,
            thumb_url: this.thumb_url,
            poster_url: this.poster_url,
            overview: this.overview,
            content: this.content,
            type: this.type,
            year: this.year,
            country: this.country,
            category: this.category,
            actor: this.actor,
            director: this.director,
            episode_current: this.episode_current,
            episodes: this.episodes
        };
    }
}