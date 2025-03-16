import { useEffect, useState } from "react";
import CommentSection from "@components/user/CommentSection";
import Banner from "@components/user/Banner";
import Slider from "@components/user/Slider/Slider";
import TopMovies from "@components/user/Slider/TopMovies";
import { getMovieListCategory, getMoveListBanner } from "@services/movieService";


function Movies() {
    const [ActionMovies, setActionMovies] = useState([]);
    const [BanerMovies, setBannerMovies] = useState([]);



    useEffect(() => {
        document.title = "Phim Lẻ";
    }, []);
    useEffect(() => {
        const fetchMovies = async () => {
            try {
                const res = await getMovieListCategory("hanh-dong", { page: 2, limit: 15 });
                console.log(res.data.data.items);
                setActionMovies(res.data.data.items);
            } catch (error) {
                console.log("Loi lay movie: " + error);
            }
        }

        const fetchBannerMovies = async () => {
            try {
                const res = await getMoveListBanner({ page: 1 });
                setBannerMovies(res.data.items);
            } catch (error) {
                console.log("Loi lay movie: " + error);
            }
        }

        fetchMovies();
        fetchBannerMovies();
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
                    data={ActionMovies.slice(0, 10)}
                    title="Top 10 phim hành động"
                    icons="false"
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
export default Movies;