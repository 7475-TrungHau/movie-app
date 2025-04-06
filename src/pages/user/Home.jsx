import { useEffect, useState } from "react";
import CommentSection from "@components/user/CommentSection";
import Banner from "@components/user/Banner";
import Slider from "@components/user/Slider/Slider";
import TopMovies from "@components/user/Slider/TopMovies";
import { getMovieListCategory, getMoveListBanner } from "@services/movieService";
import { getMovie, getMovies } from "@/services/apiService";


function Home() {
    const [ActionMovies, setActionMovies] = useState([]);
    const [BanerMovies, setBannerMovies] = useState([]);
    const [TopMovie, setTopMovie] = useState([]);



    useEffect(() => {
        document.title = "Trang chủ";
    }, []);
    useEffect(() => {
        const fetchBannerMovies = async () => {
            try {
                const res = await getMovies({ limit: 10, sort_by: "created_at", sort_dir: "desc" });
                console.log(res.data);
                setBannerMovies(res.data);
            } catch (error) {
                console.log("Loi lay movie: " + error);
            }
        }

        const fetchMovies = async () => {
            try {
                const res = await getMovies({ limit: 20, sort_by: "view", sort_dir: "desc", genre: "hanh-dong", category: "series" });
                setActionMovies(res.data);
            } catch (error) {
                console.log("Loi lay movie: " + error);
            }
        }

        const fetchTopMovie = async () => {
            try {
                const res = await getMovies({ limit: 10, sort_by: "view", sort_dir: "desc" });
                console.log("Loi", res.data);

                setTopMovie(res.data);
            } catch (error) {
                console.log("Loi lay Top movie: " + error);
            }
        }

        fetchMovies();
        fetchBannerMovies();
        fetchTopMovie();
    }, []);



    return (
        <div className="w-full">
            <Banner data={BanerMovies} />
            <div className="container my-10">
                <Slider
                    data={ActionMovies}
                    title="Phim hành động"
                    icons="true"
                    banner="FREE"
                    number={5}
                    type={2}
                />
                <br />
                <br />
                <TopMovies
                    data={TopMovie}
                    title="Top 10 phim được xem nhiều nhất"
                    icons="false"
                    banner="true"
                />
                <br />
                <br />
                <Slider
                    id="slider2"
                    data={ActionMovies}
                    title="Phim hành động"
                    icons="true"
                    banner="VIP"
                    number={6}
                    type={1}
                />
            </div>
        </div>

    );
};
export default Home;