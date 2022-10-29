import {decodeEntities}    from "@wordpress/html-entities";
import {v4 as uuidv4}      from 'uuid';
import {yasrSetRaterValue} from '../modules/yasrSetRaterValue';

const  { __ }   = wp.i18n; // Import __() from wp.i18n
const  {render} = wp.element;

/*
 * Print the stars using RaterJs
 */

function YasrCallRaterJs (props) {
    const id   = 'yasr-ranking-element-' + uuidv4();
    const size = document.getElementById(props.tableId).dataset.rankingSize;

    return (
        <div id={id} ref={() =>
            yasrSetRaterValue(size, id, false, 0.1, true, props.rating)
        }>
        </div>
    );
}

/*
 *
 * Print text after the stars
 * if number of votes is defined, means that is the
 * [yasr_most_or_highest_rated_posts] shortcode
 *
 * @author Dario Curvino <@dudo>
 * @since  2.5.7
 *
 * @param props
 * @param {Object} props.rating  - Object with post attributes
 *
 */

function YasrTextAfterStars (props) {
    //If number_of_votes exists
    if(typeof props.post.number_of_votes !== "undefined") {
        return (
            <span className='yasr-most-rated-text'>
                [{__('Total:', 'yet-another-stars-rating')} {props.post.number_of_votes}
                 &nbsp;&nbsp;
                 {__('Average:', 'yet-another-stars-rating')} {props.post.rating}]
            </span>
        )
    }
    let text = props.text;

    return (
        <span className='yasr-highest-rated-text'>
            {text} {props.post.rating}
        </span>
    );
}

/**
 * Left column for rankings table
 *
 * @author Dario Curvino <@dudo>
 * @since  2.5.7
 *
 * @param props
 * @param {string} props.colClass - Column class name
 * @param {Object} props.post     - Object with post attributes
 *
 * @return {JSX.Element} - html <td> element
 */
function YasrRankingTableLeftColumn (props) {
    return (
        <td className={props.colClass}>
            <a href={props.post.link}>{decodeEntities(props.post.title)}</a>
        </td>
    )
}

/**
* Right column for rankings table
*
* @author Dario Curvino <@dudo>
* @since  2.5.7
*
* @param props
* @param {string} props.colClass - Column class name
* @param {Object} props.post     - Object with post attributes
*
* @return {JSX.Element} - html <td> element
*/
function YasrRankingTableRightColumn (props) {

    let txtPosition = 'after';
    let cstText    = __('Rating:' , 'yet-another-stars-rating');

    let params = new URLSearchParams(props.rankingParams);
    if(params.get('text_position') !== null) {
        txtPosition = params.get('text_position');
    }
    if(params.get('custom_txt') !== null) {
        cstText = params.get('custom_txt');
    }

    if (txtPosition === 'before') {
        return (
            <td className={props.colClass}>
                <YasrTextAfterStars post={props.post} tableId={props.tableId} text={cstText}/>
                <YasrCallRaterJs rating={props.post.rating} tableId={props.tableId}/>
            </td>
        )
    }

    return (
        <td className={props.colClass}>
            <YasrCallRaterJs rating={props.post.rating} tableId={props.tableId}/>
            <YasrTextAfterStars post={props.post} tableId={props.tableId} text={cstText}/>
        </td>
    )
}

/**
 * Print row for Ranking Table
 *
 * @author Dario Curvino <@dudo>
 * @since  2.5.7
 *
 * @param props
 * @param {string} props.source - Source of data
 * @param {Object} props.post     - Object with post attributes
 *
 * @return {JSX.Element} - html <tr> element
 */
function YasrRankingTableRow(props) {
    let leftClass = '';
    let rightClass = '';

    if (props.source === 'author_ranking') {
        leftClass = 'yasr-top-10-overall-left';
        rightClass = 'yasr-top-10-overall-right'
    }
    else if (props.source === 'visitor_votes') {
        leftClass  = 'yasr-top-10-most-highest-left';
        rightClass = 'yasr-top-10-most-highest-right'
    }

    return (
        <tr className={props.trClass}>
            <YasrRankingTableLeftColumn  colClass={leftClass}  post={props.post} />
            <YasrRankingTableRightColumn colClass={rightClass} post={props.post} tableId={props.tableId} rankingParams={props.rankingParams}/>
        </tr>
    )
}

