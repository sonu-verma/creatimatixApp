import React from 'react';

function SliderContentImage(props) {
  const classNameStatic = 'flex mx-auto md:container ';

  const classNamesVariable = {
    right: classNameStatic + 'md:justify-center',
    left: classNameStatic + 'md:justify-center',
  };

  const classes = classNamesVariable[props.headlineType];

  return (
    <>
      <div className={classes}>
        <div className="relative h-80 w-80 object-cover sm:visible">
          <img
            src="https://images.unsplash.com/photo-1568354058198-064f43a19399?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=877&q=80"
            alt="Picture of the author"
          />
        </div>
      </div>
    </>
  );
}

export default SliderContentImage;
