<?php 
session_start();
include 'connect.php';
?>
        
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casi 67 - Strona główna</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <style>
.hero {
    background:linear-gradient(rgba(10, 10, 10, 0.9), rgba(10, 10, 10, 0.7)), 
                 url('images/tło.png');    
    background-size: cover;
    background-position: center;
    padding: 80px 20px;
    text-align: center;
}
.hero {
    background: linear-gradient(rgba(10, 10, 10, 0.9), rgba(10, 10, 10, 0.7)), url('images/tło.png');
    background-size: cover;
    background-position: center;
    padding: 80px 20px;
    text-align: center;
    position: relative;
}

.csgo-container {
    width: 90%;
    max-width: 900px;
    background: rgba(0,0,0,0.7);
    border-radius: 20px;
    padding: 30px;
    position: relative;
    margin: 0 auto;
    overflow: hidden;
}

.reward-strip {
    display: flex;
    gap: 15px;
    position: relative;
    left: 0;
    transform: translateX(0);
    transition: transform 3s cubic-bezier(0.25, 0.1, 0.25, 1);
    margin-bottom: 20px;
}

.reward-item {
    width: 120px;
    height: 150px;
    flex-shrink: 0;
    border-radius: 15px;
    background: #111;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #FFD700;
    font-weight: bold;
    font-size: 1.2em;
}

.reward-item img {
    width: 100px;
    height: 100px;
    object-fit: contain;
    border-radius: 12px;
}

.reward-marker {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 6px;
    height: 100%;
    background: #FFD700;
    z-index: 10;
}

.reward-popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.85);
    border-radius: 20px;
    padding: 40px 60px;
    text-align: center;
    color: #FFD700;
    font-size: 2em;
    display: none;
    z-index: 100;
}

.reward-popup img {
    width: 200px;
    height: 200px;
    margin-top: 20px;
}

