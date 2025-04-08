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
import PaymentResult from "./components/user/payment/PaymentResult";
import PlayMovie from "./pages/user/PlayMovie";
import SubscriptionPlans from "./pages/user/SubscriptionPlans";
import SearchTools from "./components/user/search/SearchTools";
import ProfilePage from "./pages/user/ProfilePage";

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
            {/* <Route path="/xem-phim/:slug/:tap?" element={<MovieDetail />} /> */}
            <Route path="/xem-phim/:slug/:tap?" element={<PlayMovie />} />
            <Route path="/register" element={<Register />} />
            <Route path="/login" element={<Login />} />
            <Route path="/subscription" element={<SubscriptionPlans />} />
            <Route path="/payment-success" element={<PaymentResult />} />
            <Route path="/payment-failed" element={<PaymentResult />} />
            <Route path="/search" element={<SearchTools />} />
            <Route path="/profile" element={<ProfilePage />} />
          </Routes>
        </div>
        <Footer />
        <ToastContainer />
      </Router>
    </ToastProvider>
  );
}

export default App;