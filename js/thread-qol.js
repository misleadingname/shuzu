function embed(e) {
	let a = e.currentTarget
	let url = a.href
	let mime = a.getAttribute("mime")

	if (mime.startsWith("image/")) {
		let img = a.children[1]

		if (img.classList.contains('fullImage')) {
			img.src = a.href + "&thumb=true"
			img.classList.remove('fullImage')
		} else {
			img.src = a.href
			img.classList.add('fullImage')
		}
	} else {
		let button = a.children[0]
		let thumb = a.children[1]
		let mime = a.parentNode.parentNode.children[1]

		if (!a.classList.contains("vidEmbedded")) {
			let video = document.createElement("video")

			mime.classList.add("hidden")

			video.src = url
			video.controls = true
			video.autoplay = true

			a.insertBefore(video, thumb)

			a.classList.add("vidEmbedded")
			thumb.classList.add("hidden")
			button.classList.remove("hidden")
		} else {
			if(e.target == button) {
				let video = a.children[1]
				let thumb = a.children[2]

				mime.classList.remove("hidden")

				video.remove()

				a.classList.remove("vidEmbedded")
				thumb.classList.remove("hidden")

				button.classList.add("hidden")
			}
		}
	}

	e.preventDefault()
	return false
}

function mention(e) {
	let a = e.currentTarget
	let id = a.parentNode.parentNode.id

	let input = document.getElementById("replyTextarea")
	input.value += ">>" + id + "\n"
	input.focus()

	e.preventDefault()
	return false
}

function submitReply(e){
	let form = e.target

	fetch(form.action, {
		method: form.method,
		body: new FormData(form),
	}).then(() => {
		fetch(window.location.href).then(res => res.text()).then(text => {
			let html = document.open("text/html", "replace");
			html.write(text);
			html.close();
			scrollTo(0,scrollMaxY)
		})
	});


	e.preventDefault()
}