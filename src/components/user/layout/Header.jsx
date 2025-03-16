import { useState } from 'react';
import logo from "@/assets/images/FPT-Play-Logo.jpg";
import { Link } from 'react-router-dom'; // Nếu bạn sử dụng React Router
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCaretDown, faSearch, faWallet } from '@fortawesome/free-solid-svg-icons';
import { useEffect } from 'react';
import { useLocation } from 'react-router-dom';

function Header() {
    const [openMenuNav, setOpenMenuNav] = useState(false);
    const [openMenu, setOpenMenu] = useState(false);
    const [isLogin, setIsLogin] = useState(false);
    const [pageCurrent, setPageCurrent] = useState("home");

    const location = useLocation();

    useEffect(() => {
        if (location.pathname === '/') {
            setPageCurrent('home');
        } else if (location.pathname === '/movies') {
            setPageCurrent('movies');
        } else if (location.pathname === '/anime') {
            setPageCurrent('anime');
        } else if (location.pathname === '/phim-le') {
            setPageCurrent('phim-le');
        } else if (location.pathname === '/phim-bo') {
            setPageCurrent('phim-bo');
        }
    }, [location]);

    const handleOpenMenuNav = () => {
        setOpenMenuNav(!openMenuNav);
    }

    document.addEventListener('click', (e) => {
        if (e.target.id !== 'openMenuNav') {
            setOpenMenuNav(false);
        }
        if (e.target.id !== 'openMenu' && e.target.id !== 'openMenu1') {
            setOpenMenu(false);
        }
    });

    const handleOpenMenu = () => {
        console.log(openMenu);
        setOpenMenu(!openMenu);
    }


    return (
        <header className="text-white bg-black py-4 shadow-md gap-4 fixed top-0 left-1/2 transform -translate-x-1/2 w-full z-50 ">
            <div className="container mx-auto flex flex-none items-center justify-between w-[80%]">
                <Link to="/" className="text-2xl font-bold">
                    <div className="flex items-center gap-2">
                        <img src={logo} alt="FPT Play Logo" className="w-8 h-8 rounded-lg" />
                        <span className="text-2xl font-bold">FPT Play</span>
                    </div>
                </Link>
                <nav className='flex-1'>
                    <ul className="flex space-x-4 px-8">
                        <li>
                            <Link to="/" className={`hover:text-orange-500 ${pageCurrent === 'home' ? 'font-bold ' : 'opacity-80'}`}>Trang chủ</Link>
                        </li>
                        <li>
                            <Link to="/movies" className={`hover:text-orange-500 ${pageCurrent === 'movies' ? 'font-bold ' : 'opacity-80'}`}>Phim</Link>
                        </li>
                        <li>
                            <Link to="/anime" className={`hover:text-orange-500 ${pageCurrent === 'anime' ? 'font-bold ' : 'opacity-80'}`}>Anime</Link>
                        </li>
                        <li className={`${pageCurrent === 'phim-le' || pageCurrent === 'phim-bo' ? 'block' : 'hidden'}`}>
                            <Link to={`/${pageCurrent === 'phim-le' ?? 'phim-bo'}`} className="hover:text-orange-500 font-bold">{pageCurrent === 'phim-le' ? 'Phim Lẻ' : 'Phim Bộ'}</Link>
                        </li>
                        <li className='relative'>
                            <p className="cursor-pointer opacity-80">
                                Xem thêm
                                <FontAwesomeIcon
                                    onClick={handleOpenMenuNav}
                                    icon={faCaretDown}
                                    className='hover:text-gray-300 px-1'
                                    id='openMenuNav'
                                />
                            </p>
                            {openMenuNav && (
                                <ul className='absolute top-full left-full no-underline w-[200%] transform -translate-x-1/2 bg-gray-800 text-white rounded-lg p-2'>
                                    <li>
                                        <Link to="/phim-le" className='hover:text-orange-500 cursor-pointer'>Phim lẻ</Link>
                                    </li>
                                    <li>
                                        <Link to="/phim-bo" className='hover:text-orange-500 cursor-pointer'>Phim bộ</Link>
                                    </li>
                                    <li>
                                        <Link to="/phim-chieu-rap" className='hover:text-orange-500 cursor-pointer'>Phim chiếu rạp</Link>
                                    </li>
                                </ul>
                            )}
                        </li>
                    </ul>
                </nav>
                <div className="flex items-center gap-3">
                    <Link to={`/search`} className="hover:text-orange-500 pt-1">
                        <FontAwesomeIcon icon={faSearch} className='hover:text-orange-500 text-xl cursor-pointer' />
                    </Link>
                    {!isLogin ? (
                        <div className="flex items-center gap-2">
                            <button type="button" className="text-white cursor-pointer bg-gradient-to-r from-orange-500 via-orange-700 to-orange-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 font-bold text-md rounded-lg px-3 py-2 text-center mx-2">
                                <FontAwesomeIcon icon={faWallet} className='mr-2' />
                                Mua Gói
                            </button>
                            <div className="relative flex items-center gap-1">
                                <div className="w-8 h-8 rounded-xl bg-gray-700" id='openMenu' onClick={handleOpenMenu}></div>
                                <FontAwesomeIcon
                                    id='openMenu1'
                                    onClick={handleOpenMenu}
                                    icon={faCaretDown}
                                    className='hover:text-orange-500 text-xl cursor-pointer'
                                />
                                {openMenu && (
                                    <ul className='absolute top-full right-0 w-[400%] bg-gray-800 text-white rounded-lg p-2 mt-2'>
                                        <li className='hover:text-orange-500 cursor-pointer'>Đăng nhập</li>
                                        <li className='hover:text-orange-500 cursor-pointer'>Đăng ký</li>
                                    </ul>
                                )}
                            </div>
                        </div>
                    ) : (
                        <button type="button" className="text-white cursor-pointer bg-gradient-to-r from-orange-500 via-orange-700 to-orange-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 font-bold text-md rounded-lg px-5 py-2 text-center">Login</button>
                    )}
                </div>
            </div>
        </header>
    );
}

export default Header;