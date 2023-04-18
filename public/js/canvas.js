const canvas = document.querySelector('canvas');
const ctx = canvas.getContext('2d');

let curzoom = 2;
let dragging = false;

let grabX = 0;
let grabY = 0;

let canvasX = 0;
let canvasY = 0;

canvas.width = 256;
canvas.height = 256;

// fill the whole canvas with random pixels color
for (let i = 0; i < canvas.width; i++) {
	for (let j = 0; j < canvas.height; j++) {
		ctx.fillStyle = `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`;
		ctx.fillRect(i, j, 1, 1);
	}
}

// on scroll zoom the canvas using height
canvas.addEventListener('wheel', (e) => {
	if (e.deltaY < 0) {
		curzoom += 0.2;
	} else {
		curzoom -= 0.2;
	}
	if (curzoom > 16) {
		curzoom = 16;
	} else if (curzoom < 2) {
		curzoom = 2;
	}
	canvas.style.transform = `translate(${canvasX}px, ${canvasY}px) scale(${curzoom})`;
});

// dragging the canvas using relative mouse position
canvas.addEventListener('mousedown', (e) => {
	canvas.style.cursor = 'grabbing';

	grabX = e.clientX - canvasX;
	grabY = e.clientY - canvasY;

	dragging = true;
});

document.addEventListener('mouseup', (e) => {
	dragging = false;
	canvas.style.cursor = "default";
});

document.addEventListener('mousemove', (e) => {
	if (dragging) {
		canvasX = e.clientX - grabX;
		canvasY = e.clientY - grabY;

		canvas.style.transform = `translate(${canvasX}px, ${canvasY}px) scale(${curzoom})`;
	}
});

canvas.style.transform = `scale(${curzoom})`;