#openBoxBtn {
    margin-top: 20px;
    padding: 15px 30px;
    background: #FFD700;
    color: #000;
    font-size: 1.2em;
    font-weight: bold;
    border: none;
    border-radius: 15px;
    cursor: pointer;
    transition: 0.3s;
}
#openBoxBtn:hover {
    background: #FFE066;
}
        .hero h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #3b82f6;
        }
        
        .hero p {
            font-size: 1.2em;
            color: #94a3b8;
            max-width: 600px;
            margin: 0 auto 30px;
        }
        
        .movies-section {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            color:rgb(0, 0, 0);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .movies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .movie-card .btn {
            opacity: 0;
            transform: translateY(20px);
            transition: 0.3s ease;
}

        .movie-card:hover .btn {
            opacity: 1;
            transform: translateY(0);
        }
        .movie-card::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            pointer-events: none;
        }

        .movie-poster {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
        
        .movie-info {
            padding: 15px;
            transform: translateZ(40px);
        }
        
        .movie-title {
            color: white;
            margin: 0 0 10px 0;
            font-size: 1.1em;
        }
        
        .movie-meta {
            color: #94a3b8;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        
        .category-badge {
            display: inline-block;
            background:rgb(0, 0, 0);
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.8em;
            margin-right: 5px;
        }
        
        .about-section {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .about-content {
            display: flex;
            gap: 40px;
            align-items: center;
        }
        
        .about-text {
            flex: 1;
        }
        
        .about-image {
            flex: 1;
        }
        
        .about-image img {
            width: 100%;
            border-radius: 10px;
        }
        .movies-carousel-section {
    padding: 60px 20px;
    background-color: #0f172a;
}

.carousel-container {
    position: relative;  
    overflow: hidden;
    padding: 20px 0;
}

.carousel-controls {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between; 
    padding: 0 10px;
    z-index: 5;
    pointer-events: none; 
}

.carousel-btn {
    pointer-events: auto; 
    background: rgba(0, 0, 0, 0.9);
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    font-size: 1.6em;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    transition: 0.3s ease;
    padding-bottom: 8.5px;
}

.carousel-btn:hover {
    background:#3b82f6;
    transform: scale(1.1);
}


.carousel-track {
    display: flex;
    gap: 20px;
    transition: transform 0.5s ease-in-out;
}

.carousel-slide {
    min-width: 280px;
    height: 700px; 
    perspective: 1000px; 
}

.movie-card {
    background: linear-gradient(145deg, #1e293b, #0f172a);
    border-radius: 16px;
    overflow: hidden;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    transform-style: preserve-3d;
    position: relative;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.movie-card:hover {
    transform: rotateY(6deg) rotateX(4deg) scale(1.05);
    box-shadow: 
        0 20px 40px rgba(0,0,0,0.7),
        0 0 20px rgba(59,130,246,0.4);
}


.movie-poster {
    width: 100%;
    height: 420px;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.movie-card:hover .movie-poster {
    transform: scale(1.08);
}

.movie-info {
    padding: 15px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.movie-title {
    min-height: 48px; 
}

.movie-meta {
    min-height: 40px;
}

.movie-info p {
    flex-grow: 1; 
}
.premiere-section {
    position: relative;
    margin-bottom: 60px;
    padding-top: 60px;
}

.premiere-banner {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    max-width: 1800px;
    margin: 0 auto;
}

.premiere-banner img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.premiere-banner:hover img {
    transform: scale(1.05);
}

.premiere-info {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    text-align: center;
    backdrop-filter: brightness(0.35);
    padding: 30px;
    border-radius: 15px;
}

.premiere-info h2 {
    font-size: 2.5em;
    margin-bottom: 15px;
    color:rgb(0, 0, 0);
}

.premiere-info p {
    font-size: 1.2em;
    margin-bottom: 20px;
}

.countdown {
    display: flex;
    justify-content: center;
    gap: 15px;
    font-size: 1.2em;
    margin-bottom: 20px;
}

.countdown div {
    background: rgba(0,0,0,0.6);
    padding: 10px 15px;
    border-radius: 10px;
}

.pulsate {
    display: inline-block;
    padding: 12px 25px;
    font-size: 1.2em;
    border-radius: 50px;
    background: #3b82f6;
    color: #fff;
    text-decoration: none;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); box-shadow: 0 0 0 rgba(59,130,246,0.7); }
    50% { transform: scale(1.05); box-shadow: 0 0 15px rgba(59,130,246,0.9); }
    100% { transform: scale(1); box-shadow: 0 0 0 rgba(59,130,246,0.7); }
}



        
        @media (max-width: 768px) {
            .about-content {
                flex-direction: column;
            }
            
            .hero h1 {
                font-size: 2em;
            }
            
            .movies-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php 
    $show_header = true;
    include 'header.php'; 
    ?>
    
<section class="hero">
    <div class="csgo-container">
        <div class="reward-strip" id="rewardStrip"></div>
        <div class="reward-marker"></div>
    </div>

    <button id="openBoxBtn">OTWÓRZ SKRZYNKĘ</button>

    <div class="reward-popup" id="rewardPopup">
        WYGRAŁEŚ:<br><span id="rewardText"></span>
        <img id="rewardImg" src="">
    </div>
</section>

    
    
    <?php include 'footer.php'; ?>
<script>
const rewards = [
    {value: 100, chance: 25},
    {value: 200, chance: 20},
    {value: 300, chance: 15},
    {value: 500, chance: 10},
    {value: 1000, chance: 8},
    {value: 1500, chance: 7},
    {value: 2000, chance: 5},
    {value: 5000, chance: 4},
    {value: 7500, chance: 3},
    {value: 10000, chance: 3}
];

const rewardImgPath = "images/cfb65be8-d96a-45c9-8769-648a36cf9dc2-removebg-preview.png";

const rewardStrip = document.getElementById("rewardStrip");
const rewardPopup = document.getElementById("rewardPopup");
const rewardText = document.getElementById("rewardText");
const rewardImg = document.getElementById("rewardImg");
const openBoxBtn = document.getElementById("openBoxBtn");

// wypełniamy pasek nagród
function populateStrip() {
    rewardStrip.innerHTML = '';
    const repeat = 6;
    for (let i = 0; i < repeat; i++) {
        rewards.forEach(r => {
            const div = document.createElement('div');
            div.classList.add('reward-item');
            div.innerHTML = `<img src="${rewardImgPath}" alt="Nagroda"><br>${r.value}`;
            rewardStrip.appendChild(div);
        });
    }
}
populateStrip();

// funkcja losowania według procentów
function getRandomReward() {
    const rand = Math.random() * 100; // 0-100
    let sum = 0;
    for (let i = 0; i < rewards.length; i++) {
        sum += rewards[i].chance;
        if (rand <= sum) return rewards[i];
    }
    return rewards[rewards.length - 1]; // awaryjnie ostatnia nagroda
}

function openAnimation() {
    rewardPopup.style.display = 'none';
    rewardStrip.style.transition = 'none';
    rewardStrip.style.transform = 'translateX(0)';

    setTimeout(() => {
        const items = rewardStrip.children;
        const winReward = getRandomReward();
        const winIndex = rewards.findIndex(r => r.value === winReward.value) + rewards.length*2;
        const width = items[0].offsetWidth + 15;
        const containerWidth = rewardStrip.parentElement.offsetWidth;
        const targetX = -(winIndex * width - containerWidth/2 + width/2);

        rewardStrip.style.transition = 'transform 3s cubic-bezier(0.25, 0.1, 0.25, 1)';
        rewardStrip.style.transform = `translateX(${targetX}px)`;

        setTimeout(() => {
            rewardText.innerText = winReward.value;
            rewardImg.src = rewardImgPath;
            rewardPopup.style.display = 'block';

            setTimeout(() => {
                rewardPopup.style.display = 'none';
            }, 3000);
        }, 3000);
    }, 50);
}

openBoxBtn.addEventListener('click', openAnimation);
function updateButtonTimer() {
    const openBoxBtn = document.getElementById('openBoxBtn');
    const lastOpen = localStorage.getItem('lastBoxOpen');
    const now = Date.now();

    if(!lastOpen || now - parseInt(lastOpen) >= 24*60*60*1000) {
        openBoxBtn.innerText = "OTWÓRZ SKRZYNKĘ";
        openBoxBtn.disabled = false;
    } else {
        openBoxBtn.disabled = true;
        const remaining = 24*60*60*1000 - (now - parseInt(lastOpen));
        const hours = Math.floor(remaining / (1000*60*60));
        const minutes = Math.floor((remaining % (1000*60*60)) / (1000*60));
        const seconds = Math.floor((remaining % (1000*60)) / 1000);
        openBoxBtn.innerText = `${hours}h ${minutes}m ${seconds}s do otwarcia`;
    }
}

// wywołanie co sekundę, żeby licznik działał w czasie rzeczywistym
setInterval(updateButtonTimer, 1000);
updateButtonTimer(); // wywołanie od razu przy ładowaniu

openBoxBtn.addEventListener('click', function() {
    const lastOpen = localStorage.getItem('lastBoxOpen');
    const now = Date.now();

    if(!lastOpen || now - parseInt(lastOpen) >= 24*60*60*1000) {
        localStorage.setItem('lastBoxOpen', now);
        openAnimation(); // odpal animację
        updateButtonTimer(); // od razu aktualizujemy przycisk
    }
});
</script>
</body>
</html>
