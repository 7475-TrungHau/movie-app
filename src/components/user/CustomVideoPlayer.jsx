import React, { useState, useRef, useEffect } from 'react';
import ReactPlayer from 'react-player';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faVolumeMute, faVolumeUp, faVolumeDown, faGear } from '@fortawesome/free-solid-svg-icons';

const CustomVideoPlayer = ({ src, height, getTimes, onTimeUpdate, initialTime = 0 }) => {
    const playerRef = useRef(null);
    const [playing, setPlaying] = useState(false);
    const [played, setPlayed] = useState(0);
    const [seeking, setSeeking] = useState(false);
    const [volume, setVolume] = useState(0.8);
    const [muted, setMuted] = useState(false);
    const [playbackRate, setPlaybackRate] = useState(1.0);
    const [isHovering, setIsHovering] = useState(false);
    const [showSettings, setShowSettings] = useState(false);
    const [loaded, setLoaded] = useState(0);
    const [isFullscreen, setIsFullscreen] = useState(false);
    const [currentTime, setCurrentTime] = useState(0);
    const [duration, setDuration] = useState(0);
    const initialTimeApplied = useRef(false);

    // Effect to seek to initial time when video is loaded
    useEffect(() => {
        if (playerRef.current && initialTime > 0 && !initialTimeApplied.current && duration > 0) {
            playerRef.current.seekTo(initialTime);
            initialTimeApplied.current = true;
            console.log('Seeking to initial time:', initialTime);
        }
    }, [initialTime, duration, playerRef.current]);

    // Reset initialTimeApplied when src changes
    useEffect(() => {
        initialTimeApplied.current = false;
    }, [src]);

    const handleProgress = (state) => {
        if (!seeking) {
            setPlayed(state.played);

            // Update current time
            if (playerRef.current) {
                const currentTime = playerRef.current.getCurrentTime();
                setCurrentTime(currentTime);

                // Report time update to parent component
                if (onTimeUpdate) {
                    onTimeUpdate(currentTime, duration);
                }
            }
        }
        setLoaded(state.loaded);
    };

    const handlePlayPause = () => { setPlaying(!playing); };

    const handleSeekMouseDown = () => { setSeeking(true); };

    const handleSeekChange = (e) => { setPlayed(parseFloat(e.target.value)); };

    const handleSeekMouseUp = (e) => {
        setSeeking(false);
        playerRef.current.seekTo(parseFloat(e.target.value));
    };

    const handleVolumeChange = (e) => {
        setVolume(parseFloat(e.target.value));
        setMuted(parseFloat(e.target.value) === 0);
    };

    const handleMute = () => { setMuted(!muted); };

    const handlePlaybackRateChange = (rate) => {
        setPlaybackRate(rate);
        setShowSettings(false);
    };

    const toggleFullscreen = () => {
        if (playerRef.current) {
            if (!document.fullscreenElement) {
                playerRef.current.wrapper.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }
    };

    useEffect(() => {
        if (playerRef.current) {
            const videoDuration = playerRef.current.getDuration();
            setDuration(videoDuration);

            if (getTimes) {
                getTimes(videoDuration);
            }

            if (onTimeUpdate) {
                onTimeUpdate(currentTime, videoDuration);
            }
        }
    }, [playerRef.current, getTimes, onTimeUpdate]);

    useEffect(() => {
        const handleFullscreenChange = () => {
            setIsFullscreen(!!document.fullscreenElement);
        };
        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('mozfullscreenchange', handleFullscreenChange);
        document.addEventListener('MSFullscreenChange', handleFullscreenChange);
        return () => {
            document.removeEventListener('fullscreenchange', handleFullscreenChange);
            document.removeEventListener('webkitfullscreenchange', handleFullscreenChange);
            document.removeEventListener('mozfullscreenchange', handleFullscreenChange);
            document.removeEventListener('MSFullscreenChange', handleFullscreenChange);
        };
    }, []);

    const handleRewind = () => { playerRef.current.seekTo(playerRef.current.getCurrentTime() - 10); };

    const handleFastForward = () => { playerRef.current.seekTo(playerRef.current.getCurrentTime() + 10); };

    const formatTime = (seconds) => {
        if (isNaN(seconds)) { return '00:00'; }
        const date = new Date(seconds * 1000);
        const hh = date.getUTCHours();
        const mm = date.getUTCMinutes();
        const ss = String(date.getUTCSeconds()).padStart(2, '0');
        if (hh) { return `${hh}:${String(mm).padStart(2, '0')}:${ss}`; }
        return `${mm}:${ss}`;
    };

    const handleMouseEnter = () => { setIsHovering(true); };

    const handleMouseLeave = () => {
        setIsHovering(false);
        setShowSettings(false);
    };

    const handlePlayerClick = () => { setPlaying(!playing) }

    if (!src) { return <div>No video source</div>; }

    // Remove these duplicate variable declarations
    // const currentTime = playerRef.current ? playerRef.current.getCurrentTime() : 0;
    // const duration = playerRef.current ? playerRef.current.getDuration() : 0;

    return (
        <div
            className="relative w-full overflow-hidden border border-slate-900"
            style={{ height: isFullscreen ? '100vh' : '610px' }}
            onMouseEnter={handleMouseEnter}
            onMouseLeave={handleMouseLeave}

        >   <div onClick={handlePlayerClick} className={`w-[100%] h-[610px] object-cover`}>
                <ReactPlayer
                    ref={playerRef}
                    url={src}
                    playing={playing}
                    volume={muted ? 0 : volume}
                    playbackRate={playbackRate}
                    onProgress={handleProgress}
                    controls={isFullscreen}
                    width="100%"
                    height="100%"
                />
            </div>
            {!isFullscreen && (
                <div
                    className={`absolute bottom-0 left-0 right-0  bg-black bg-opacity-20  text-white p-2 transition-opacity duration-300 ${isHovering ? 'opacity-100' : 'opacity-0'}`}
                >
                    <div className="flex items-center justify-between">
                        <div className="flex items-center">
                            <button className="mx-1" onClick={handleRewind}>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M11.25 9l-3 3m0 0l3 3m-3-3h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                            <button className="mx-1" onClick={handlePlayPause}>
                                {playing ? (
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                ) : (
                                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.47v4.059a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                )}
                            </button>
                            <button className="mx-1" onClick={handleFastForward}>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M12.75 15l3-3m0 0l-3-3m3 3h-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                            <button onClick={handleMute} className="mx-1">
                                {muted || volume === 0 ? (
                                    <FontAwesomeIcon icon={faVolumeMute} className='h-6 w-6' />
                                ) : (
                                    <FontAwesomeIcon icon={faVolumeUp} className='h-6 w-6' />
                                )}
                            </button>
                            <input
                                type="range"
                                min={0}
                                max={1}
                                step="0.01"
                                value={muted ? 0 : volume}
                                onChange={handleVolumeChange}
                                className="w-20 mx-2"
                            />
                            <span className="mx-2">
                                {formatTime(currentTime)} / {formatTime(duration)}
                            </span>
                        </div>
                        <div className="flex items-center">
                            <div className="relative inline-block text-left">
                                <button onClick={() => setShowSettings(!showSettings)} className="mx-1">
                                    <FontAwesomeIcon icon={faGear} className='h-6 w-6' />
                                </button>
                                {showSettings && (
                                    <div className="absolute right-0 bottom-full mt-2 w-40 bg-gray-700 rounded-md shadow-lg z-10">
                                        <div className="py-1">
                                            <span className="block px-4 py-2 text-sm text-gray-300">Tốc độ phát</span>
                                            {[0.25, 0.5, 0.75, 1, 1.25, 1.5, 2].map((rate) => (
                                                <button
                                                    key={rate}
                                                    onClick={() => handlePlaybackRateChange(rate)}
                                                    className={`block w-full text-left px-4 py-2 text-sm ${playbackRate === rate ? 'bg-blue-500 text-white' : 'text-gray-300 hover:bg-gray-600'}`}
                                                >
                                                    {rate}x
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                            <button className="mx-1" onClick={toggleFullscreen}>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <input
                        type="range"
                        min={0}
                        max={1}
                        step="any"
                        value={played}
                        onMouseDown={handleSeekMouseDown}
                        onChange={handleSeekChange}
                        onMouseUp={handleSeekMouseUp}
                        className="w-full h-2 bg-gray-200 rounded-full appearance-none cursor-pointer"
                        style={{
                            background: `linear-gradient(to right, #e53935 ${played * 100}%, #808080 ${played * 100}%, #808080 ${loaded * 100}%, #ddd ${loaded * 100}%)`,
                        }}
                    />
                </div>
            )}
        </div>
    );
};

export default CustomVideoPlayer;