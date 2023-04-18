const canvas = document.querySelector('canvas');
const colors = document.querySelectorAll('.color');
const ctx = canvas.getContext('2d');

let curzoom = 2;
let held = false;
let dragging = false;

let grabX = 0;
let grabY = 0;

let canvasX = (window.innerWidth / 2) - (256 / 2)
let canvasY = (window.innerHeight / 2) - (256 / 2)

applyTransform();

let canvasClickX = 0;
let canvasClickY = 0;

let lerpOp;

canvas.width = 256;
canvas.height = 256;

for (let i = 0; i < canvas.width; i++) {
	for (let j = 0; j < canvas.height; j++) {
		ctx.fillStyle = `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`;
		ctx.fillRect(i, j, 1, 1);
	}
}

function lerp(a, b, t) {
	return a + (b - a) * t;
}

function applyTransform() {
	canvas.style.left = `${canvasX}px`;
	canvas.style.top = `${canvasY}px`;
	canvas.style.transform = `scale(${curzoom})`;
}

function applyLerpedTransform(targetX, targetY, zoom = 1, frame_timeout = 10, ease = 0.001) {
	let t = 0;

	clearInterval(lerpOp);

	lerpOp = setInterval(() => {
		t += ease;

		targetX = (window.innerWidth / 2) - (canvas.width / 2) - (canvasClickX - (canvas.width / 2)) * (zoom);
		targetY = (window.innerHeight / 2) - (canvas.height / 2) - (canvasClickY - (canvas.height / 2)) * (zoom);

		curzoom = lerp(curzoom, zoom, t);
		canvasX = lerp(canvasX, targetX , t);
		canvasY = lerp(canvasY, targetY, t);

		applyTransform();

		if (t >= 0.1) {
			clearInterval(lerpOp);
		}
	}, frame_timeout);
}

canvas.addEventListener('wheel', (e) => {
	document.querySelector(".info").classList.add("fade")

	if (e.deltaY < 0) {
		curzoom += 0.5;
	} else {
		curzoom -= 0.5;
	}
	if (curzoom > 64) {
		curzoom = 64;
	} else if (curzoom < 2) {
		curzoom = 2;
	}

	console.log(e)

	// zoom to cursor
	// e.layerX, e.layerY are the coordinates of the cursor relative to the canvas
	// canvasClickX, canvasClickY are the coordinates of the cursor relative to the canvas
	// canvasX, canvasY are the coordinates of the canvas relative to the window
	// canvas.width, canvas.height are the dimensions of the canvas
	canvasX += (e.layerX * (1 / curzoom) * curzoom) - (e.layerX * (1 / (curzoom - 0.5)) * (curzoom - 0.5));
	canvasY += (e.layerY * (1 / curzoom) * curzoom) - (e.layerY * (1 / (curzoom - 0.5)) * (curzoom - 0.5));

	applyTransform();
});

canvas.addEventListener('mousedown', (e) => {
	document.querySelector(".info").classList.add("fade")

	grabX = e.clientX - canvasX;
	grabY = e.clientY - canvasY;

	canvasClickX = Math.floor(e.layerX * (1 / curzoom) * curzoom);
	canvasClickY = Math.floor(e.layerY * (1 / curzoom) * curzoom);

	console.log(e.layerX, e.layerY)
	console.log(canvasClickX, canvasClickY)

	held = true;
});


document.addEventListener('mouseup', (e) => {
	canvas.style.cursor = "default";
	held = false;

	if (e.target !== canvas) {
		return;
	}

	if (dragging) {
		dragging = false;
	} else {
		// color the pixel black
		ctx.fillStyle = 'black';
		ctx.fillRect(canvasClickX, canvasClickY, 1, 1);

		let x = (window.innerWidth / 2) - (canvas.width / 2) - ((canvasClickX) - (canvas.width / 2)) * (curzoom);
		let y = (window.innerHeight / 2) - (canvas.height / 2) - ((canvasClickY) - (canvas.height / 2)) * (curzoom);

		applyLerpedTransform(x, y, 64);
	}
});

document.addEventListener('mousemove', (e) => {
	if (held) {
		dragging = true;
		canvas.style.cursor = 'grabbing';

		canvasX = e.clientX - grabX;
		canvasY = e.clientY - grabY;

		applyTransform();
	}
});

for (let i = 0; i < colors.length; i++) {
	colors[i].style.backgroundColor = colors[i].dataset.color;
	colors[i].addEventListener('click', (e) => {
		colors[i].classList.add('active');
		for (let j = 0; j < colors.length; j++) {
			if (j !== i) {
				colors[j].classList.remove('active');
			}
		}
	});
}