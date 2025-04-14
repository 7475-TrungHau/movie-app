import React, { useState, useEffect, useMemo } from "react";
import CustomVideoPlayer from "@components/user/CustomVideoPlayer";
import RatingDisplay from "@components/RatingDisplay";
import { faCircle, faHeart, faShare, faEye } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import TruncatedText from "@components/common/TruncatedText";
import FavoriteHeart from "@components/common/Button/FavoriteHeart";
import Slider from "@components/user/Slider/Slider";
import { useParams, Link, useNavigate } from "react-router-dom";
import { getMovie, getMovies, postRateMovie, postFavoriteMovie } from "@/Services/apiService";
import { getUserProfileData } from "../../services/authService";
import { extractCountryFromGenres } from "../../utils/stringUtils";
import EpisodeSlider from "@components/user/Slider/EpisodeSlider";
import { useToast } from "../../context/ToastContext";

function PlayMovie() {
    const text = "Selenium là một tập hợp các công cụ kiểm thử tự động mã nguồn mở và hoàn toàn miễn phí, được thiết kế để hỗ trợ việc kiểm tra các ứng dụng web trên nhiều trình duyệt và hệ điều hành khác nhau . Với khả năng tương thích với nhiều ngôn ngữ lập trình như Java, C#, và Python, Selenium mang đến sự linh hoạt cho các nhà phát triển và kiểm thử viên trong việc lựa chọn công cụ phù hợp với kỹ năng và yêu cầu của dự án kiểm thử.";
    const movieSrc = "https://www.youtube.com/watch?v=kJ4jqq0AKZo";
    const movieSrc2 = "https://player.phimapi.com/player/?url=https://s3.phim1280.tv/20240524/znnHM21Z/index.m3u8";

    const [movie, setMovie] = useState([]);
    const [episodes, setEpisodes] = useState([]);
    const [episode, setEpisode] = useState([]);
    const [moviesSlider, setMoviesSlider] = useState([]);
    const [initialRating, setInitialRating] = useState(5);
    const [totalRating, setTotalRating] = useState(0);
    const [userRating, setUserRating] = useState(0);
    const [isFavorite, setIsFavorite] = useState(false);
    const [time, setTime] = useState(0);
    const [onLoad, setOnLoad] = useState(false);
    const { success, error: errorToast } = useToast();
    const navigate = useNavigate();

    const { slug, tap } = useParams();

    useEffect(() => {
        window.scrollTo(0, 0);
    }, [slug, tap]);


    useEffect(() => {
        const fetchMovie = async () => {
            try {
                const ep = tap ? tap : 'tap-01';
                console.log("Tap: ", tap);
                const user = localStorage.getItem("user");
                const res = await getMovie(slug, { id: user ? JSON.parse(user).id : "" });

                console.log("movie: ", res.data);
                console.log("movie ep: ", res.data.episodes);
                setMovie(res.data);
                setEpisodes(res.data.episodes);
                setInitialRating(res.data.rating);
                setTotalRating(res.data.ratings_count);

            } catch (error) {
                console.log("Loi lay movie : " + error);
                if (error.response && error.response.status === 403) {
                    console.log("Loi lay movie : " + error);
                    navigate("/error");
                }
            }
        };
        const fetchAnimeJapan = async () => {
            try {
                const res = await getMovies({ category: "movie" });
                console.log("Anime Japan: ", res.data);
                setMoviesSlider(res.data.data);

            } catch (error) {
                console.log("Error fetching movies: " + error);
            }
        }
        fetchMovie();
        fetchAnimeJapan();
    }, [slug]);

    useEffect(() => {
        const fetchUserRating = async () => {
            if (!movie?.id) return; // Ensure movie.id exists before fetching
            try {
                const res = await getUserProfileData(`ratings/${movie.id}`);
                console.log(res.data.message + "User rating: ", res.data);
                setUserRating(res.data.rating);
            } catch (error) {
                console.error("Loi lay rating: ", error);
            }
        };
        const fetchUserFavorite = async () => {
            if (!movie?.id) return;
            try {
                const res = await getUserProfileData(`favorites/${movie.id}`);
                console.log(res.data.message + "User favorite: ", res.data);
                setIsFavorite(res.data.is_favorite === 1 ? true : false);
            } catch (error) {
                console.error("Loi lay favorite: ", error);
            }
        }
        if (localStorage.getItem("token") && movie?.id) {
            fetchUserRating();
            fetchUserFavorite();
        }
    }, [movie?.id, localStorage.getItem("token")])

    useEffect(() => {
        if (tap) {
            const selectedEpisode = episodes.find((item) => item.slug === tap);
            setEpisode(selectedEpisode || episodes[0]);
        } else {
            setEpisode(episodes[0]);
        }
    }, [tap, movie, episodes]);

    const handleRatingChange = async (newRating) => {
        if (newRating === initialRating) {
            return;
        }
        console.log("newRating: ", newRating);

        try {
            if (localStorage.getItem("token")) {
                const res = await postRateMovie(movie.id, { rating: newRating });
                setInitialRating(res.data.rating);
                setTotalRating(res.data.total_rating);
                success(res.data.message, 10000);
            } else {
                errorToast("Bạn cần đăng nhập để đánh giá phim.", 3000);
            }
        } catch (error) {
            const errorMessage = error.response?.data?.message || "Đã xảy ra lỗi khi đánh giá phim.";
            errorToast(errorMessage, 3000);
            console.error("Error rating movie: ", error.response?.data || error.message);
        }

    };

    const handleFavorite = async () => {
        const user = JSON.parse(localStorage.getItem("user"));
        if (!user) {
            errorToast("Bạn cần đăng nhập để thêm phim vào danh sách yêu thích!", 3000);
            return;
        }
        try {
            const res = await postFavoriteMovie(movie.id);
            if (res.data.action === "add") {
                setIsFavorite(true);
                success("Thêm phim vào danh sách yêu thích thành công!")

            } else {
                setIsFavorite(false);
                success("Xóa phim khỏi danh sách yêu thích thành công!");

            }
        } catch (error) {
            console.error("Error adding to favorites: ", error);
        }
    };

    const getTimes = (time) => {
        setTime(time);
    }



    // Add 'name' field to each episode and create a new array
    const episodesWithName = episodes.map(ep => ({
        ...ep,
        name: ep.title
    }));

    return (
        <div className="w-full border  mt-18" >
            <div className="w-full h-[610px] ">

                <CustomVideoPlayer src={`${episode?.video_url}`} height={"610px"} getTimes={getTimes} />
            </div>

            <div className="w-full mt-10 flex justify-center items-center gap-20 space-x-10 text-sm text-[#d2d2d2]">
                <div className="w-3/5 py-2   ">
                    <h2 className="text-2xl font-bold font-serif uppercase pb-1 text-white">{movie.name} -  <span className="font-medium font-sans">{episode?.title}</span></h2>
                    <h3 className="text-lg font-light  mb-2">{movie.origin_name}</h3>
                    <RatingDisplay
                        rating={initialRating}
                        totalRating={totalRating}
                        onRatingChange={handleRatingChange}
                        value={userRating === 0 ? userRating : initialRating}
                    />
                    <div className="flex items-center gap-5 py-2 mt-1">
                        <p>{movie.year}</p>
                        <FontAwesomeIcon icon={faCircle} className="text-white w-1 h1" />
                        <p>
                            {time < 60
                                ? `${time} phút`
                                : `${Math.floor(time / 3600)} giờ ${Math.floor(time % 3600 / 60)} phút`
                            }
                        </p>
                        <FontAwesomeIcon icon={faCircle} className="text-white w-1 h-1" />
                        <p>{extractCountryFromGenres(movie.genres) || "Không có thông tin quốc gia"}</p>
                        <FontAwesomeIcon icon={faCircle} className="text-white w-1 h-1" />
                        <p>Tập {episode?.episode_number} /{movie.episodes_count}</p>
                        <FontAwesomeIcon icon={faCircle} className="text-white w-1 h-1" />
                        <p>
                            <FontAwesomeIcon icon={faEye} className="text-white w-5 h-4" /> {"   " + movie.view}
                        </p>
                    </div>

                    <div className="py-2">
                        <TruncatedText text={movie?.description || "Không có thông tin mô tả"} maxLine={4} />
                    </div>
                </div>
                <div className="w-2/5 ">
                    <div className="flex items-center gap-10 mb-5">
                        <div className="flex items-center gap-2">
                            <FavoriteHeart isFavorite={isFavorite} onFavoriteChange={handleFavorite} style="rounded-full bg-gray-500/50 hover:bg-gray-700/50 text-white font-bold p-2 cursor-pointer" />
                            <h3 className="text-md text-white font-bold">Theo dõi</h3>
                        </div>
                        <div className="flex items-center gap-2">
                            <div className="px-1.5 py-2 bg-gray-500/50 hover:bg-gray-700/50 rounded-full cursor-pointer">
                                <FontAwesomeIcon icon={faShare} className="text-white w-6 h-6" />
                            </div>
                            <h3 className="text-md text-white font-bold">Theo dõi</h3>
                        </div>

                    </div>
                    <div className="flex flex-col gap-2 font-extralight ">
                        <div className="flex gap-1 w-full ">
                            <span className=" font-bold w-1/4 ">Diễn viên: </span>
                            <span className="w-3/4 ">
                                {movie?.actor}
                            </span>
                        </div>
                        <div className="flex gap-1 w-full ">
                            <span className=" font-bold w-1/4 ">Đạo diễn: </span>
                            <span className="w-3/4">{movie.director}</span>
                        </div>
                        <div className="flex gap-1 w-full ">
                            <span className=" font-bold w-1/4 ">Thể loại: </span>
                            <span className="w-3/4 ">
                                {movie.genres}
                            </span>
                        </div>
                        <div className="flex gap-1 w-full ">
                            <span className=" font-bold w-1/4 ">Danh mục: </span>
                            <span className="w-3/4">
                                {movie?.category?.name ?? "Category"}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div className="container mx-auto my-10">

                {movie?.type === "series" ? (<EpisodeSlider
                    episodes={episodes}
                    number={5}
                    title="Các tập phim"
                    id="episode-list-slider"
                />) : (
                    <div className="text-white text-center text-2xl font-bold">Phim này không có tập phim nào</div>
                )}
            </div>

            <div className="w-full h-1 bg-gray-500/50 mt-5"></div>

            <br />
            {moviesSlider && (
                <div className="container mx-auto mt-10 mb-15">
                    <Slider data={moviesSlider} title={"Phim liên quan"} />
                </div>
            )}

        </div >
    )
}

export default PlayMovie;