const upgradeRankingButtons = document.querySelectorAll('#ranking-up');
const downgradeRankingButtons = document.querySelectorAll('#ranking-down');
const differentClassification = ['NC', 'E6', 'E5', 'E4', 'E3', 'E2', 'E1', 'E0', 'D6', 'D5', 'D4', 'D3', 'D2', 'D1', 'D0', 'C6', 'C5', 'C4', 'C3', 'C2', 'C1', 'C0', 'B6', 'B5', 'B4', 'B3', 'B2', 'B1', 'B0', 'A20', 'A19', 'A18', 'A17', 'A16', 'A15', 'A14', 'A13', 'A12', 'A11', 'A10', 'A9', 'A8', 'A7', 'A6', 'A5', 'A4', 'A3', 'A2', 'A1'];

function addListenerForWishList() {
    if (upgradeRankingButtons && downgradeRankingButtons) {
        upgradeRankingButtons.forEach((button) =>
            button.addEventListener('click', () => {
                const userId = parseInt(button.getAttribute('data-user-id'));
                const currentRankingShowingHTML = document.getElementById('ranking-show-' + userId);
                const currentRankingProfileHTML = document.getElementById('ranking-profile-' + userId);
                const currentRanking = currentRankingShowingHTML.getAttribute('data-user-ranking');
                let currentRankingPosition = differentClassification.indexOf(currentRanking);
                if (currentRankingPosition !== differentClassification.length - 1) {
                    const newRanking = differentClassification[currentRankingPosition + 1];
                    updateRanking(userId, newRanking);
                    currentRankingShowingHTML.outerHTML = `<span id="ranking-show-${userId}" data-user-ranking="${newRanking}">${newRanking}</span>`;
                    currentRankingProfileHTML.outerHTML = `<p id="ranking-profile-${userId}">${newRanking}</p>`;
                }
            }))
        downgradeRankingButtons.forEach((button) =>
            button.addEventListener('click', () => {
                const userId = parseInt(button.getAttribute('data-user-id'));
                const currentRankingShowingHTML = document.getElementById('ranking-show-' + userId);
                const currentRankingProfileHTML = document.getElementById('ranking-profile-' + userId);
                const currentRanking = currentRankingShowingHTML.getAttribute('data-user-ranking');
                let currentRankingPosition = differentClassification.indexOf(currentRanking);
                if (currentRankingPosition !== 0) {
                    const newRanking = differentClassification[currentRankingPosition - 1];
                    updateRanking(userId, newRanking);
                    currentRankingShowingHTML.outerHTML = `<span id="ranking-show-${userId}" data-user-ranking="${newRanking}">${newRanking}</span>`;
                    currentRankingProfileHTML.outerHTML = `<p id="ranking-profile-${userId}">${newRanking}</p>`;
                }
            }))
    }
}

function updateRanking(userId, newRanking) {
    const xhr = new XMLHttpRequest();
    return new Promise((() => {
        xhr.open('GET', `/admin/classement/${userId}/${newRanking}`)
        xhr.send();
    }))
}

addListenerForWishList();