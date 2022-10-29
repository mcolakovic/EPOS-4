const {registerBlockType}                = wp.blocks; // Import from wp.blocks
const {PanelBody}                        = wp.components;
const {Fragment}                         = wp.element;
const {useBlockProps, InspectorControls} = wp.blockEditor;

import {YasrNoSettingsPanel} from "./yasrGutenUtils";

registerBlockType(
    'yet-another-stars-rating/overall-rating-ranking', {
        edit:
            function(props) {
                const blockProps = useBlockProps( {
                    className: 'yasr-ov-ranking-block',
                } );

                let YasrORRSettings = [<YasrNoSettingsPanel key={0}/>];
                {wp.hooks.doAction('yasr_overall_rating_rankings', YasrORRSettings)}

                function YasrORRPanel (props) {
                    return (
                        <InspectorControls>
                            <PanelBody title='Settings'>
                                <div className="yasr-guten-block-panel">
                                    <div>
                                        {YasrORRSettings}
                                    </div>
                                </div>

                            </PanelBody>
                        </InspectorControls>
                    );
                }

                return (
                    <Fragment>
                        <YasrORRPanel />
                        <div {...blockProps}>
                            [yasr_ov_ranking]
                        </div>
                    </Fragment>
                );
            },


        /**
         * The save function defines the way in which the different attributes should be combined
         * into the final markup, which is then serialized by Gutenberg into post_content.
         *
         * The "save" property must be specified and must be a valid function.
         *
         * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
         */
        save:
            function(props) {
                const blockProps = useBlockProps.save( {
                    className: 'yasr-ov-ranking-block',
                } );
                return (
                    <div  {...blockProps}>[yasr_ov_ranking]</div>
                );
            },

    });

registerBlockType(
    'yet-another-stars-rating/visitor-votes-ranking', {
        edit:
            function(props) {
                const blockProps = useBlockProps( {
                    className: 'yasr-vv-ranking-block',
                } );
                let YasrVVRSettings = [<YasrNoSettingsPanel key={0}/>];
                {wp.hooks.doAction('yasr_visitor_votes_rankings', YasrVVRSettings)}

                function YasrVVRPanel (props) {
                    return (
                        <InspectorControls>
                            <PanelBody title='Settings'>
                                <div className="yasr-guten-block-panel">
                                    <div>
                                        {YasrVVRSettings}
                                    </div>
                                </div>

                            </PanelBody>
                        </InspectorControls>
                    );
                }

                return (
                    <Fragment>
                        <YasrVVRPanel />
                        <div {...blockProps}>
                            [yasr_most_or_highest_rated_posts]
                        </div>
                    </Fragment>
                );
            },

        /**
         * The save function defines the way in which the different attributes should be combined
         * into the final markup, which is then serialized by Gutenberg into post_content.
         *
         * The "save" property must be specified and must be a valid function.
         *
         * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
         */
        save:
            function(props) {
                const blockProps = useBlockProps.save( {
                    className: 'yasr-vv-ranking-block',
                } );
                return (
                    <div  {...blockProps}>[yasr_most_or_highest_rated_posts]</div>
                );
            },

    });