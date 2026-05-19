// ===== DATA =====
const CASES = [
  {
    id: 'weapon',
    name: 'Weapon Case',
    emoji: '📦',
    price: 0,
    label: 'free',
    contents: [
      // Consumer
      { name: 'P2000 | Скелет',       emoji: '🔫', rarity: 'consumer',   price: 0.05 },
      { name: 'Glock-18 | Пипетка',   emoji: '🔫', rarity: 'consumer',   price: 0.05 },
      { name: 'FAMAS | Поверхностная', emoji: '🔫', rarity: 'consumer',   price: 0.05 },
      // Industrial
      { name: 'MP7 | Северный лес',   emoji: '🔫', rarity: 'industrial', price: 0.08 },
      { name: 'Mac-10 | Медная кроль', emoji: '🔫', rarity: 'industrial', price: 0.10 },
      // Mil-spec
      { name: 'AUG | Wings',          emoji: '🔫', rarity: 'milspec',    price: 0.30 },
      { name: 'USP-S | Мятная капля', emoji: '🔫', rarity: 'milspec',    price: 0.45 },
      { name: 'P250 | Горячая алая',  emoji: '🔫', rarity: 'milspec',    price: 0.50 },
      // Restricted
      { name: 'FAMAS | Пламя',        emoji: '🔫', rarity: 'restricted', price: 1.20 },
      { name: 'Desert Eagle | Кобра', emoji: '🦅', rarity: 'restricted', price: 2.50 },
      // Classified
      { name: 'AK-47 | Ягуарья кожа', emoji: '🔥', rarity: 'classified', price: 12.00 },
      { name: 'M4A1-S | Синяя Фосфо.', emoji: '🔥', rarity: 'classified', price: 18.00 },
      // Covert
      { name: 'AWP | Asiimov',        emoji: '💥', rarity: 'covert',     price: 55.00 },
      { name: 'M4A4 | Вой',           emoji: '💥', rarity: 'covert',     price: 40.00 },
      // Rare
      { name: '★ Нож Керамбит | Вороненая сталь', emoji: '🔪', rarity: 'gold', price: 350.00 },
    ]
  },
  {
    id: 'clutch',
    name: 'Clutch Case',
    emoji: '🟥',
    price: 2.49,
    label: 'mid',
    contents: [
      { name: 'Nova | Антропик',       emoji: '🔫', rarity: 'consumer',   price: 0.08 },
      { name: 'CZ75-Auto | Тактика',   emoji: '🔫', rarity: 'consumer',   price: 0.07 },
      { name: 'Tec-9 | Сера',          emoji: '🔫', rarity: 'industrial', price: 0.15 },
      { name: 'SSG 08 | Слепая зона',  emoji: '🔫', rarity: 'industrial', price: 0.20 },
      { name: 'P90 | Бронебойный',     emoji: '🔫', rarity: 'milspec',    price: 0.70 },
      { name: 'SCAR-20 | Руны',        emoji: '🔫', rarity: 'milspec',    price: 0.90 },
      { name: 'Galil AR | Spiral Trip',emoji: '🔫', rarity: 'milspec',    price: 1.10 },
      { name: 'MP9 | Горячий кварц',   emoji: '🔫', rarity: 'restricted', price: 4.00 },
      { name: 'G3SG1 | Шерстяная ракета',emoji:'🔫',rarity: 'restricted', price: 5.50 },
      { name: 'M4A1-S | Мастер кусок', emoji: '🔥', rarity: 'classified', price: 28.00 },
      { name: 'Desert Eagle | Хронос', emoji: '🔥', rarity: 'classified', price: 22.00 },
      { name: 'AWP | Хиперзверь',      emoji: '💥', rarity: 'covert',     price: 90.00 },
      { name: 'AK-47 | Неоновый Всадник',emoji:'💥',rarity: 'covert',    price: 120.00 },
      { name: '★ М9-Штык | Мраморный Градиент', emoji:'🔪', rarity:'gold', price: 600.00 },
    ]
  },
  {
    id: 'dreams',
    name: 'Dreams & Nightmares',
    emoji: '🌌',
    price: 7.50,
    label: 'high',
    contents: [
      { name: 'MAC-10 | Кошмар',       emoji: '🔫', rarity: 'consumer',   price: 0.12 },
      { name: 'MP9 | Ночной сад',      emoji: '🔫', rarity: 'consumer',   price: 0.10 },
      { name: 'Dual Berettas | Лунный',emoji: '🔫', rarity: 'industrial', price: 0.30 },
      { name: 'XM1014 | Мираж',        emoji: '🔫', rarity: 'industrial', price: 0.40 },
      { name: 'MP5-SD | Видения',      emoji: '🔫', rarity: 'milspec',    price: 1.80 },
      { name: 'Nova | Сны',            emoji: '🔫', rarity: 'milspec',    price: 2.00 },
      { name: 'Negev | Кошмарный Туман',emoji:'🔫', rarity: 'milspec',    price: 2.50 },
      { name: 'AUG | Ужас',           emoji: '🔫', rarity: 'restricted', price: 8.00 },
      { name: 'SG 553 | Галлюцинация', emoji: '🔫', rarity: 'restricted', price: 9.50 },
      { name: 'FAMAS | Ночной лилит',  emoji: '🔥', rarity: 'classified', price: 35.00 },
      { name: 'MP7 | Приключение',     emoji: '🔥', rarity: 'classified', price: 45.00 },
      { name: 'AK-47 | Лунные волны',  emoji: '💥', rarity: 'covert',     price: 200.00 },
      { name: 'M4A4 | Призрачный Берёз',emoji:'💥', rarity: 'covert',     price: 180.00 },
      { name: '★ Штык М9 | Изумруд Градиент', emoji:'🔪', rarity:'gold',  price: 1200.00 },
    ]
  }
];

