import React, { useState, useRef } from 'react';
import ReactPlayer from 'react-player';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faRotateLeft, faRotateRight, faPlay, faPause } from '@fortawesome/free-solid-svg-icons';

const VideoPlayer = ({ src }) => {
    const playerRef = useRef(null);
    const [isPlaying, setIsPlaying] = useState(false);
    const [isHovering, setIsHovering] = useState(false);

    const handleSeekForward = () => {
        const currentTime = playerRef.current.getCurrentTime();
        playerRef.current.seekTo(currentTime + 10);
        setIsPlaying(true);
    };

    const handleSeekBackward = () => {
        const currentTime = playerRef.current.getCurrentTime();
        playerRef.current.seekTo(currentTime - 10);
        setIsPlaying(true);
    };

    //cach kiem tra video dang play hay pause
    // const handlePlayPause = () => {
    //     const player = playerRef.current.getInternalPlayer();
    //     if (player.paused) {
    //         player.play();
    //     } else {
    //         player.pause();
    //     }
    // };



    const handlePlayPause = () => {
        const player = playerRef.current.getInternalPlayer();
        player.paused ? player.play() : player.pause();
        setIsPlaying(!isPlaying);

    };

    const handleMouseEnter = () => {
        setIsHovering(true);
    };

    const handleMouseLeave = () => {
        setIsHovering(false);
    };

    if (!src) {
        return <div className="text-white">Không có nguồn video.</div>;
    }

    return (
        <div
            className="relative w-full mx-auto"
            style={{ height: `calc(100vh - 80px)` }}
            onMouseEnter={handleMouseEnter}
            onMouseLeave={handleMouseLeave}
        >
            {/* Video Player */}
            <ReactPlayer
                ref={playerRef}
                url={src}
                playing={isPlaying}
                controls={true}
                width="100%"
                height="100%"
                className="rounded-lg shadow-lg"
            />

            {isHovering && (
                <button
                    onClick={handleSeekBackward}
                    className="text-white absolute cursor-pointer rounded-full px-2 py-1 hover:opacity-50 hover:bg-slate-600 top-1/2 left-1/4"
                >
                    <FontAwesomeIcon icon={faRotateLeft} style={{ width: '40px', height: '40px' }} className=" " />
                </button>
            )}

            {/* Seek Forward */}
            {isHovering && (
                <button
                    onClick={handleSeekForward}
                    className='text-white cursor-pointer hover:opacity-50 hover:bg-slate-600 px-2 py-1 rounded-full absolute top-1/2 right-1/4 '
                >
                    <FontAwesomeIcon icon={faRotateRight} style={{ width: '40px', height: '40px' }} className="w-12 h-6" />
                </button>
            )}


        </div>
    );
};

export default VideoPlayer;