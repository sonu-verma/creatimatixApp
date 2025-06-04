import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import Logo from './../../public/assets/images/logo.png'
import { Link } from "react-router-dom"

const Navbar = () => {
  const navigate = useNavigate();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [isMenuActive, setIsMenuActive] = useState(false);
  const user = JSON.parse(localStorage.getItem('user'));

  useEffect(() => {
    setIsLoggedIn( localStorage.getItem('token')? true: false)
  },[navigate]);
  
  return (
    <nav className="bg-white shadow-md">
      <div className="px-4 sm:px-6 lg:px-8">
        <div className="flex items-center h-16">
            <div className="flex items-center">
                <div className="mr-10">
                    <a href="/" className="text-xl font-bold text-gray-800">
                    <img src={Logo} 
                    style={ {height:"50px", width: "160px"} } />
                    </a>
                </div>
                <div>
                    <select
                        className="block w-40 px-3 py-2 border-t-0 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                    >
                        <option>Select Area</option>
                        <option>New York</option>
                        <option>Los Angeles</option>
                        <option>Chicago</option>
                    </select>
                </div>
            </div>
         
          <div className="hidden md:flex space-x-4">
              <div className="relative ml-30" >
                  <input type="text" className="w-[529px] p-3 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow" placeholder="find your turf..." />
                  <button className="absolute right-0.5 top-0.5 rounded bg-slate-800 p-3 border border-transparent text-center text-sm text-white transition-all shadow-sm hover:shadow focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" type="button">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" className="w-4 h-4">
                      <path fillRule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clipRule="evenodd" />
                  </svg>
                  </button>
              </div>
          </div>
          <div className="flex absolute items-center right-16 space-x-4">
            {
              !isLoggedIn && 
              <Link to={'login'} className="block text-gray-800 hover:text-blue-500 text-sm font-medium invisible  md:visible">
                Login
            </Link>
            }
            
            <button className="relative bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-md focus:outline-none">
              Cart
              <span className="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-500 rounded-full">
                3
              </span>
            </button>
            {
              isLoggedIn && 
                ( 
                <div>
                  <div className="flex gap-2 border-2 border-black p-1 rounded-md" onClick={ () => setIsMenuActive(!isMenuActive)}>
                    { user && user.name} <span className="text-black-300 border-l-2 px-1 border-l-gray-500 hover:text-red-500">v</span>
                  </div>
                  {isMenuActive && 
                  <div className="absolute bg-gray-400 rounded-b-md w-[126px] z-10">
                      <Link to={'logout'} className="py-2 text-white text-left bg-black block text-gray-800 hover:bg-slate-500 text-sm font-medium">
                        Logout
                      </Link> 
                      <Link to={'logout'} className="py-2 text-white text-left bg-black block text-gray-800 hover:bg-slate-500 text-sm font-medium">
                        Manage Turf
                      </Link> 
                    </div>
                  }
                </div>

                )
              
            }
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="md:hidden bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-md focus:outline-none"
            >
              <svg
                className="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth="2"
                  d="M4 6h16M4 12h16m-7 6h7"
                ></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      {/* Mobile Menu */}
      {mobileMenuOpen && (
        <div className="md:hidden">
          <div className="px-4 py-3">
            <select
              className="block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
            >
              <option>Select City</option>
              <option>New York</option>
              <option>Los Angeles</option>
              <option>Chicago</option>
            </select>
          </div>
          <div className="px-4 py-3">
            <input
              type="text"
              className="block w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
              placeholder="Search..."
            />
          </div>
          <div className="px-4 py-3">
          {
              isLoggedIn ? <Link to={'/logout'} className="block text-gray-800 hover:text-blue-500 text-sm font-medium">
              Logout
            </Link> : 
            <Link to={'/login'} className="block text-gray-800 hover:text-blue-500 text-sm font-medium">
              Login
            </Link>
            }
          </div>
        
        </div>
      )}
    </nav>
  );
};

export default Navbar;
