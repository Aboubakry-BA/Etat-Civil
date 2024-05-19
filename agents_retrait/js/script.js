function toggle() {
	let x = document.getElementById("showpsw");
	if (x.type === "password") {
		x.type = "text";
	} else {
		x.type = "password";
	}
	let y = document.getElementById("showpsw2");
	if (y.type === "password") {
		y.type = "text";
	} else {
		y.type = "password";
	}
}