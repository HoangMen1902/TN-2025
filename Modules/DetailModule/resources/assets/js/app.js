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
    svgStyle: { width: '100%', height: '10%', borderRadius: '10px' },
    text: {
        style: {
            color: '#fff'
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
bar.animate(0.75);


var fiveStar = new ProgressBar.Line('.five-star-bar', {
    strokeWidth: 4,
    easing: 'easeInOut',
    duration: 1400,
    color: '#fca404',
    trailColor: '#eee',
    trailWidth: 1,
    svgStyle: { width: '100%', height: '100%', borderRadius: '10px' },
    text: {
        style: { display: 'none' }, 
        autoStyleContainer: false
    },
    from: { color: '#fca404' },
    to: { color: '#fca404' },
    step: function (state, bar) {
        document.getElementById('five-star-text').textContent = Math.round(bar.value() * 100) + '%';
    }
});
fiveStar.animate(0.5);


var fourStar = new ProgressBar.Line('.four-star-bar', {
    strokeWidth: 4,
    easing: 'easeInOut',
    duration: 1400,
    color: '#fca404',
    trailColor: '#eee',
    trailWidth: 1,
    svgStyle: { width: '100%', height: '100%', borderRadius: '10px' },
    text: {
        style: { display: 'none' }, 
        autoStyleContainer: false
    },
    from: { color: '#fca404' },
    to: { color: '#fca404' },
    step: function (state, bar) {
        document.getElementById('four-star-text').textContent = Math.round(bar.value() * 100) + '%';
    }
});

fourStar.animate(0.90);


var threeStar = new ProgressBar.Line('.three-star-bar', {
    strokeWidth: 4,
    easing: 'easeInOut',
    duration: 1400,
    color: '#fca404',
    trailColor: '#eee',
    trailWidth: 1,
    svgStyle: { width: '100%', height: '100%', borderRadius: '10px' },
    text: {
        style: { display: 'none' }, 
        autoStyleContainer: false
    },
    from: { color: '#fca404' },
    to: { color: '#fca404' },
    step: function (state, bar) {
        document.getElementById('three-star-text').textContent = Math.round(bar.value() * 100) + '%';
    }
});

threeStar.animate(0.8);


var twoStar = new ProgressBar.Line('.two-star-bar', {
    strokeWidth: 4,
    easing: 'easeInOut',
    duration: 1400,
    color: '#fca404',
    trailColor: '#eee',
    trailWidth: 1,
    svgStyle: { width: '100%', height: '100%', borderRadius: '10px' },
    text: {
        style: { display: 'none' }, 
        autoStyleContainer: false
    },
    from: { color: '#fca404' },
    to: { color: '#fca404' },
    step: function (state, bar) {
        document.getElementById('two-star-text').textContent = Math.round(bar.value() * 100) + '%';
    }
});

twoStar.animate(0.75);

var oneStar = new ProgressBar.Line('.one-star-bar', {
    strokeWidth: 4,
    easing: 'easeInOut',
    duration: 1400,
    color: '#fca404',
    trailColor: '#eee',
    trailWidth: 1,
    svgStyle: { width: '100%', height: '100%', borderRadius: '10px' },
    text: {
        style: { display: 'none' }, 
        autoStyleContainer: false
    },
    from: { color: '#fca404' },
    to: { color: '#fca404' },
    step: function (state, bar) {
        document.getElementById('one-star-text').textContent = Math.round(bar.value() * 100) + '%';
    }
});

oneStar.animate(0.5);



