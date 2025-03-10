import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom'
import parkingImg from '../../../public/assets/images/parking.png'
import locationImg from '../../../public/assets/images/location.png'
import { turfData } from "./../../utils/turfData"

import { sportIcons } from './../../utils/constance';
import BookingApp from '../Book';
import BookNow from './BookNow';

const TurfPage = () => {

    const [markAsFavorite, setMarkAsFavorite] = useState(true)
    const [turf, setTurf] = useState(null)
    const [activeTab, setActiveTab] = useState(0)
    const [showBookNow, setShowBookNow] = useState(false);
    const {slug} = useParams()
    const [activeImage, setActiveImage] = useState(
        "https://images.unsplash.com/photo-1499696010180-025ef6e1a8f9?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80",
    );

    
    useEffect(() => {
        const turf = turfData.find( turf => turf.slug == slug)
        setTurf(turf)
    },[slug])

    const turfImage = [
        {
          imgelink:
            "https://images.unsplash.com/photo-1499696010180-025ef6e1a8f9?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80",
        },
        {
          imgelink:
            "https://images.unsplash.com/photo-1432462770865-65b70566d673?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80",
        },
        {
          imgelink:
            "https://images.unsplash.com/photo-1493246507139-91e8fad9978e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2940&q=80",
        },
        {
          imgelink:
            "https://images.unsplash.com/photo-1518623489648-a173ef7824f3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2762&q=80",
        },
        {
          imgelink:
            "https://images.unsplash.com/photo-1682407186023-12c70a4a35e0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2832&q=80",
        },
      ];
     
    const handleBookNow = () => {
        setShowBookNow(!showBookNow)
    }
    
    return <>  { 
        turf && 
        <div>
            <div className="flex mt-5 mx-10">
                <div className="w-8/12">
                    <div className="grid gap-4">
                        <div>
                            <img
                            className=" w-full max-w-full rounded-lg h-96 object-cover"
                            src={activeImage}
                            alt=""
                            />
                        </div>
                        <div className="grid grid-cols-5 gap-4 ml-5">
                            {turfImage.map(({ imgelink }, index) => (
                            <div key={index}>
                                <img
                                onClick={() => setActiveImage(imgelink)}
                                src={imgelink}
                                className="h-20 max-w-full cursor-pointer rounded-lg object-cover object-center"
                                alt="gallery-image"
                                />
                            </div>
                            ))}
                        </div>
                    </div>
                    <div className='border-2 border-gray-300 rounded-lg my-10'>
                        <h2 className='font-bold p-2 text-2xl pb-10'>
                            Included with this Turf
                        </h2>

                        <div className='flex justify-evenly p-4'>
                            <p>
                                <img src={parkingImg} className='h-16' />
                            </p>
                            <p>
                                <img src="https://hudle.in/icons/Football.svg" />
                            </p>
                            <p>
                                <img src="https://hudle.in/icons/Box%20Cricket.svg" />
                                
                            </p>
                        </div>
                    </div>
                    <div className='border-2 border-gray-100 block'>
                        <ul className="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                            {
                                Object.entries(turf.sports).map(([sport, details]) =>
                                    (
                                        <li className="me-2" key={sport} onClick={ () => {
                                            setActiveTab(sport)
                                        } }>
                                            <a className={`inline-flex items-center justify-center p-4 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group ${activeTab === sport ? 'border-b-2 border-gray-500' : 'border-b-2 border-transparent'}`}>
                                            <img src={sportIcons[sport]} className='h-8 w-7 mr-3' />{ sport }
                                            </a>
                                        </li>
                                    )
                                )
                            }
                        </ul>
                        {
                            ( activeTab != 0 &&  
                                <div className='p-4 leading-10'>
                                    <h3 className="text-lg font-semibold py-2">{activeTab} Details</h3>
                                    <p><strong>Rate Per Hour:</strong> ${turf?.sports[activeTab].ratePerHour}</p>
                                    <p><strong>Dimensions:</strong> {turf?.sports[activeTab].dimensions}</p>
                                    <p><strong>Capacity:</strong> {turf?.sports[activeTab].capacity} people</p>
                                    <p><strong>Rules:</strong></p>
                                    <ul className="list-disc pl-5">
                                        {turf?.sports[activeTab].rules.map((rule, index) => (
                                            <li key={index}>{rule}</li>
                                        ))}
                                    </ul>    
                                </div>
                            )    
                        }
                            
                    </div>
                    {
                        turf?.rules ? (
                            <div>
                                <h1 className='font-bold text-2xl my-4'>Rules</h1>
                                <ul className='list-disc mx-10'>
                                {
                                    turf?.rules.map((rule, index) => <li key={index}>{rule}</li>)
                                }
                                </ul>
                            </div>

                        ) : ""
                    }
                </div>
                {/* Right Sidebar - Sticky */}
                <div className="w-4/12 border-2 rounded-sm border-gray-500 sticky top-2 h-fit ml-8">
                    <div className="bg-blue-100">
                        <p className="text-sm p-2">On Rent</p>
                        <div className="flex justify-between items-center">
                            <p className="p-2 font-bold text-2xl">{turf?.name}</p>
                            <div className="mx-2">
                                {markAsFavorite ? (
                                    <button onClick={() => setMarkAsFavorite(!markAsFavorite)}>
                                        <svg className="text-green-400 w-6 h-auto fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path d="M244 84L255.1 96L267.1 84.02C300.6 51.37 347 36.51 392.6 44.1C461.5 55.58 512 115.2 512 185.1V190.9C512 232.4 494.8 272.1 464.4 300.4L283.7 469.1C276.2 476.1 266.3 480 256 480C245.7 480 235.8 476.1 228.3 469.1L47.59 300.4C17.23 272.1 0 232.4 0 190.9V185.1C0 115.2 50.52 55.58 119.4 44.1C164.1 36.51 211.4 51.37 244 84C243.1 84 244 84.01 244 84L244 84zM255.1 163.9L210.1 117.1C188.4 96.28 157.6 86.4 127.3 91.44C81.55 99.07 48 138.7 48 185.1V190.9C48 219.1 59.71 246.1 80.34 265.3L256 429.3L431.7 265.3C452.3 246.1 464 219.1 464 190.9V185.1C464 138.7 430.4 99.07 384.7 91.44C354.4 86.4 323.6 96.28 301.9 117.1L255.1 163.9z"/>
                                        </svg>
                                    </button>
                                ) : (
                                    <button onClick={() => setMarkAsFavorite(!markAsFavorite)}>
                                        <svg className="text-green-400 w-6 h-auto fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path d="M0 190.9V185.1C0 115.2 50.52 55.58 119.4 44.1C164.1 36.51 211.4 51.37 244 84.02L256 96L267.1 84.02C300.6 51.37 347 36.51 392.6 44.1C461.5 55.58 512 115.2 512 185.1V190.9C512 232.4 494.8 272.1 464.4 300.4L283.7 469.1C276.2 476.1 266.3 480 256 480C245.7 480 235.8 476.1 228.3 469.1L47.59 300.4C17.23 272.1 .0003 232.4 .0003 190.9L0 190.9z"/>
                                        </svg>
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>
                    <div className="py-4 px-2">
                        <div className="flex">
                            <img src="https://images.sharepal.in/misc/hard-coded/money-icon.svg" className="h-5 w-10" />
                            <p>Pay on ground available</p>
                        </div>
                        <div className="flex mt-5">
                            <img src={locationImg} className="h-5 w-5 ml-2 mr-3" />
                            <p>{turf?.location}</p>
                        </div>
                    </div>
                    <div className="border-t-2 py-4 flex justify-between">
                        <div className="py-2 px-4 flex">
                            <p className="font-bold mr-2">Total Amount: </p>
                            <p className="justify-center align-middle">₹1250</p>
                        </div>
                        <div className="justify-center align-middle">
                            <button className="border-2 border-black p-2 rounded-lg mr-2 hover:bg-green-400 hover:border-green-400 hover:text-white " onClick={handleBookNow} >Check Availability</button>
                        </div>
                    </div>
                </div>
            </div> 
            { showBookNow && <div className="fixed top-10 items-center text-white bg-green-400 rounded-sm p-5 w-3/4 mx-auto ml-[11%]">
                <BookNow turf={turf} handleBookNow={handleBookNow} />
            </div>
            }
            
    
        </div>
        }
    </>
}

export default TurfPage