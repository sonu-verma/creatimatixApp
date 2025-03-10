import { useState } from "react";

const locations = [
  { id: 1, name: "Conference Room A", pricePerHour: 50 },
  { id: 2, name: "Meeting Room B", pricePerHour: 40 },
  { id: 3, name: "Event Hall C", pricePerHour: 80 },
];

const generateTimeSlots = () => {
  let slots = [];
  for (let hour = 0; hour < 24; hour++) {
    slots.push({ label: `${hour}:00 - ${hour}:30`, value: hour * 2 });
    slots.push({ label: `${hour}:30 - ${hour + 1}:00`, value: hour * 2 + 1 });
  }
  return slots;
};

const BookingApp = () => {
  const [selectedLocation, setSelectedLocation] = useState(null);
  const [selectedSlots, setSelectedSlots] = useState([]);
  const [selectedDate, setSelectedDate] = useState("");
  const timeSlots = generateTimeSlots();
  const now = new Date();
  const currentHour = now.getHours();
  const currentMinute = now.getMinutes();
  const currentTimeValue = currentHour * 2 + (currentMinute >= 30 ? 1 : 0);
  const today = now.toISOString().split("T")[0];

  console.log("currentHour:", currentHour);
  console.log("currentMinute:", currentMinute);
  console.log("currentTimeValue:", currentTimeValue);
  
  const handleSlotSelection = (slotIndex) => {
    if (selectedSlots.length === 0) {
      setSelectedSlots([slotIndex]);
      return;
    }

    const firstSelected = selectedSlots[0];
    const lastSelected = selectedSlots[selectedSlots.length - 1];
    
    if (slotIndex === lastSelected + 1) {
      setSelectedSlots([...selectedSlots, slotIndex]);
    } else {
      setSelectedSlots([slotIndex]);
    }
  };

  const getTotalPrice = () => {
    if (!selectedLocation) return 0;
    const location = locations.find((loc) => loc.id === Number(selectedLocation));
    return (selectedSlots.length / 2) * location.pricePerHour;
  };

  return (
    <div className="p-6">
    <div className="flex justify-between">
        <h1 className="text-2xl font-bold mb-4">Venue Booking App</h1>
        <div className="text-xl font-bold">Total Price: ${getTotalPrice()}</div>
    </div>
      <div className="mb-4">
        <label className="block mb-2">Select Date:</label>
        <input
          type="date"
          className="p-2 border rounded"
          value={selectedDate}
          min={today}
          onChange={(e) => setSelectedDate(e.target.value)}
        />
      </div>
      {selectedDate && (
        <>
          <div className="mb-4">
            <label className="block mb-2">Select Venue:</label>
            <select
              className="p-2 border rounded"
              onChange={(e) => setSelectedLocation(e.target.value)}
            >
              <option value="">Choose...</option>
              {locations.map((loc) => (
                <option key={loc.id} value={loc.id}>{loc.name} - ${loc.pricePerHour}/hour</option>
              ))}
            </select>
          </div>
          <div className="grid grid-cols-2 md:grid-cols-8 gap-4 mb-4">
            {timeSlots.map((slot) => (
              <div
                key={slot.value}
                className={`p-4 border rounded-lg cursor-pointer text-center ${selectedSlots.includes(slot.value) ? 'bg-green-300' : 'bg-white'} ${slot.value < currentTimeValue && selectedDate === today ? 'opacity-50 cursor-not-allowed' : ''}`}
                onClick={() => slot.value >= currentTimeValue || selectedDate !== today ? handleSlotSelection(slot.value) : null}
              >
                {slot.label}
              </div>
            ))}
          </div>
        </>
      )}
    </div>
  );
};

export default BookingApp;
