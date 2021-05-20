import LazyLoad from 'vanilla-lazyload';
import Typed from 'typed.js';

new LazyLoad();


let typed_options = {
    strings: ['Ny enebolig eller fritidsbolig?', 'Særegen og meget flott beliggenhet','Ingen boplikt!', 'Ligger idyllisk til ved Ekrabukta, helt inntil stranda', 'Ta et morgenbad på en Askers fineste strand','Kun 50 min fra Oslo','Kun 350 meter å gå til Villa Malla','Velkommen til visning ^1000 <br> søndag 21. mai 2021 kl 12-14.'],
    typeSpeed: 50,
    startDelay: 100,
    backDelay: 1600,
    backSpeed: 10,
    loop: true
};

new Typed('#typed_title', typed_options);
