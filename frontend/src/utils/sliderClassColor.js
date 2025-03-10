const sliderClassColor = (color, type = '')  => {
    const classNameStatic = 'p-3 ';
  
    // Slider
    if (type === 'slider') {
      const classNamesVariable = {
        yellow: classNameStatic + 'bg-yellow-100 ',
        orange: classNameStatic + 'bg-orange-300 ',
        red: classNameStatic + 'bg-red-100 ',
        blue: classNameStatic + 'bg-blue-200 ',
      };
  
      return classNamesVariable[color];
    }
  }
  
  export default sliderClassColor;
  