// Rarity drop weights (sum = 1000)
const WEIGHTS = {
  consumer:   399,
  industrial: 250,
  milspec:    199,
  restricted:  99,
  classified:  37,
  covert:      13,
  gold:         3
};

const RARITY_NAMES = {
  consumer:   'Ширпотреб',
  industrial: 'Промышленное',
  milspec:    'Армейское',
  restricted: 'Запрещённое',
  classified: 'Засекреченное',
  covert:     'Тайное',
  gold:       '★ Редкий предмет'
};

const DREAMS_ULTRA_DROP = {
  name: 'Латул 67',
  img: 'secretDrop.jpg',
  rarity: 'gold',
  price: 676767.00
};

// Очень маленький шанс (0.05% = 1 из 2000)
const DREAMS_ULTRA_CHANCE = 1 / 2000;

function maybeUltraDreamsDrop(caseData) {
  if (!caseData || caseData.id !== 'dreams') return null;
  return Math.random() < DREAMS_ULTRA_CHANCE ? DREAMS_ULTRA_DROP : null;
}

function showBigCongrats(text, imgSrc) {
  const overlay = document.createElement('div');
  overlay.className = 'big-congrats-overlay';

  const box = document.createElement('div');
  box.className = 'big-congrats-box';

  if (imgSrc) {
    const img = document.createElement('img');
    img.className = 'big-congrats-img';
    img.src = imgSrc;
    img.alt = '';
    img.loading = 'lazy';
    box.appendChild(img);
  }

  const title = document.createElement('div');
  title.className = 'big-congrats-text';
  title.textContent = text;
  box.appendChild(title);

  overlay.appendChild(box);
  document.body.appendChild(overlay);

  setTimeout(() => overlay.classList.add('hide'), 1700);
  setTimeout(() => overlay.remove(), 2200);
}
const RARITY_COLORS = {
  consumer:   '#b0c3d9',
  industrial: '#5e98d9',
  milspec:    '#4b69ff',
  restricted: '#8847ff',
  classified: '#d32ce6',
  covert:     '#eb4b4b',
  gold:       '#ffd700'
};

// ===== STATE =====
let balance = 500;
let inventory = [];
let selectedCase = null;
let wonItem = null;
let spinning = false;

