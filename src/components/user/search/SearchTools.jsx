import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import { BASE_IMAGE_URL } from '../../../constants/index';
import { Link } from 'react-router-dom';

function SearchTools() {
    const [query, setQuery] = useState(''); // Từ khóa tìm kiếm
    const [suggestions, setSuggestions] = useState([]); // Gợi ý tìm kiếm
    const [showSuggestions, setShowSuggestions] = useState(false); // Hiển thị dropdown gợi ý
    const [searchResults, setSearchResults] = useState([]); // Kết quả tìm kiếm full-text
    const [currentPage, setCurrentPage] = useState(1); // Trang hiện tại
    const [totalPages, setTotalPages] = useState(1); // Tổng số trang
    const [category, setCategory] = useState('All categories'); // Danh mục tìm kiếm
    const [showDropdown, setShowDropdown] = useState(false); // Hiển thị dropdown danh mục
    const [categories, setCategories] = useState([]);
    const suggestionsRef = useRef(null); // Ref để xử lý click ngoài dropdown gợi ý
    const [user, setUser] = useState(null);

    const package_name = user ? user.packages : [];
    const navigate = useNavigate();

    useEffect(() => {
        window.scrollTo(0, 0);
    }, []);

    // Xử lý click ngoài để đóng dropdown gợi ý
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (suggestionsRef.current && !suggestionsRef.current.contains(event.target)) {
                setShowSuggestions(false);
            }
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    useEffect(() => {
        const fetchCategories = async () => {
            try {
                const response = await axios.get('http://localhost:8000/api/categories');
                console.log(response.data.categories);

                setCategories(response.data.categories);
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        };
        fetchCategories();
    }, []);

    // Gọi API tìm kiếm thường khi nhập query
    useEffect(() => {
        if (query.length > 0) {
            const fetchSuggestions = async () => {
                try {
                    const response = await axios.get(`http://localhost:8000/api/search?keyword=${query}`);
                    setSuggestions(response.data.movies);
                    setShowSuggestions(true);
                } catch (error) {
                    console.error('Error fetching suggestions:', error);
                    setSuggestions([]);
                    setShowSuggestions(false);
                }
            };
            fetchSuggestions();
        } else {
            setSuggestions([]);
            setShowSuggestions(false);
        }
    }, [query]);

    // Gọi API full-text search khi submit
    const handleSearch = async (e) => {
        e.preventDefault();
        if (!query) return;


        setCurrentPage(1); // Reset về trang 1 khi tìm kiếm mới
        await fetchSearchResults(query, 1);
    };

    // Hàm gọi API full-text search
    const fetchSearchResults = async (searchQuery, page) => {
        try {
            const response = await axios.get(
                `http://localhost:8000/api/search/full-text?keyword=${searchQuery}&page=${page}`
            );
            console.log('Query:', response.data.data);
            setSearchResults(response.data.data);
            setTotalPages(response.data.last_page);
            setCurrentPage(page);
            setShowSuggestions(false); // Ẩn gợi ý sau khi tìm kiếm
        } catch (error) {
            console.error('Error fetching search results:', error);
            setSearchResults([]);
            setTotalPages(1);
        }
    };

    // const handleSearch = () => {
    //     fetchSearchResults(query, 1);
    //     setShowSuggestions(false);
    // }

    // Xử lý thay đổi trang
    const handlePageChange = async (page) => {
        if (page < 1 || page > totalPages) return;
        await fetchSearchResults(query, page);
    };

    // Xử lý chọn gợi ý
    const handleSuggestionClick = (suggestion) => {
        setQuery(suggestion);
        setShowSuggestions(false);
        fetchSearchResults(suggestion, 1);
    };

    return (
        <div className="min-h-screen w-full mt-10 bg-gray-900 text-white flex flex-col items-center py-12 px-4">
            {/* Thanh tìm kiếm */}
            <form className="max-w-lg mx-auto w-full m-10" onSubmit={handleSearch}>
                <div className="flex relative">
                    {/* Dropdown danh mục */}
                    <button
                        id="dropdown-button"
                        type="button"
                        onClick={() => setShowDropdown(!showDropdown)}
                        className="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-l-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600"
                    >
                        {category}
                        <svg
                            className="w-2.5 h-2.5 ml-2.5"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 10 6"
                        >
                            <path
                                stroke="currentColor"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                d="m1 1 4 4 4-4"
                            />
                        </svg>
                    </button>
                    <div
                        id="dropdown"
                        className={`z-10 ${showDropdown ? 'block' : 'hidden'} absolute top-12 left-0 bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700`}
                    >
                        <ul className="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                            <li>
                                <button
                                    type="button"
                                    onClick={() => {
                                        setCategory('All categories');
                                        setShowDropdown(false);
                                    }}
                                    className="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                >
                                    All categories
                                </button>
                            </li>
                            {categories.map((category) => (
                                <li key={category.id}>
                                    <button
                                        type="button"
                                        onClick={() => {
                                            setCategory(category.name);
                                            setShowDropdown(false);
                                        }}
                                        className="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                    >
                                        {category.name}
                                    </button>
                                </li>
                            ))}

                        </ul>
                    </div>

                    {/* Input tìm kiếm */}
                    <div className="relative w-full">
                        <input
                            type="search"
                            id="search-dropdown"
                            className="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-r-lg border-l-gray-50 border-l-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-l-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                            placeholder="Search Mockups, Logos, Design Templates..."
                            value={query}
                            onChange={(e) => setQuery(e.target.value)}
                            required
                        />
                        <button onClick={handleSearch}
                            type="submit"
                            className="absolute top-0 right-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-r-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            <svg
                                className="w-4 h-4"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    stroke="currentColor"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
                                />
                            </svg>
                            <span className="sr-only">Search</span>
                        </button>
                    </div>
                </div>

                {/* Dropdown gợi ý */}
                {showSuggestions && suggestions.length > 0 && (
                    <div
                        ref={suggestionsRef}
                        className="absolute z-20 mt-1 max-w-lg w-full bg-white rounded-lg shadow-sm dark:bg-gray-700 max-h-60 overflow-y-auto"
                    >
                        <ul className="py-2 text-sm text-gray-700 dark:text-gray-200">
                            {suggestions.map((suggestion, index) => (
                                <li key={index}>
                                    <button
                                        type="button"
                                        onClick={() => handleSuggestionClick(suggestion.name)}
                                        className="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                    >
                                        {suggestion.name}
                                    </button>
                                </li>
                            ))}
                        </ul>
                    </div>
                )}
            </form>

            {/* Kết quả tìm kiếm */}
            {searchResults.length > 0 && (
                <div className="mt-12 w-full ">
                    <h2 className="text-2xl font-bold mb-6">Kết Quả Tìm Kiếm</h2>
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        {searchResults.map((movie) => (
                            <div
                                key={movie.id}
                                className="bg-gray-800 rounded-lg shadow-lg p-4 flex flex-col items-center cursor-pointer hover:scale-105 transition-transform duration-200"
                                onClick={() => navigate(`/xem-phim/${movie.slug}`)}
                                title={movie.name}
                            >
                                <Link to={`/xem-phim/${movie.slug}`} className="w-full h-full flex flex-col items-center">
                                    <img
                                        src={movie.poster_url.startsWith('http') ? movie.poster_url : BASE_IMAGE_URL + movie.poster_url}
                                        alt={movie.name}
                                        className="w-full h-48 object-cover rounded-md mb-4"
                                    />
                                    <h3 className="text-lg font-semibold text-center">{movie.name}</h3>
                                    <p className="text-gray-400 text-sm mt-2">{movie.year}</p>
                                </Link>
                            </div>
                        ))}
                    </div>

                    {/* Phân trang */}
                    {totalPages > 1 && (
                        <div className="mt-8 flex justify-center space-x-2">
                            <button
                                onClick={() => handlePageChange(currentPage - 1)}
                                disabled={currentPage === 1}
                                className="px-4 py-2 bg-gray-700 text-white rounded disabled:opacity-50 hover:bg-gray-600"
                            >
                                Trước
                            </button>
                            <span className="px-4 py-2 text-gray-400">
                                Trang {currentPage} / {totalPages}
                            </span>
                            <button
                                onClick={() => handlePageChange(currentPage + 1)}
                                disabled={currentPage === totalPages}
                                className="px-4 py-2 bg-gray-700 text-white rounded disabled:opacity-50 hover:bg-gray-600"
                            >
                                Sau
                            </button>
                        </div>
                    )}
                </div>
            )}

            {/* Thông báo không có kết quả */}
            {searchResults.length === 0 && query && !showSuggestions && (
                <div className="mt-12 w-full max-w-4xl text-center">
                    <h2 className="text-2xl font-bold mb-6 text-gray-400">
                        Không tìm thấy kết quả cho "{query}"
                    </h2>
                </div>
            )}
        </div>
    );
}

export default SearchTools;