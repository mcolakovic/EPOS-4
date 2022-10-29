const {registerBlockType}                = wp.blocks; // Import from wp.blocks
const {PanelBody}                        = wp.components;
const {Fragment}                         = wp.element;
const {useBlockProps, InspectorControls} = wp.blockEditor;

import {YasrNoSettingsPanel} from "./yasrGutenUtils";

//Most active users
registerBlockType(
    'yet-another-stars-rating/most-active-users', {
        edit:
            function(props) {
                const blockProps = useBlockProps( {
                    className: 'yasr-active-users-block'
                } );

                let YasrTopVisitorSettings = [<YasrNoSettingsPanel key={0}/>];
                {wp.hooks.doAction('yasr_top_visitor_setting', YasrTopVisitorSettings)}

                function YasrTopVisitorPanel (props) {
                    return (
                        <InspectorControls>
                            <PanelBody title='Settings'>
                                <div className="yasr-guten-block-panel">
                                    <div>
                                        {YasrTopVisitorSettings}
                                    </div>
                                </div>
                            </PanelBody>
                        </InspectorControls>
                    );
                }

                return (
                    <Fragment>
                        <YasrTopVisitorPanel />
                        <div {...blockProps}>
                            [yasr_most_active_users]
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
                    className: 'yasr-active-users-block'
                } );
                return (
                    <div {...blockProps}>[yasr_most_active_users]</div>
                );
            },

    }
);

//Most Active reviewers
registerBlockType(
    'yet-another-stars-rating/most-active-reviewers', {
        edit:
            function(props) {
                const blockProps = useBlockProps( {
                    className: 'yasr-reviewers-block',
                } );

                let YasrTopReviewersSettings = [<YasrNoSettingsPanel key={0}/>];
                {wp.hooks.doAction('yasr_top_reviewers_setting', YasrTopReviewersSettings)}

                function YasrTopReviewersPanel (props) {
                    return (
                        <InspectorControls>
                            <PanelBody title='Settings'>
                                <div className="yasr-guten-block-panel">
                                    <div>
                                        {YasrTopReviewersSettings}
                                    </div>
                                </div>
                            </PanelBody>
                        </InspectorControls>
                    );
                }

                return (
                    <Fragment>
                        <YasrTopReviewersPanel />
                        <div {...blockProps}>
                            [yasr_top_reviewers]
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
                    className: 'yasr-reviewers-block',
                } );
                return (
                    <div  {...blockProps}>[yasr_top_reviewers]</div>
                );
            },

    }
);