import React, { useState, useEffect } from "react";
import CustomVideoPlayer from "@components/user/CustomVideoPlayer";
import RatingDisplay from "@components/RatingDisplay";
import { faCircle, faHeart, faShare } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import TruncatedText from "@components/common/TruncatedText";
import FavoriteHeart from "@components/common/Button/FavoriteHeart";
import Slider from "@components/user/Slider/Slider";
import { useParams } from "react-router-dom";
import { getMovie, getMovieList } from "@services/movieService";

function MovieDetail() {
    const text = "Selenium là một tập hợp các công cụ kiểm thử tự động mã nguồn mở và hoàn toàn miễn phí, được thiết kế để hỗ trợ việc kiểm tra các ứng dụng web trên nhiều trình duyệt và hệ điều hành khác nhau . Với khả năng tương thích với nhiều ngôn ngữ lập trình như Java, C#, và Python, Selenium mang đến sự linh hoạt cho các nhà phát triển và kiểm thử viên trong việc lựa chọn công cụ phù hợp với kỹ năng và yêu cầu của dự án kiểm thử.";
    const movieSrc = "https://www.youtube.com/watch?v=kJ4jqq0AKZo";
    const movieSrc2 = "https://player.phimapi.com/player/?url=https://s3.phim1280.tv/20240524/znnHM21Z/index.m3u8";

    const [movie, setMovie] = useState([]);
    const [episodes, setEpisodes] = useState([]);
    const [episode, setEpisode] = useState([]);
    const [moviesSlider, setMoviesSlider] = useState([]);
    const [initialRating, setInitialRating] = useState(4.9);
    const [totalRating, setTotalRating] = useState(1000);
    const [isFavorite, setIsFavorite] = useState(false);
    const [time, setTime] = useState(0);

    const { slug, tap } = useParams();

    useEffect(() => {


        const fetchMovie = async () => {
            try {
                const ep = tap ? tap : 'tap-01';
                const res = await getMovie(slug);
                console.log("movie: ", res.data.movie);
                console.log("movie ep: ", res.data.episodes[0].server_data);
                setMovie(res.data.movie);
                setEpisodes(res.data.episodes[0].server_data);
                res.data.episodes[0].server_data.map((item) => {
                    if (item.slug === ep && res.data.movie.type === "series") {
                        console.log("episode link: ", item.link_m3u8);

                        setEpisode(item);
                    } else {
                        if (res.data.movie.type !== "series") {
                            setEpisode(res.data.episodes[0].server_data[0]);
                        }
                    }
                });
            } catch (error) {
                console.log("Loi lay movie : " + error);
            }
        };
        const fetchAnimeJapan = async () => {
            try {
                const res = await getMovieList("hoat-hinh", { page: 3, country: "nhat-ban" });
                console.log("Anime Japan: " + res.data.data.items);
                setMoviesSlider(res.data.data.items);

            } catch (error) {
                console.log("Error fetching movies: " + error);
            }
        }

        fetchMovie();
        fetchAnimeJapan();

    }, [slug, tap]);

    const handleRatingChange = (newRating) => {
        setInitialRating(newRating);
        setTotalRating(totalRating + 1);
    };

    const handleFavorite = () => {
        setIsFavorite(!isFavorite);
    };

    const getTimes = (time) => {
        setTime(time);
    }



    return (
        <div className="w-full border  mt-18" >
            {/* <VideoPlayer src={movieSrc} className="w-full h-full object-cover absolute top-0 left-0" /> */}
            {/* <VideoPlayer src="https://s3.phim1280.tv/20240524/znnHM21Z/index.m3u8" className="w-full h-full object-cover absolute top-0 left-0" /> */}

            <div className="w-full h-[610px] ">
                {episode && console.log("episode: ", episode.link_m3u8)}
                <CustomVideoPlayer src={`${episode.link_m3u8}`} height={"610px"} getTimes={getTimes} />
            </div>

            <div className="w-full mt-10 flex justify-center items-center gap-20 space-x-10 text-sm text-[#d2d2d2]">
                <div className="w-3/5 py-2  h-96 ">
                    <h2 className="text-2xl font-bold font-serif uppercase pb-1 text-white">{movie.name} -  <span className="font-medium font-sans">{episode.name}</span></h2>
                    <h3 className="text-lg font-light  mb-2">{movie.origin_name}</h3>
                    <RatingDisplay
                        rating={initialRating}
                        totalRating={totalRating}
                        onRatingChange={handleRatingChange}
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
                        <p>{movie?.country?.[0]?.name || "Không có thông tin quốc gia"}</p>
                        <FontAwesomeIcon icon={faCircle} className="text-white w-1 h-1" />
                        <p>{movie.episode_current}</p>
                    </div>

                    <div className="py-2">
                        <TruncatedText text={movie?.content || "Không có thông tin mô tả"} maxLine={4} />
                    </div>
                </div>
                <div className="w-2/5 h-96">
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
                                {movie?.actor?.map((actor, index) => (
                                    <span key={index} className="underline px-1">{actor}</span>
                                ))}
                            </span>
                        </div>
                        <div className="flex gap-1 w-full ">
                            <span className=" font-bold w-1/4 ">Đạo diễn: </span>
                            <span className="w-3/4">{movie.director}</span>
                        </div>
                        <div className="flex gap-1 w-full ">
                            <span className=" font-bold w-1/4 ">Thể loại: </span>
                            <span className="w-3/4 ">
                                {movie?.category?.map((category, index) => (
                                    <span key={index} className="underline px-1 cursor-pointer hover:font-bold hover:text-white">{category.name}</span>
                                ))
                                }
                            </span>
                        </div>
                        <div className="flex gap-1 w-full ">
                            <span className=" font-bold w-1/4 ">Danh mục: </span>
                            <span className="w-3/4">
                                Anime {'>'} Hành động</span>
                        </div>
                    </div>
                </div>
            </div>

            <br />
            <Slider title="Phim liên quan" data={moviesSlider} />

        </div >
    )
}

export default MovieDetail;
