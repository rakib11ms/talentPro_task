import logo from './logo.svg';
import './App.css';
import React, { useState, useEffect, Suspense, lazy } from "react";
import {
  Link,
  Navigate,
  useNavigate,
  Routes,
  Route,
  useLocation,
  useHistory,
  BrowserRouter,
} from "react-router-dom";
import axios from "axios";

import MasterDashboardLayout from './AdminPanel/MasterDashboardLayout';
import HomePage from './pages/HomePage';
import ViewCategory from './pages/Category/ViewCategory';
import ViewProduct from './pages/Product/ViewProduct';
function App() {
  return (
    <div className="App">
          <Routes>
          <Route path="/" element={<HomePage />} />
          <Route path="/view-category" element={<ViewCategory />} />
          <Route path="/view-product" element={<ViewProduct />} />
       
          </Routes>
      {/* <MasterDashboardLayout/> */}

      
      </div>
      

   
  );
}

export default App;