// ===== DOM =====
const balanceEl = document.getElementById('balance-display');
const casesGrid = document.getElementById('cases-grid');
const contentsGrid = document.getElementById('contents-grid');
const contentsLabel = document.getElementById('contents-label');
const rouletteOverlay = document.getElementById('roulette-overlay');
const rouletteTitle = document.getElementById('roulette-title');
const rouletteTrack = document.getElementById('roulette-track');
const resultPanel = document.getElementById('result-panel');
const resIcon = document.getElementById('res-icon');
const resName = document.getElementById('res-name');
const resRarity = document.getElementById('res-rarity');
const resPrice = document.getElementById('res-price');
const btnKeep = document.getElementById('btn-keep');
const btnSell = document.getElementById('btn-sell');
const btnReopen = document.getElementById('btn-reopen');
const invGrid = document.getElementById('inv-grid');
const invEmpty = document.getElementById('inv-empty');
const invCount = document.getElementById('inv-count');
const invWorth = document.getElementById('inv-worth');
const sellAllBtn = document.getElementById('sell-all-btn');
const toast = document.getElementById('toast');

// ===== TABS =====
document.querySelectorAll('.tab').forEach(t => {
  t.addEventListener('click', () => {
    document.querySelectorAll('.tab').forEach(x => x.classList.remove('active'));
    document.querySelectorAll('.page').forEach(x => x.classList.remove('active'));
    t.classList.add('active');
    document.getElementById('page-' + t.dataset.tab).classList.add('active');
    if (t.dataset.tab === 'inventory') renderInventory();
  });
});

// ===== BALANCE =====
function updateBalance(delta) {
  balance = Math.max(0, balance + delta);
  balanceEl.textContent = '$' + balance.toFixed(2);
  balanceEl.classList.add('bump');
  setTimeout(() => balanceEl.classList.remove('bump'), 300);
}

function fmt(n) { return '$' + n.toFixed(2); }

