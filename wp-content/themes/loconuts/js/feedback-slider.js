document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('.feedback-slider');
    const nextBtn = document.getElementById('next');
    const prevBtn = document.getElementById('prev');

    if (!slider || !nextBtn || !prevBtn) return;

    function getCenterCard() {
        const cards = slider.querySelectorAll('.feedback-card');
        const sliderRect = slider.getBoundingClientRect();
        const sliderCenter = sliderRect.left + sliderRect.width / 2;

        let closestCard = cards[0];
        let closestDistance = Math.abs(closestCard.getBoundingClientRect().left + closestCard.offsetWidth / 2 - sliderCenter);

        cards.forEach(card => {
            const cardCenter = card.getBoundingClientRect().left + card.offsetWidth / 2;
            const distance = Math.abs(cardCenter - sliderCenter);
            if (distance < closestDistance) {
                closestDistance = distance;
                closestCard = card;
            }
        });

        return closestCard;
    }

    function scrollToCenter(direction) {
        const cards = slider.querySelectorAll('.feedback-card');
        const currentCard = getCenterCard();
        const currentIndex = Array.from(cards).indexOf(currentCard);

        let targetCard;
        if (direction === 'next' && currentIndex < cards.length - 1) {
            targetCard = cards[currentIndex + 1];
        } else if (direction === 'prev' && currentIndex > 0) {
            targetCard = cards[currentIndex - 1];
        } else {
            return;
        }

        // Arvuta õige scroll positsioon
        const sliderRect = slider.getBoundingClientRect();
        const cardRect = targetCard.getBoundingClientRect();
        const scrollLeft = slider.scrollLeft;
        const cardLeft = cardRect.left - sliderRect.left + scrollLeft;
        const cardWidth = cardRect.width;
        const sliderWidth = sliderRect.width;
        const targetScrollLeft = cardLeft - (sliderWidth / 2) + (cardWidth / 2);

        slider.scrollTo({
            left: targetScrollLeft,
            behavior: 'smooth'
        });
    }

    prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        scrollToCenter('prev');
    });

    nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        scrollToCenter('next');
    });
});
