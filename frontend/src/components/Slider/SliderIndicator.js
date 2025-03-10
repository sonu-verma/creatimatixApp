import React, { useContext } from 'react';
import SliderContext from '../../context/sliderContext';

function SliderIndicator(props) {
  const { activeIndex, handleChangeIndex } = useContext(SliderContext);

  const { indicatorNumber } = props;
  const indicatorNumberItems = Array(indicatorNumber).fill(0);

  return (
    <>
      <div className="container mx-auto">
        <div className="absolute bottom-0 flex px-4 py-1 space-x-2 transform -translate-x-1/2 -translate-y-1/2 top-2/2 left-1/2">
          {indicatorNumberItems.map((_, i) => {
            return (
              <>
                <button
                  key={i}
                  onClick={() => handleChangeIndex(i)}
                  className={`h-4 w-4 rounded-full transition duration-150 ease-in-out ${
                    activeIndex === i
                      ? 'focus:shadow-outline-blue bg-black focus:outline-none'
                      : 'bg-gray-300 hover:bg-gray-100'
                  }`}
                ></button>
              </>
            );
          })}
        </div>
      </div>
    </>
  );
}

export default SliderIndicator;
