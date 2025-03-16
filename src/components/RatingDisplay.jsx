import { useState } from "react";
import { Rating } from "react-simple-star-rating";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar } from "@fortawesome/free-solid-svg-icons";

function RatingDisplay({ rating, totalRating, onRatingChange }) {
    const [currentRating, setCurrentRating] = useState((rating / 5) * 100 || 0);
    const [hoverRating, setHoverRating] = useState(0);

    // Catch Rating value
    const handleRatingChange = (rate) => {
        setCurrentRating(rate);
        if (onRatingChange) {
            onRatingChange(rate / 20);
        }
    };

    return (
        <div className="flex  items-start gap-2">
            <div className="flex gap-1 items-center bg-[#2c2c2c] rounded-md p-1 px-3 text-white font-bold">
                <FontAwesomeIcon icon={faStar} className="text-orange-600" />
                <span className="text-sm">{rating.toFixed(1)}</span>
                <span className="opacity-70 font-light text-sm">({totalRating})</span>
            </div>
            <div>
                <Rating
                    onClick={handleRatingChange}
                    initialValue={currentRating}
                    transition
                    size={20}
                    fillColor="orange"
                    emptyColor="gray"
                    allowFraction
                    SVGstyle={{ display: 'inline-block' }} // fix rating not inline
                />

            </div>
        </div>
    );
}

export default RatingDisplay;