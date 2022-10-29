const {__}                                        = wp.i18n; // Import __() from wp.i18n
const {registerPlugin}                            = wp.plugins;
const {PluginSidebar, PluginSidebarMoreMenuItem}  = wp.editPost;
const {PanelBody}                                 = wp.components;
const {Fragment}                                  = wp.element;

const ContentBelowSidebar = () => {
    return <div/>;
};

import {YasrDivRatingOverall} from './blocks/yasrGutenUtils';

/**
 * Show auto insert option
 */
class YasrSideBarAutoInsert extends React.Component {

    constructor(props) {
        super(props);

        let isThisPostExluded         = wp.data.select('core/editor').getCurrentPost().meta.yasr_auto_insert_disabled;
        let isThisPostExludedCheckbox = false;

        if (isThisPostExluded === 'yes') {
            isThisPostExludedCheckbox = true;
        }

        this.state = {postExcluded: isThisPostExludedCheckbox};

        this.yasrUpdatePostMetaAutoInsert = this.yasrUpdatePostMetaAutoInsert.bind(this);
    }

    yasrUpdatePostMetaAutoInsert(event) {
        const target = event.target;
        const postExcluded = target.type === 'checkbox' ? target.checked : target.value;

        this.setState({postExcluded: postExcluded});

        if (postExcluded === true) {
            wp.data.dispatch('core/editor').editPost(
                { meta: { yasr_auto_insert_disabled: 'yes' } }
            );
        } else {
            wp.data.dispatch('core/editor').editPost(
                { meta: { yasr_auto_insert_disabled: 'no' } }
            );
        }
    }

    render () {
        return (
            <div className="yasr-guten-block-panel-center">
                <hr />
                <label><span>{__('Disable auto insert for this post or page?', 'yet-another-stars-rating')}</span></label>
                <div className="yasr-onoffswitch-big yasr-onoffswitch-big-center" id="yasr-switcher-disable-auto-insert">
                    <input type="checkbox"
                           name="yasr_auto_insert_disabled"
                           className="yasr-onoffswitch-checkbox"
                           value="yes"
                           id="yasr-auto-insert-disabled-switch"
                           defaultChecked={this.state.postExcluded}
                           onChange={this.yasrUpdatePostMetaAutoInsert}
                    />
                    <label className="yasr-onoffswitch-label" htmlFor="yasr-auto-insert-disabled-switch">
                        <span className="yasr-onoffswitch-inner"/>
                        <span className="yasr-onoffswitch-switch"/>
                    </label>
                </div>
            </div>
        );
    }

}

class yasrSidebar extends React.Component {

    constructor(props) {
        super(props);

        let yasrAutoInsertEnabled = false;

        //this is not for the post, but from settings
        if (yasrConstantGutenberg.autoInsert !== 'disabled') {
            yasrAutoInsertEnabled = true;
        }

        this.state = {yasrAutoInsertEnabled: yasrAutoInsertEnabled};

    }

    render() {
        let YasrBelowSidebar = [<ContentBelowSidebar key={0}/>];
        {wp.hooks.doAction('yasr_below_panel', YasrBelowSidebar)}
        return (
            <Fragment>
                <PluginSidebarMoreMenuItem name="yasr-sidebar" type="sidebar" target="yasr-guten-sidebar" >
                    { __( 'YASR post settings', 'yet-another-stars-rating' ) }
                </PluginSidebarMoreMenuItem>
                <PluginSidebar name="yasr-guten-sidebar" title="YASR Settings">
                    <PanelBody>
                        <div className="yasr-guten-block-panel yasr-guten-block-panel-center">
                            <YasrDivRatingOverall />
                            <div>
                                {__('This is the same value that you find the "Yasr: Overall Rating" block.',
                                'yet-another-stars-rating')}
                            </div>
                            {this.state.yasrAutoInsertEnabled && <YasrSideBarAutoInsert />}
                            {YasrBelowSidebar}
                        </div>
                    </PanelBody>
                </PluginSidebar>
            </Fragment>
        );
    }
}

//Custom sidebar
registerPlugin( 'yasr-sidebar', {
    icon: 'star-half',
    title: __( 'Yasr: Page Settings', 'yet-another-stars-rating' ),
    render: yasrSidebar
} );