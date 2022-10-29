//this is the function that print the overall rating shortcode, get overall rating and starsize
export function yasrSetRaterValue (starSize,
                                   htmlId,
                                   element=false,
                                   step=0.1,
                                   readonly=true,
                                   rating=false,
                                   rateCallback=false
                                   ) {
    let domElement;
    if(element) {
        domElement = element;
    } else {
        domElement = document.getElementById(htmlId)
    }

    //convert to be a number
    starSize = parseInt(starSize);

    raterJs({
        starSize: starSize,
        showToolTip: false,
        element: domElement,
        step: step,
        readOnly: readonly,
        rating: rating,
        rateCallback: rateCallback
    });

}