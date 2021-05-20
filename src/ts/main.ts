import LazyLoad from 'vanilla-lazyload';
import Typed from 'typed.js';

new LazyLoad();


let typed_options = {
    strings: ['Enebolig', 'eller','Fritidsbolig', 'Særegen beliggenhet', 'Rett ved stranda', 'Rett ved Ekrabukta','350m til Villa Malla', 'Ta fergen fra Oslo til Filtvet Brygge'],
    typeSpeed: 50,
    startDelay: 100,
    backDelay: 1600,
    backSpeed: 10,
    loop: true
};

new Typed('#typed_title', typed_options);
