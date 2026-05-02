
const transactions = [
  {
    transaction_id: "T001",
    transaction_date: "2024-01-15",
    transaction_amount: 250.75,
    transaction_type: "debit",
    transaction_description: "Grocery shopping at local supermarket",
    merchant_name: "SuperMart",
    card_type: "debit",
  },
  {
    transaction_id: "T002",
    transaction_date: "2024-01-20",
    transaction_amount: 1200.00,
    transaction_type: "credit",
    transaction_description: "Salary deposit",
    merchant_name: "Employer Inc.",
    card_type: "credit",
  },
  {
    transaction_id: "T003",
    transaction_date: "2024-02-05",
    transaction_amount: 89.99,
    transaction_type: "debit",
    transaction_description: "Online subscription service",
    merchant_name: "StreamFlix",
    card_type: "credit",
  },
  {
    transaction_id: "T004",
    transaction_date: "2024-02-14",
    transaction_amount: 320.50,
    transaction_type: "debit",
    transaction_description: "Valentine's Day dinner",
    merchant_name: "La Bella Cucina",
    card_type: "credit",
  },
  {
    transaction_id: "T005",
    transaction_date: "2024-02-28",
    transaction_amount: 45.00,
    transaction_type: "debit",
    transaction_description: "Taxi ride",
    merchant_name: "QuickRide",
    card_type: "debit",
  },
  {
    transaction_id: "T006",
    transaction_date: "2024-03-01",
    transaction_amount: 500.00,
    transaction_type: "credit",
    transaction_description: "Freelance payment received",
    merchant_name: "ClientX",
    card_type: "debit",
  },
  {
    transaction_id: "T007",
    transaction_date: "2024-03-10",
    transaction_amount: 130.20,
    transaction_type: "debit",
    transaction_description: "Pharmacy purchase",
    merchant_name: "HealthPlus",
    card_type: "debit",
  },
  {
    transaction_id: "T008",
    transaction_date: "2024-03-15",
    transaction_amount: 75.00,
    transaction_type: "debit",
    transaction_description: "Book store purchase",
    merchant_name: "BookWorld",
    card_type: "credit",
  },
  {
    transaction_id: "T009",
    transaction_date: "2024-03-22",
    transaction_amount: 200.00,
    transaction_type: "credit",
    transaction_description: "Cashback reward",
    merchant_name: "BankRewards",
    card_type: "credit",
  },
  {
    transaction_id: "T010",
    transaction_date: "2024-04-03",
    transaction_amount: 980.00,
    transaction_type: "debit",
    transaction_description: "Monthly rent payment",
    merchant_name: "City Rentals",
    card_type: "debit",
  },
  {
    transaction_id: "T011",
    transaction_date: "2024-04-11",
    transaction_amount: 55.40,
    transaction_type: "debit",
    transaction_description: "Coffee shop visit",
    merchant_name: "BeanBrew",
    card_type: "debit",
  },
  {
    transaction_id: "T012",
    transaction_date: "2024-04-19",
    transaction_amount: 410.00,
    transaction_type: "credit",
    transaction_description: "Part-time job payment",
    merchant_name: "LocalShop",
    card_type: "debit",
  },
];

//1 . Возвращает массив уникальных типов транзакций.
function getUniqueTransactionTypes(transactions) {
  return [...new Set(transactions.map((t) => t.transaction_type))];
}

//2 . Вычисляет общую сумму всех транзакций.
function calculateTotalAmount(transactions) {
  return transactions.reduce((sum, t) => sum + t.transaction_amount, 0);
}

//3 . Вычисляет общую сумму транзакций за указанный год, месяц и/или день.
function calculateTotalAmountByDate(transactions, year, month, day) {
  const filtered = transactions.filter((t) => {
    const [tYear, tMonth, tDay] = t.transaction_date.split("-").map(Number);
    if (year  !== undefined && tYear  !== year)  return false;
    if (month !== undefined && tMonth !== month) return false;
    if (day   !== undefined && tDay   !== day)   return false;
    return true;
  });
  return filtered.reduce((sum, t) => sum + t.transaction_amount, 0);
}

//4 . Возвращает массив транзакций указанного типа ("debit" или "credit").
function getTransactionByType(transactions, type) {
  return transactions.filter((t) => t.transaction_type === type);
}

