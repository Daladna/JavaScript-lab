arr = [1,2,3,4,5,6,7,8,9,10]
//Фунция 1
function printArray(arr) {
    for (let i = 0; i < arr.length; i++){
        console.log("Element " + i + ": " + "value " + arr[i]);
    };
}

// printArray(arr)

//Фунция 2
function printArray1(arr) {
    for (let i = 0; i < arr.length; i++){
        console.log(i + ": "  + arr[i]);
    };
}
// printArray1()


//Фунция 3
function forEach(arr,callback) {
      for (let i = 0; i < arr.length; i++){
        callback(arr[i], i, arr);
      }
}
// forEach(arr, (element, index) => {
//      console.log(index + ": " + element);
//  });


//Фунция 4
function map(array,callback){
    const result = []
    for (let i = 0; i < array.length; i++){
        const newValue = callback(array[i], i, array);
        result.push(newValue);}
    return result;

}

// const numbers = [1, 2, 3];
// const squared = map(numbers,(element) => element * element);
// console.log(squared);





//Фунция 5
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





//Фунция 6
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






//Фунция 7
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





//Фунция 8
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




//функция 9
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