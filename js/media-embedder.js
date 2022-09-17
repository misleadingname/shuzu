function embed(e) {
	let a = e.currentTarget
	let url = a.href
	let mime = a.getAttribute("mime")

	if (mime.startsWith("image/")) {
		let img = a.children[0]

		if (img.classList.contains('fullImage')) {
			img.src = a.href + "&thumb=true"
			img.classList.remove('fullImage')
		} else {
			img.src = a.href
			img.classList.add('fullImage')
		}
	} else if (mime.startsWith("video/")) {
		let button = a.children[0]
		let breakline = a.children[1]
		let thumb = a.children[2]
		let video = a.children[3]

		let mime = a.parentNode.parentNode.children[1]

		if(video.classList.contains("hidden")) {
			a.href = ""

			thumb.classList.add("hidden")

			breakline.classList.remove("hidden")

			mime.classList.add("hidden")

			video.classList.remove("hidden")
			video.classList.remove("hidden")

			button.classList.remove("hidden")

			video.play()
		} else {
			if (e.target == button) {
				a.href = url

				thumb.classList.remove("hidden")

				breakline.classList.add("hidden")

				mime.classList.remove("hidden")

				video.classList.add("hidden")
				video.classList.add("hidden")

				button.classList.add("hidden")

				video.pause()
			}
		}
	}

	e.preventDefault()
	return false
}