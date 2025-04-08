import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { faCircle } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import FavoriteHeart from '@components/common/Button/FavoriteHeart';
import { extractCountryFromGenres } from '../../utils/stringUtils';
import { useToast } from '../../context/ToastContext';

const Banner = ({ data, imgUrlBase }) => {
    const [movies, setMovies] = useState([]);
    const [currentSlide, setCurrentSlide] = useState(0);
    const [favorite, setFavorite] = useState(false);
    const [isLogin, setIsLogin] = useState(true);
    const { error } = useToast();
    const packageNotBanner = "Basic"; // Gói không có banner
    const package1 = "VIP";
    const package2 = "PRO";
    const user = JSON.parse(localStorage.getItem('user')) ?? null;

    useEffect(() => {
        const checkLoginStatus = () => {
            const token = localStorage.getItem('token');
            console.log("token   ssss: ", token);

            if (token) {
                setIsLogin(true);
            } else {
                setIsLogin(false);
            }
        };
        checkLoginStatus();
    }, [localStorage.getItem('token')]);

    useEffect(() => {
        const fetchMovies = () => {
            setMovies(data);
        }
        fetchMovies();
    }, [data]);


    useEffect(() => {

        const intervalId = setInterval(() => {
            setCurrentSlide((prevSlide) => (prevSlide + 1) % movies.length);
        }, 5000);


        return () => clearInterval(intervalId);
    }, [currentSlide, movies.length]);
    if (movies.length === 0) {
        return (
            <div className="banner container w-full bg-slate-300 mt-20" style={{ height: `calc(100vh - 80px)` }}>
                <div className="container w-full h-full flex items-center justify-center text-white text-2xl">
                    Loading ...
                </div>
            </div>
        );
    }

    const handleSetFavorite = () => {
        setFavorite(!favorite);
    }
    const handleClick = (item) => {
        if (!item.packages.some(pkg => pkg.name === packageNotBanner) && user == null) {
            error("Vui lòng đăng nhập để xem phim này!");
            return;
        }
        if (item.packages.some(pkg => pkg.name === packageNotBanner) || item.packages.isEmpty) {
            window.location.href = "/xem-phim/" + item.slug;
        }
        if (!item.packages.some(pkg => pkg.name === packageNotBanner) && user != null) {
            if (item.packages.some(pkg => user.packages?.some(user_pkg => user_pkg.name === pkg.name))) {
                window.location.href = "/xem-phim/" + item.slug;
            }
            else {
                error("Vui lòng nâng cấp gói để xem phim này!");
            }
        }
    }

    return (
        <div className="banner relative w-full  mt-20" style={{ height: `calc(100vh - 80px)` }}>
            {/* Phần hình ảnh */}
            <div className="relative w-full h-full overflow-hidden">
                {movies.map((movie, index) => (
                    <div
                        key={movie._id}
                        className={`absolute w-full h-full transition-opacity duration-1000 ${index === currentSlide ? 'opacity-100' : 'opacity-0'}`}
                        style={{
                            backgroundImage: `url(${imgUrlBase ? imgUrlBase + movie.thumbnail_url : movie.thumbnail_url})`,
                            backgroundSize: 'cover',
                            backgroundPosition: 'center',
                            backgroundRepeat: 'no-repeat',
                        }}
                    >

                    </div>
                ))}
                {/* Phần nội dung  */}
                <div className={`absolute inset-0 bg-gradient-to-tr from-black to-transparent flex items-end  border border-red-500/50`}>
                    <div className="px-5 pb-8  text-white z-10 max-w-1/3 ">
                        <h2 className="text-4xl font-bold  mb-2">{movies[currentSlide].name}</h2>
                        <p className="text-sm mb-4 overflow-hidden text-ellipsis line-clamp-4">{movies[currentSlide].description ??
                            "Trí tuệ nhân tạo (AI) đang dần len lỏi vào mọi ngóc ngách của cuộc sống, mang đến những thay đổi to lớn và đầy hứa hẹn. Từ những chiếc điện thoại thông minh có khả năng nhận diện giọng nói, đến xe tự lái, hệ thống chẩn đoán bệnh, hay các trợ lý ảo, AI đang chứng minh tiềm năng vô hạn của mình. AI không chỉ đơn thuần là một công nghệ, mà còn là một lĩnh vực nghiên cứu đầy thách thức, nơi các nhà khoa học nỗ lực tái tạo khả năng học hỏi, suy luận và giải quyết vấn đề của con người."}</p>

                        <div className='py-2 flex items-center gap-2'>
                            <p>{movies[currentSlide].year}</p>
                            <FontAwesomeIcon icon={faCircle} className='w-1 h-1 px-2' />
                            <p>{movies[currentSlide].episodes_count + " " + "tập"}</p>
                            <FontAwesomeIcon icon={faCircle} className='w-1 h-1 px-2' />
                            <p>{extractCountryFromGenres(movies[currentSlide].genres ?? "Quốc gia")}</p>
                            <FontAwesomeIcon icon={faCircle} className='w-1 h-1 px-2' />
                            <p>{movies[currentSlide].type === "series" ? "Series" : "Movie"} </p>
                        </div>

                        <div className='flex gap-2'>
                            <button className="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                onClick={() => {
                                    handleClick(movies[currentSlide])
                                }}
                            >
                                Xem ngay
                            </button>
                            {/* <Link to={movies[currentSlide].packages[0].name === "Basic" ? `/xem-phim/${movies[currentSlide].slug}` : localStorage.getItem('user') ? `/xem-phim/${movies[currentSlide].slug}` : `/login`} className="flex items-center gap-2">

                            </Link> */}
                            <button className="bg-gray-500/50 hover:bg-gray-700/50 text-white font-bold py-2 px-4 rounded flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-4 h-4">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>

                                <span>Chi tiết</span>
                            </button>
                            {isLogin && (
                                <FavoriteHeart isFavorite={favorite} onFavoriteChange={handleSetFavorite} />
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {/* Các chấm indicator để theo dõi slide */}
            <div className="absolute w-full px-2 bottom-4 left-1/2 transform -translate-x-1/2 flex items-center justify-end space-x-2 z-20 ">
                {movies.map((_, index) => (
                    <button
                        key={index}
                        className={`w-3 h-3 rounded-full cursor-pointer ${index === currentSlide ? 'bg-white w-4 h-4' : 'bg-gray-500'}`}
                        onClick={() => setCurrentSlide(index)}
                        aria-label={`Go to slide ${index + 1}`}
                    ></button>
                ))}
            </div>
        </div>
    );
};

export default Banner;