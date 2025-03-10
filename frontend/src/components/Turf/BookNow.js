import { useState } from "react";

const generateTimeSlot = () =>{
    let slots = [];
    for(let hour = 0; hour < 24; hour++){
        slots.push({ label: `${hour}:00 - ${hour}:30`, value: hour * 2 });
        slots.push({ label: `${hour}:30 - ${hour + 1}:00`, value: hour * 2 + 1 });
    }

    return slots;
}


const BookNow = ({turf, handleBookNow}) => {

    const [selectedDate, setSelectedDate] = useState("");
    const [selectedSlots, setSelectedSlots] = useState([]);
    const [lastSelectedSlot, setLastSelectedSlot] = useState(null);
    const now = new Date();
    const today = now.toISOString().split("T")[0];
    const slotTimes = generateTimeSlot();
    const currentHour = now.getHours();
    const currentMinute = now.getMinutes();
    const currentTimeValue = currentHour * 2 + (currentMinute >= 30 ? 1 : 0); 
   
    const bookedSlots = [];

    const handleSlotSelection = (slotValue) => {
       

        if (bookedSlots.includes(slotValue)){
            console.log("in");
            return;
        }
        if(selectedSlots.length == 0){
            setSelectedSlots([slotValue])
            return ;
        }

        const firstSelectedSlot = selectedSlots[0];
        const lastSelectedSlot = selectedSlots[selectedSlots.length - 1]; 

        if(slotValue === lastSelectedSlot + 1){
            setSelectedSlots([...selectedSlots, slotValue])
        }else{
            setSelectedSlots([slotValue])
        }
        setLastSelectedSlot(slotValue)
    }

    const handleSelectedDate = (selectedDateFromCalendar) => {
        setSelectedSlots([]);
        setSelectedDate(selectedDateFromCalendar);
    }

    const getPrice = () => {
        if(selectedSlots.length == 0){
            return 0
        }

        return selectedSlots.length / 2 * turf.ratePerHour
    }

    const getActiveSlotClass = (slot) => {
    
        if (bookedSlots.includes(slot.value)) {
            return 'bg-red-500 text-white cursor-not-allowed';  // Booked slots are red and not clickable
        } else if (selectedSlots.includes(slot.value)) {
            return 'bg-blue-700 text-white'; // Selected but not booked
        }
        return ''; // Default state
    };

    const getBookedlotClass = (slot) => {
        if (bookedSlots.includes(slot.value)) {
          return 'bg-red-700 text-white';
        }
        return '';
    };

    const payNow = () => {
        const apiData = [];
        if(selectedSlots.length > 0){
            selectedSlots.map((slot) => {
                let label = generateTimeSlot().find(slots => slots.value == slot)
                apiData.push(label)
            })
        }

        const turfData = {
            selectedDate: selectedDate,
            totalPrice: getPrice(),
            selectedSlots: apiData
        };

        console.log("slots: ", generateTimeSlot())
        console.log("turfData: ", turfData)
    }

    console.log(selectedSlots.length)
    console.log(selectedSlots)

     
    if(selectedSlots.length == 1 && selectedSlots.includes(lastSelectedSlot)){
        setSelectedSlots([])
        setLastSelectedSlot(null)
    }

    return <>
       <div className="bookingApp h-[40%]">
            <div className="flex justify-end">
                <span className="bg-white text-black rounded-full px-2 py-1 hover:bg-red-400" onClick={handleBookNow}>X</span>
            </div>
            <div className="flex justify-between mt-3">
                    <h2 className="text-center text-2xl">{turf?.name}</h2>
                    <p>Total Price: ${getPrice()}</p>
            </div>
            <div className="mt-4">
                <label className="text-white">Select Date: </label>
                <input 
                    className="text-black w-1/4 p-1.5 rounded-lg" 
                    min={today} 
                    value={selectedDate} 
                    type="date" 
                    onChange={(e) => handleSelectedDate(e.target.value)} 
                />
            </div>
            { selectedDate && <div className="grid grid-cols-8  p-2 text-black gap-2 my-6 text-center">
                {
                    
                    slotTimes.map((slot, index) => (
                        <div 
                            key={slot.value} 
                            className={` p-4 border rounded-lg cursor-pointer text-center 
                                ${getActiveSlotClass(slot)}
                                ${getBookedlotClass(slot)}
                                ${slot.value < currentTimeValue && selectedDate === today ? 'opacity-50 bg-gray-700 text-white cursor-not-allowed' : ''}
                                ${!selectedSlots.includes(slot.value) && !bookedSlots.includes(slot.value) && !(slot.value < currentTimeValue && selectedDate === today) ? 'bg-white hover:bg-gray-200' : ''}    
                            `}
                            onClick = { () => slot.value >= currentTimeValue || selectedDate != today ? handleSlotSelection(slot.value) : null }
                        >
                            { slot.label }
                        </div>
                    ))
                }
            </div> 
            }
            {
                getPrice() > 0 && (
                    <div className="flex content-end justify-end text-white mr-2">
                        <button className="border-2 border-white p-3 px-10 rounded-md hover:bg-primary-500" onClick={ () => payNow()}>Book Now</button>
                    </div>
                )
            }
       </div>
    </>
}

export default BookNow