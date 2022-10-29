export const copyToClipboard = string => {
    const el = document.createElement('textarea');
    el.value = string;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
};

tippy(
    document.querySelectorAll('.yasr-copy-shortcode'),
    {
        content: 'Copied! Insert into your post!',
        theme: 'yasr',
        arrow: 'true',
        arrowType: 'round',
        trigger: 'click'
    }
);