//5. Возвращает транзакции, совершённые в указанном диапазоне дат (включительно).
function getTransactionsInDateRange(transactions, startDate, endDate) {
  const start = new Date(startDate);
  const end   = new Date(endDate);
  return transactions.filter((t) => {
    const date = new Date(t.transaction_date);
    return date >= start && date <= end;
  });
}

// 6 . Возвращает транзакции, совершённые у указанного продавца.
function getTransactionsByMerchant(transactions, merchantName) {
  return transactions.filter((t) => t.merchant_name === merchantName);
}

// 7 . Вычисляет среднюю сумму транзакций.
function calculateAverageTransactionAmount(transactions) {
  if (transactions.length === 0) return 0;
  return calculateTotalAmount(transactions) / transactions.length;
}

// 8 . Возвращает транзакции, сумма которых находится в указанном диапазоне (включительно).
function getTransactionsByAmountRange(transactions, minAmount, maxAmount) {
  return transactions.filter(
    (t) => t.transaction_amount >= minAmount && t.transaction_amount <= maxAmount
  );
}

//9 . Вычисляет общую сумму всех дебетовых транзакций.
function calculateTotalDebitAmount(transactions) {
  return calculateTotalAmount(getTransactionByType(transactions, "debit"));
}

//10  . Возвращает месяц с наибольшим количеством транзакций.
function findMostTransactionsMonth(transactions) {
  if (transactions.length === 0) return null;
  const counts = {};
  transactions.forEach((t) => {
    const month = t.transaction_date.slice(0, 7); // "YYYY-MM"
    counts[month] = (counts[month] || 0) + 1;
  });
  return Object.entries(counts).sort((a, b) => b[1] - a[1])[0][0];
}

// 11 . Возвращает месяц с наибольшим количеством дебетовых транзакций.
function findMostDebitTransactionMonth(transactions) {
  return findMostTransactionsMonth(getTransactionByType(transactions, "debit"));
}

//12 . Определяет, каких транзакций больше: "debit", "credit" или их поровну.
function mostTransactionTypes(transactions) {
  const debitCount  = getTransactionByType(transactions, "debit").length;
  const creditCount = getTransactionByType(transactions, "credit").length;
  if (debitCount > creditCount)  return "debit";
  if (creditCount > debitCount)  return "credit";
  return "equal";
}
// 13 . Возвращает транзакции, совершённые до указанной даты (не включая её).
function getTransactionsBeforeDate(transactions, date) {
  const limit = new Date(date);
  return transactions.filter((t) => new Date(t.transaction_date) < limit);
}

// 14 . Находит транзакцию по её id. Если транзакция не найдена, возвращает null.
function findTransactionById(transactions, id) {
  return transactions.find((t) => t.transaction_id === id) || null;
}

//15 . Возвращает массив описаний всех транзакций.
function mapTransactionDescriptions(transactions) {
  return transactions.map((t) => t.transaction_description);
}

// Тестa


function separator(title) {
  console.log("\n" + "═".repeat(55));
  console.log("  " + title);
  console.log("═".repeat(55));
}

// Основной набор данных
separator("1. Уникальные типы транзакций");
console.log(getUniqueTransactionTypes(transactions));

separator("2. Общая сумма всех транзакций");
console.log(calculateTotalAmount(transactions).toFixed(2));

separator("3. [extra] Сумма за 2024 год");
console.log(calculateTotalAmountByDate(transactions, 2024).toFixed(2));

separator("3. [extra] Сумма за февраль 2024");
console.log(calculateTotalAmountByDate(transactions, 2024, 2).toFixed(2));

separator("3. [extra] Сумма за 15 января 2024");
console.log(calculateTotalAmountByDate(transactions, 2024, 1, 15).toFixed(2));

separator("4. Транзакции типа 'debit'");
console.log(getTransactionByType(transactions, "debit"));

separator("5. Транзакции с 01.02.2024 по 31.03.2024");
console.log(getTransactionsInDateRange(transactions, "2024-02-01", "2024-03-31"));

separator("6. Транзакции продавца 'StreamFlix'");
console.log(getTransactionsByMerchant(transactions, "StreamFlix"));

separator("7. Средняя сумма транзакций");
console.log(calculateAverageTransactionAmount(transactions).toFixed(2));

