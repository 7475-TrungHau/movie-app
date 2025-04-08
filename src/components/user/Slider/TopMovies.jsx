/*
    Lưu ý: 
    - Phải có id - Khi dùng slider TopMovies khi dùng từ >= 2 cái slider trong cùng 1 trang thì bắt buộc có id
    - icons = true: hiển thị icon, icons = false: không hiển thị icon
    - data: dữ liệu cần hiển thị
    - title: tiêu đề slider
    - banner: hiển thị banner hay không

*/

import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { faAngleLeft, faAngleRight, faPlayCircle } from "@fortawesome/free-solid-svg-icons";
import { IoSparkles } from "react-icons/io5";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { BASE_IMAGE_URL } from "../../../constants";
import { useToast } from "../../../context/ToastContext";


const TopMovies = ({ data, title, icons, banner, top, id }) => {

    const [currentSlide, setCurrentSlide] = useState(0);
    const { error } = useToast();
    const itemsPerSlide = 5;
    const packageNotBanner = "Basic"; // Gói không có banner
    const package1 = "VIP";
    const package2 = "PRO";
    const user = JSON.parse(localStorage.getItem('user')) ?? null;

    const nextSlide = () => {
        console.log(currentSlide);

        setCurrentSlide((prev) => prev === data.length - itemsPerSlide ? 0 : Math.min(prev + itemsPerSlide, data.length - itemsPerSlide));
    };

    const prevSlide = () => {
        const prevIndex = Math.max(currentSlide - itemsPerSlide, 0);
        setCurrentSlide((prev) => prev === 0 ? data.length - itemsPerSlide : prevIndex);
    };

    useEffect(() => {
        const slider = document.getElementById(`${id ? id : 'top-movies'}`);
        slider.style.transform = `translateX(-${(currentSlide * 100) / data.length}%)`;
    }, [currentSlide, data]);

    const handleClick = (item) => {
        if (!user) {
            if (!item.packages.some(pkg => pkg.name === packageNotBanner)) {
                error("Vui lòng đăng nhập để xem phim này!");
                return;
            }
            window.location.href = "/xem-phim/" + item.slug;
            return;
        }

        if (item.packages.some(pkg => pkg.name === packageNotBanner) || item.packages.length === 0) {
            window.location.href = "/xem-phim/" + item.slug;
            return;
        }

        const hasAccess = item.packages.some(pkg =>
            user.packages?.some(user_pkg => user_pkg.name === pkg.name)
        );

        if (hasAccess) {
            window.location.href = "/xem-phim/" + item.slug;
        } else {
            error("Vui lòng nâng cấp gói để xem phim này!");
        }
    };

    return (
        <div className="relative">
            <div className="flex items-center justify-between mb-4">
                <h2 className="text-2xl font-bold tracking-tight text-white">{title}</h2>
                <Link to="/all-movies">
                    <p className="text-blue-500 underline hover:text-blue-600">See all {'>'}{'>'}{'>'}</p>
                </Link>
            </div>
            <div className={`relative w-full  py-2 overflow-hidden  h-48`}>
                <div id={`${id ? id : 'top-movies'}`} className="absolute flex gap-4 transform transition-transform duration-1000 " style={{ width: `${data.length * (100 / itemsPerSlide)}%` }}>
                    {data.map((item, index) => (
                        <div
                            className="relative h-45  rounded-md overflow-hidden cursor-pointer transform transition-transform duration-300 hover:scale-105"
                            key={index}
                            style={{ width: `${100 / itemsPerSlide}%` }}
                            title={item.name}
                            onClick={() => handleClick(item)}
                        >
                            <div className="relative  h-5/6 bg-black">
                                {index < 9 ? (
                                    <div
                                        className="absolute -top-1/4 w-3/5 h-full  text-[10rem] left-5 font-bold text-gray-200/50 select-none"
                                    >
                                        {index + 1}
                                    </div>
                                ) : (
                                    <div
                                        className="absolute -top-1/4 w-2/5 h-full  text-[10rem] left-0 font-bold text-gray-200/50 select-none"
                                    >
                                        {index + 1}
                                    </div>
                                )}
                                <img
                                    className={`absolute top-0 right-0  h-full object-cover object-center ${index < 9 ? 'w-3/5' : 'w-2/5'}`}
                                    src={item.poster_url.startsWith('http') ? item.poster_url : BASE_IMAGE_URL + item.poster_url}
                                    alt={item.name}

                                />
                            </div>
                            {/* <Link to={item.packages[0].name === 'Basic' ? "/xem-phim/" + item.slug : localStorage.getItem('user') ? "/xem-phim/" + item.slug : "/login"}
                                onClick={() => {
                                    if (item.packages[0].name !== 'Basic' && !localStorage.getItem('user')) {
                                        error("Vui lòng đăng nhập để xem phim này!");
                                    }
                                }}>
                                <div className="relative  h-5/6 bg-black">
                                    {index < 9 ? (
                                        <div
                                            className="absolute -top-1/4 w-3/5 h-full  text-[10rem] left-5 font-bold text-gray-200/50 select-none"
                                        >
                                            {index + 1}
                                        </div>
                                    ) : (
                                        <div
                                            className="absolute -top-1/4 w-2/5 h-full  text-[10rem] left-0 font-bold text-gray-200/50 select-none"
                                        >
                                            {index + 1}
                                        </div>
                                    )}
                                    <img
                                        className={`absolute top-0 right-0  h-full object-cover object-center ${index < 9 ? 'w-3/5' : 'w-2/5'}`}
                                        src={item.poster_url.startsWith('http') ? item.poster_url : BASE_IMAGE_URL + item.poster_url}
                                        alt={item.name}

                                    />
                                </div>
                            </Link> */}
                            <div className="w-full h-1/6 p-1">
                                <p className="hover:text-orange-600 text-white hover:font-bold text-center line-clamp-2 overflow-ellipsis">{item.name}</p>
                            </div>
                            {!item.packages.some(pkg => pkg.name === packageNotBanner) && (
                                <>
                                    {item.packages.some(pkg => pkg.name === package1) && (
                                        <div className="absolute top-2 right-2 font-bold text-white bg-red-500 p-1 rounded-md">{package1}</div>
                                    )}
                                    {item.packages.some(pkg => pkg.name === package2) && (
                                        <div className="absolute top-2 left-2 font-bold rounded-md p-1 bg-white">
                                            <IoSparkles color="blue" className="text-2xl" />
                                        </div>
                                    )}
                                </>
                            )}


                        </div>
                    ))}

                </div>
                <div className="absolute top-1/2 left-0 transform -translate-y-1/2 h-20 w-10 bg-gradient-to-r from-black to-transparent flex items-center justify-center cursor-pointer opacity-50 hover:opacity-100" onClick={prevSlide}>
                    <FontAwesomeIcon icon={faAngleLeft} className="text-4xl text-white active:text-orange-500" />
                </div>
                <div className="absolute top-1/2 right-0 transform -translate-y-1/2 h-30 w-10 opacity-50 hover:opacity-100  bg-gradient-to-l from-black to-transparent flex items-center justify-center cursor-pointer" onClick={nextSlide}>
                    <FontAwesomeIcon icon={faAngleRight} className="text-4xl text-white active:text-orange-500" />
                </div>
            </div>
        </div>
    );
};

export default TopMovies;