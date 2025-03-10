import React, { useContext, useEffect } from 'react';
import sliderClassColor from '../../utils/sliderClassColor';
import SliderContext from '../../context/sliderContext';
import SliderContentImage from './SliderContentImage';
import SliderContentText from './SliderContentText';

const  SliderContent = (props) => {
  const { activeIndex, handleChangeIndexAuto } = useContext(SliderContext);

  useEffect(() => {
    const intervalId = setInterval(() => {
      handleChangeIndexAuto();
    }, 3000);
    return () => clearInterval(intervalId);
  }, [handleChangeIndexAuto]);

  return (
    <>
      {
        Object.entries(props).map(([key, value]) => {
          return (
            <>
              <div
                key={value?value.id:key}
                className={`absolute inset-0  transition duration-500 ease-in-out ${
                  activeIndex === parseInt(key) ? 'opacity-100' : 'opacity-0'
                }`}
              >
                {/* <a href={value.buttonLink}> */}
                  <div
                    className={`${sliderClassColor(value.color, 'slider') +
                      'grid min-h-[384px] gap-4 p-6 text-black'} ${value.image ? " md:grid-cols-2": ""}`}
                  >
                    {/* only text */}
                    {!value.image && <SliderContentText align="center" {...value} />}
                    {/* text left / image right */}
                    {value.image?.headlineType === 'left' && (
                      <>
                        {/* TODO add min height */}
                        <SliderContentImage align={value.image?.headlineType} {...value.image} />
                        <SliderContentText align={value.image?.headlineType} {...value} />
                      </>
                    )}
                    {/* image left / text right  */}
                    {value.image?.headlineType === 'right' && (
                      <>
                        <SliderContentText align={value.image?.headlineType} {...value} />
                        <SliderContentImage align={value.image?.headlineType} {...value.image} />
                      </>
                    )}
                  </div>
                {/* </a> */}
              </div>
            </>
          );
        })
      }
    </>
  );
}

export default SliderContent;