separator("8. Транзакции от 100 до 500");
console.log(getTransactionsByAmountRange(transactions, 100, 500));

separator("9. Общая сумма дебетовых транзакций");
console.log(calculateTotalDebitAmount(transactions).toFixed(2));

separator("10. Месяц с наибольшим числом транзакций");
console.log(findMostTransactionsMonth(transactions));

separator("11. Месяц с наибольшим числом дебетовых транзакций");
console.log(findMostDebitTransactionMonth(transactions));

separator("12. Каких транзакций больше?");
console.log(mostTransactionTypes(transactions));

separator("13. Транзакции до 2024-03-01");
console.log(getTransactionsBeforeDate(transactions, "2024-03-01"));

separator("14. Транзакция по id 'T007'");
console.log(findTransactionById(transactions, "T007"));

separator("15. Описания всех транзакций");
console.log(mapTransactionDescriptions(transactions));

// [extra] Пустой массив
separator("[extra] Тест на пустом массиве");
const empty = [];
console.log("getUniqueTransactionTypes:          ", getUniqueTransactionTypes(empty));
console.log("calculateTotalAmount:               ", calculateTotalAmount(empty));
console.log("calculateTotalAmountByDate:         ", calculateTotalAmountByDate(empty, 2024));
console.log("getTransactionByType:               ", getTransactionByType(empty, "debit"));
console.log("getTransactionsInDateRange:         ", getTransactionsInDateRange(empty, "2024-01-01", "2024-12-31"));
console.log("getTransactionsByMerchant:          ", getTransactionsByMerchant(empty, "SuperMart"));
console.log("calculateAverageTransactionAmount:  ", calculateAverageTransactionAmount(empty));
console.log("getTransactionsByAmountRange:       ", getTransactionsByAmountRange(empty, 0, 1000));
console.log("calculateTotalDebitAmount:          ", calculateTotalDebitAmount(empty));
console.log("findMostTransactionsMonth:          ", findMostTransactionsMonth(empty));
console.log("findMostDebitTransactionMonth:      ", findMostDebitTransactionMonth(empty));
console.log("mostTransactionTypes:               ", mostTransactionTypes(empty));
console.log("getTransactionsBeforeDate:          ", getTransactionsBeforeDate(empty, "2024-06-01"));
console.log("findTransactionById:                ", findTransactionById(empty, "T001"));
console.log("mapTransactionDescriptions:         ", mapTransactionDescriptions(empty));

// [extra] Массив с одной транзакцией
separator("[extra] Тест на массиве с одной транзакцией");
const single = [
  {
    transaction_id: "T999",
    transaction_date: "2024-06-01",
    transaction_amount: 150.00,
    transaction_type: "debit",
    transaction_description: "Single test transaction",
    merchant_name: "TestShop",
    card_type: "debit",
  },
];
console.log("getUniqueTransactionTypes:          ", getUniqueTransactionTypes(single));
console.log("calculateTotalAmount:               ", calculateTotalAmount(single));
console.log("calculateTotalAmountByDate(2024,6): ", calculateTotalAmountByDate(single, 2024, 6));
console.log("getTransactionByType(debit):        ", getTransactionByType(single, "debit").length, "шт.");
console.log("getTransactionsInDateRange:         ", getTransactionsInDateRange(single, "2024-01-01", "2024-12-31").length, "шт.");
console.log("getTransactionsByMerchant:          ", getTransactionsByMerchant(single, "TestShop").length, "шт.");
console.log("calculateAverageTransactionAmount:  ", calculateAverageTransactionAmount(single));
console.log("getTransactionsByAmountRange:       ", getTransactionsByAmountRange(single, 100, 200).length, "шт.");
console.log("calculateTotalDebitAmount:          ", calculateTotalDebitAmount(single));
console.log("findMostTransactionsMonth:          ", findMostTransactionsMonth(single));
console.log("findMostDebitTransactionMonth:      ", findMostDebitTransactionMonth(single));
console.log("mostTransactionTypes:               ", mostTransactionTypes(single));
console.log("getTransactionsBeforeDate:          ", getTransactionsBeforeDate(single, "2025-01-01").length, "шт.");
console.log("findTransactionById(T999):          ", findTransactionById(single, "T999"));
console.log("mapTransactionDescriptions:         ", mapTransactionDescriptions(single));
