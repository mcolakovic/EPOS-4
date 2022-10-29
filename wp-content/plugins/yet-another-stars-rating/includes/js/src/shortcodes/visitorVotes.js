const { __ }                           = wp.i18n; // Import __() from wp.i181n
import {yasrSetRaterValue}             from "../modules/yasrSetRaterValue";
import {yasrTrueFalseStringConvertion} from "../modules/yasrTrueFalseStringConvertion";

const yasrRaterInDom = document.getElementsByClassName('yasr-rater-stars-vv');

yasrSearchVVInDom(yasrRaterInDom);

export function yasrSearchVVInDom(yasrRaterInDom) {
    if (yasrRaterInDom.length > 0) {
        yasrVisitorVotesFront(yasrRaterInDom);
        if (yasrCommonData.visitorStatsEnabled === 'yes') {
            let yasrStatsInDom = document.getElementsByClassName('yasr-dashicons-visitor-stats');
            if (yasrStatsInDom) {
                yasrVvStats (yasrStatsInDom);
            }
        }
    }
}

/**
 *
 * @param yasrRaterVVInDom
 */
function yasrVisitorVotesFront (yasrRaterVVInDom) {
    //Check in the object
    for (let i = 0; i < yasrRaterVVInDom.length; i++) {
        (function(i) {
            //yasr-star-rating is the class set by rater.js : so, if already exists,
            //means that rater already run for the element
            if(yasrRaterVVInDom.item(i).classList.contains('yasr-star-rating') !== false) {
                return;
            }

            const elem            = yasrRaterVVInDom.item(i);
            let rating            = elem.getAttribute('data-rating');
            let readonlyShortcode = elem.getAttribute('data-readonly-attribute');
            let readonly          = elem.getAttribute('data-rater-readonly');

            if (readonlyShortcode === null) {
                readonlyShortcode = false;
            }

            readonlyShortcode = yasrTrueFalseStringConvertion(readonlyShortcode);
            readonly          = yasrTrueFalseStringConvertion(readonly);

            //if comes from shortcode attribute, and is true, readonly is always true
            if (readonlyShortcode === true) {
                readonly = true;
            }

            let postId        = elem.getAttribute('data-rater-postid');
            let htmlId        = elem.id;
            let uniqueId      = htmlId.replace('yasr-visitor-votes-rater-', '');
            let starSize      = parseInt(elem.getAttribute('data-rater-starsize'));
            let nonce         = elem.getAttribute('data-rater-nonce');
            let isSingular    = elem.getAttribute('data-issingular');

            let   containerVotesNumberName   = 'yasr-vv-votes-number-container-' + uniqueId;
            let   containerAverageNumberName = 'yasr-vv-average-container-' + uniqueId;
            let   bottomContainerName        = 'yasr-vv-bottom-container-' + uniqueId;
            let   loaderContainerName      = 'yasr-vv-loader-' + uniqueId;
            const containerVotesNumber     = document.getElementById(containerVotesNumberName);
            const containerAverageNumber   = document.getElementById(containerAverageNumberName);
            const bottomContainer          = document.getElementById(bottomContainerName);
            const loaderContainer          = document.getElementById(loaderContainerName);

            if(yasrCommonData.ajaxEnabled === 'yes') {
                //show the loader
                if(loaderContainer !== null) {
                    loaderContainer.innerHTML = yasrCommonData.loaderHtml;
                }

                let data = {
                    action: 'yasr_load_vv',
                    post_id: postId,
                };

                jQuery.get(yasrCommonData.ajaxurl, data).done(
                    function (response) {
                        let data = JSON.parse(response);
                        let readonly;
                        //if has readonly attribute, it is always true
                        if(readonlyShortcode === true) {
                            readonly = true;
                        } else {
                            readonly = data.yasr_visitor_votes.stars_attributes.read_only;
                        }

                        if (data.yasr_visitor_votes.number_of_votes > 0) {
                            rating = data.yasr_visitor_votes.sum_votes / data.yasr_visitor_votes.number_of_votes;
                        } else {
                            rating = 0;
                        }
                        rating   = rating.toFixed(1);
                        rating   = parseFloat(rating);

                        yasrSetVisitorVotesRater(starSize, rating, postId, readonly, htmlId, uniqueId, nonce, isSingular,
                            containerVotesNumber, containerAverageNumber,  loaderContainer, bottomContainer);

                        //do this only if yasr_visitor_votes has not the readonly attribute
                        if(readonlyShortcode !== true) {
                            if(containerVotesNumber !== null) {
                                containerVotesNumber.innerHTML = data.yasr_visitor_votes.number_of_votes;
                            }
                            if(containerAverageNumber !== null) {
                                containerAverageNumber.innerHTML = rating;
                            }

                            //insert span with text after the average
                            if(data.yasr_visitor_votes.stars_attributes.span_bottom !== false
                                && bottomContainer !== null) {
                                bottomContainer.innerHTML     = data.yasr_visitor_votes.stars_attributes.span_bottom;
                                bottomContainer.style.display = '';
                            }
                        }

                    }).fail(
                    function(e, x, settings, exception) {
                        console.info(__('YASR ajax call failed. Showing ratings from html', 'yet-another-stars-rating'));
                        yasrSetVisitorVotesRater(starSize, rating, postId, readonly, htmlId, uniqueId, nonce, isSingular,
                            containerVotesNumber, containerAverageNumber,  loaderContainer, bottomContainer);

                        //Unhide the div below the stars
                        if(readonlyShortcode !== true) {
                            bottomContainer.style.display = '';
                        }
                    });
            } else {
                yasrSetVisitorVotesRater(starSize, rating, postId, readonly, htmlId, uniqueId, nonce, isSingular,
                    containerVotesNumber, containerAverageNumber,  loaderContainer, bottomContainer);
            }

        })(i);
    }//End for

}


