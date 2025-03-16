import React from "react";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import Home from "./pages/user/Home";
import Header from "./components/user/layout/Header";
import Footer from "./components/user/layout/Footer";
import Anime from "./pages/user/Anime";
import Movies from "./pages/user/Movies";
import MovieDetail from "./pages/user/MovieDetail";
import "./App.css";

function App() {
  return (
    <Router>
      <Header />
      <div className="App w-full h-full flex items-center justify-center">
        <Routes>
          <Route path="/" element={<Home title="Home" />} />
          <Route path="/movies" element={<Movies title="Home" />} />
          <Route path="/phim-le" element={<Home title="Home" />} />
          <Route path="/anime" element={<Anime title="Anime" />} />
          <Route path="/xem-phim/" element={<MovieDetail />} />

        </Routes>
      </div>
      <Footer />
    </Router>


  );
}

export default App;