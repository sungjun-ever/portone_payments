import './bootstrap';
import '../css/app.css';
import React from 'react';
import { BrowserRouter, Routes, Route} from "react-router";
import { createRoot } from 'react-dom/client';
import Main from "./components/Main.jsx";

const root = createRoot(document.getElementById('app'));

root.render(
  <BrowserRouter>
    <Routes>
      <Route index element={<Main />} />
        
    </Routes>
  </BrowserRouter>
);