/**
 *
 * @param starSize
 * @param rating
 * @param postId
 * @param readonly
 * @param htmlId
 * @param uniqueId
 * @param nonce
 * @param isSingular
 * @param containerVotesNumber
 * @param containerAverageNumber
 * @param loaderContainer
 * @param bottomContainer
 */
function yasrSetVisitorVotesRater (starSize, rating, postId, readonly, htmlId, uniqueId, nonce, isSingular,
                                   containerVotesNumber, containerAverageNumber, loaderContainer, bottomContainer) {

    //Be sure is a number and not a string
    rating = parseFloat(rating);

    //raterjs accepts only boolean for readOnly element
    readonly = yasrTrueFalseStringConvertion(readonly);

    const elem = document.getElementById(htmlId);

    //Be sure the loader is hidden
    if (loaderContainer !== null) {
        loaderContainer.innerHTML = '';
    }
    let rateCallback = function (rating, done) {
        //show the loader
        if (loaderContainer !== null) {
            loaderContainer.innerHTML = yasrCommonData.loaderHtml;
        }

        //Creating an object with data to send
        var data = {
            action: 'yasr_send_visitor_rating',
            rating: rating,
            post_id: postId,
            nonce_visitor: nonce,
            is_singular: isSingular
        };

        this.setRating(rating);
        this.disable();

        //Send value to the Server
        jQuery.post(yasrCommonData.ajaxurl, data, function (response) {
            //decode json
            response = JSON.parse(response);

            let number_of_votes;
            let average_rating;
            let text;

            if(response.status === 'success') {
                number_of_votes = response.number_of_votes;
                average_rating  = response.average_rating;
                text            = response.rating_saved_text;

                //Update the ratings only if response is success
                if (containerVotesNumber !== null) {
                    containerVotesNumber.innerHTML   = number_of_votes;
                }
                if (containerAverageNumber !== null) {
                    containerAverageNumber.innerHTML = average_rating;
                }
            } else {
                //get response error
                text = response.text;
            }

            if (bottomContainer !== null) {
                bottomContainer.innerHTML = text;
                //Be sure the bottom container is showed
                bottomContainer.style.display = '';
            }

            //empty loader gif
            if (loaderContainer !== null) {
                loaderContainer.innerHTML = '';
            }

        });
        done();
    }

    yasrSetRaterValue (starSize, htmlId, elem, 1, readonly, rating, rateCallback);

}


/****** Tooltip functions ******/

//used in shortcode page and ajax page
function yasrVvStats (yasrStatsInDom) {
    //htmlcheckid declared false
    let htmlIdChecked = false;

    let txtContainer;  //the container of the text [TOTAL...];  needed to get the text color
    let computedcolor;

    for (let i = 0; i < yasrStatsInDom.length; i++) {
        (function (i) {

            let htmlId = '#'+yasrStatsInDom.item(i).id;
            let postId = yasrStatsInDom.item(i).getAttribute('data-postid');

            //get the font color from yasr-vv-text-container only the first time
            if(i===0) {
                //main container
                txtContainer     = document.getElementsByClassName('yasr-vv-text-container');
                if(txtContainer !== null) {
                    computedcolor = window.getComputedStyle(txtContainer[0], null).getPropertyValue("color");
                }
            }

            //if computed color exists, change the color to the svg
            if(computedcolor) {
                //get the svg element
                let svg = document.getElementById(yasrStatsInDom.item(i).id);
                //fill with the same color of the text
                svg.style.fill=computedcolor;
            }

            let data = {
                action: 'yasr_stats_visitors_votes',
                post_id: postId
            };

            let initialContent = '<span style="color: #0a0a0a">Loading...</span>';

            tippy(htmlId, {
                allowHTML: true,
                content: initialContent,
                theme: 'yasr',
                arrow: true,
                arrowType: 'round',

                //When support for IE will be dropped out, this will become onShow(tip)
                onShow: function onShow(tip) {
                    if (htmlId !== htmlIdChecked) {
                        //must be post or wont work
                        jQuery.post(yasrCommonData.ajaxurl, data, function (response) {
                            response = JSON.parse(response);
                            tip.setContent(yasrReturnToolbarStats(response));
                        });
                    }
                },
                onHidden: function onHidden() {
                    htmlIdChecked = htmlId;
                }

            });

        })(i);
    }

}

