import Typed from 'typed.js';

let typed_options = {
    strings: [
        'Ny enebolig ^500 eller fritidsbolig? ^700 <br><small style="font-weight: 400;font-size: 1rem">Ingen boplikt i Asker</small>',
        'Særegen og <u>meget</u> flott beliggenhet ^700 <br> rett ved Oslofjorden', 'Ligger idyllisk til ved Ekrabukta^800, <br> helt inntil stranda',
        'Ta et morgenbad på en av de fineste strendene i Asker!',
        'Kun 50 meter fra huset til strandkanten',
        'Kun 50 minutter fra Oslo!^1000 <br> Ligger på sydspissen i Asker',
        'Kun 350 meter å gå til Villa Malla',
        'Velkommen til visning ^1000 <br> søndag 21. mai 2021 ^800 <br> kl 12-14. ^500 <br><small style="font-size: 1rem;font-weight: 400">Moloveien 7 - Filtvet i Asker</small>'
    ],
    typeSpeed: 40,
    startDelay: 100,
    backDelay: 2000,
    backSpeed: 8,
    loop: false
};

new Typed('#typed_title', typed_options);


const images = Array.from(document.querySelectorAll('.bgImage'));


async function hideAllImage() {
    for (let image of images) {
        image.classList.add('hidden');
    }
}

async function loadVideo() {
    const video = document.getElementById('video');
    video.setAttribute('src', video.getAttribute('data-src'));
    return new Promise((r) => setTimeout(r, 2000));
}

function* getImage() {
    for (let image of images) {
        yield image;
    }
}

const imageGenerator = getImage();

function loadImage(image) {

    if (image && !image.getAttribute('srcset') && image.getAttribute('data-srcset')) {
        image.setAttribute('srcset', image.getAttribute('data-srcset'));
    }

    setTimeout(() => {
        if (image && !image.getAttribute('src') && image.getAttribute('data-src')) {
            image.setAttribute('src', image.getAttribute('data-src'));
        }
    }, 10)

    return new Promise(resolve => setTimeout(resolve, 2000));

}

function showNextImage() {

    const image = imageGenerator.next().value as HTMLImageElement;

    if (image && image.classList.contains('first')) {
        setTimeout(showNextImage, 5000);
        return;
    }

    if (!image) {
        loadVideo().then(hideAllImage).catch(() => {
        });
        return;
    }

    loadImage(image).then(hideAllImage).then(() => {
        image.setAttribute('style', '-webkit-animation:none;animation:none;');
        setTimeout(() => {
            image.removeAttribute('style');
            image.setAttribute('style', '-webkit-animation-delay:5ms;animation-delay:5ms');
            image.classList.remove('hidden');

        }, 10);
        setTimeout(showNextImage, 4000);
    });

}

showNextImage();