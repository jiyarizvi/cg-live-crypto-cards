document.addEventListener("DOMContentLoaded", () => {
    const wrapper = document.getElementById("cg-live-cards-wrapper");
    const container = document.getElementById("cg-live-cards");
    const s = cgLiveCards.settings;

    // Layout classes
    if (wrapper) {
        wrapper.classList.add(`cg-layout-${s.layout}`);
    }

    // Dark mode
    function applyDarkMode() {
        if (!wrapper) return;
        const mode = wrapper.dataset.darkMode || s.dark_mode;

        wrapper.classList.remove("cg-dark");
        if (mode === "dark") {
            wrapper.classList.add("cg-dark");
        } else if (mode === "auto") {
            if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
                wrapper.classList.add("cg-dark");
            }
        }
    }
    applyDarkMode();

    if (window.matchMedia) {
        window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", applyDarkMode);
    }

    // Inject CSS variables for colors
    if (wrapper) {
        wrapper.style.setProperty("--cg-card-bg", s.card_bg_light);
        wrapper.style.setProperty("--cg-card-border", s.card_border);
        wrapper.style.setProperty("--cg-text", s.text_light);
        wrapper.style.setProperty("--cg-up", s.color_up);
        wrapper.style.setProperty("--cg-down", s.color_down);
    }

    if (wrapper && wrapper.classList.contains("cg-dark")) {
        wrapper.style.setProperty("--cg-card-bg", s.card_bg_dark);
        wrapper.style.setProperty("--cg-text", s.text_dark);
    }

    // Load coins
    (s.coins || []).forEach(coin => loadCoin(coin));

    function loadCoin(coin) {
        fetch(`${cgLiveCards.ajax_url}?action=cg_get_coin_data&coin=${encodeURIComponent(coin)}`)
            .then(res => res.json())
            .then(data => {
                if (!data || !data.market || !data.market[0]) return;
                renderCard(coin, data);
            })
            .catch(console.error);
    }

    function renderCard(coin, data) {
        const m = data.market[0];
        const prices = (data.chart && data.chart.prices || []).map(p => p[1]);
        if (!prices.length) return;

        const isUp = m.price_change_percentage_24h >= 0;
        const stroke = isUp ? s.color_up : s.color_down;

        const svg = buildSmoothedSparkline(prices, stroke);

        const card = document.createElement("div");
        card.className = "cg-card";

        card.innerHTML = `
            <div class="cg-left">
                <div class="cg-card-header">
                    <img src="${m.image}" alt="${m.symbol.toUpperCase()}">
                    <div class="cg-card-title">${m.name} (${m.symbol.toUpperCase()})</div>
                </div>
                <div class="cg-price">$${m.current_price.toLocaleString()}</div>
                <div class="cg-change ${isUp ? "cg-up" : "cg-down"}">
                    ${m.price_change_percentage_24h.toFixed(2)}%
                </div>
            </div>
            <div class="cg-chart-wrapper">${svg}</div>
        `;

        container.appendChild(card);
    }

    // Smoothed sparkline using quadratic curves
    function buildSmoothedSparkline(prices, stroke) {
        const width = 100, height = 40;
        const min = Math.min(...prices);
        const max = Math.max(...prices);
        const range = max - min || 1;
        const stepX = width / (prices.length - 1);

        const points = prices.map((p, i) => {
            const x = i * stepX;
            const y = height - ((p - min) / range) * (height - 4) - 2;
            return { x, y };
        });

        if (points.length < 2) return "";

        let d = `M ${points[0].x},${points[0].y}`;
        for (let i = 1; i < points.length; i++) {
            const prev = points[i - 1];
            const curr = points[i];
            const cx = (prev.x + curr.x) / 2;
            const cy = (prev.y + curr.y) / 2;
            d += ` Q ${prev.x},${prev.y} ${cx},${cy}`;
        }

        const last = points[points.length - 1];
        const first = points[0];

        const areaPath = `${d} L ${last.x},${height} L ${first.x},${height} Z`;

        const gradId = `grad-${stroke.replace('#','')}-${Math.floor(Math.random()*100000)}`;

        return `
        <svg viewBox="0 0 ${width} ${height}" class="cg-chart" preserveAspectRatio="none">
            <defs>
                <linearGradient id="${gradId}" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="${stroke}" stop-opacity="0.35"/>
                    <stop offset="100%" stop-color="${stroke}" stop-opacity="0"/>
                </linearGradient>
            </defs>

            <path 
                d="${d}" 
                fill="none" 
                stroke="${stroke}" 
                stroke-width="2.2"
                class="cg-line-path"
            />

            <path 
                d="${areaPath}"
                fill="url(#${gradId})"
                opacity="0.9"
            />
        </svg>`;
    }
});
