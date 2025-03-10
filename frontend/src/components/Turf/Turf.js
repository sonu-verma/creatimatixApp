import TurfCard from "./TurfCard"
import { turfData } from "./../../utils/turfData"
const Turf = () => {
    return <>
        <div className="text-center font-bold p-10 text-3xl">
            <h1>Top Turf to Rent</h1>
        </div>
        <div className="flex space-x-4 overflow-x-scroll scrollbar-hide px-4 sm:px-6">
            {
                turfData.map( (turf, index) => <TurfCard key={turf.id} turf={turf} />)
            }
            
        </div>
    </>
}

export default Turf