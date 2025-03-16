import React, { useState } from "react";
import VideoPlayer from "@components/user/VideoPlayer";
import CustomVideoPlayer from "@components/user/CustomVideoPlayer";
import RatingDisplay from "@components/RatingDisplay";

function MovieDetail() {
    const movieSrc = "https://www.youtube.com/watch?v=kJ4jqq0AKZo";
    const movieSrc2 = "https://player.phimapi.com/player/?url=https://s3.phim1280.tv/20240524/znnHM21Z/index.m3u8";

    const [initialRating, setInitialRating] = useState(4.9);
    const [totalRating, setTotalRating] = useState(1000);

    const handleRatingChange = (newRating) => {
        setInitialRating(newRating);
        setTotalRating(totalRating + 1);
    };

    return (
        <div className="w-full border  mt-20" >
            {/* <VideoPlayer src={movieSrc} className="w-full h-full object-cover absolute top-0 left-0" /> */}
            {/* <VideoPlayer src="https://s3.phim1280.tv/20240524/znnHM21Z/index.m3u8" className="w-full h-full object-cover absolute top-0 left-0" /> */}

            <div className="w-full h-[620px] ">
                <CustomVideoPlayer src="https://s3.phim1280.tv/20240524/znnHM21Z/index.m3u8" />
            </div>

            <div className="w-full mt-20 flex justify-center items-center gap-20 space-x-10">
                <div className="w-3/5 border-white border-2 h-96 text-white">
                    <h2 className="text-2xl font-bold font-serif uppercase py-1 mb-2">Movie Detail</h2>
                    <RatingDisplay
                        rating={initialRating}
                        totalRating={totalRating}
                        onRatingChange={handleRatingChange}
                    />
                </div>
                <div className="w-2/5 border-white border-2 h-96"></div>
            </div>

        </div >
    )
}

export default MovieDetail;