// ===== DRAW CASES =====
function renderItemIcon(item, size = 42) {
  if (item?.img) {
    const safeAlt = String(item.name ?? '').replace(/"/g, '&quot;');
    return `<img class="item-icon" src="${item.img}" alt="${safeAlt}" width="${size}" height="${size}" loading="lazy">`;
  }
  return `<span class="item-emoji">${item?.emoji ?? ''}</span>`;
}
function renderCases() {
  casesGrid.innerHTML = '';
  CASES.forEach(c => {
    const card = document.createElement('div');
    card.className = 'case-card';
    const badgeClass = { free:'badge-free', mid:'badge-mid', high:'badge-high' }[c.label];
    const badgeText  = { free:'Бесплатно', mid:'Средний', high:'Премиум' }[c.label];
    const priceClass = c.price === 0 ? 'case-price free' : 'case-price';
    const priceText  = c.price === 0 ? 'БЕСПЛАТНО' : fmt(c.price);
    card.innerHTML = `
      <span class="case-badge ${badgeClass}">${badgeText}</span>
      <div class="case-img">${c.emoji}</div>
      <div class="case-info">
        <div class="case-name">${c.name}</div>
        <div class="${priceClass}">${priceText}</div>
        <button class="case-open-btn" id="open-btn-${c.id}">Открыть</button>
      </div>`;
    card.querySelector('.case-open-btn').addEventListener('click', () => openCase(c));
    card.addEventListener('mouseenter', () => renderContents(c));
    casesGrid.appendChild(card);
  });
  renderContents(CASES[0]);
}

function renderContents(c) {
  contentsLabel.textContent = `Содержимое: ${c.name}`;
  contentsGrid.innerHTML = '';
  c.contents.forEach(item => {
    const el = document.createElement('div');
    el.className = `content-item rarity-${item.rarity}`;
    el.innerHTML = `${renderItemIcon(item, 36)}
      <div class="ci-name">${item.name}</div>
      <div class="ci-price">${fmt(item.price)}</div>`;
    contentsGrid.appendChild(el);
  });
}

// ===== WEIGHTED RANDOM =====
function roll(caseData) {
  const ultra = maybeUltraDreamsDrop(caseData);
  if (ultra) return ultra;

  const total = Object.values(WEIGHTS).reduce((a,b)=>a+b,0);
  let r = Math.random() * total;
  for (const [rarity, w] of Object.entries(WEIGHTS)) {
    r -= w;
    if (r <= 0) {
      const pool = caseData.contents.filter(x => x.rarity === rarity);
      if (pool.length) return pool[Math.floor(Math.random() * pool.length)];
    }
  }
  return caseData.contents[0];
}

// ===== OPEN CASE =====
function openCase(c) {
  if (spinning) return;
  if (c.price > balance) { showToast('Недостаточно средств!', true); return; }
  if (c.price > 0) updateBalance(-c.price);
  selectedCase = c;
  wonItem = roll(c);

  spinning = true;
  rouletteTitle.textContent = `Открытие: ${c.name}`;
  rouletteOverlay.classList.add('show');
  resultPanel.classList.remove('show');
  startRoulette(wonItem);
}

// ===== ROULETTE =====
function buildTrackItems(won, count) {
  const items = [];
  const pool = selectedCase.contents;
  for (let i = 0; i < count - 1; i++) {
    items.push(pool[Math.floor(Math.random() * pool.length)]);
  }
  items.push(won);
  return items;
}

const ITEM_W = 110 + 6; // width + gap
const VISIBLE_ITEMS = 7;
const WIN_IDX_FROM_END = 2; // winner lands 2 from right of centre

function startRoulette(won) {
  rouletteTrack.innerHTML = '';
  const TOTAL = 55;
  const winPos = TOTAL - WIN_IDX_FROM_END - 1;

  const pool = selectedCase.contents;
  const trackItems = [];
  for (let i = 0; i < TOTAL; i++) {
    if (i === winPos) trackItems.push(won);
    else trackItems.push(pool[Math.floor(Math.random() * pool.length)]);
  }

  trackItems.forEach((item, i) => {
    const el = document.createElement('div');
    el.className = `r-item rarity-${item.rarity}`;
    el.id = i === winPos ? 'winner-item' : '';
   el.innerHTML = `${renderItemIcon(item, 42)}<div class="r-label">${item.name}</div>`;
    rouletteTrack.appendChild(el);
  });

  const trackWrap = document.querySelector('.roulette-track-wrap');
  const wrapWidth = trackWrap.offsetWidth;
  const centre = wrapWidth / 2;
  const winnerLeft = winPos * ITEM_W + 6;
  const targetScroll = winnerLeft - centre + ITEM_W / 2;
  const jitter = (Math.random() - 0.5) * 40;

  rouletteTrack.style.transform = 'translateX(0)';
  rouletteTrack.style.transition = 'none';

  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      rouletteTrack.style.transition = 'transform 4s cubic-bezier(0.05,0.6,0.25,1)';
      rouletteTrack.style.transform = `translateX(${-(targetScroll + jitter)}px)`;
    });
  });

  setTimeout(() => {
    const winEl = document.getElementById('winner-item');
    if (winEl) winEl.classList.add('winner');

    setTimeout(() => {
      // ПОКАЗЫВАЕМ ПОСЛЕ ДОКРУТКИ, ПЕРЕД РЕЗУЛЬТАТОМ:
      if (selectedCase?.id === 'dreams' && won === DREAMS_ULTRA_DROP) {
       showBigCongrats('ПОЗДРАВЛЯЮ ВАМ ВЫПАЛ ЛАТУЛ 67', DREAMS_ULTRA_DROP.img);
      }

      showResult(won);
    }, 600);
  }, 4300);
}
function showResult(item) {
  resultPanel.classList.add('show');
  resIcon.innerHTML = renderItemIcon(item, 72);
  resName.textContent = item.name;
  resRarity.textContent = RARITY_NAMES[item.rarity];
  resRarity.style.background = RARITY_COLORS[item.rarity] + '33';
  resRarity.style.color = RARITY_COLORS[item.rarity];
  resRarity.style.border = '1px solid ' + RARITY_COLORS[item.rarity];
  resPrice.textContent = fmt(item.price);

  if (['classified','covert','gold'].includes(item.rarity)) {
    spawnSparkles(RARITY_COLORS[item.rarity]);
  }
}

