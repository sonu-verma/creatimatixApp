import React from 'react';

function SliderContentText(props) {
  console.log(props);
  // const context = useContext(SliderContext);
  // console.log(`🚀 ~ SliderContentText ~ context`, context.activeIndex);

  return (
    <>
      <div className={`p-3 md:pt-24 ${props.align ? "justify-items-center": "justify-items-"+props.align}`} >
        <div className="pt-3 text-4xl font-bold ">{props.headline}</div>
        <div className="text-2xl">{props.subHeadline}</div>

        <div className="pt-3 text-sm font-bold underline ">
          <a href={props.buttonLink}>{props.buttonName} -&gt;</a>
        </div>
      </div>
    </>
  );
}

export default SliderContentText;
