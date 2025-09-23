document.addEventListener('DOMContentLoaded', function() {
    const programCards = Array.from(document.querySelectorAll('.program-card'));
    const totalCards = programCards.length;
    const cardsPerSlide = 4;
    let previousIndices = [];

    function arraysEqual(arr1, arr2) {
        if (arr1.length !== arr2.length) return false;
        const sorted1 = arr1.slice().sort();
        const sorted2 = arr2.slice().sort();
        return sorted1.every((value, index) => value === sorted2[index]);
    }

    function showRandomSlide() {
        let newIndices;
        do {
            const allIndices = Array.from({length: totalCards}, (_, i) => i);
            shuffleArray(allIndices);
            newIndices = allIndices.slice(0, cardsPerSlide);
        } while (arraysEqual(newIndices, previousIndices));

        programCards.forEach(card => card.classList.add('hidden'));
        newIndices.forEach(index => {
            programCards[index].classList.remove('hidden');
        });

        previousIndices = newIndices;
    }

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    showRandomSlide();

    setInterval(showRandomSlide, 2000);
});