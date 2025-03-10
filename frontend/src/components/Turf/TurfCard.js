import { assetPath } from "../../utils/constance"

import image1 from '../../../public/assets/images/1.jpg'
import { Link } from "react-router-dom"
const TurfCard = ({turf}) => {
    console.log("turf :", turf)
    return <>
        <Link to={`turf/`+turf.slug}>
        <div className="bg-green-200 w-80 rounded-md mb-3 p-3 flex-shrink-0 transition-transform duration-500 ease-in-out hover:scale-105">
            <div className="relative h-48 w-full object-cover rounded-t-md overflow-hidden">
                <img src={image1} alt="Turf" className="h-full w-full object-cover" />
            </div>
            <div className="leading-[55PX] text-base mt-2">
                <h2 className="text-lg font-semibold">{turf.name}</h2>
                <p>Ratings: {turf.rating}</p>
                <p>Rent at ${turf.ratePerHour} for 1 hour</p>
            </div>
        </div>
        </Link>
    </>
}

export default TurfCard