/**
 * Loop the data array and return the Tbody
 *
 * @author Dario Curvino <@dudo>
 * @since  2.5.7
 *
 * @param props
 * @return {JSX.Element}
 */
function YasrRankingTableRowMap(props) {
    return (
        <tbody id={props.tBodyId} style={{display: props.show}}>
            {
                /*Loop the array, and set the style*/
            }
            {props.data.map(function (post, i) {
                let trClass = 'yasr-rankings-td-colored';
                if(props.source === 'author_ranking') {
                    trClass = 'yasr-rankings-td-white';
                }
                if (i % 2 === 0) {
                    trClass = 'yasr-rankings-td-white';
                    if(props.source === 'author_ranking') {
                        trClass = 'yasr-rankings-td-colored';
                    }
                }

                return(
                    <YasrRankingTableRow
                        key={post.post_id}
                        source={props.source}
                        tableId={props.tableId}
                        rankingParams={props.rankingParams}
                        post={post}
                        trClass={trClass}
                    />
                )
                })
            }
        </tbody>
    )
}

/**
 * @author Dario Curvino <@dudo>
 * @since  2.5.6
 */
class YasrRanking extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            data: [],
            tableId:        props.tableId,
            source:         props.source,
            rankingParams:  props.params,
            nonce:          props.nonce
        };

    }

    /**
     * Get data here.
     * Data can come from:
     * rest API if ajax is enabled and no errors are found
     * from a global variable (created with wp_localize_script) retrived from the window object
     * if ajax is disabled or error with rest response are found
     */
    componentDidMount() {
        const rankingData = JSON.parse(document.getElementById(this.state.tableId).dataset.rankingData);
        let data = {};

        //If ajax is disabled, use global value
        if(yasrCommonData.ajaxEnabled !== 'yes') {
            console.info(__('Ajax Disabled, getting data from source', 'yet-another-stars-rating'));
            this.setState({
                isLoaded: true,
                data: rankingData
            });
        }
        else {
            if (this.state.source) {
                //fet the rest urls
                const urlYasrRankingApi = this.returnRestUrl();
                Promise.all(urlYasrRankingApi.map((url) =>
                    fetch(url)
                        .then(response => {
                            if (response.ok === true) {
                                return response.json();
                            } else {
                                console.info(__('Ajax Call Failed. Getting data from source'))
                                return 'KO';
                            }
                        })
                        /**
                         * If response is not ok, get data from global var
                         */
                        .then(response => {
                            if (response === 'KO') {
                                data = rankingData;
                            } else {
                                if(response.source === 'overall_rating' || response.source === 'author_multi') {
                                    if(response.source === 'overall_rating') {
                                        data = response.data_overall;
                                    } else {
                                        data = response.data_mv;
                                    }
                                }
                                //if data is from visitor votes, create an array like this
                                //data[most]
                                //data[highest]
                                else {
                                    data[response.show] = response.data_vv
                                }
                            }
                        })
                        .catch((error) => {
                            data = rankingData;
                            console.info(__(error));
                        })
                ))
                    //At the end of promise all, data can be from rest api or global var
                    .then(r => {
                        this.setState({
                            isLoaded: true,
                            data: data
                        });
                    })
                    .catch((error) => {
                        console.info(__(error));
                        this.setState({
                            isLoaded: true,
                            data: data
                        });
                });

            } else {
                this.setState({
                    error: __('Invalid Data Source', 'yet-another-stars-rating')
                });
            }
        }
    }

    /*
     * Returns an array with the REST API urls
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.7
     *
     * @return array of urls
     */
    returnRestUrl(){
        let queryParams       = ((this.state.rankingParams !== '') ? this.state.rankingParams : '');
        let dataSource        = this.state.source;
        const nonce           = '&nonce_rankings='+this.state.nonce;
        let urlYasrRanking;

        let cleanedQuery = '';

        if (queryParams !== '' && queryParams !== false) {
            let params = new URLSearchParams(queryParams);

            if(params.get('order_by') !== null) {
                cleanedQuery += 'order_by='+params.get('order_by');
            }

            if(params.get('limit') !== null) {
                cleanedQuery += '&limit='+params.get('limit');
            }

            if(params.get('start_date') !== null && params.get('start_date') !== '0') {
                cleanedQuery += '&start_date='+params.get('start_date');
            }

            if(params.get('end_date') !== null && params.get('end_date') !== '0') {
                cleanedQuery += '&end_date='+params.get('end_date');
            }

            if(params.get('ctg') !== null) {
                cleanedQuery += '&ctg='+params.get('ctg');
            }
            else if(params.get('cpt') !== null) {
                cleanedQuery += '&cpt='+params.get('cpt');
            }

            if (cleanedQuery !== '') {
                cleanedQuery = cleanedQuery.replace(/\s+/g, '');
                cleanedQuery  = '&'+cleanedQuery;
            }

            if(dataSource === 'visitor_multi' || dataSource === 'author_multi') {
                if(params.get('setid') !== null) {
                    cleanedQuery += '&setid=' + params.get('setid');
                }
            }

        } else {
            cleanedQuery = '';
        }

        if(dataSource === 'author_ranking' || dataSource === 'author_multi') {
            urlYasrRanking = [yasrCommonData.ajaxurl + '?action=yasr_load_rankings&source=' + dataSource + cleanedQuery + nonce];
        }
        else {
            let requiredMost    = '';
            let requiredHighest = '';

            if(queryParams !== '') {
                let params = new URLSearchParams(queryParams);
                if (params.get('required_votes[most]') !== null) {
                    requiredMost = '&required_votes=' + params.get('required_votes[most]');
                }

                if (params.get('required_votes[highest]') !== null) {
                    requiredHighest = '&required_votes=' + params.get('required_votes[highest]');
                }
            }

            urlYasrRanking = [
                yasrCommonData.ajaxurl + '?action=yasr_load_rankings&show=most&source='    + dataSource + cleanedQuery + requiredMost + nonce,
                yasrCommonData.ajaxurl + '?action=yasr_load_rankings&show=highest&source=' + dataSource + cleanedQuery + requiredHighest + nonce
            ];

        }

        return urlYasrRanking;
    }

    /**
     * Print Thead Ranking Table Head
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.7
     *
     * @return {JSX.Element} - html <thead> element
     */
    rankingTableHead(source, defaultView) {
        const tableId       = this.state.tableId;
        const idLinkMost    = 'link-most-rated-posts-'+tableId;
        const idLinkHighest = 'link-highest-rated-posts-'+tableId;

        if(source !== 'author_ranking') {
            let containerLink = <span>
                                    <span id={idLinkMost}>
                                        {__('Most Rated', 'yet-another-stars-rating')}
                                    </span>&nbsp;|&nbsp;
                                    <a href='#' id={idLinkHighest} onClick={this.switchTBody.bind(this)}>
                                        {__('Highest Rated', 'yet-another-stars-rating')}
                                    </a>
                                 </span>

            if(defaultView === 'highest') {
                containerLink = <span>
                                    <span id={idLinkHighest} >
                                        {__('Highest Rated', 'yet-another-stars-rating')}
                                    </span>&nbsp;|&nbsp;
                                    <a href='#' id={idLinkMost} onClick={this.switchTBody.bind(this)}>
                                        {__('Most Rated', 'yet-another-stars-rating')}
                                    </a>
                                 </span>
            }

            return (
                <thead>
                    <tr className='yasr-rankings-td-colored yasr-rankings-heading'>
                        <th>{__('Post', 'yet-another-stars-rating')}</th>
                        <th>
                            {__('Order By', 'yet-another-stars-rating')}:&nbsp;&nbsp;
                            {containerLink}
                        </th>
                    </tr>
                </thead>
            )
        }

        return (<></>)
    }

    /**
     * Change style attribute for assigned tbody
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.7
     *
     */
    switchTBody(event) {
        event.preventDefault();
        const linkId        = event.target.id;

        const tableId       = this.state.tableId;
        const idLinkMost    = 'link-most-rated-posts-'+tableId;
        const idLinkHighest = 'link-highest-rated-posts-'+tableId;
        const bodyIdMost    = 'most-rated-posts-'+tableId;
        const bodyIdHighest = 'highest-rated-posts-'+tableId;

        //change html from a to span and vice versa
        //https://stackoverflow.com/a/13071899/3472877
        let anchor = document.getElementById(linkId);
        let span   = document.createElement("span");

        //Copy innerhtml and id into span element
        span.innerHTML = anchor.innerHTML;
        span.id        = anchor.id;

        //replace <a> with <span>
        anchor.parentNode.replaceChild(span,anchor);

        if(linkId === idLinkMost) {
            //Dispaly body for Most
            document.getElementById(bodyIdHighest).style.display = 'none';
            document.getElementById(bodyIdMost).style.display = '';

            //Here I've to replace <span> with <a>
            span             = document.getElementById(idLinkHighest);
            anchor.innerHTML = span.innerHTML;
            anchor.id        = span.id;
            span.parentNode.replaceChild(anchor,span);
        }
        if(linkId === idLinkHighest) {
            //Dispaly body for Highest
            document.getElementById(bodyIdMost).style.display = 'none';
            document.getElementById(bodyIdHighest).style.display = '';

            //Here I've to replace <span> with <a>
            span             = document.getElementById(idLinkMost);
            anchor.innerHTML = span.innerHTML;
            anchor.id        = span.id;
            span.parentNode.replaceChild(anchor,span);
        }
    }

    /**
     * Print Tbody Ranking Table
     *
     * @author Dario Curvino <@dudo>
     * @since  2.5.6
     *
     * @return {JSX.Element} - html <tbody> element
     */
    rankingTableBody() {
        const {data, source, rankingParams} = this.state;
        if(source === 'overall_rating' || source === 'author_multi') {
            return (
                <YasrRankingTableRowMap
                    data={data}
                    tableId={this.state.tableId}
                    tBodyId={'overall_'+this.state.tableId}
                    rankingParams={rankingParams}
                    show={'table-row-group'}
                    source={source}
                />
            )
        }

        else {
            const vvMost      = data.most;
            const vvHighest   = data.highest;
            const display = 'table-row-group';
            const hide    = 'none';

            let defaultView = 'most';
            let styleMost    = display;
            let styleHighest = hide;

            let params = new URLSearchParams(rankingParams);

            if(params.get('view') !== null) {
                defaultView = params.get('view');
            }

            if(defaultView === 'highest') {
                styleMost    = hide;
                styleHighest = display;
            }

            return (
                <>
                    {this.rankingTableHead(source, defaultView)}
                    <YasrRankingTableRowMap
                        data={vvMost}
                        tableId={this.state.tableId}
                        tBodyId={'most-rated-posts-'+this.state.tableId}
                        rankingParams={rankingParams}
                        show={styleMost}
                        source={source}
                    />
                    <YasrRankingTableRowMap
                        data={vvHighest}
                        tableId={this.state.tableId}
                        tBodyId={'highest-rated-posts-'+this.state.tableId}
                        rankingParams={rankingParams}
                        show={styleHighest}
                        source={source}
                    />
                </>
            )
        }
    }

    /**
     * Render rankings, error should never occour here
     */
    render() {
        const {error, isLoaded} = this.state;
        if(error) {
            return (
                <tbody>
                    <tr>
                        <td>
                        {console.log(error)}
                        Error
                        </td>
                    </tr>
                </tbody>
            )
        } else {
            if (isLoaded === false) {
                return (
                    <tbody>
                    <tr>
                        <td>
                            {__('Loading Charts', 'yet-another-stars-rating')}
                        </td>
                    </tr>
                    </tbody>
                )
            } else {
                return (
                    <>
                        {this.rankingTableBody()}
                    </>
                )
            }
        }
    }
}

export function yasrDrawRankings () {
    //check if there is some shortcode with class yasr-table-chart
    const yasrRankingsInDom = document.getElementsByClassName('yasr-stars-rankings');

    if (yasrRankingsInDom.length > 0) {
        for (let i = 0; i < yasrRankingsInDom.length; i++) {
            const tableId      = yasrRankingsInDom.item(i).id;
            const source       = JSON.parse(yasrRankingsInDom.item(i).dataset.rankingSource);
            const params       = JSON.parse(yasrRankingsInDom.item(i).dataset.rankingParams);
            const nonce        = JSON.parse(yasrRankingsInDom.item(i).dataset.rankingNonce);
            const rankingTable = document.getElementById(tableId);

            render(<YasrRanking source={source} tableId={tableId} params={params} nonce={nonce} />, rankingTable);
        }
    }
}

//Drow Rankings
yasrDrawRankings();