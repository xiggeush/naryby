// js/game.js - Logika gry
let isFishing = false;
let position = 10;
let progress = 0;
let gameLoopInterval;

function toggleDrawer(id) {
    document.getElementById(id).classList.toggle('open');
}

function triggerCast() {
    if (isFishing) return;
    
    const energy = parseInt(document.getElementById('hudEnergy').innerText);
    if (energy < 10) {
        alert("Brak energii!");
        return;
    }

    isFishing = true;
    document.getElementById('btnCast').disabled = true;

    const bobber = document.getElementById('bobber');
    bobber.style.top = "68%";

    setTimeout(() => {
        bobber.classList.add('bitting');
        setTimeout(() => {
            bobber.classList.remove('bitting');
            startSimpleMinigame();
        }, 800);
    }, Math.random() * 1500 + 1000);
}

function startSimpleMinigame() {
    document.getElementById('minigame').style.display = 'block';
    position = 10;
    progress = 0;

    window.addEventListener('keydown', handlePull);
    document.getElementById('viewport').addEventListener('mousedown', handlePull);

    gameLoopInterval = setInterval(() => {
        position -= 1.8;
        if (position < 0) position = 0;

        document.getElementById('fishPointer').style.left = position + '%';

        if (position >= 35 && position <= 75) {
            progress += 2.5;
        } else {
            progress -= 1;
        }

        if (progress < 0) progress = 0;
        
        document.getElementById('pullProgress').style.width = progress + '%';
        document.getElementById('pullProgressTxt').innerText = Math.round(progress) + '%';

        if (progress >= 100) {
            finishMinigame(true);
        }
    }, 40);
}

function handlePull(e) {
    if (e.type === 'keydown' && e.code !== 'Space') return;
    if (isFishing) {
        position += 7;
        if (position > 95) position = 95;
    }
}

function finishMinigame(success) {
    clearInterval(gameLoopInterval);
    document.getElementById('minigame').style.display = 'none';
    document.getElementById('bobber').style.top = "65%";
    document.getElementById('btnCast').disabled = false;
    isFishing = false;

    window.removeEventListener('keydown', handlePull);
    document.getElementById('viewport').removeEventListener('mousedown', handlePull);

    if (success) {
        fetch('api.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=catch_success'
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('catchName').innerText = data.fish;
                document.getElementById('catchWeight').innerText = `Waga: ${data.weight} kg`;
                document.getElementById('catchValue').innerText = `+${data.value} 💰`;
                document.getElementById('catchExp').innerText = `+${data.exp} EXP`;
                document.getElementById('catchModal').classList.add('active');
            }
        });
    }
}

function closeCatchModal() {
    document.getElementById('catchModal').classList.remove('active');
    location.reload();
}

function sellAllFish() {
    fetch('api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=sell_all'
    })
    .then(res => res.json())
    .then(() => location.reload());
}

function buyItem(itemId) {
    fetch('api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=buy_item&item_id=${itemId}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}