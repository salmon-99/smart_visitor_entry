function validateForm() {
    const inputs = document.querySelectorAll("input[required]");
    for (let input of inputs) {
        if (input.value.trim() === "") {
            alert("Please fill all required fields.");
            return false;
        }
    }
    return true;
}