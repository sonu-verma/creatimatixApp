import { sliderData } from "./../../utils/sliderData"
import { Slider } from "../Slider/Slider"
import Turf from "../Turf/Turf"
import Why from "./Why"

const Home  = ({text}) => {
    return <>
        <h2>{text}</h2>
        <Slider {...sliderData} />
        <Turf />
        <Why />
    </>
}

export default Home