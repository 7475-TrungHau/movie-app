

function FavoriteHeart({ isFavorite, onFavoriteChange, size, style }) {
    const sizeStyle = (size ? `w-${size} h-${size}` : 'w-6 h-6').toString();

    return (
        <button className={`${style ? style : 'bg-gray-500/50 hover:bg-gray-700/50 text-white font-bold py-2 px-4 rounded'}`} onClick={onFavoriteChange}>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className={`${sizeStyle}`}>
                <path strokeLinecap="round" strokeLinejoin="round" fill={isFavorite ? 'red' : ''} d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
        </button>
    )
}

export default FavoriteHeart;