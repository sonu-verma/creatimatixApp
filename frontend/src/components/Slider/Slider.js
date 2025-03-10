import React, { useState } from 'react';
import SliderContext from './../../context/sliderContext'
import SliderContent from './SliderContent';
import SliderIndicator from './SliderIndicator';

export const Slider = (props) => {
  const [activeIndex, setActiveIndex] = useState(0);

  const indicatorNumber = Object.keys(props).length;

  function handleChangeIndex(key) {
    setActiveIndex(key);
  }

  function handleChangeIndexAuto() {
    setActiveIndex((prevIndex) => (prevIndex + 1) % indicatorNumber);
  }

  return (
    <>
      <SliderContext.Provider
        value={{
          activeIndex,
          handleChangeIndex,
          handleChangeIndexAuto,
          indicatorNumber,
        }}
      >
        <div
          className={
            'container relative mx-auto h-96 overflow-hidden p-3'
          }
        >
          <SliderContent {...props} />
          <SliderIndicator indicatorNumber={indicatorNumber} />
        </div>
      </SliderContext.Provider>
    </>
  );
};
