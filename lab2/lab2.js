arr = [1,2,3,4,5,6,7,8,9,10]
//Фунция 1 - выводит элементы массива и их индексы в виде "Element 0: value 1", "Element 1: value 2" и т.д.
function printArray(arr) {
    for (let i = 0; i < arr.length; i++){
        console.log("Element " + i + ": " + "value " + arr[i]);
    };
}

// printArray(arr)

//Фунция 2 - выводит элементы массива и их индексы в виде "0: 1", "1: 2" и т.д.
function printArray1(arr) {
    for (let i = 0; i < arr.length; i++){
        console.log(i + ": "  + arr[i]);
    };
}
// printArray1()


//Фунция 3 - выводит элементы массива и их индексы в виде "Index: 0, Value: 1", "Index: 1, Value: 2" и т.д.
function forEach(arr,callback) {
      for (let i = 0; i < arr.length; i++){
        callback(arr[i], i, arr);
      }
}
// forEach(arr, (element, index) => {
//     console.log(index + ": " + element);
// });


//Фунция 4 - принимает массив и функцию обратного вызова, которая применяется к каждому элементу массива, и возвращает новый массив с результатами применения функции обратного вызова к каждому элементу.
function map(array,callback){
    const result = []
    for (let i = 0; i < array.length; i++){
        const newValue = callback(array[i], i, array);
        result.push(newValue);}
    return result;

}
/*
const numbers = [1, 2, 3];
const squared = map(numbers,(element) => element * element);
console.log(squared);*/





//Фунция 5 - принимает массив и функцию обратного вызова, которая применяется к каждому элементу массива, и возвращает новый массив, содержащий только те элементы, для которых функция обратного вызова возвращает true.
function filter(array, callback) {
    const result = [];
    for (let i = 0; i < array.length; i++) {
        if (callback(array[i], i, array)) {
            result.push(array[i]);
        }
    }
     return result;
}
// const numbers = [1, 2, 3, 4, 5];
// const evenNumbers = filter(numbers, (element) => element % 2 === 0);
// console.log(evenNumbers); // [2, 4]





//Фунция 6 - принимает массив и функцию обратного вызова, которая применяется к каждому элементу массива, и возвращает первый элемент, для которого функция обратного вызова возвращает true.
function find(array, callback) {
    for (let i = 0; i < array.length; i++) {
        if (callback(array[i], i, array)) {
            return array[i];
        }
    }
}
// const numbers = [1, 2, 3, 4, 5];
// const firstEven = find(numbers, (element) => element % 2 === 0);
// console.log(firstEven); // 2






//Фунция 7 - принимает массив и функцию обратного вызова, которая применяется к каждому элементу массива, и возвращает true, если функция обратного вызова возвращает true для хотя бы одного элемента массива, и false в противном случае.
function some(array, callback) {
    for (let i = 0; i < array.length; i++) {
        if (callback(array[i], i, array)) {
            return true;
        }
    }
    return false;
}

// const numbers = [1, 2, 3, 4, 5];
// const hasEven = some(numbers, (element) => element % 2 === 0);
// console.log(hasEven); // true





//Фунция 8 - принимает массив и функцию обратного вызова, которая применяется к каждому элементу массива, и возвращает true, если функция обратного вызова возвращает true для всех элементов массива, и false в противном случае.
function every(array, callback) {
    for (let i = 0; i < array.length; i++) {
        if (!callback(array[i], i, array)) {
            return false;
        }
    }
    return true;
}
// const numbers = [2, 4, 6];
// const allEven = every(numbers, (element) => element % 2 === 0);
// console.log(allEven); // true




//функция 9 - принимает массив и функцию обратного вызова, которая применяется к каждому элементу массива, и возвращает единственное значение, которое является результатом последовательного применения функции обратного вызова к каждому элементу массива, начиная с начального значения.
function reduce(array, callback, initialValue) {
    let accumulator = initialValue;
    for (let i = 0; i < array.length; i++) {
        accumulator = callback(accumulator, array[i], i, array);
    }
    return accumulator;
}
const numbers = [1, 2, 3, 4, 5];
const sum = reduce(numbers, (accumulator, element) => accumulator + element, 0);
console.log(sum); // 15