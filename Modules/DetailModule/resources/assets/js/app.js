import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';
import ProgressBar from 'progressbar.js';
const lightbox = new PhotoSwipeLightbox({
    gallery: '.gallery',
    children: 'a',
    pswpModule: () => import('photoswipe')
});
lightbox.init();

//progress bar
var bar = new ProgressBar.Line('.flashsale-progress', {
    strokeWidth: 4,
    easing: 'easeInOut',
    duration: 1400,
    color: '#91BBFF',
    trailColor: '#eee',
    trailWidth: 1,
    svgStyle: { width: '100%', height: '100%', borderRadius: '10px' },
    text: {
        style: { 
            color: 'fff'
        },
        autoStyleContainer: false
    },
    from: { color: '#91BBFF' },
    to: { color: '#91BBFF' },
    step: function (state, bar) {
        bar.path.setAttribute('stroke', state.color);
        bar.setText(Math.round(bar.value() * 100) + ' %');
    }
});
bar.animate(1);