function spawnSparkles(color) {
  for (let i = 0; i < 24; i++) {
    const p = document.createElement('div');
    p.className = 'shimmer-particle';
    p.style.cssText = `
      background:${color};
      left:${20 + Math.random()*60}%;
      top:${20 + Math.random()*60}%;
      animation-delay:${Math.random()*0.5}s;
    `;
    document.body.appendChild(p);
    setTimeout(() => p.remove(), 1400);
  }
}

// ===== KEEP / SELL =====
btnKeep.addEventListener('click', () => {
  inventory.push({ ...wonItem, id: Date.now() + Math.random() });
  rouletteOverlay.classList.remove('show');
  spinning = false;
  showToast(`${wonItem.name} добавлен в инвентарь`);
});

btnSell.addEventListener('click', () => {
  updateBalance(wonItem.price);
  rouletteOverlay.classList.remove('show');
  spinning = false;
  showToast(`Продано за ${fmt(wonItem.price)}`);
});

btnReopen.addEventListener('click', () => {
  // Проверяем баланс ДО того, как сбросим состояние
  if (selectedCase.price > balance) {
    showToast('Недостаточно средств!', true);
    return;
  }

  // Автоматически сохраняем предыдущий предмет в инвентарь
  inventory.push({ ...wonItem, id: Date.now() + Math.random() });
  showToast(`${wonItem.name} добавлен в инвентарь`);

  // СБРОС СОСТОЯНИЯ ДЛЯ ПОВТОРНОГО СТАРТА:
  spinning = false; // Разрешаем запуск новой анимации

  // Быстро убираем старые классы анимации и панель результата, чтобы подготовить трек
  resultPanel.classList.remove('show');
  rouletteTrack.style.transition = 'none';
  rouletteTrack.style.transform = 'translateX(0)';

  // Небольшая задержка, чтобы браузер успел применить сброс стилей трека, и запускаем заново
  setTimeout(() => {
    openCase(selectedCase);
  }, 50);
});

// ===== INVENTORY =====
function renderInventory() {
  invGrid.innerHTML = '';
  if (!inventory.length) {
    invEmpty.style.display = 'block';
    invCount.textContent = '(0)';
    invWorth.textContent = 'Стоимость: $0.00';
    return;
  }
  invEmpty.style.display = 'none';
  invCount.textContent = `(${inventory.length})`;
  const total = inventory.reduce((s,i) => s + i.price, 0);
  invWorth.textContent = `Стоимость: ${fmt(total)}`;

  inventory.forEach(item => {
    const el = document.createElement('div');
    el.className = `inv-item rarity-${item.rarity}`;
    el.style.borderBottomWidth = '3px';
    el.style.borderBottomColor = RARITY_COLORS[item.rarity];
    el.innerHTML = `
        <div class="inv-icon">${renderItemIcon(item, 40)}</div>
      <div class="inv-name">${item.name}</div>
      <div class="inv-price">${fmt(item.price)}</div>
      <button class="inv-sell-btn" data-id="${item.id}">Продать</button>`;
    el.querySelector('.inv-sell-btn').addEventListener('click', e => {
      e.stopPropagation();
      sellFromInventory(item.id);
    });
    invGrid.appendChild(el);
  });
}

function sellFromInventory(id) {
  const idx = inventory.findIndex(i => i.id === id);
  if (idx === -1) return;
  const item = inventory[idx];
  inventory.splice(idx, 1);
  updateBalance(item.price);
  showToast(`Продано: ${item.name} за ${fmt(item.price)}`);
  renderInventory();
}

sellAllBtn.addEventListener('click', () => {
  if (!inventory.length) return;
  const total = inventory.reduce((s,i) => s + i.price, 0);
  inventory = [];
  updateBalance(total);
  renderInventory();
  showToast(`Всё продано за ${fmt(total)}`);
});

// ===== TOAST =====
let toastTimer;
function showToast(msg, warn=false) {
  toast.textContent = msg;
  toast.style.borderColor = warn ? 'var(--red)' : 'var(--gold)';
  toast.style.color = warn ? 'var(--red)' : 'var(--gold)';
  toast.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toast.classList.remove('show'), 2400);
}

// ===== INIT =====
renderCases();