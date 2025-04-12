import { useState, useEffect } from "react";
import { Link, useParams } from "react-router-dom";
import { faAngleLeft, faAngleRight, faPlayCircle } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { BASE_IMAGE_URL } from "../../../constants";

const EpisodeSlider = ({ episodes, title, number, id }) => {
    const { slug: movieSlug, tap } = useParams(); // Lấy slug tập đang xem
    const [currentSlide, setCurrentSlide] = useState(0);
    const [hoveredEpisode, setHoveredEpisode] = useState(null); // Tập đang hover
    const itemsPerSlide = number ?? 5;

    // Đảm bảo episodes luôn là một mảng
    const validEpisodes = Array.isArray(episodes) ? episodes : [];
    const totalItems = validEpisodes.length;

    const nextSlide = () => {
        setCurrentSlide((prev) =>
            prev === totalItems - itemsPerSlide ? 0 : Math.min(prev + itemsPerSlide, totalItems - itemsPerSlide)
        );
    };

    const prevSlide = () => {
        setCurrentSlide((prev) =>
            prev === 0 ? totalItems - itemsPerSlide : Math.max(prev - itemsPerSlide, 0)
        );
    };

    useEffect(() => {
        const sliderElement = document.getElementById(`${id ? id : "episode-slider"}`);
        if (sliderElement) {
            sliderElement.style.transform = `translateX(-${(currentSlide * 100) / totalItems}%)`;
        }
    }, [currentSlide, totalItems, id]);

    if (validEpisodes.length === 0) {
        return <div className="text-white">Không có tập phim nào.</div>;
    }

   

    return (
        <div className="relative">
            <div className="flex items-center justify-between mb-4">
                <h2 className="text-2xl font-bold tracking-tight text-white">{title}</h2>
            </div>
            <div className="relative w-full py-2 overflow-hidden h-40">
                <div
                    id={`${id ? id : "episode-slider"}`}
                    className="absolute flex gap-4 transform transition-transform duration-1000 ease-in-out"
                    style={{ width: `${(totalItems * 100) / itemsPerSlide}%` }}
                >
                    {validEpisodes.map((episode, index) => {
                        const isActive = episode.slug === tap; // Kiểm tra tập đang xem

                        return (
                            <Link
                                to={`/xem-phim/${movieSlug}/${episode.slug}`}
                                key={episode.id || index}
                                className={`relative h-36 bg-gray-800 rounded-md overflow-hidden cursor-pointer transform transition-transform duration-300 hover:scale-105 shadow-md group ${isActive ? "border-2 border-red-500 scale-105" : ""
                                    }`}
                                style={{ width: `${100 / itemsPerSlide}%` }}
                                title={episode.title}
                                onMouseEnter={() => setHoveredEpisode(episode)} // Lưu tập đang hover
                                onMouseLeave={() => setHoveredEpisode(null)} // Xóa tập đang hover
                            >
                                {isActive && (
                                    <div className="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full z-10">
                                        Đang xem
                                    </div>
                                )}
                                <img
                                    className="w-full h-3/5 object-cover object-center"
                                    src={
                                        episode.thumbnail_url?.startsWith("http")
                                            ? episode.thumbnail_url
                                            : BASE_IMAGE_URL + episode.thumbnail_url
                                    }
                                    alt={episode.title}
                                    onError={(e) => {
                                        e.target.onerror = null;
                                        e.target.src = "/path/to/default/thumbnail.jpg";
                                    }}
                                />
                                <div className="w-full h-2/5 px-2 py-1 flex flex-col justify-center bg-gray-700">
                                    <p
                                        className={`text-sm font-semibold text-center line-clamp-2 ${isActive
                                            ? "text-red-400"
                                            : "text-white group-hover:text-orange-400"
                                            }`}
                                    >
                                        {episode.title || `Tập ${episode.episode_number}`}
                                    </p>
                                </div>
                                <div className="w-full h-full flex items-center hover:opacity-50 justify-center absolute top-0 left-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <FontAwesomeIcon
                                        icon={faPlayCircle}
                                        className="text-4xl text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                    />
                                </div>
                            </Link>
                        );
                    })}
                </div>
                {totalItems > itemsPerSlide && (
                    <>
                        <button
                            className="absolute top-1/2 left-0 transform -translate-y-1/2 h-full w-10 bg-gradient-to-r from-black via-black/80 to-transparent flex items-center justify-center cursor-pointer opacity-50 hover:opacity-100 z-10 disabled:opacity-20 disabled:cursor-not-allowed"
                            onClick={prevSlide}
                            disabled={currentSlide === 0}
                            aria-label="Previous episode slide"
                        >
                            <FontAwesomeIcon icon={faAngleLeft} className="text-3xl text-white" />
                        </button>
                        <button
                            className="absolute top-1/2 right-0 transform -translate-y-1/2 h-full w-10 bg-gradient-to-l from-black via-black/80 to-transparent flex items-center justify-center cursor-pointer opacity-50 hover:opacity-100 z-10 disabled:opacity-20 disabled:cursor-not-allowed"
                            onClick={nextSlide}
                            disabled={currentSlide >= totalItems - itemsPerSlide}
                            aria-label="Next episode slide"
                        >
                            <FontAwesomeIcon icon={faAngleRight} className="text-3xl text-white" />
                        </button>
                    </>
                )}
            </div>
            {/* Hiển thị description của tập đang hover */}
            {hoveredEpisode && (
                <div className="absolute -bottom-1/3 left-0 w-full bg-gray-900 bg-opacity-80 text-white p-4 rounded-md mt-2">
                    <h3 className="text-lg font-bold">{hoveredEpisode.title}</h3>
                    <p className="text-sm">{hoveredEpisode.description}</p>
                </div>
            )}
        </div>
    );
};

export default EpisodeSlider;