/**
 * Return the HTML with toolbars
 *
 * @param ratings MUST be an object
 * with 6 params: 1 int and 5 suboject
 * int:       medium_rating
 * subobject: each one must have:
 *     - progressbar
 *     - n_of_votes
 *     - votes (optional)
 *     e.g.
 {
          "1": {
            "progressbar": "17.14%",
            "n_of_votes": 6,
            "vote": "1"
          },
          "2": {
            "progressbar": "25.71%",
            "n_of_votes": 9,
            "vote": "2"
          },
          "3": {
            "progressbar": "8.57%",
            "n_of_votes": 3,
            "vote": "3"
          },
          "4": {
            "progressbar": "0%",
            "n_of_votes": 0,
            "vote": 4
          },
          "5": {
            "progressbar": "48.57%",
            "n_of_votes": 17,
            "vote": "5"
          }
        }
 *
 */
function yasrReturnToolbarStats (ratings) {
    //Get the medium rating
    const mediumRating        = ratings.medium_rating;

    //remove medium rating from the object, so I can loop only the ratings later
    delete ratings['medium_rating'];

    let highestNumberOfVotes = 0;
    //loop a first time the array to get the highest number of votes
    for (let i = 1;  i <= 5; i++) {
        if(i === 1) {
            highestNumberOfVotes = ratings[i].n_of_votes;
        } else {
            if (ratings[i].n_of_votes > highestNumberOfVotes) {
                highestNumberOfVotes = ratings[i].n_of_votes;
            }
        }
    }
    //Later, I need to get the number of digits of the highest number
    let lengthHighestNumberOfVotes = Math.log(highestNumberOfVotes) * Math.LOG10E + 1 | 0;

    //Later, I've to calculate the flexbasis based on the lenght of the nummber
    //default flexbasis is 5%
    let flexbasis = '5%'

    //if the length of the number is less or equal of 3 digits (999)
    //flexbasis is 5%
    if(lengthHighestNumberOfVotes <= 3) {
        flexbasis = '5%';
    }
    //if the length of the number is major of 3 digits and less or equal of 5 digits (99999)
    //flexbasis is 10%
    if(lengthHighestNumberOfVotes > 3 && lengthHighestNumberOfVotes <= 5) {
        flexbasis = '10%';
    }
    //if highest of 5 digits, flexbasis is 15 % (note that this will break into a newline when 8 digits are
    //reached, but I think that a number of 9,999,999 is enough
    if(lengthHighestNumberOfVotes > 5) {
        flexbasis = '15%';
    }

    //prepare the html to return
    let html_to_return  = '<div class="yasr-visitors-stats-tooltip">';
    html_to_return      += '<span id="yasr-medium-rating-tooltip">'
        + mediumRating
        + ' '
        + __('out of 5 stars', 'yet-another-stars-rating')
        + '</span>';
    html_to_return      += '<div class="yasr-progress-bars-container">';

    let stars_text = __('stars', 'yet-another-stars-rating');     //default is plural
    let progressbar = 0; //default value for progressbar
    let n_votes     = 0; //default n_votes

    //Do a for with 5 rows
    for (let i = 5;  i > 0; i--) {
        if (i === 1) {
            stars_text = __('star', 'yet-another-stars-rating'); //single form
        }

        //should never happen, just to be sage
        if(typeof ratings[i] !== 'undefined') {
            progressbar = ratings[i].progressbar;
            n_votes     = ratings[i].n_of_votes;
        }

        html_to_return += `<div class='yasr-progress-bar-row-container yasr-w3-container'>
                               <div class='yasr-progress-bar-name'>${i} ${stars_text}</div> 
                               <div class='yasr-single-progress-bar-container'> 
                                   <div class='yasr-w3-border'> 
                                       <div class='yasr-w3-amber' style='height:17px;width:${progressbar}'></div> 
                                   </div>
                               </div> 
                               <div class='yasr-progress-bar-votes-count' style="flex-basis:${flexbasis} ">${n_votes}</div>
                           </div>`;

    } //End foreach

    html_to_return += '</div></div>';

    return html_to_return;
}

/****** End tooltipfunction ******/