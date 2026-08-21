<!-- Mini-gra & Popup Złowienia -->
<div class="minigame-box" id="minigame">
    <h4 class="fw-bold text-warning mb-1"><i class="bi bi-exclamation-triangle-fill"></i> ZACIĘCIE!</h4>
    <p class="small text-muted mb-0">Klikaj <strong>SPACJĘ</strong> lub <strong>MYSZKĘ</strong>, aby utrzymać wskaźnik na zielonym polu!</p>
    
    <div class="pull-track">
        <div class="green-zone"></div>
        <div class="fish-pointer" id="fishPointer"></div>
    </div>

    <div class="progress mb-2" style="height: 10px; background: rgba(255,255,255,0.1);">
        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" id="pullProgress" style="width: 0%"></div>
    </div>
    <div class="d-flex justify-content-between small text-muted">
        <span>Progress holowania</span>
        <span id="pullProgressTxt">0%</span>
    </div>
</div>

<div class="modal-overlay" id="catchModal">
    <div class="catch-card">
        <div class="display-1 text-warning mb-2">🐟</div>
        <h3 class="fw-bold text-white mb-1" id="catchName">Płoć</h3>
        <p class="text-muted mb-3" id="catchWeight">Waga: 0.5 kg</p>
        
        <div class="d-flex justify-content-around bg-dark bg-opacity-50 p-3 rounded-4 mb-4 border border-secondary">
            <div>
                <small class="text-muted d-block">Wartość</small>
                <strong class="text-success fs-5" id="catchValue">+10 💰</strong>
            </div>
            <div class="border-end border-secondary"></div>
            <div>
                <small class="text-muted d-block">Doświadczenie</small>
                <strong class="text-info fs-5" id="catchExp">+15 EXP</strong>
            </div>
        </div>

        <button class="btn btn-warning btn-lg w-100 fw-bold rounded-pill" onclick="closeCatchModal()">DODAJ DO SIATKI</button>
    </div>
</div>