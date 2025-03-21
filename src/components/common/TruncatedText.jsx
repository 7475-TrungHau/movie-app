import { useState } from "react";

function TruncatedText({ text, maxLine }) {
    const [isExpanded, setIsExpanded] = useState(false);

    const toggleExpand = () => {
        setIsExpanded(!isExpanded);
    };

    return (
        <div>
            <p
                className={`${isExpanded ? "" : "line-clamp-" + maxLine} overflow-hidden text-ellipsis`}
            >
                {text}
            </p>
            {!isExpanded && text.length > 100 && (
                <button
                    className="  w-full text-center text-blue-500 hover:text-blue-700 cursor-pointer opacity-75"
                    onClick={toggleExpand}
                >
                    Xem thêm
                </button>
            )}
            {isExpanded && (
                <button
                    className="text-blue-500 w-full text-center  hover:text-blue-700 cursor-pointer opacity-75"
                    onClick={toggleExpand}
                >
                    Thu gọn
                </button>
            )}
        </div>
    )
}
export default TruncatedText;