import React from 'react'
import ReactDOM from 'react-dom/client'
import './../style.css'

import { BrowserRouter as Router, Routes, Route, Link } from 'react-router-dom'

import Navbar from './components/Navbar'
import { Slider } from './components/Slider/Slider'
import { SliderData } from './utils/sliderData'
import Turf from './components/Turf/Turf'
import Why from './components/Home/Why'
import Error from './components/Error'
import Home from './components/Home/Home'
import Shop from './components/Shop/Shop'
import TurfPage from './components/Turf/TurfPage'
import Layout from './components/Layout'
import BookingApp from './components/Book'
import Login from './components/Login/Login'
import ProtectedRoute from './components/ProtectedRoute'
import AdminDashboard from './components/Home/AdminDashbaord'
import ManagerDashboard from './components/Home/ManagerDashboard'
import Logout from './components/Logout'
const App = () => {

    return (
        <Router future={{ v7_startTransition: true, v7_relativeSplatPath: true }}>
            <Routes>
                <Route path='/' element={<Layout />}>
                    <Route index element={<Home />} />
                    <Route path="/login" element={<Login />} />
                    <Route path='/turf/:slug' element={<TurfPage />} />
                    <Route path='/shop' element={ <Shop />} />
                    <Route path='/book' element={ <BookingApp />} />

                    {/* Protected Route */}
                    <Route path='/admin' element={ <ProtectedRoute role="admin"><AdminDashboard /></ProtectedRoute> } />
                    <Route path='/manager' element={ <ProtectedRoute role="manager"><ManagerDashboard /></ProtectedRoute> } />
                    <Route path="/logout" element={ <Logout /> } />

                    <Route path='*' element={ <Error />} />
                </Route>
            </Routes>
        </Router>
    )

    // return <>
    //     <Navbar />
    //     {/* <Outlet /> */}
    //     <Slider {...SliderData} />
    //     <Turf />
    //     <Why />
    // </>
}


const root  = ReactDOM.createRoot(document.getElementById('root'))
root.render(<App />)