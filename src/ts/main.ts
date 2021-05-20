import LazyLoad from 'vanilla-lazyload';
import Typed from 'typed.js';

new LazyLoad();


let typed_options = {
    strings: ['Ny enebolig ^500 eller fritidsbolig?', 'Særegen og meget flott beliggenhet^700<br>rett ved Oslofjorden','Ingen boplikt!', 'Ligger idyllisk til ved Ekrabukta^800,<br> helt inntil stranda', 'Ta et morgenbad på en av de fineste strendene i Asker!','Kun 50 meter fra huset til strandkanten','Kun 50 minutter fra Oslo!^1000<br>Ligger på sydspissen i Asker','Kun 350 meter å gå til Villa Malla','Velkommen til visning ^1000 <br> søndag 21. mai 2021 ^800 <br>kl 12-14.'],
    typeSpeed: 45,
    startDelay: 100,
    backDelay: 1600,
    backSpeed: 10,
    loop: true
};

new Typed('#typed_title', typed_options);
