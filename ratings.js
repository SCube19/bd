function reversePolish(newExpr) {
    let expr = newExpr.split(" ");
    let stack = [];
    if (expr === '')
        return 0;

    for (let i = 0; i < expr.length; i++) {
        if (!isNaN(expr[i]) && isFinite(expr[i]))
            stack.push(expr[i]);
        else {
            let a = stack.pop();
            let b = stack.pop();
            if (expr[i] === "+")
                stack.push(parseInt(a) + parseInt(b));
            else if (expr[i] === "-")
                stack.push(parseInt(a) - parseInt(b));
            else if (expr[i] === "*")
                stack.push(parseInt(a) * parseInt(b));
            else if (expr[i] === "/")
                stack.push(parseInt(a) / parseInt(b));
            else if (expr[i] === "^")
                stack.push(Math.pow(parseInt(a), parseInt(b)));
        }
    }

    if (stack.length > 1)
        return "ERROR";
    else
        return stack[0];


}