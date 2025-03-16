import { useEffect, useState } from "react";
import CommentSection from "@components/user/CommentSection";
import Banner from "@components/user/Banner";
import Slider from "@components/user/Slider/Slider";
import TopMovies from "@components/user/Slider/TopMovies";
import { getMovieList } from "@services/movieService";


function Anime() {
    const [ActionMovies, setActionMovies] = useState([]);
    const [BanerMovies, setBannerMovies] = useState([]);
    const [TopAnime, setTopAnime] = useState([]);
    const [AnimeNhat, setAnimeNhat] = useState([]);
    //Hồi ức ko tên

    useEffect(() => {
        document.title = "Anime";
    }, []);
    useEffect(() => {
        const fetchMovies = async () => {
            try {
                const res = await getMovieList("hoat-hinh", { page: 2, limit: 15, category: "hanh-dong" });
                console.log("anime hanh dong: " + res.data.data.items);
                setActionMovies(res.data.data.items);
            } catch (error) {
                console.log("Loi lay movie: " + error);
            }
        }

        const fetchAnimeNhat = async () => {
            try {
                const res = await getMovieList("hoat-hinh", { page: 2, country: "nhat-ban" });
                console.log("anime Nhat: " + res.data.data.items);
                setAnimeNhat(res.data.data.items);
            } catch (error) {
                console.log("Loi lay movie: " + error);
            }
        }

        const fetchBannerMovies = async () => {
            try {
                const res = await getMovieList("hoat-hinh", { page: 1, limit: 10, sort_type: "asc", country: "nhat-ban" });
                console.log("Anime: " + res.data);
                setBannerMovies(res.data.data.items);
            } catch (error) {
                console.log("Loi lay movie: " + error);
            }
        }

        const fetchTopAnime = async () => {
            try {
                const res = await getMovieList("hoat-hinh", { page: 1, limit: 10, sort_type: "asc", year: 2024 });
                console.log("Anime: " + res.data);
                setTopAnime(res.data.data.items);
            } catch (error) {
                console.log("Loi lay movie: " + error);
            }
        }
        fetchTopAnime();
        fetchAnimeNhat();
        fetchMovies();
        fetchBannerMovies();
    }, []);



    return (
        <div className="w-full">
            <Banner
                data={BanerMovies}
                imgUrlBase="https://phimimg.com/"
            />
            <br />
            <br />
            <TopMovies
                data={TopAnime.slice(0, 10)}
                title="Top 10 anime năm 2024"
                icons="false"
            />
            <br />

            <div className="container my-10">
                <Slider
                    data={AnimeNhat}
                    title="Anime Nhật Bản"
                    icons="true"
                    banner="FREE"
                    number={5}
                    type={2}
                />


                <br />
                <br />
                <Slider
                    id="slider2"
                    data={ActionMovies}
                    title="Anime hành động"
                    icons="true"
                    banner="VIP"
                    number={6}
                    type={1}
                />
            </div>
        </div>

    );
};
export default Anime;