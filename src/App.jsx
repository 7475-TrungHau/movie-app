import React from "react";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import { ToastProvider } from "./context/ToastContext";
import ToastContainer from "./components/common/Toast/ToastContainer";
import Home from "./pages/user/Home";
import Header from "./components/user/layout/Header";
import Footer from "./components/user/layout/Footer";
import Anime from "./pages/user/Anime";
import Movies from "./pages/user/Movies";
import MovieDetail from "./pages/user/MovieDetail";
import Register from "./pages/user/Register";
import Login from "./pages/user/Login";
import "./App.css";
import { useNavigate } from "react-router-dom";

function App() {
  return (
    <ToastProvider>
      <Router>
        <Header />
        <div className="App w-full h-full flex items-center justify-center">
          <Routes>
            <Route path="/" element={<Home title="Home" />} />
            <Route path="/movies" element={<Movies title="Home" />} />
            <Route path="/phim-le" element={<Home title="Home" />} />
            <Route path="/anime" element={<Anime title="Anime" />} />
            <Route path="/xem-phim/:slug/:tap?" element={<MovieDetail />} />
            <Route path="/register" element={<Register />} />
            <Route path="/login" element={<Login />} />
          </Routes>
        </div>
        <Footer />
        <ToastContainer />
      </Router>
    </ToastProvider>
  );
}

export default App;