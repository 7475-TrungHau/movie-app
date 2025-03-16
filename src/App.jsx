import React from "react";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import Home from "./pages/user/Home";
import Header from "./components/user/layout/Header";
import Footer from "./components/user/layout/Footer";
import "./App.css";

function App() {
  return (
    <Router>
      <Header />
      <div className="App w-full h-full flex items-center justify-center">
        <Routes>
          <Route path="/" element={<Home title="Home" />} />
          <Route path="/phim-le" element={<Home title="Home" />} />

        </Routes>
      </div>
      <Footer />
    </Router>


  );
}

export default App;