function rating(formula, rating, opponentRating, ...args) {
    var mapping = { "R": String(rating), "E": String(opponentRating) };
    args.forEach(element => {
        mapping[element[0]] = String(element[1]);
    });

    return reversePolish(overrideLetters(formula, mapping));
}

function overrideLetters(formula, mapping) {
    let expr = formula.split(" ");
    for (let i = 0; i < expr.length; i++)
        if (expr[i] in mapping)
            expr[i] = mapping[expr[i]];

    return expr;
}

function reversePolish(expr) {
    console.log(expr);
    let stack = [];
    try {
        if (expr === '')
            return 0;

        for (let i = 0; i < expr.length; i++) {
            if (!isNaN(parseFloat(expr[i])) && isFinite(expr[i])) {
                console.log(expr[i]);
                stack.push(expr[i]);
            } else {
                let a = stack.pop();
                let b = stack.pop();
                console.log("EXPRESSION");
                console.log(a);
                console.log(b);
                if (expr[i] === "+")
                    stack.push(parseFloat(a) + parseFloat(b));
                else if (expr[i] === "-")
                    stack.push(parseFloat(b) - parseFloat(a));
                else if (expr[i] === "*")
                    stack.push(parseFloat(a) * parseFloat(b));
                else if (expr[i] === "/")
                    stack.push(parseFloat(b) / parseFloat(a));
                else if (expr[i] === "^")
                    stack.push(Math.pow(parseFloat(b), parseFloat(a)));

                console.log(stack[stack.length - 1]);
            }
        }
    } catch (e) {
        console.log("DIVISION BY 0");
    }

    if (stack.length > 1)
        return "ERROR";
    else
        return parseInt(stack[0]);


}

function test() {
    return console.log(5);
}