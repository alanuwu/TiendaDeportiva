document.addEventListener('DOMContentLoaded', () => {
    const track = document.querySelector('.carousel-track');
    const items = Array.from(document.querySelectorAll('.carousel-item'));
    const prevBtn = document.querySelector('.carousel-btn.prev');
    const nextBtn = document.querySelector('.carousel-btn.next');
    const indicators = Array.from(document.querySelectorAll('.carousel-indicators .indicator'));
    let current = 0;
    let interval;

    function updateCarousel() {
        track.style.transform = `translateX(-${current * 100}%)`;
        items.forEach((item, idx) => {
            item.classList.toggle('active', idx === current);
        });
        indicators.forEach((ind, idx) => {
            ind.classList.toggle('active', idx === current);
        });
    }

    function goToSlide(idx) {
        current = idx;
        updateCarousel();
        resetInterval();
    }

    function nextSlide() {
        current = (current + 1) % items.length;
        updateCarousel();
    }

    function prevSlide() {
        current = (current - 1 + items.length) % items.length;
        updateCarousel();
    }

    function resetInterval() {
        clearInterval(interval);
        interval = setInterval(nextSlide, 5000);
    }

    prevBtn.addEventListener('click', prevSlide);
    nextBtn.addEventListener('click', nextSlide);

    indicators.forEach((ind, idx) => {
        ind.addEventListener('click', () => goToSlide(idx));
    });

    // Inicializar
    updateCarousel();
    interval = setInterval(nextSlide, 5000);
});