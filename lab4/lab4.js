// ============================================================
//  Лабораторная работа №4: Продвинутые объекты в JavaScript
// ============================================================

// ──────────────────────────────────────────────────────────────
// Шаг 1. Класс Item
// ──────────────────────────────────────────────────────────────

class Item {
  constructor(name, weight, rarity) {
    this.name   = name;
    this.weight = weight;
    this.rarity = rarity;
  }

  getInfo() {
    return `[${this.rarity.toUpperCase()}] ${this.name} | Weight: ${this.weight} kg`;
  }

  setWeight(newWeight) {
    if (newWeight <= 0) {
      console.log("Weight must be greater than 0.");
      return;
    }
    this.weight = newWeight;
    console.log(`${this.name} weight updated to ${this.weight} kg.`);
  }
}

// ──────────────────────────────────────────────────────────────
// Шаг 2. Класс Weapon (наследует Item)
// ──────────────────────────────────────────────────────────────

class Weapon extends Item {
  constructor(name, weight, rarity, damage, durability) {
    super(name, weight, rarity);
    this.damage     = damage;
    this.durability = durability;
  }

  getInfo() {
    return `${super.getInfo()} | Damage: ${this.damage} | Durability: ${this.durability}/100`;
  }

  use() {
    if (this.durability <= 0) {
      console.log(`${this.name} is broken and cannot be used!`);
      return;
    }
    this.durability = Math.max(0, this.durability - 10);
    console.log(`${this.name} used. Durability: ${this.durability}/100`);
  }

  repair() {
    this.durability = 100;
    console.log(`${this.name} repaired. Durability: ${this.durability}/100`);
  }
}

// ──────────────────────────────────────────────────────────────
// Шаг 3. Тестирование классов
// ──────────────────────────────────────────────────────────────

function separator(title) {
  console.log("\n" + "═".repeat(55));
  console.log("  " + title);
  console.log("═".repeat(55));
}

separator("Тест класса Item");
const potion = new Item("Health Potion", 0.5, "common");
const amulet = new Item("Ancient Amulet", 0.2, "legendary");
console.log(potion.getInfo());
console.log(amulet.getInfo());
potion.setWeight(0.6);
potion.setWeight(-1);
console.log(potion.getInfo());

separator("Тест класса Weapon");
const sword = new Weapon("Steel Sword", 3.5, "rare", 45, 100);
const bow   = new Weapon("Longbow",     2.0, "uncommon", 15, 100);
console.log(sword.getInfo());
console.log(bow.getInfo());
bow.use();
bow.use();
bow.use();
console.log(`bow.durability = ${bow.durability}`);
bow.repair();
console.log(`bow.durability after repair = ${bow.durability}`);

separator("Тест сломанного оружия");
const brokenAxe = new Weapon("Old Axe", 5.0, "common", 20, 10);
console.log(brokenAxe.getInfo());
brokenAxe.use();
brokenAxe.use();

// ──────────────────────────────────────────────────────────────
// Шаг 4а. Опциональная цепочка (?.)
// ──────────────────────────────────────────────────────────────

separator("Опциональная цепочка (?.)");

const inventory = [
  new Weapon("Dagger", 1.0, "uncommon", 25, 80),
  null,
  new Item("Iron Shield", 4.5, "common"),
  undefined,
];

inventory.forEach((slot, i) => {
  const info = slot?.getInfo();
  const dur  = slot?.durability;
  console.log(`Slot ${i}: info="${info ?? "empty"}" | durability=${dur ?? "n/a"}`);
  slot?.repair?.();
});

// ──────────────────────────────────────────────────────────────
// Шаг 4б. Функции-конструкторы (без class)
// ──────────────────────────────────────────────────────────────

separator("Функция-конструктор ItemFn");

function ItemFn(name, weight, rarity) {
  this.name   = name;
  this.weight = weight;
  this.rarity = rarity;
}

ItemFn.prototype.getInfo = function () {
  return `[${this.rarity.toUpperCase()}] ${this.name} | Weight: ${this.weight} kg`;
};

ItemFn.prototype.setWeight = function (newWeight) {
  if (newWeight <= 0) { console.log("Weight must be greater than 0."); return; }
  this.weight = newWeight;
  console.log(`${this.name} weight updated to ${this.weight} kg.`);
};

const staff = new ItemFn("Magic Staff", 2.0, "rare");
console.log(staff.getInfo());
staff.setWeight(2.5);
console.log(staff.getInfo());

separator("Функция-конструктор WeaponFn (наследует ItemFn)");

function WeaponFn(name, weight, rarity, damage, durability) {
  ItemFn.call(this, name, weight, rarity);
  this.damage     = damage;
  this.durability = durability;
}

WeaponFn.prototype = Object.create(ItemFn.prototype);
WeaponFn.prototype.constructor = WeaponFn;

WeaponFn.prototype.getInfo = function () {
  const base = ItemFn.prototype.getInfo.call(this);
  return `${base} | Damage: ${this.damage} | Durability: ${this.durability}/100`;
};

WeaponFn.prototype.use = function () {
  if (this.durability <= 0) { console.log(`${this.name} is broken!`); return; }
  this.durability = Math.max(0, this.durability - 10);
  console.log(`${this.name} used. Durability: ${this.durability}/100`);
};

WeaponFn.prototype.repair = function () {
  this.durability = 100;
  console.log(`${this.name} repaired. Durability: ${this.durability}/100`);
};

const lance = new WeaponFn("Silver Lance", 4.0, "legendary", 70, 100);
console.log(lance.getInfo());
lance.use();
lance.use();
lance.repair();

console.log(`lance instanceof WeaponFn: ${lance instanceof WeaponFn}`);
console.log(`lance instanceof ItemFn:   ${lance instanceof ItemFn}`);
